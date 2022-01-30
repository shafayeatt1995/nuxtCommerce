<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $subCategories = SubCategory::with('category', 'commission')->latest()->paginate(50);
        return response()->json(compact('subCategories'));
    }
}
