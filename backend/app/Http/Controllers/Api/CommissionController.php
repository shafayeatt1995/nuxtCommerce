<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $this->authorize('adminOrSeller');
        $subCategories = SubCategory::with('category', 'commission')->latest()->paginate(50);
        return response()->json(compact('subCategories'));
    }

    public function searchCommission(Request $request)
    {
        $this->authorize('adminOrSeller');
        $request->validate([
            "collum" => "required"
        ]);
        $subCategories = SubCategory::with('category', 'commission')->where($request->collum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(500);
        return response()->json(compact('subCategories'));
    }

    public function updateCommission(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'subCategoryId' => 'required|numeric',
            'commission' => 'required|numeric',
        ]);
        $exist = Commission::where('sub_category_id', $request->subCategoryId)->first();
        $comission = $exist ? $exist : new Commission();
        $comission->sub_category_id = $request->subCategoryId;
        $comission->type = $request->type;
        $comission->commission = $request->type === null ? 0 : $request->commission;
        $comission->save();
        return response()->json('Commission successfully updated');
    }
}
