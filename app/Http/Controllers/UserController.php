<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $limit = $request->input('limit')?$request->input('limit'):5;
        $users = User::orderBy('user_id','asc')
        ->select(
            'user_id',
            'user_name',
            'user_pass',
            'user_email',
            'user_company_name',
            'user_owner_name',
            'user_country',
            'created_at',
            'updated_at'
        )
        ->paginate($limit);
        return response()->json($this->transformCollection($users),200);
       
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
    public function transformCollection($users) {
        $usersToArray = $users->toArray();
        return [    
            'current_page' => $usersToArray['current_page'],
            'first_page_url' => $usersToArray['first_page_url'],
            'last_page_url' => $usersToArray['last_page_url'],
            'next_page_url' => $usersToArray['next_page_url'],
            'prev_page_url' => $usersToArray['prev_page_url'],
            'per_page' => $usersToArray['per_page'],
            'from' => $usersToArray['from'],
            'to' => $usersToArray['to'],
            'total' => $usersToArray['total'],
            'status' => 0,
            'messages' => 'Return success!',
            'data' => array_map([$this,'transformData'],$usersToArray['data'])

        ];
    }
    public function transformData($users) {
        $show = json_decode(json_encode($users));
        return [
            'user_id' => $users['user_id'],
            'user_name' => $users['user_name'],
            'user_pass' => $users['user_pass'],
            'user_email' => $users['user_email'],
            'user_company_name' => $users['user_company_name'],
            'user_owner_name' => $users['user_owner_name'],
            'user_country' => $users['user_country'],
            'created_at' => $users['created_at'],
            'updated_at' => $users['updated_at']
           
        ];
    }
}