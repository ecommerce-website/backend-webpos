<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;



class EditCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
         $customer = Customer::where('customer_id',$id)
        ->get();

        
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
        $obj = $request->input('customer');
        $customer_fname = $obj->customer_fname;
        $customer_lname = $obj->customer_lname;
        $customer_telephone = $obj->customer_telephone;
        if ($customer_fname === '' && $customer_lname === '' && $customer_telephone === '') {
            return response()->json([
                'error' => [
                    'status' => 1,
                    'message' => 'Hãy cung cấp đủ thông tin'
                ]
            ],422);
        }
         $customer->customer_fname = $obj->customer_fname;
       $customer->customer_lname = $obj->customer_lname;
       $customer->customer_gender = $obj->customer_gender;
       $customer->customer_email =$obj->customer_email;
       $customer->customer_city = $obj->customer_city;
       $customer->customer_mobile = $obj->customer_mobile;
       $customer->customer_telephone = $obj->customer_telephone;
       $customer->customer_street = $obj->customer_street;
       $customer->customer_address = $obj->customer_address;
       $customer->customer_note = $obj->customer_note;
       $customer->customer_birthday = $obj->customer_birthday;
       $customer->save();
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
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$customerToArray)
        ];
    }
    public function transformData($customer) {
       // $show = json_decode(json_encode($customer));
        return [
            'customer_id' => $customer['customer_id'],
            'customer_fname' => $customer['customer_fname'],
            'customer_lname' => $customer['customer_lname'],
            'customer_gender' => $customer['customer_gender'],
            'customer_email' => $customer['customer_email'],
            'customer_city' => $customer['customer_city'],
            'customer_mobile' => $customer['customer_mobile'],
            'customer_telephone' => $customer['customer_telephone'],
            'customer_street' => $customer['customer_street'],
           'customer_address' => $customer['customer_address'],
           'customer_note' => $customer['customer_note'],
           'customer_birthday' => $customer['customer_birthday']

        ];
    }
}
