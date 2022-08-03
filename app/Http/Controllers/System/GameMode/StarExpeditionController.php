<?php

namespace App\Http\Controllers\System\GameMode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StarExpeditionController extends Controller
{
    protected $guildModel;
    protected $periodModel;
    protected $starExpeditionModel;
    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->guildModel = new \App\Models\Guild();
        $this->periodModel = new \App\Models\Period();
        $this->starExpeditionModel = new \App\Models\StarExpedition();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.system.game-mode.star-expedition.index');
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
            'period_id' => ['required', 'string', 'exists:'.$this->periodModel->getTable().',uuid'],
            'guild_id' => ['required', 'string', 'exists:'.$this->guildModel->getTable().',uuid'],
        ]);

        $period = $this->periodModel->where(\DB::raw('BINARY `uuid`'), $request->period_id)
            ->firstOrFail();
        $guild = $this->guildModel->where(\DB::raw('BINARY `uuid`'), $request->guild_id)
            ->firstOrFail();

        // Check combination
        $checkCombination = \App\Models\StarExpedition::where('period_id', $period->id)
            ->where('guild_id', $guild->id)
            ->first();
        if(!empty($checkCombination)){
            // Throw validation error for duplicate data
            return response()->json([
                'message' => 'The guild id is invalid',
                'errors' => [
                    'guild_id' => ['Selected Guild already had same data with selected period, duplicate entry detected']
                ]
            ], 422);
        }

        \DB::transaction(function () use($request, $period, $guild) {
            $data = $this->starExpeditionModel;
            $data->period_id = $period->id;
            $data->guild_id = $guild->id;
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => "Data Stored"
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
        $data = $this->starExpeditionModel->with('period', 'guild', 'guild.association')
            ->where(\DB::raw('BINARY `uuid`'), $id)
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
        
        return view('content.system.game-mode.star-expedition.show', [
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
            'period_id' => ['required', 'string', 'exists:'.$this->periodModel->getTable().',uuid'],
            'guild_id' => ['required', 'string', 'exists:'.$this->guildModel->getTable().',uuid'],
        ]);

        $period = $this->periodModel->where(\DB::raw('BINARY `uuid`'), $request->period_id)
            ->firstOrFail();
        $guild = $this->guildModel->where(\DB::raw('BINARY `uuid`'), $request->guild_id)
            ->firstOrFail();
        $data = $this->starExpeditionModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();

        // Check combination
        if($guild->id != $data->guild_id){
            $checkCombination = \App\Models\StarExpedition::where('period_id', $period->id)
                ->where('guild_id', $guild->id)
                ->first();
            if(!empty($checkCombination)){
                // Throw validation error for duplicate data
                return response()->json([
                    'message' => 'The guild id is invalid',
                    'errors' => [
                        'guild_id' => ['Selected Guild already had same data with selected period, duplicate entry detected']
                    ]
                ], 422);
            }
        }

        \DB::transaction(function () use($request, $period, $guild, $data) {
            $data->period_id = $period->id;
            $data->guild_id = $guild->id;
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => "Data Stored"
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
        $data = $this->starExpeditionModel->query()
            ->with('period', 'guild', 'guild.association');
        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->whereHas('guild', function($q) use ($request){
                return $q->where('name', 'like', '%'.$request->search.'%');
            })->orWhereHas('period', function($q) use ($request){
                return $q->where('datetime', 'like', '%'.$request->search.'%');
            });
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
            'data' => $data->get()->sortByDesc(function($q){
                return $q->period->datetime;
            }),
        ]);
    }
}
