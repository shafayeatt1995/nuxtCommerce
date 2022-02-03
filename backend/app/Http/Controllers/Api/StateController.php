<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $states = State::with('country')->latest()->paginate(20);
        return response()->json(compact('states'));
    }

    public function stateList($id)
    {
        $this->authorize('admin');
        $states = State::where('country_id', $id)->orderBy('name')->get();
        return response()->json(compact('states'));
    }

    public function createState(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'countryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $state = new State();
        $state->country_id = $request->countryId;
        $state->name = $request->name;
        $state->slug = Str::slug($request->name) . Str::random(1);
        $state->status = $request->status;
        $state->save();
        return response()->json('State successfully created');
    }

    public function editState($id)
    {
        $this->authorize('admin');
        $state = State::where('id', $id)->first();
        if (isset($state)) {
            return response()->json(compact('state'));
        } else {
            return response()->json(['message' => 'State not found'], 422);
        }
    }

    public function updateState(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'countryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $state = State::where('id', $id)->first();
        $state->country_id = $request->countryId;
        $state->name = $request->name;
        $state->slug = Str::slug($request->name) . Str::random(1);
        $state->status = $request->status;
        $state->save();
        return response()->json('State successfully updated');
    }

    public function deleteState(Request $request)
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
            $state = State::where('id', $id)->first();
            if (isset($state)) {
                $state->delete();
            } else {
                return response()->json(['message' => 'State not found'], 422);
            }
        }
        return response()->json('State successfully deleted');
    }

    public function searchState(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $states = State::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('country')->latest()->paginate(20);
        return response()->json(compact('states'));
    }

    public function statusState($id)
    {
        $this->authorize('admin');
        $state = State::where('id', $id)->first();
        $state->status = !$state->status;
        $state->save();
        return response()->json('States status successfully changed');
    }
}
