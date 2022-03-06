<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class RequestController extends Controller
{
    public function index($status)
    {
        $this->authorize('admin');
        if ($status === 'all') {
            $requests = NewRequest::with('user.store')->orderBy('created_at', 'desc')->paginate(20);
        } else if ($status === 'active') {
            $requests = NewRequest::where('status', true)->with('user.store')->orderBy('created_at', 'desc')->paginate(20);
        } else if ($status === 'reject') {
            $requests = NewRequest::where('status', false)->with('user.store')->orderBy('created_at', 'desc')->paginate(20);
        } else if ($status === 'pending') {
            $requests = NewRequest::where('status', null)->with('user.store')->orderBy('created_at', 'desc')->paginate(20);
        }
        $all = NewRequest::count();
        $active = NewRequest::where('status', true)->count();
        $pending = NewRequest::where('status', null)->count();
        $reject = NewRequest::where('status', false)->count();
        return response()->json(compact('requests', 'all', 'active', 'pending', 'reject'));
    }

    public function myRequest()
    {
        $this->authorize('seller');
        $requests = NewRequest::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(20);
        return response()->json(compact('requests'));
    }

    public function approveRequest($id)
    {
        $this->authorize('admin');
        $newRequest = NewRequest::where('id', $id)->first();
        $newRequest->status = true;
        $newRequest->save();
        return response()->json('Request approved successfully');
    }

    public function rejectRequest($id)
    {
        $this->authorize('admin');
        $newRequest = NewRequest::where('id', $id)->first();
        $newRequest->status = false;
        $newRequest->save();
        return response()->json('Request reject successfully');
    }

    public function deleteRequest(Request $request)
    {
        $this->authorize('admin');
        $request->validate(
            [
                'idList' => 'required|array|min:1'
            ],
            [
                'idList.required' => 'Please select an item',
            ]
        );
        foreach ($request->idList as $id) {
            $newRequest = NewRequest::where('id', $id)->first();
            if (isset($newRequest)) {
                if ($newRequest->image !== null && File::exists($newRequest->image)) {
                    unlink($newRequest->image);
                }
                $newRequest->delete();
            } else {
                return response()->json(['message' => 'Request Not found'], 422);
            }
        }
        return response()->json('Request delete successfully');
    }

    public function deleteMyRequest(Request $request)
    {
        $this->authorize('seller');
        $request->validate(
            [
                'idList' => 'required|array|min:1'
            ],
            [
                'idList.required' => 'Please select an item',
            ]
        );
        foreach ($request->idList as $id) {
            $newRequest = NewRequest::where('id', $id)->first();
            if (isset($newRequest) && $newRequest->status === null) {
                if ($newRequest->image !== null && File::exists($newRequest->image)) {
                    unlink($newRequest->image);
                }
                $newRequest->delete();
            } else {
                return response()->json(['message' => 'Request Not found'], 422);
            }
        }
        return response()->json('Request delete successfully');
    }

    public function requestBrand(Request $request)
    {
        $this->authorize('seller');
        $request->validate([
            'name' => 'required|unique:brands',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/request/brands/';
        $imageName = $path . $slug . time() . '.webp';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (Image::make($request->logo)->encode('webp', 80)->save($imageName)) {
            $newRequest = new NewRequest();
            $newRequest->user_id = Auth::id();
            $newRequest->name = $request->name;
            $newRequest->message = 'Need a new brand';
            $newRequest->image = $imageName;
            $newRequest->save();
        };

        return response()->json('Brand request submit successfully');
    }
}
