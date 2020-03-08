<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;
use App\Invoices;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;

class statisTicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $listDate = [];
        $dateToIndex = array();
        $income = array();
        $outcome = array();
        $total = array();

        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $idx = 0;
        foreach ($period as $dt) {
            array_push($listDate, $dt->format("d/m"));
            $dateToIndex[$dt->format("d/m")] = $idx++;
            array_push($income, 0);
            array_push($outcome, 0);
            array_push($total, 0);
        }

        $transactions = Transactions::orderBy('transaction_id','desc')
        ->join('ql_transactions', 'ql_transactions_transaction_id', '=', 'transaction_id')
        ->where([
            ['transaction_date','>=',$startDate],
            ['transaction_date','<',$endDate]
        ])
        ->select(
            DB::raw('transaction_id as id'),
            DB::raw('"transaction" as type'),
            DB::raw('transaction_date as created_date'),
            DB::raw('SUM(ql_transactions_quantity_bought*ql_transactions_cost) as money'),
            DB::raw('DATE_FORMAT(transaction_date, "%d/%m") as day')
        )
        ->groupBy('transaction_id')
        ->get();

        foreach ($transactions as $row) {
            $outcome[$dateToIndex[$row['day']]] += $row['money'];
            $total[$dateToIndex[$row['day']]] -= $row['money'];
        }

        $invoices = Invoices::orderBy('invoice_id','desc')
        ->where([
            ['invoice_date','>=',$startDate],
            ['invoice_date','<=',$endDate]
        ])
        ->select(
            DB::raw('invoice_id as id'),
            DB::raw('"invoice" as type'),
            DB::raw('invoice_date as created_date'),
            DB::raw('invoice_total as money'),
            DB::raw('DATE_FORMAT(invoice_date, "%d/%m") as day')
        )
        ->get();

        foreach ($invoices as $row) {
            $income[$dateToIndex[$row['day']]] += $row['money'];
            $total[$dateToIndex[$row['day']]] += $row['money'];
        }

        $details = array_merge($transactions->all(), $invoices->all());
        usort($details, function($v1, $v2) { return strcmp($v2['created_date'], $v1['created_date']); });

        // SELECT transaction_id, SUM(ql_transactions.ql_transactions_quantity_bought*ql_transactions.ql_transactions_cost) as money, DATE_FORMAT(transaction_date, '%d/%m') as day FROM `transactions` JOIN ql_transactions ON transactions.transaction_id = ql_transactions.ql_transactions_transaction_id WHERE '2020/02/20' <= transaction_date AND transaction_date <= '2020/02/23 23:59:59' GROUP BY transaction_id
        return response()->json(array(
            'listDate' => $listDate,
            'income' => $income,
            'outcome' => $outcome,
            'total' => $total,
            'details' => $details,
        ),200);
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
