<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected $guildModel;
    protected $playerModel;
    protected $guildMemberModel;
    protected $associationModel;

    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->guildModel = new \App\Models\Guild();
        $this->playerModel = new \App\Models\Player();
        $this->guildMemberModel = new \App\Models\GuildMember();
        $this->associationModel = new \App\Models\Association();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.system.player.index');
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
            'association_id' => ['required', 'string', 'exists:'.$this->associationModel->getTable().',uuid'],
            'name' => ['required', 'string', 'max:191'],
            'player_id' => ['nullable', 'string', 'max:191']
        ]);

        \DB::transaction(function () use ($request) {
            $association = $this->associationModel->where(\DB::raw('BINARY `uuid`'), $request->association_id)
                ->where('user_id', \Auth::user()->id)
                ->firstOrFail();

            $data = $this->playerModel;
            $data->association_id = $association->id;
            $data->name = $request->name;
            $data->player_identifier = $request->player_id;
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
        $data = $this->playerModel->with('association', 'guildMember', 'guildMember.guild')->where(\DB::raw('BINARY `uuid`'), $id)
            ->whereHas('association', function($q){
                return $q->where('user_id', \Auth::user()->id);
            })
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

        return view('content.system.player.show', [
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
            'association_id' => ['required', 'string', 'exists:'.$this->associationModel->getTable().',uuid'],
            'name' => ['required', 'string', 'max:191'],
            'player_id' => ['nullable', 'string', 'max:191']
        ]);

        \DB::transaction(function () use ($request, $id) {
            $association = $this->associationModel->where(\DB::raw('BINARY `uuid`'), $request->association_id)
                ->where('user_id', \Auth::user()->id)
                ->firstOrFail();

            $data = $this->playerModel->where(\DB::raw('BINARY `uuid`'), $id)
                ->firstOrFail();
            $data->association_id = $association->id;
            $data->name = $request->name;
            $data->player_identifier = $request->player_id;
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

        $data = $this->playerModel->query()
            ->with('guildMember', 'guildMember.guild')
            ->whereHas('association', function($q){
                return $q->where('user_id', \Auth::user()->id);
            });
        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
        }

        if($request->has('action') && $request->action != ''){
            if($request->action === 'guild-member'){
                if($request->has('status') && $request->status === 'member' && $request->has('guild_id') && $request->guild_id != ''){
                    $data->whereHas('guildMember', function($q) use ($request){
                        $guild = $this->guildModel->where(\DB::raw('BINARY `uuid`'), $request->guild_id)
                            ->firstOrFail();
                        return $q->where('guild_id', $guild->id);
                    });
                } else {
                    $data->where(function($q){
                        return $q->whereDoesntHave('guildMember');
                    });
                }
            }
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
    public function jsonGuildHistory(Request $request, $id)
    {
        $data_limit = $request->limit ?? 10;

        $data = $this->guildMemberModel->query()
            ->with('guild')
            ->whereHas('player', function($q) use ($id){
                return $q->where(\DB::raw('BINARY `uuid`'), $id);
            });

        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->whereHas('guild', function($q) use ($request){
                return $q->where('name', 'like', '%'.$request->search.'%');
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
            'data' => $data->orderBy('join', 'desc')->get(),
        ]);
    }
}
