<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $materials = Material::latest()->paginate(20);
        return response()->json(compact('materials'));
    }

    public function createMaterial(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:materials',
            'status' => 'required|boolean',
        ]);

        $material = new Material();
        $material->name = $request->name;
        $material->slug = Str::slug($request->name);
        $material->status = $request->status;
        $material->save();
        return response()->json('Material successfully created');
    }

    public function editMaterial($id)
    {
        $this->authorize('admin');
        $material = Material::where('id', $id)->first();
        if (isset($material)) {
            return response()->json(compact('material'));
        } else {
            return response()->json(['message' => 'Material not found'], 422);
        }
    }

    public function updateMaterial(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'status' => 'required|boolean',
        ]);

        $material = Material::where('id', $id)->first();
        $material->name = $request->name;
        $material->slug = Str::slug($request->name);
        $material->status = $request->status;
        $material->save();
        return response()->json('Material successfully updated');
    }

    public function deleteMaterial(Request $request)
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
            $material = Material::where('id', $id)->first();
            if (isset($material)) {
                $material->delete();
            } else {
                return response()->json(['message' => 'Material not found'], 422);
            }
        }
        return response()->json('Material successfully deleted');
    }

    public function searchMaterial(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $materials = Material::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('materials'));
    }

    public function statusMaterial($id)
    {
        $this->authorize('admin');
        $material = Material::where('id', $id)->first();
        $material->status = !$material->status;
        $material->save();
        return response()->json('Material status successfully changed');
    }
}
