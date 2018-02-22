<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;

class inventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $all = $request->input('all');
        if(empty($all)){
            $products = Products::with(array(
                'qltags' => function($query){
                    $query->with('tags');
                },
                'qltransactions' => function($query) {
                    $query->orderBy('ql_transactions_id', 'desc');
                }
            ))
            ->orderBy('product_id','asc')
            ->paginate(15);
        } else {
            $products = Products::with(array(
                'qltags' => function($query){
                    $query->with('tags');
                },
                'qltransactions' => function($query) {
                    $query->orderBy('ql_transactions_id', 'desc');
                }
            ))
            ->orderBy('product_id','asc')
            ->get();
        }
        foreach($products as $product){
            $product->product_img = $request->root().'/'.$product->product_img;
            $product->remain_value = 0;
            $product->average_cost = 0;
            $count = 0;
            if($product->product_on_hand > 0){
                foreach($product->qltransactions as $qltransaction){
                    if($count + $qltransaction->ql_transactions_quantity_bought < $product->product_on_hand){
                        $count += $qltransaction->ql_transactions_quantity_bought;
                        $product->remain_value += $qltransaction->ql_transactions_quantity_bought * $qltransaction->ql_transactions_cost;
                    } else {
                        $tmp = $product->product_on_hand - $count;
                        $product->remain_value += $tmp * $qltransaction->ql_transactions_cost;
                        break;
                    }
                }
                $product->average_cost = $product->remain_value / $product->product_on_hand;
            }
        }
        return response()->json($products,200);

    }

    public function query(Request $request){
        $search = $request->input('query');
        if ($search === "") {
            return index($request);
        }
        else {
            $products = Products::with(array(
                'qltags' => function($query){
                    $query->with('tags');
                },
                'qltransactions' => function($query) {
                    $query->orderBy('ql_transactions_id', 'desc');
                }
            ))
            ->where('product_name', 'LIKE', '%'.$search.'%')
            ->orWhere('product_barcode_name', 'LIKE', '%'.$search.'%')
            ->orderBy('product_id','desc')
            ->paginate(15);                
            foreach($products as $product){
                $product->product_img = $request->root().'/'.$product->product_img;
                $product->remain_value = 0;
                $product->average_cost = 0;
                $count = 0;
                if($product->product_on_hand > 0){
                    foreach($product->qltransactions as $qltransaction){
                        if($count + $qltransaction->ql_transactions_quantity_bought < $product->product_on_hand){
                            $count += $qltransaction->ql_transactions_quantity_bought;
                            $product->remain_value += $qltransaction->ql_transactions_quantity_bought * $qltransaction->ql_transactions_cost;
                        } else {
                            $tmp = $product->product_on_hand - $count;
                            $product->remain_value += $tmp * $qltransaction->ql_transactions_cost;
                            break;
                        }
                    }
                    $product->average_cost = $product->remain_value / $product->product_on_hand;
                }
            }
            return response()->json($products, 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
