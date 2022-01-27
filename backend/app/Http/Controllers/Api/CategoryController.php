<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $categories = Category::latest()->paginate(20);
        return response()->json(compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:categories',
            'status' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();
        return response()->json('Category successfully created');
    }

    public function editCategory($id)
    {
        $this->authorize('admin');
        $category = Category::where('id', $id)->first();
        if (isset($category)) {
            return response()->json(compact('category'));
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        $category = Category::where('id', $id)->first();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();
        return response()->json('Category successfully updated');
    }

    public function deleteCategory(Request $request)
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
            $category = Category::where('id', $id)->first();
            if (isset($category)) {
                $category->delete();
            } else {
                return response()->json(['message' => 'Category Not found'], 422);
            }
        }
        return response()->json('Category successfully deleted');
    }

    public function searchCategory(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $categories = Category::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->paginate(20);
        return response()->json(compact('categories'));
    }

    public function statusCategory($id)
    {
        $this->authorize('admin');
        $category = Category::where('id', $id)->first();
        $category->status = !$category->status;
        $category->save();
        return response()->json('Category status successfully changed');
    }
}
