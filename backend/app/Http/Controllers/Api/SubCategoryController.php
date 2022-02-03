<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $categories = SubCategory::with('category')->latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function subCategoryList($id)
    {
        $this->authorize('admin');
        $subCategories = SubCategory::where('category_id', $id)->orderBy('name')->get();
        return response()->json(compact('subCategories'));
    }

    public function createSubCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'categoryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $subCategory = new SubCategory();
        $subCategory->category_id = $request->categoryId;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name) . Str::random(1);
        $subCategory->status = $request->status;
        $subCategory->save();
        return response()->json('Sub category successfully created');
    }

    public function editSubCategory($id)
    {
        $this->authorize('admin');
        $category = SubCategory::where('id', $id)->first();
        if (isset($category)) {
            return response()->json(compact('category'));
        } else {
            return response()->json(['message' => 'Sub Category not found'], 422);
        }
    }

    public function updateSubCategory(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'categoryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $subCategory = SubCategory::where('id', $id)->first();
        $subCategory->category_id = $request->categoryId;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name) . Str::random(1);
        $subCategory->status = $request->status;
        $subCategory->save();
        return response()->json('Sub category successfully updated');
    }

    public function deleteSubCategory(Request $request)
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
            $subCategory = SubCategory::where('id', $id)->first();
            if (isset($subCategory)) {
                $subCategory->delete();
            } else {
                return response()->json(['message' => 'Sub category not found'], 422);
            }
        }
        return response()->json('Sub category successfully deleted');
    }

    public function searchSubCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $categories = SubCategory::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('category')->latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function statusSubCategory($id)
    {
        $this->authorize('admin');
        $subCategory = SubCategory::where('id', $id)->first();
        $subCategory->status = !$subCategory->status;
        $subCategory->save();
        return response()->json('Sub category status successfully changed');
    }
}
