<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $brands = Brand::latest()->paginate(20);
        return response()->json(compact('brands'));
    }

    public function createBrand(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:brands',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/brands/logo/';
        $imageName = $path . $slug . time() . '.webp';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (Image::make($request->logo)->encode('webp', 80)->save($imageName)) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->logo = $imageName;
            $brand->save();
        };

        return response()->json('Brand successfully created');
    }

    public function editBrand($id)
    {
        $this->authorize('admin');
        $brand = Brand::where('id', $id)->first();
        if (isset($brand)) {
            return response()->json(compact('brand'));
        } else {
            return response()->json(['message' => 'Brand not found'], 422);
        }
    }

    public function updateBrand(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
        ]);

        $brand = Brand::where('id', $id)->first();
        $slug = Str::slug($request->name);
        $path = 'images/brands/logo/';

        $image = $request->hasFile('logo');
        if ($image) {
            if (File::exists($brand->logo)) {
                unlink($brand->logo);
            }
            $imageName = $path . $slug . time() . '.webp';
            Image::make($request->logo)->encode('webp', 80)->save($imageName);
        } else {
            $imageName = $brand->logo;
        }

        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->logo = $imageName;
        $brand->save();
        return response()->json('Brand successfully updated');
    }

    public function deleteBrand(Request $request)
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
            $brand = Brand::where('id', $id)->first();
            if (isset($brand)) {
                if (File::exists($brand->logo)) {
                    unlink($brand->logo);
                }
                $brand->delete();
            } else {
                return response()->json(['message' => 'Brand Not found'], 422);
            }
        }
        return response()->json('Brand successfully deleted');
    }

    public function searchBrand(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $brands = Brand::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->paginate(500);
        return response()->json(compact('brands'));
    }
}
