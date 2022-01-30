<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $currencies = Currency::latest()->paginate(20);
        return response()->json(compact('currencies'));
    }

    public function createCurrency(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:currencies',
            'country' => 'required',
            'symble' => 'required',
            'rate' => 'required',
            'status' => 'required|boolean',
        ]);
        $count = count(Currency::all());

        $currency = new Currency();
        $currency->name = $request->name;
        $currency->country = $request->country;
        $currency->symble = $request->symble;
        $currency->rate = $request->rate;
        $currency->status = $request->status;
        $currency->default = $count > 0 ? false : true;
        $currency->save();
        return response()->json('Currency successfully created');
    }

    public function editCurrency($id)
    {
        $this->authorize('admin');
        $currency = Currency::where('id', $id)->first();
        if (isset($currency)) {
            return response()->json(compact('currency'));
        } else {
            return response()->json(['message' => 'Currency not found'], 422);
        }
    }

    public function updateCurrency(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'country' => 'required',
            'symble' => 'required',
            'rate' => 'required',
            'status' => 'required|boolean',
        ]);

        $currency = Currency::where('id', $id)->first();
        $currency->name = $request->name;
        $currency->country = $request->country;
        $currency->symble = $request->symble;
        $currency->rate = $request->rate;
        $currency->status = $request->status;
        $currency->save();
        return response()->json('Currency successfully updated');
    }

    public function deleteCurrency(Request $request)
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
            $currency = Currency::where('id', $id)->first();
            if (isset($currency)) {
                $currency->delete();
            } else {
                return response()->json(['message' => 'Currency not found'], 422);
            }
        }
        return response()->json('Currency successfully deleted');
    }

    public function searchCurrency(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            "collum" => "required"
        ]);
        $currencies = Currency::where($request->collum, 'LIKE', '%' . $request->keyword . '%')->latest()->paginate(20);
        return response()->json(compact('currencies'));
    }

    public function statusCurrency($id)
    {
        $this->authorize('admin');
        $currency = Currency::where('id', $id)->first();
        $currency->status = !$currency->status;
        $currency->save();
        return response()->json('Currency status successfully changed');
    }

    public function defaultCurrency($id)
    {
        $this->authorize('admin');
        $exist = Currency::where('default', true)->first();
        if (isset($exist)) {
            $exist->default = false;
            $exist->save();
        }
        $currency = Currency::where('id', $id)->first();
        $currency->default = true;
        $currency->save();
        return response()->json('Default currency successfully changed');
    }
}
