<?php

namespace App\Http\Controllers\System\GameMode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StarExpeditionParticipationController extends Controller
{
    protected $guildMemberModel;
    protected $starExpeditionModel;
    protected $starExpeditionParticipationModel;
    protected $starExpeditionParticipationProgressModel;
    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->guildMemberModel = new \App\Models\GuildMember();
        $this->starExpeditionModel = new \App\Models\StarExpedition();
        $this->starExpeditionParticipationModel = new \App\Models\StarExpeditionParticipation();
        $this->starExpeditionParticipationProgressModel = new \App\Models\StarExpeditionParticipationProgress();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->has('action') && $request->action === 'participation_point'){
            return $this->storePoint($request);
        } else if($request->has('action') && $request->action === 'participation_map'){
            return $this->storeMap($request);
        }

        $request->validate([
            'star_expedition_id' => ['required', 'string', 'exists:'.$this->starExpeditionModel->getTable().',uuid'],
            'guild_member_id' => ['required', 'string', 'exists:'.$this->guildMemberModel->getTable().',uuid']
        ]);

        $starExpedition = $this->starExpeditionModel->where(\DB::raw('BINARY `uuid`'), $request->star_expedition_id)
            ->firstOrFail();
        $guildMember = $this->guildMemberModel->where(\DB::raw('BINARY `uuid`'), $request->guild_member_id)
            ->firstOrFail();
        // Check duplicate
        $check = $this->starExpeditionParticipationModel->where('star_expedition_id', $starExpedition->id)
            ->where('guild_member_id', $guildMember->id)
            ->first();
        if(!empty($check)){
            // Throw validation error for duplicate data
            return response()->json([
                'message' => 'The guild member is invalid',
                'errors' => [
                    'guild_member_id' => ['Selected Guild member already registered, duplicate entry detected']
                ]
            ], 422);
        }

        \DB::transaction(function () use ($request, $starExpedition, $guildMember) {
            $data = $this->starExpeditionParticipationModel;
            $data->star_expedition_id = $starExpedition->id;
            $data->guild_member_id = $guildMember->id;
            $data->save();
        });

        return response()->json([
            'status' => true,
            'message' => "Data Stored"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePoint(Request $request)
    {
        $request->validate([
            'point_member_id' => ['required', 'string', 'exists:'.$this->starExpeditionParticipationModel->getTable().',uuid'],
            'progress' => ['required', 'string', 'in:day_1,day_2,day_3,day_4,day_5,day_6'],
            'point' => ['required', 'numeric', 'min:0']
        ]);

        $participant = $this->starExpeditionParticipationModel->where(\DB::raw('BINARY `uuid`'), $request->point_member_id)
            ->firstOrFail();

        \DB::transaction(function () use ($request, $participant) {
            $data = $this->starExpeditionParticipationProgressModel->updateOrCreate([
                'star_expedition_participation_id' => $participant->id,
                'key' => $request->progress
            ], [
                'value' => $request->point
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Data Stored'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMap(Request $request)
    {
        $request->validate([
            'participant_member_id' => ['required', 'string', 'exists:'.$this->starExpeditionParticipationModel->getTable().',uuid'],
            'progress' => ['required', 'string', 'in:map_1,map_2,map_3,map_4,map_5,map_6,map_7'],
            'date' => ['required']
        ]);

        $participant = $this->starExpeditionParticipationModel->where(\DB::raw('BINARY `uuid`'), $request->participant_member_id)
            ->firstOrFail();
        $data = \DB::transaction(function () use ($request, $participant) {
            // Validate Date
            $inputDate = date("Y-m-d H:i:s", strtotime($request->date));
            if($inputDate !== date("Y-m-d H:i:s", strtotime('-'))){
                $inputDate = date('Y-m-d H:i:s', strtotime($inputDate));
            }

            return $this->starExpeditionParticipationProgressModel->updateOrCreate([
                'star_expedition_participation_id' => $participant->id,
                'key' => $request->progress
            ], [
                'value' => $inputDate,
                'timezone_offset' => $request->_timezone ?? env('APP_TIMEZONE_OFFSET', null);
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => "Data Stored",
            'result' => [
                'request' => $request->all(),
                'data' => $data
            ]
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
        $data = $this->starExpeditionParticipationModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();

        if($data->starExpeditionParticipationProgress()->exists()){
            $data->starExpeditionParticipationProgress()->delete();
        }

        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data deleted'
        ]);
    }

    /**
     * 
     */
    public function jsonList(Request $request)
    {
        $data_limit = $request->limit ?? 10;
        $data = $this->starExpeditionParticipationModel->query()
            ->with('starExpedition', 'starExpedition.period', 'guildMember', 'guildMember.player');
        $last_page = null;

        // Apply Filter
        if($request->has('star_expedition_id') && $request->star_expedition_id != ''){
            $data->whereHas('starExpedition', function($q) use ($request){
                return $q->where(\DB::raw('BINARY `uuid`'), $request->star_expedition_id);
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
            'data' => $data->get()->map(function($data) use ($request){
                if($request->has('progress_type') && $request->progress_type === 'map'){
                    $maps = [1, 2, 3, 4, 5, 6, 7];
                    foreach($maps as $map){
                        $data['map_'.$map] = !empty($data->getProgress('map_'.$map)) ? $data->getProgress('map_'.$map) : null;
                        $data['map_'.$map.'_tz'] = !empty($data->getProgress('map_'.$map, 'tz')) ? $data->getProgress('map_'.$map, 'tz') : null;
                    }
                } else {
                    $days = [1, 2, 3, 4, 5, 6];
                    foreach($days as $day){
                        $data['day_'.$day] = !empty($data->getProgress('day_'.$day)) ? $data->getProgress('day_'.$day) : null;
                    }
                }

                return $data;
            }),
        ]);
    }
}
