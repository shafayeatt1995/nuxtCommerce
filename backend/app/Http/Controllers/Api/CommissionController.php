<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Commission;
use App\Models\SubCategory;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

class CommissionController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $subCategories = SubCategory::with('category', 'commission')->latest()->paginate(50);
        return response()->json(compact('subCategories'));
    }

    public function updateCommission(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'subCategoryId' => 'required|numeric',
            'type' => 'required|boolean',
            'commission' => 'required|numeric',
        ]);
        $exist = Commission::where('sub_category_id', $request->subCategoryId)->first();
        $comission = $exist ? $exist : new Commission();
        $comission->sub_category_id = $request->subCategoryId;
        $comission->type = $request->type;
        $comission->commission = $request->commission;
        $comission->save();
        return response()->json('Commission successfully updated');
    }
}
