<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $colors = Color::latest()->paginate(20);
        return response()->json(compact('colors'));
    }

    public function createColor(Request $request)
    {
        $this->authorize('admin');
        $request->validate(
            [
                'name' => 'required|unique:colors',
                'code' => 'required|unique:colors|min:7|max:7',
                'status' => 'required|boolean',
            ],
            [
                'code.min' => 'Color code not valid',
                'code.max' => 'Color code not valid',
            ]
        );

        $color = new Color();
        $color->name = $request->name;
        $color->slug = Str::slug($request->name);
        $color->code = $request->code;
        $color->status = $request->status;
        $color->save();
        return response()->json('Color successfully created');
    }

    public function editColor($id)
    {
        $this->authorize('admin');
        $color = Color::where('id', $id)->first();
        if (isset($color)) {
            return response()->json(compact('color'));
        } else {
            return response()->json(['message' => 'Color not found'], 422);
        }
    }

    public function updateColor(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate(
            [
                'name' => 'required',
                'code' => 'required|min:7|max:7',
                'status' => 'required|boolean',
            ],
            [
                'code.min' => 'Color code not valid',
                'code.max' => 'Color code not valid',
            ]
        );

        $color = Color::where('id', $id)->first();
        $color->name = $request->name;
        $color->slug = Str::slug($request->name);
        $color->code = $request->code;
        $color->status = $request->status;
        $color->save();
        return response()->json('Color successfully updated');
    }

    public function deleteColor(Request $request)
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
            $color = Color::where('id', $id)->first();
            if (isset($color)) {
                $color->delete();
            } else {
                return response()->json(['message' => 'Color not found'], 422);
            }
        }
        return response()->json('Color successfully deleted');
    }

    public function searchColor(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $colors = Color::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('colors'));
    }

    public function statusColor($id)
    {
        $this->authorize('admin');
        $color = Color::where('id', $id)->first();
        $color->status = !$color->status;
        $color->save();
        return response()->json('Color status successfully changed');
    }
}
