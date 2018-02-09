<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit')?$request->input('limit'):5;
        $customers = Customer::orderBy('customer_id','asc')
        ->select(
            'customer_id',
            'customer_fname',
            'customer_lname',
            'customer_gender',
            'customer_email',
            'customer_city',
            'customer_mobile',
            'customer_telephone',
            'customer_street',
            'customer_address',
            'customer_note',
            'customer_birthday'

        )
        ->paginate($limit);
        return response()->json($this->transformCollection($customers),200);
       
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
       $customer = new Customer;
       $customers = $request->input('customer');

       if($customers->customer_fname === "" && 
          $customers->customer_lname === "" &&
          $customers->customer_telephone === ""){
        return Response::json([
            'error' => [
                'status'=>1,
                'message'=>'Hãy điền đầy đủ thông tin'
                ]
            ],422);

       }
       $customer_id = Customer::select('customer_id')->max('customer_id')+1;
       $customer->customer_fname = $customers->customer_fname;
       $customer->customer_lname = $customers->customer_lname;
       $customer->customer_gender = $customers->customer_gender;
       $customer->customer_email =$customers->customer_email;
       $customer->customer_city = $customers->customer_city;
       $customer->customer_mobile = $customers->customer_mobile;
       $customer->customer_telephone = $customers->customer_telephone;
       $customer->customer_street = $customers->customer_street;
       $customer->customer_address = $customers->customer_address;
       $customer->customer_note = $customers->customer_note;
       $customer->customer_birthday = $customers->customer_birthday;
       $customer->save();
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
       //  if(!$request=>input('customer_fname')||
       //    !$request=>input('customer_lname')){
       //  return Response::json([
       //      'error' => [
       //          'status'=>1,
       //          'message'=>'Hãy điền đầy đủ tên'
       //          ]
       //      ],422);

       // }
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
        for ($i = 0;$i < count($listCustomer);$i++) {
            $customer = Customer::find($listCustomer[$i]);
            $customer->delete();
        }
    }
     public function transformCollection($customers) {
        $customersToArray = $customers->toArray();
        return [    
            'current_page' => $customersToArray['current_page'],
            'first_page_url' => $customersToArray['first_page_url'],
            'last_page_url' => $customersToArray['last_page_url'],
            'next_page_url' => $customersToArray['next_page_url'],
            'prev_page_url' => $customersToArray['prev_page_url'],
            'per_page' => $customersToArray['per_page'],
            'from' => $customersToArray['from'],
            'to' => $customersToArray['to'],
            'total' => $customersToArray['total'],
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$customersToArray['data'])

        ];
    }
    public function transformData($customers) {
        $show = json_decode(json_encode($customers));
        return [
            'customer_id' => $customers['customer_id'],
            'customer_fname' => $customers['customer_fname'],
            'customer_lname' => $customers['customer_lname'],
            'customer_gender' => $customers['customer_gender'],
            'customer_email' => $customers['customer_email'],
            'customer_city' => $customers['customer_city'],
            'customer_mobile' => $customers['customer_mobile'],
            'customer_telephone' => $customers['customer_telephone'],
            'customer_street' => $customers['customer_street'],
           'customer_address' => $customers['customer_address'],
           'customer_note' => $customers['customer_note'],
           'customer_birthday' => $customers['customer_birthday']

        ];
    }
}
