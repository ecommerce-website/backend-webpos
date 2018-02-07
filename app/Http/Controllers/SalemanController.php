<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class SalemanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
         $saleman = User::where('user_id',$id)
        ->get();

        
        return response()->json($this->transformCollection($saleman),200);
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
      public function transformCollection($saleman) {
        
         $salemanToArray = $saleman->toArray();
        return [  
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$salemanToArray)
        ];
    }
    public function transformData($saleman) {
        $show = json_decode(json_encode($saleman));
        return [
            'user_id' => $saleman['user_id'],
            'user_name' => $saleman['user_name'],
            'user_email' => $saleman['user_email'],
            'user_pass' => $saleman['user_pass'],
            'user_company_name' => $saleman['user_company_name'],
            'user_owner_name' => $saleman['user_owner_name'],
            'user_country' => $saleman['user_country'],
        ];
    }
}
