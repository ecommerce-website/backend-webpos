<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;

class SearchCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customerName = $request->input('customerName');
        // $customerdemo = "pham mai";
        $customer_telephone = $request->input('customerTelephone');
        $customerN = explode(" ", $customerdemo);
        $customer_fname =  $customerN[0];
        $customer_lname = $customerN[1];
        echo $customer_fname;
       
         if ($customerName === null  || $customer_telephone === null) {
               $customer = Customer::orderBy('customer_id','desc')->paginate(10);
            return response()->json($this->transformCollection($customer),200);
        }
        else {
            $customer = Customer::orderBy('customer_id','desc')->where([['customer_fname','LIKE','%'.$customer_fname.'%'],['customer_lname','LIKE','%'.$customer_lname.'%'],['customer_telephone','LIKE','%'.$customer_telephone.'%']])
            ->paginate(10);
            return response()->json($this->transformCollection($customer),200);
        }
         return response()->json($this->transformCollection($customer),200);
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
     public function transformCollection($customer) {
        $customerToArray = $customer->toArray();
        return [    
            'current_page' => $customerToArray['current_page'],
            'first_page_url' => $customerToArray['first_page_url'],
            'last_page_url' => $customerToArray['last_page_url'],
            'next_page_url' => $customerToArray['next_page_url'],
            'prev_page_url' => $customerToArray['prev_page_url'],
            'per_page' => $customerToArray['per_page'],
            'from' => $customerToArray['from'],
            'to' => $customerToArray['to'],
            'total' => $customerToArray['total'],
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$customerToArray['data'])

        ];
    }
     public function transform($customer) {
        return [
            'customer_fname' => $customer['customer_fname'],
            'customer_lname' => $customer['customer_lname'],
            'customer_telephone' => $customer['customer_telephone']
        ];
    }

}
