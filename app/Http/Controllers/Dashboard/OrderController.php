<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Order;
use App\Order_Detail;
use App\Product;
use App\Product_Detail;
use App\Update_Order;
use App\Update_Order_Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id != '') {
            return Order::where('id', $request->id)->get();
        } else {
            return Order::paginate(5);
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
            'client_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        return Order::create(['client_id' => $request->client_id, 'user_id' => $request->user_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
//        $order = DB::table('order__details')
//            ->join('products', 'order__details.product_id', '=', 'products.id')
//            ->select('order__details.*', 'products.name')->orderBy('order__details.id')
//            ->where('order__details.order_id', $order->id)->get();
//
//        dd($order);

//        return $order->details()->get()->keyBy('product_id');
//        return response()->json(['categories' => $categories, 'sumPrice' => $sumPrice]);
//        return $order->details()->join('products', 'product_detail', '=', 'products.id')->get(['order__details.*', 'products.name', 'order__details.product_detail as product_id'])->keyBy('product_detail');
//        return Order_Detail::where("order_id", $order['id'])->get();

        return Order_Detail::join('product__details', 'order__details.product_detail', '=', 'product__details.id')
            ->join('products', 'product__details.product_id', '=', 'products.id')
            ->select('order__details.*', 'products.name as product_name')
            ->where("order__details.order_id", $order['id'])
            ->get();



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if (Order_Detail::where('order_id', $order->id)->count() > 0) {


            foreach ($request->order as $item) {
                $quantity = (int)$item['quantity'];
                $order_quantity = Order_Detail::where('order_id', $order->id)->where('product_detail', $item['product_detail'])->first();
                if ($order_quantity != null) {
                    Product_Detail::find($item['product_detail'])->increment("stock", $quantity);
                    Product::find($item['product_id'])->increment("stock", $quantity);
                }
            }
        }
        Order_Detail::where('order_id', $request->order_id)->delete();
        foreach ($request->order as $item) {
            Order_Detail::create([
                'order_id' => $request['order_id'],
                'product_detail' => $item['product_detail'],
                'sale_price' => $item['sale_price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'paid_price' => $item['paid_price']
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order_details = Order_Detail::where('order_id', $order->id)->get();
        foreach ($order_details as $item) {
            $productDetail = Product_Detail::find($item->product_detail)->increment("stock", (int)$order->quantity);
            Product::find($productDetail['product_id'])->increment("stock", (int)$order->quantity);
        }

        $order->delete();
    }
}
