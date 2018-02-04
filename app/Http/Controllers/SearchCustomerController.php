<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = $request->input('name')?$request->input('name');
        if($name !== ''){
            $customer = SearchCustomer::select(
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
            =>where('customer_lname','LIKE','%name%');
            =>get();
        }
        else{
            $customer = SearchCustomer::select(
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
            =>get();
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
}
