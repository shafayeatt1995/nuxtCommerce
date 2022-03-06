<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Notifications\StoreApproveNotification;
use App\Notifications\StoreDeclineNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $stores = Store::with('user')->latest()->paginate(20);
        $all = Store::all()->count();
        $active = Store::where('pending', false)->where('suspend', false)->count();
        $pending = Store::where('pending', true)->count();
        $suspend = Store::where('suspend', true)->count();
        return response()->json(compact('stores', 'all', 'active', 'pending', 'suspend'));
    }

    public function storeActive()
    {
        $this->authorize('admin');
        $stores = Store::where('pending', false)->where('suspend', false)->with('user')->latest()->paginate(20);
        return response()->json(compact('stores'));
    }

    public function storePending()
    {
        $this->authorize('admin');
        $stores = Store::where('pending', true)->with('user')->latest()->paginate(20);
        return response()->json(compact('stores'));
    }

    public function storeSuspend()
    {
        $this->authorize('admin');
        $stores = Store::where('suspend', true)->with('user')->latest()->paginate(20);
        return response()->json(compact('stores'));
    }

    public function pendingStore()
    {
        $this->authorize('admin');
        $stores = Store::with('user')->where('pending', true)->latest()->paginate(20);
        return response()->json(compact('stores'));
    }

    public function editStore($id)
    {
        $this->authorize('admin');
        $store = Store::where('id', $id)->first();
        return response()->json(compact('store'));
    }

    public function sellerRegestration(Request $request)
    {
        $this->authorize('customer');
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $exist = Auth::user()->store;
        if (!isset($exist)) {

            $slug = Str::slug($request->name);
            $path = 'images/store/logo/';
            $imageName = $path . $slug . time() . '.webp';

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($request->logo)->encode('webp', 80)->save($imageName);

            $store = new Store();
            $store->user_id = Auth::id();
            $store->name = $request->name;
            $store->slug = $slug . Str::random(3);
            $store->phone = $request->phone;
            $store->address = $request->address;
            $store->description = $request->description;
            $store->logo = $imageName;
            $store->save();
            return response()->json('Requuest submit successfully');
        } else {
            return response()->json(['message' => 'We are review your request'], 422);
        }
    }

    public function changeStoreStatus(Request $request)
    {
        $this->authorize('admin');
        $request->validate(
            [
                'idList' => 'required|array|min:1',
                'action' => 'required'
            ],
            [
                'idList.required' => 'Please select an item',
            ]
        );
        foreach ($request->idList as $id) {
            $store = Store::where('id', $id)->with('user')->first();
            if (isset($store)) {
                $user = User::where('id', $store->user_id)->first();
                if ($request->action === 'approve') {
                    $store->pending = false;
                    $store->save();

                    $user->role_id = 2;
                    $user->save();
                    Notification::send($store->user, new StoreApproveNotification);
                } else if ($request->action === 'decline') {
                    if (File::exists($store->logo)) {
                        unlink($store->logo);
                    }
                    $store->delete();
                    Notification::send($store->user, new StoreDeclineNotification);
                }
            }
        }
        return response()->json('Store status update successfully');
    }

    public function updateStore(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'description' => 'required',
        ]);

        $store = Store::where('id', $id)->first();

        $slug = Str::slug($request->name);
        $path = 'images/store/logo/';

        $image = $request->hasFile('logo');
        if ($image) {
            if (File::exists($store->logo)) {
                unlink($store->logo);
            }
            $imageName = $path . $slug . time() . '.webp';
            Image::make($request->logo)->encode('webp', 80)->save($imageName);
        } else {
            $imageName = $store->logo;
        }

        $store->name = $request->name;
        $store->slug = $slug . Str::random(3);
        $store->phone = $request->phone;
        $store->address = $request->address;
        $store->description = $request->description;
        $store->logo = $imageName;
        $store->save();
        return response()->json('Store successfully updated');
    }

    public function storeStatus($id)
    {
        $this->authorize('admin');
        $store = Store::where('id', $id)->first();
        $store->suspend = !$store->suspend;
        $store->save();
        return response()->json('Store status update successfully');
    }

    public function searchStore(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'collum' => 'required'
        ]);
        $stores = Store::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('user')->latest()->paginate(500);
        return response()->json(compact('stores'));
    }
}
