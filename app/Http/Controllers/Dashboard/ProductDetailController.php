<?php

namespace App\Http\Controllers\Dashboard;

use App\Debt;
use App\Http\Controllers\Controller;
use App\Price;
use App\Product;
use App\Product_Detail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
//
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
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|integer',
            'stock' => 'required',
            'purchase_price' => 'required',
            'mfgDate' => 'required',
            'expDate' => 'required',
        ]);

        $detail = Product_Detail::create([
            'product_id' => $request['product_id'],
            'batch' => $request['batch'],
            'mfgDate' => $request['mfgDate'],
            'expDate' => $request['expDate'],
            'stock' => $request['stock'],
        ]);

        Product::find($request['product_id'])->increment("stock", (int)$request['stock']);

        $dept = Debt::create([
            'product_detail' => $detail->id,
            'supplier_id' => $request['supplier_id'],
            'purchase_price' => $request['purchase_price'],
            'quantity' => $request['stock'],
            'paid_price' => $request['paid_price'],
            'user_id' => $request['user_id']
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function show(Product_Detail $product_Detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function edit(Product_Detail $product_Detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product_Detail $product_Detail)
    {
        $this->validate($request, [
            'mfgDate' => 'required',
            'expDate' => 'required',
        ]);

        $product_Detail->update([
            'batch' => $request['batch'],
            'mfgDate' => $request['mfgDate'],
            'expDate' => $request['expDate'],
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product_Detail $product_Detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product_Detail $product_Detail)
    {
        $product_Detail->delete();
    }
}
