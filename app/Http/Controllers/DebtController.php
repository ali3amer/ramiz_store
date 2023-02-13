<?php

namespace App\Http\Controllers;

use App\Debt;
use App\Product;
use App\Product_Detail;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return \DB::table('debts')
            ->join('product__details', 'debts.product_detail', '=', 'product__details.id')
            ->join('products', 'product__details.product_id', '=', 'products.id')
            ->where('debts.supplier_id', $request['supplier'])
            ->select('debts.*', 'products.name as product_name')
            ->get();
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
        Debt::find($request['id'])->increment("paid_price", $request['paid_price']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Debt $debt
     * @return \Illuminate\Http\Response
     */
    public function show(Debt $debt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Debt $debt
     * @return \Illuminate\Http\Response
     */
    public function edit(Debt $debt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Debt $debt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Debt $debt)
    {
        $product_detail = Product_Detail::find($debt['product_detail']);
        $product = Product::find($product_detail['product_id']);

        $product->decrement('stock', $request['quantity']);
        $product_detail->decrement('stock', $debt['quantity']);

        $product->increment('stock', $request['quantity']);
        $product_detail->increment('stock', $request['quantity']);
        $debt->update([
            'quantity' => $request['quantity'],
            'purchase_price' => $request['purchase_price'],
            'paid_price' => $request['paid_price'],
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Debt $debt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debt $debt)
    {
        //
    }
}
