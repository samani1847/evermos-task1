<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
 
        return response()->json(['products' => $products]);
    }

    public function show($id)
    {
        $products = DB::table('products')
            ->join('inventories','products.id','=','inventories.product_id')
            ->where('products.id','=', $id)
            ->select('products.name','products.id', 'products.price', 'products.description','inventories.stock')
            ->limit(1)
            ->get();
   
        if (empty($products)) {
            return response()->json('Data not found', 404); 
        }
 
        return response()->json(['product' => $products[0]]);
    }
}
