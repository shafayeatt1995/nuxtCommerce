<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $categories = ChildCategory::with('category', 'subCategory')->latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function childCategoryList($id)
    {
        $this->authorize('admin');
        $childCategories = ChildCategory::where('sub_category_id', $id)->orderBy('name')->get();
        return response()->json(compact('childCategories'));
    }

    public function createChildCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'categoryId' => 'required|numeric',
            'subCategoryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $childCategory = new ChildCategory();
        $childCategory->category_id = $request->categoryId;
        $childCategory->sub_category_id = $request->subCategoryId;
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name) . Str::random(1);
        $childCategory->status = $request->status;
        $childCategory->save();
        return response()->json('Child category successfully created');
    }

    public function editChildCategory($id)
    {
        $this->authorize('admin');
        $category = ChildCategory::where('id', $id)->first();
        if (isset($category)) {
            $categoryList = Category::orderBy('name')->get();
            $subCategoryList = SubCategory::where('category_id', $category->category_id)->orderBy('name')->get();
            return response()->json(compact('category', 'categoryList', 'subCategoryList'));
        } else {
            return response()->json(['message' => 'Child Category not found'], 422);
        }
    }

    public function updateChildCategory(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'categoryId' => 'required|numeric',
            'subCategoryId' => 'required|numeric',
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $childCategory = ChildCategory::where('id', $id)->first();
        $childCategory->category_id = $request->categoryId;
        $childCategory->sub_category_id = $request->subCategoryId;
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name) . Str::random(1);
        $childCategory->status = $request->status;
        $childCategory->save();
        return response()->json('Child category successfully updated');
    }

    public function deleteChildCategory(Request $request)
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
            $childCategory = ChildCategory::where('id', $id)->first();
            if (isset($childCategory)) {
                $childCategory->delete();
            } else {
                return response()->json(['message' => 'Child category not found'], 422);
            }
        }
        return response()->json('Child category successfully deleted');
    }

    public function searchChildCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $categories = ChildCategory::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('category', 'subCategory')->latest()->paginate(500);
        return response()->json(compact('categories'));
    }

    public function statusChildCategory($id)
    {
        $this->authorize('admin');
        $childCategory = ChildCategory::where('id', $id)->first();
        $childCategory->status = !$childCategory->status;
        $childCategory->save();
        return response()->json('Child category status successfully changed');
    }
}
