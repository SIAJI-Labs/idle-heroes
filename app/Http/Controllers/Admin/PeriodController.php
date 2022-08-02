<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    protected $periodModel;

    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->periodModel = new \App\Models\Period();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.game-mode.period.index');
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
        $request->validate([
            'period' => ['required', 'string'],
            'length' => ['required', 'numeric', 'min:1']
        ]);

        \DB::transaction(function () use($request) {
            // Calculate Datetime
            $raw = date('Y-m-d H:i:s', strtotime($request->period));
            $timezone = ($request->_timezone ?? env('APP_TIMEZONE_OFFSET', 0));
            // Convert to UTC
            $utc = convertToUtc($raw, $timezone);
            $datetime = date('Y-m-d H:i:00', strtotime($utc));
            $date = date('Y-m-d', strtotime($utc));
            $time = date('H:i:00', strtotime($utc));

            $data = $this->periodModel;
            $data->date = date("Y-m-d", strtotime($datetime));
            $data->time = date("H:i:s", strtotime($datetime));
            $data->datetime = date("Y-m-d H:i:s", strtotime($datetime));
            $data->length = $request->length;
            $data->timezone_offset = $request->_timezone ?? env('APP_TIMEZONE_OFFSET', null);
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Stored',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->periodModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Data Fetched',
            'result' => [
                'data' => $data
            ]
        ]);
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
        $request->validate([
            'period' => ['required', 'string'],
            'length' => ['required', 'numeric', 'min:1']
        ]);

        \DB::transaction(function () use($request, $id) {
            // Calculate Datetime
            $raw = date('Y-m-d H:i:s', strtotime($request->period));
            $timezone = ($request->_timezone ?? env('APP_TIMEZONE_OFFSET', 0));
            // Convert to UTC
            $utc = convertToUtc($raw, $timezone);
            $datetime = date('Y-m-d H:i:00', strtotime($utc));
            $date = date('Y-m-d', strtotime($utc));
            $time = date('H:i:00', strtotime($utc));

            $data = $this->periodModel->where(\DB::raw('BINARY `uuid`'), $id)
                ->firstOrFail();
            $data->date = date("Y-m-d", strtotime($datetime));
            $data->time = date("H:i:s", strtotime($datetime));
            $data->datetime = date("Y-m-d H:i:s", strtotime($datetime));
            $data->length = $request->length;
            $data->timezone_offset = $request->_timezone ?? env('APP_TIMEZONE_OFFSET', null);
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Updated',
        ]);
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

    /**
     * 
     */
    public function jsonList(Request $request)
    {
        $data_limit = $request->limit ?? 10;

        $data = $this->periodModel->query();
        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('date', 'like', '%'.$request->search.'%');
        }

        // Apply Force Sort
        if ($request->has('force_order_column') && ! empty($request->force_order_column)) {
            $data->orderBy($request->force_order_column, $request->force_order ?? 'desc');
        }

        if ($request->has('page')) {
            // If request has page parameter, add paginate to eloquent
            $data->paginate($data_limit);
            // Get last page
            $last_page = $data
                ->paginate($data_limit)
                ->lastPage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'last_page' => $last_page,
            'data' => $data->get(),
        ]);
    }
}
