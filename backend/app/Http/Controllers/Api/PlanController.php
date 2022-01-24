<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
	public function index()
	{
		$plans = Plan::latest()->paginate(50);
		return response()->json(compact('plans'));
	}

	public function createPlan(Request $request)
	{
		$request->validate([
			'name' => 'required|unique:plans',
			'description' => 'required',
			'durationName' => 'required',
			'durationDay' => 'required|numeric',
			'price' => 'required|numeric',
			'discountPrice' => 'required_if:discount,true',
			'discountDate' => 'required_if:discount,true',
			'productLimit' => 'required|numeric',
			'storageLimit' => 'required|numeric',
			'variationLimit' => 'required|numeric',
			'inventory' => 'required',
			'pos' => 'required',
			'support' => 'required',
			'qrCode' => 'required',
			'status' => 'required',
		]);

		$plan = new Plan();
		$plan->name = $request->name;
		$plan->slug = Str::slug($request->name);
		$plan->description = $request->description;
		$plan->duration_name = $request->durationName;
		$plan->duration_day = $request->durationDay;
		$plan->price = $request->price;
		$plan->product_limit = $request->productLimit;
		$plan->storage_limit = $request->storageLimit;
		$plan->variation_limit = $request->variationLimit;
		$plan->inventory = $request->inventory;
		$plan->pos = $request->pos;
		$plan->support = $request->support;
		$plan->qr_code = $request->qrCode;
		$plan->status = $request->status;
		$plan->discount_price = $request->discount ? $request->discountPrice : null;
		$plan->discount_start = $request->discount ? $request->discountDate[0] : null;
		$plan->discount_end = $request->discount ? $request->discountDate[1] : null;
		$plan->save();
		return response()->json('Plan successfully created');
	}

	public function editPlan($id)
	{
		$plan = Plan::where('id', $id)->first();
		if (isset($plan)) {
			return response()->json(compact('plan'));
		} else {
			return response()->json('plan not found', 404);
		}
	}

	public function updateePlan(Request $request, $id)
	{
		$request->validate([
			'name' => 'required',
			'description' => 'required',
			'durationName' => 'required',
			'durationDay' => 'required|numeric',
			'price' => 'required|numeric',
			'discountPrice' => 'required_if:discount,true',
			'discountDate' => 'required_if:discount,true',
			'productLimit' => 'required|numeric',
			'storageLimit' => 'required|numeric',
			'variationLimit' => 'required|numeric',
			'inventory' => 'required',
			'pos' => 'required',
			'support' => 'required',
			'qrCode' => 'required',
			'status' => 'required',
		]);

		$plan = Plan::where('id', $id)->first();
		$plan->name = $request->name;
		$plan->slug = Str::slug($request->name);
		$plan->description = $request->description;
		$plan->duration_name = $request->durationName;
		$plan->duration_day = $request->durationDay;
		$plan->price = $request->price;
		$plan->product_limit = $request->productLimit;
		$plan->storage_limit = $request->storageLimit;
		$plan->variation_limit = $request->variationLimit;
		$plan->inventory = $request->inventory;
		$plan->pos = $request->pos;
		$plan->support = $request->support;
		$plan->qr_code = $request->qrCode;
		$plan->status = $request->status;
		$plan->discount_price = $request->discount ? $request->discountPrice : null;
		$plan->discount_start = $request->discount ? $request->discountDate[0] : null;
		$plan->discount_end = $request->discount ? $request->discountDate[1] : null;
		$plan->save();
		return response()->json('Plan successfully updated');
	}

	public function deletePlan(Request $request)
	{
		$request->validate([
			"idList" => "required|array|min:1"
		]);
		foreach ($request->idList as $id) {
			$plan = Plan::where('id', $id)->first();
			if (isset($plan)) {
				$plan->delete();
			} else {
				return response()->json('Plan Not found', 422);
			}
		}
		return response()->json('Plan successfully deleted');
	}
}
