<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $sizes = Size::with('category', 'subCategory')->latest()->paginate(20);
        return response()->json(compact('sizes'));
    }

    public function createSize(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:sizes',
            'categoryId' => 'required|numeric',
            'subCategoryId' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        $size = new Size();
        $size->category_id = $request->categoryId;
        $size->sub_category_id = $request->subCategoryId;
        $size->name = $request->name;
        $size->slug = Str::slug($request->name) . Str::random(1);
        $size->status = $request->status;
        $size->save();
        return response()->json('Size successfully created');
    }

    public function editSize($id)
    {
        $this->authorize('admin');
        $size = Size::where('id', $id)->first();
        if (isset($size)) {
            $categories = Category::orderBy('name')->get();
            $subCategories = SubCategory::where('category_id', $size->category_id)->orderBy('name')->get();
            return response()->json(compact('size', 'categories', 'subCategories'));
        } else {
            return response()->json(['message' => 'Size not found'], 422);
        }
    }

    public function updateSize(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'categoryId' => 'required|numeric',
            'subCategoryId' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        $size = Size::where('id', $id)->first();
        $size->category_id = $request->categoryId;
        $size->sub_category_id = $request->subCategoryId;
        $size->name = $request->name;
        $size->slug = Str::slug($request->name) . Str::random(1);
        $size->status = $request->status;
        $size->save();
        return response()->json('Size successfully updated');
    }

    public function deleteSize(Request $request)
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
            $size = Size::where('id', $id)->first();
            if (isset($size)) {
                $size->delete();
            } else {
                return response()->json(['message' => 'Size not found'], 422);
            }
        }
        return response()->json('Size successfully deleted');
    }

    public function searchSize(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $sizes = Size::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->with('category', 'subCategory')->latest()->paginate(500);
        return response()->json(compact('sizes'));
    }

    public function statusSize($id)
    {
        $this->authorize('admin');
        $size = Size::where('id', $id)->first();
        $size->status = !$size->status;
        $size->save();
        return response()->json('Size status successfully changed');
    }
}
