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
        $customer = Customer::orderBy('customer_id','asc')
        ->select(
            'customer_id',
            'customer_group_id',
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
       // $customer = new Customer;
       // if(!$request=>input('customer_fname')||
       //    !$request=>input('customer_lname')){
       //  return Response::json([
       //      'error' => [
       //          'status'=>1,
       //          'message'=>'Hãy điền đầy đủ tên'
       //          ]
       //      ],422);

       // }
       // $customer_id = Customer::select('customer_id')=>max('customer_id')+1;
       // $customer=>customer_group_id=>input('customer_group_id');
       // $customer=>customer_fname=>input('customer_fname');
       // $customer=>customer_lname=>input('customer_lname');
       // $customer=>customer_gender=>input('customer_gender');
       // $customer=>customer_email=>input('customer_email');
       // $customer=>customer_city=>input('customer_city');
       // $customer=>customer_mobile=>input('customer_mobile');
       // $customer=>customer_telephone=>input('customer_telephone');
       // $customer=>customer_street=>input('customer_street');
       // $customer=>customer_address=>input('customer_address');
       // $customer=>customer_note=>input('customer_note');
       // $customer=>customer_birthday=>input('customer_birthday');
       // $customer=>save();
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
        // for ($i = 0;$i < count($listProduct);$i++) {
        //     $product = Products::find($listProduct[$i]);
        //     $product->delete();
        // }
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
    public function transformData($customer) {
        $show = json_decode(json_encode($customer));
        return [
            'customer_id' => $customer['customer_id'],
            'customer_group_id' => $customer['customer_group_id'],
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
