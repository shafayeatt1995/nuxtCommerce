<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $countries = Country::latest()->paginate(20);
        return response()->json(compact('countries'));
    }

    public function countryList()
    {
        $this->authorize('admin');
        $countries = Country::orderBy('name')->get();
        return response()->json(compact('countries'));
    }

    public function createCountry(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:countries',
            'status' => 'required|boolean',
        ]);

        $country = new Country();
        $country->name = $request->name;
        $country->slug = Str::slug($request->name);
        $country->status = $request->status;
        $country->save();
        return response()->json('Country successfully created');
    }

    public function editCountry($id)
    {
        $this->authorize('admin');
        $country = Country::where('id', $id)->first();
        if (isset($country)) {
            return response()->json(compact('country'));
        } else {
            return response()->json(['message' => 'Country not found'], 422);
        }
    }

    public function updateCountry(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $country = Country::where('id', $id)->first();
        $country->name = $request->name;
        $country->slug = Str::slug($request->name);
        $country->status = $request->status;
        $country->save();
        return response()->json('Country successfully updated');
    }

    public function deleteCountry(Request $request)
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
            $country = Country::where('id', $id)->first();
            if (isset($country)) {
                $country->delete();
            } else {
                return response()->json(['message' => 'Country not found'], 422);
            }
        }
        return response()->json('Country successfully deleted');
    }

    public function searchCountry(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'collum' => 'required'
        ]);
        $countries = Country::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(500);
        return response()->json(compact('countries'));
    }

    public function statusCountry($id)
    {
        $this->authorize('admin');
        $countries = Country::where('id', $id)->first();
        $countries->status = !$countries->status;
        $countries->save();
        return response()->json('Country status successfully changed');
    }
}
