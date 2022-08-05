<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    protected $associationModel;

    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->associationModel = new \App\Models\Association();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.system.association.index');
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
            'name' => ['required', 'string', 'max:191']
        ]);

        \DB::transaction(function () use ($request) {
            $data = $this->associationModel;
            $data->user_id = \Auth::user()->id;
            $data->name = $request->name;
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Stored'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->associationModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->where('user_id', \Auth::user()->id)
            ->firstOrFail();

        if($request->ajax()){
            return response()->json([
                'status' => true,
                'message' => 'Data Fetched',
                'result' => [
                    'data' => $data
                ]
            ]);
        }

        return view('content.system.association.show', [
            'data' => $data
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
            'name' => ['required', 'string', 'max:191']
        ]);

        \DB::transaction(function () use ($request, $id) {
            $data = $this->associationModel->where(\DB::raw('BINARY `uuid`'), $id)
                ->firstOrFail();
            $data->name = $request->name;
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Updated'
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

        $data = $this->associationModel->query()
            ->where('user_id', \Auth::user()->id);
        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
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
            'data' => $data->orderBy('name', 'asc')->get(),
        ]);
    }
}
