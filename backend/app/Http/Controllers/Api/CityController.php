<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $cities = City::with('country', 'state')->latest()->paginate(20);
        return response()->json(compact('cities'));
    }

    public function cityList($id)
    {
        $this->authorize('admin');
        $cities = City::where('state_id', $id)->orderBy('name')->get();
        return response()->json(compact('cities'));
    }

    public function createCity(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'countryId' => 'required|numeric',
            'stateId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $city = new City();
        $city->country_id = $request->countryId;
        $city->state_id = $request->stateId;
        $city->name = $request->name;
        $city->slug = Str::slug($request->name) . Str::random(1);
        $city->status = $request->status;
        $city->save();
        return response()->json('City successfully created');
    }

    public function editCity($id)
    {
        $this->authorize('admin');
        $city = City::where('id', $id)->first();
        if (isset($city)) {
            $countryList = Country::orderBy('name')->get();
            $stateList = State::where('country_id', $city->country_id)->orderBy('name')->get();
            return response()->json(compact('city', 'countryList', 'stateList'));
        } else {
            return response()->json(['message' => 'City not found'], 422);
        }
    }

    public function updateCity(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'countryId' => 'required|numeric',
            'stateId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $city = City::where('id', $id)->first();
        $city->country_id = $request->countryId;
        $city->state_id = $request->stateId;
        $city->name = $request->name;
        $city->slug = Str::slug($request->name) . Str::random(1);
        $city->status = $request->status;
        $city->save();
        return response()->json('City successfully updated');
    }

    public function deleteCity(Request $request)
    {
        $this->authorize('admin');
        $request->validate(
            [
                "idList" => "required|array|min:1"
            ],
            [
                'idList.required' => 'Please select an item',
            ]
        );
        foreach ($request->idList as $id) {
            $city = City::where('id', $id)->first();
            if (isset($city)) {
                $city->delete();
            } else {
                return response()->json(['message' => 'City not found'], 422);
            }
        }
        return response()->json('City successfully deleted');
    }

    public function searchCity(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $cities = City::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('country', 'state')->latest()->paginate(500);
        return response()->json(compact('cities'));
    }

    public function statusCity($id)
    {
        $this->authorize('admin');
        $city = City::where('id', $id)->first();
        $city->status = !$city->status;
        $city->save();
        return response()->json('City status successfully changed');
    }
}
