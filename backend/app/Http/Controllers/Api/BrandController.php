<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $brands = Brand::latest()->paginate(50);
        return response()->json(compact('brands'));
    }

    public function createBrand(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:brands',
            'logo' => 'required',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->save();
        return response()->json('Brand successfully created');
    }

    public function editBrand($id)
    {
        $this->authorize('admin');
        $brand = Brand::where('id', $id)->first();
        if (isset($brand)) {
            return response()->json(compact('brand'));
        } else {
            return response()->json(['message' => 'Brand not found'], 404);
        }
    }

    public function updateeBrand(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'logo' => 'required',
        ]);

        $brand = Brand::where('id', $id)->first();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->save();
        return response()->json('Brand successfully updated');
    }

    public function deleteBrand(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "idList" => "required|array|min:1"
        ]);
        foreach ($request->idList as $id) {
            $brand = Brand::where('id', $id)->first();
            if (isset($brand)) {
                $brand->delete();
            } else {
                return response()->json(['message' => 'Brand Not found'], 422);
            }
        }
        return response()->json('Brand successfully deleted');
    }
}
