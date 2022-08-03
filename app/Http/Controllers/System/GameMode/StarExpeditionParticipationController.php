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
                // Convert to UTC
                $raw = date('Y-m-d H:i:s', strtotime($inputDate));
                $timezone = ($request->_timezone ?? env('APP_TIMEZONE_OFFSET', 0));
                // Convert to UTC
                $utc = convertToUtc($raw, $timezone);
                $datetime = date('Y-m-d H:i:00', strtotime($utc));
                $date = date('Y-m-d', strtotime($utc));
                $time = date('H:i:00', strtotime($utc));

                $inputDate = $datetime;
            }

            return $this->starExpeditionParticipationProgressModel->updateOrCreate([
                'star_expedition_participation_id' => $participant->id,
                'key' => $request->progress
            ], [
                'value' => $inputDate,
                'timezone_offset' => $request->_timezone
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
        //
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
                    $data->map_1 = !empty($data->getProgress('map_1')) ? $data->getProgress('map_1') : null;
                    $data->map_1_tz = !empty($data->getProgress('map_1', 'tz')) ? $data->getProgress('map_1', 'tz') : null;
                    $data->map_2 = !empty($data->getProgress('map_2')) ? $data->getProgress('map_2') : null;
                    $data->map_2_tz = !empty($data->getProgress('map_2', 'tz')) ? $data->getProgress('map_2', 'tz') : null;
                    $data->map_3 = !empty($data->getProgress('map_3')) ? $data->getProgress('map_3') : null;
                    $data->map_3_tz = !empty($data->getProgress('map_3', 'tz')) ? $data->getProgress('map_3', 'tz') : null;
                    $data->map_4 = !empty($data->getProgress('map_4')) ? $data->getProgress('map_4') : null;
                    $data->map_4_tz = !empty($data->getProgress('map_4', 'tz')) ? $data->getProgress('map_4', 'tz') : null;
                    $data->map_5 = !empty($data->getProgress('map_5')) ? $data->getProgress('map_5') : null;
                    $data->map_5_tz = !empty($data->getProgress('map_5', 'tz')) ? $data->getProgress('map_5', 'tz') : null;
                    $data->map_6 = !empty($data->getProgress('map_6')) ? $data->getProgress('map_6') : null;
                    $data->map_6_tz = !empty($data->getProgress('map_6', 'tz')) ? $data->getProgress('map_6', 'tz') : null;
                    $data->map_7 = !empty($data->getProgress('map_7')) ? $data->getProgress('map_7') : null;
                    $data->map_7_tz = !empty($data->getProgress('map_7', 'tz')) ? $data->getProgress('map_7', 'tz') : null;
                } else {
                    $data->day_1 = !empty($data->getProgress('day_1')) ? $data->getProgress('day_1') : null;
                    $data->day_2 = !empty($data->getProgress('day_2')) ? $data->getProgress('day_2') : null;
                    $data->day_3 = !empty($data->getProgress('day_3')) ? $data->getProgress('day_3') : null;
                    $data->day_4 = !empty($data->getProgress('day_4')) ? $data->getProgress('day_4') : null;
                    $data->day_5 = !empty($data->getProgress('day_5')) ? $data->getProgress('day_5') : null;
                    $data->day_6 = !empty($data->getProgress('day_6')) ? $data->getProgress('day_6') : null;
                }

                return $data;
            }),
        ]);
    }
}
