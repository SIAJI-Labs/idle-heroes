<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuildPlayerController extends Controller
{
    protected $playerModel;
    protected $guildModel;
    protected $guildMemberModel;
    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->playerModel = new \App\Models\Player();
        $this->guildModel = new \App\Models\Guild();
        $this->guildMemberModel = new \App\Models\GuildMember();
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
        $request->validate([
            'action' => ['required', 'string', 'in:join,expel']
        ]);
        if($request->action === 'expel'){
            return $this->update($request, $request->uuid);
        }

        // Add Player to Guild
        $player = $this->playerModel->where(\DB::raw('BINARY `uuid`'), $request->uuid)
            ->firstOrFail();
        $guild = $this->guildModel->where(\DB::raw('BINARY `uuid`'), $request->guild_id)
            ->whereHas('association', function($q){
                return $q->where('user_id', \Auth::user()->id);
            })
            ->firstOrFail();

        $data = $this->guildMemberModel;
        $data->guild_id = $guild->id;
        $data->player_id = $player->id;
        $data->join = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'); //UTC
        $data->timezone_offset = $request->_timezone ?? env('APP_TIMEZONE_OFFSET', null);
        $data->save();

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
        $request->validate([
            'action' => ['required', 'string', 'in:join,expel']
        ]);

        // Remove Player from Guild
        $player = $this->playerModel->where(\DB::raw('BINARY `uuid`'), $request->uuid)
            ->firstOrFail();
        $guild = $this->guildModel->where(\DB::raw('BINARY `uuid`'), $request->guild_id)
            ->whereHas('association', function($q){
                return $q->where('user_id', \Auth::user()->id);
            })
            ->firstOrFail();

        $data = $this->guildMemberModel->where('guild_id', $guild->id)
            ->where('player_id', $player->id)
            ->whereNull('out')
            ->orderBy('join', 'desc')
            ->firstOrFail();
        $data->out = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'); //UTC
        $data->timezone_offset = $request->_timezone ?? env('APP_TIMEZONE_OFFSET', null);
        $data->save();

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
}
