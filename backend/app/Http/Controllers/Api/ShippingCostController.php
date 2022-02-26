<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingCost;
use App\Models\ShippingCostRule;
use App\Models\State;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $this->authorize("admin");
        $costs = ShippingCost::latest()->paginate(20);
        return response()->json(compact("costs"));
    }

    public function shippingCostList()
    {
        $this->authorize("admin");
        $costs = ShippingCost::orderBy("name")->get();
        return response()->json(compact("costs"));
    }

    public function createShippingCost(Request $request)
    {
        $this->authorize("admin");
        $request->validate([
            "name" => "required",
            "rules" => "required|array|min:1",
        ]);
        $cost = new ShippingCost();
        $cost->name = $request->name;
        $cost->rules = json_encode($request->rules);
        $cost->save();
        return response()->json("Shipping cost rules successfully created");
    }

    public function editShippingCost($id)
    {
        $this->authorize("admin");
        $cost = ShippingCost::where("id", $id)->first();
        return response()->json(compact("cost"));
    }

    public function updateShippingCost(Request $request, $id)
    {
        $this->authorize("admin");
        $request->validate([
            "name" => "required",
            "rules" => "required|array|min:1",
        ]);
        $cost = ShippingCost::where("id", $id)->first();
        $cost->name = $request->name;
        $cost->rules = json_encode($request->rules);
        $cost->save();
        return response()->json("Shipping cost rules successfully updated");
    }

    public function deleteShippingCost(Request $request)
    {
        $this->authorize("admin");
        $request->validate(
            [
                "idList" => "required|array|min:1"
            ],
            [
                "idList.required" => "Please select an item",
            ]
        );
        foreach ($request->idList as $id) {
            $cost = ShippingCost::where("id", $id)->first();
            if (isset($cost)) {
                $cost->delete();
            } else {
                return response()->json(["message" => "Shipping cost not found"], 422);
            }
        }
        return response()->json("Shipping cost successfully deleted");
    }

    public function searchShippingCost(Request $request)
    {
        $this->authorize("admin");
        $request->validate([
            "collum" => "required"
        ]);
        $states = State::where($request->collum, "LIKE", "%" . $request->keyword . "%")->with('shpippingCostRule.shippingCost')->latest()->paginate(500);
        return response()->json(compact('states'));
    }

    public function updateShippingCostRules(Request $request)
    {
        $this->authorize("admin");
        $request->validate([
            "stateId" => "required|numeric",
            "rulesId" => "required|numeric",
        ], [
            "rulesId.required" => "Please select a rules",
        ]);

        $exist = ShippingCostRule::where("state_id", $request->stateId)->first();
        $cost = $exist ? $exist : new ShippingCostRule();
        $cost->state_id = $request->stateId;
        $cost->shipping_cost_id = $request->rulesId;
        $cost->save();
        return response()->json("Shipping cost rules update successfully");
    }
}
