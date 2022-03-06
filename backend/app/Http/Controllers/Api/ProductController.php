<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('seller');
        $products = Product::where('store_id', Auth::user()->store->id)->with('variations.color', 'variations.size', 'productImages', 'category', 'subCategory', 'childCategory', 'brand')->latest()->paginate(20);
        return response()->json(compact('products'));
    }

    public function newProductInfo()
    {
        $this->authorize('seller');
        $categories = Category::orderBy('name')->select('id', 'name')->get();
        $brands = Brand::orderBy('name')->select('id', 'name')->get();
        $materials = Material::orderBy('name')->select('id', 'name')->get();
        $colors = Color::orderBy('name')->select('id', 'name', 'code')->get();
        return response()->json(compact('categories', 'brands', 'materials', 'colors'));
    }

    public function editProductInfo($id)
    {
        $this->authorize('seller');
        $product = Product::where('store_id', Auth::user()->store->id)->where('suspend', false)->with('variations', 'materials', 'productImages')->first();
        if (isset($product)) {
            $categories = Category::orderBy('name')->select('id', 'name')->get();
            $subCategories = SubCategory::where('category_id', $product->category_id)->orderBy('name')->select('id', 'name')->get();
            $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->orderBy('name')->select('id', 'name')->get();
            $brands = Brand::orderBy('name')->select('id', 'name')->get();
            $materials = Material::orderBy('name')->select('id', 'name')->get();
            $colors = Color::orderBy('name')->select('id', 'name', 'code')->get();
            $sizes = Size::where('sub_category_id', $product->sub_category_id)->orderBy('name')->select('id', 'name')->get();
            return response()->json(compact('product', 'categories', 'subCategories', 'childCategories', 'brands', 'materials', 'colors', 'sizes'));
        } else {
            return response()->json(['message' => 'Product not found'], 422);
        }
    }

    public function productChildCategoryList($id)
    {
        $this->authorize('seller');
        $childCategories = ChildCategory::where('sub_category_id', $id)->orderBy('name')->select('id', 'name')->get();
        $sizes = Size::where('sub_category_id', $id)->select('id', 'name')->get();
        return response()->json(compact('childCategories', 'sizes'));
    }

    public function createProduct(Request $request)
    {
        $this->authorize('seller');
        $request->validate([
            'name' => 'required|max:200',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'child_category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'features' => 'required|array|min:1',
            'description' => 'required',
            'items' => 'required|array|min:1',
            'materials' => 'required|array|min:1',
            'specifications' => 'required|array|min:1',
            'weight' => 'required',
            'image' => 'required|array|min:1|max:10',
            'status' => 'required|boolean',
            'variations' => 'array|min:1',
            'tags' => 'array|min:1|max:10',
            'meta' => 'required',
        ]);

        $slug = Str::slug($request->name) . uniqid();

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->store_id = Auth::user()->store->id;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->features = json_encode($request->features);
        $product->description = $request->description;
        $product->items = json_encode($request->items);
        $product->specifications = json_encode($request->specifications);
        $product->weight = $request->weight;
        $product->status = $request->status;
        $product->tags = json_encode($request->tags);
        $product->meta = $request->meta;
        $product->save();
        $product->materials()->attach($request->materials);


        foreach ($request->variations as $data) {
            $variation = new Variation();
            $variation->product_id = $product->id;
            $variation->color_id = $data['color_id'];
            $variation->size_id = $data['size_id'];
            $variation->price = $data['price'];
            $variation->discount = $data['discount'];
            $variation->special_price = $data['special_price'];
            $variation->start_date = $data['start_date'];
            $variation->end_date = $data['end_date'];
            $variation->inventory = $data['inventory'];
            $variation->quantity = $data['quantity'];
            $variation->sku = $data['sku'];
            $variation->save();
        }

        return response()->json(compact('slug'));
    }

    public function uploadProductImage(Request $request, $slug)
    {
        $this->authorize('seller');

        $product = Product::where('slug', $slug)->first();

        if (isset($product)) {
            foreach ($request->images as $image) {
                $path = 'images/products/';
                $imageName = $path . uniqid() . time() . '.webp';

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                if (Image::make($image)->encode('webp', 80)->save($imageName)) {
                    $productImage = new ProductImage();
                    $productImage->user_id = Auth::id();
                    $productImage->product_id = $product->id;
                    $productImage->image = $imageName;
                    $productImage->save();
                }
            }
        }

        return response()->json('product upload successfully');
    }

    public function updateProduct(Request $request, $id)
    {
        $this->authorize('seller');
        $request->validate([
            'name' => 'required|max:200',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'child_category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'features' => 'required|array|min:1',
            'description' => 'required',
            'items' => 'required|array|min:1',
            'materials' => 'required|array|min:1',
            'specifications' => 'required|array|min:1',
            'weight' => 'required',
            'image' => 'required|array|min:1|max:10',
            'status' => 'required|boolean',
            'variations' => 'array|min:1',
            'tags' => 'array|min:1|max:10',
            'meta' => 'required',
        ]);

        $slug = Str::slug($request->name) . uniqid();

        $product = Product::where('id', $id)->where('store_id', Auth::user()->store->id)->first();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->features = json_encode($request->features);
        $product->description = $request->description;
        $product->items = json_encode($request->items);
        $product->specifications = json_encode($request->specifications);
        $product->weight = $request->weight;
        $product->status = $request->status;
        $product->tags = json_encode($request->tags);
        $product->meta = $request->meta;
        $product->save();
        $product->materials()->sync($request->materials);

        foreach ($request->deleteVariations as $data) {
            $variation = Variation::where('id', $data)->delete();
        }

        foreach ($request->newVariations as $data) {
            $variation = new Variation();
            $variation->product_id = $product->id;
            $variation->color_id = $data['color_id'];
            $variation->size_id = $data['size_id'];
            $variation->price = $data['price'];
            $variation->discount = $data['discount'];
            $variation->special_price = $data['special_price'];
            $variation->start_date = $data['start_date'];
            $variation->end_date = $data['end_date'];
            $variation->inventory = $data['inventory'];
            $variation->quantity = $data['quantity'];
            $variation->sku = $data['sku'];
            $variation->save();
        }

        return response()->json(compact('slug'));
    }

    public function updateProductImage(Request $request, $slug)
    {
        $this->authorize('seller');

        $product = Product::where('slug', $slug)->where('store_id', Auth::user()->store->id)->first();
        if (isset($product)) {
            if ($request->deleteImages) {
                foreach ($request->deleteImages as $image) {
                    $existImage = ProductImage::where('id', $image)->first();
                    if (isset($existImage)) {
                        if (File::exists($existImage->image)) {
                            unlink($existImage->image);
                        }
                        $existImage->delete();
                    }
                }
            }

            if (isset($request->uploadImages)) {
                foreach ($request->uploadImages as $image) {
                    $path = 'images/products/';
                    $imageName = $path . uniqid() . time() . '.webp';

                    if (!File::exists($path)) {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }

                    if (Image::make($image)->encode('webp', 80)->save($imageName)) {
                        $productImage = new ProductImage();
                        $productImage->user_id = Auth::id();
                        $productImage->product_id = $product->id;
                        $productImage->image = $imageName;
                        $productImage->save();
                    }
                }
            }
        }

        return response()->json('product update successfully');
    }

    public function statusProduct($id)
    {
        $this->authorize('seller');
        $product = Product::where('id', $id)->first();
        $product->status = !$product->status;
        $product->save();

        return response()->json('product status update successfully');
    }

    public function searchProduct(Request $request)
    {
        $this->authorize('seller');
        $request->validate([
            'collum' => 'required'
        ]);
        $products = Product::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->where('store_id', Auth::user()->store->id)->with('variations')->paginate(500);
        return response()->json(compact('products'));
    }

    public function deleteProduct($id)
    {
        $this->authorize('adminOrSeller');
        $product = Product::where('id', $id)->with('productImages')->first();

        foreach ($product->productImages as $image) {
            if (File::exists($image['image'])) {
                unlink($image['image']);
            }
            $image->delete();
        }
        $product->delete();

        return response()->json('product delete successfully');
    }
}
