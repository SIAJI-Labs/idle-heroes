<?php

namespace App\Http\Controllers\System\GameMode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuildWarParticipationController extends Controller
{
    protected $guildWarModel;
    protected $guildMemberModel;
    protected $guildWarParticipationModel;
    protected $guildWarParticipationPointModel;
    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->guildWarModel = new \App\Models\GuildWar();
        $this->guildMemberModel = new \App\Models\GuildMember();
        $this->guildWarParticipationModel = new \App\Models\GuildWarParticipation();
        $this->guildWarParticipationPointModel = new \App\Models\GuildWarParticipationPoint();
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
        }

        $request->validate([
            'guild_war_id' => ['required', 'string', 'exists:'.$this->guildWarModel->getTable().',uuid'],
            'guild_member_id' => ['required', 'string', 'exists:'.$this->guildMemberModel->getTable().',uuid']
        ]);

        $guildWar = $this->guildWarModel->where(\DB::raw('BINARY `uuid`'), $request->guild_war_id)
            ->firstOrFail();
        $guildMember = $this->guildMemberModel->where(\DB::raw('BINARY `uuid`'), $request->guild_member_id)
            ->firstOrFail();
        // Check duplicate
        $check = $this->guildWarParticipationModel->where('guild_war_id', $guildWar->id)
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

        \DB::transaction(function () use ($request, $guildWar, $guildMember) {
            $data = $this->guildWarParticipationModel;
            $data->guild_war_id = $guildWar->id;
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
            'point_member_id' => ['required', 'string', 'exists:'.$this->guildWarParticipationModel->getTable().',uuid'],
            'progress' => ['required', 'string', 'in:day_1,day_2,day_3,day_4,day_5,day_6'],
            'point' => ['required', 'numeric', 'min:0']
        ]);

        $participant = $this->guildWarParticipationModel->where(\DB::raw('BINARY `uuid`'), $request->point_member_id)
            ->firstOrFail();

        \DB::transaction(function () use ($request, $participant) {
            $data = $this->guildWarParticipationPointModel->updateOrCreate([
                'guild_war_participation_id' => $participant->id,
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
        $data = $this->guildWarParticipationModel->query()
            ->with('guildWar', 'guildWar.period', 'guildMember', 'guildMember.player');
        $last_page = null;

        // Apply Filter
        if($request->has('guild_war_id') && $request->guild_war_id != ''){
            $data->whereHas('guildWar', function($q) use ($request){
                return $q->where(\DB::raw('BINARY `uuid`'), $request->guild_war_id);
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
            'data' => $data->get()->map(function($data){
                $data->day_1 = !empty($data->getProgress('day_1')) ? $data->getProgress('day_1') : null;
                $data->day_2 = !empty($data->getProgress('day_2')) ? $data->getProgress('day_2') : null;
                $data->day_3 = !empty($data->getProgress('day_3')) ? $data->getProgress('day_3') : null;
                $data->day_4 = !empty($data->getProgress('day_4')) ? $data->getProgress('day_4') : null;
                $data->day_5 = !empty($data->getProgress('day_5')) ? $data->getProgress('day_5') : null;
                $data->day_6 = !empty($data->getProgress('day_6')) ? $data->getProgress('day_6') : null;

                return $data;
            }),
        ]);
    }
}
