<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Price;
use App\Product;
use App\Product_Detail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->lessThan != null) {
            return Product::where('stock', '<=', $request->lessThan)->get();
        } elseif ($request->name != null && $request->category != null) {
            return Product::where('name', 'like', '%' . $request->name . '%')->where('category_id', $request->category)->get();
        } elseif ($request->name != null) {
            return Product::where('name', 'like', '%' . $request->name . '%')->get();

        } elseif ($request->category != null) {
            return Product::where('category_id', $request->category)->get();

        } else {
            return Product::all()->keyBy('id');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:products',
//            'barcode' => 'sometimes|integer',
            'category_id' => 'required|integer',
            'purchase_price' => 'required|integer',
            'sale_price' => 'required|integer'
        ]);

        Product::create([
            'barcode' => $request['barcode'],
            'name' => $request['name'],
            'category_id' => $request['category_id'],
            'purchase_price' => $request['purchase_price'],
            'sale_price' => $request['sale_price'],
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Request $request)
    {
        if ($request->screen == "order") {
            return $product->details()->where("stock", "!=", 0)->get()->keyBy("id")->map(function ($item) use ($product) {
                $item["sale_price"] = $product->sale_price;
                return $item;
            });
        } else {
            return $product->details()->get();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191unique:products,name,' . $product->id,
//            'barcode' => 'sometimes|integer',
            'category_id' => 'required|integer',
            'purchase_price' => 'required',
            'sale_price' => 'required',
        ]);
        if ($request->sale_price != $product->sale_price) {
            Price::create([
                'product_id' => $product->id,
                'old_price' => $product->sale_price,
                'new_price' => $request->sale_price,
            ]);
        }
        $product->update([
            'barcode' => $request['barcode'],
            'name' => $request['name'],
            'category_id' => $request['category_id'],
            'purchase_price' => $request['purchase_price'],
            'sale_price' => $request['sale_price'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
