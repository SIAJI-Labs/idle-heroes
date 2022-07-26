<?php

namespace App\Http\Controllers\Admin;

use App\Traits\FileUploadTraits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    use FileUploadTraits;

    protected $heroModel;
    protected $classModel;
    protected $factionModel;
    private $basePath = 'files';
    private $prefixPath = 'hero';
    private $filePath;

    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->heroModel = new \App\Models\Hero();
        $this->classModel = new \App\Models\HeroClass();
        $this->factionModel = new \App\Models\HeroFaction();
        $this->filePath = $this->basePath.'/'.$this->prefixPath;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.hero.index');
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
            'faction_id' => ['required', 'string', 'exists:'.$this->factionModel->getTable().',uuid'],
            'class_id' => ['required', 'string', 'exists:'.$this->classModel->getTable().',uuid'],
            'avatar' => ['nullable', 'mimes:jpg,jpeg,png,svg,gif,webp', 'max:500'],
            'name' => ['string', 'max:191'],
        ]);

        \DB::transaction(function () use ($request) {
            $avatar = null;
            if ($request->hasFile('avatar') && ! empty($request->avatar)) {
                // Upload File
                $uploaded = $this->requestUpload($request->avatar, $this->filePath, false, null);
                if (! empty($uploaded)) {
                    $avatar = $uploaded['file']['path'];
                }
            }
            // Get Faction
            $faction = $this->factionModel->where(\DB::raw('BINARY `uuid`'), $request->faction_id)
                ->firstOrFail();
            // Get Class
            $class = $this->classModel->where(\DB::raw('BINARY `uuid`'), $request->class_id)
                ->firstOrFail();

            $data = $this->heroModel;
            $data->faction_id = $faction->id;
            $data->class_id = $class->id;
            $data->avatar = $avatar;
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
        $data = $this->heroModel->with('heroFaction', 'heroClass')
            ->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data Fetched',
                'result' => [
                    'data' => $data
                ]
            ]);
        }
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
            'faction_id' => ['required', 'string', 'exists:'.$this->factionModel->getTable().',uuid'],
            'class_id' => ['required', 'string', 'exists:'.$this->classModel->getTable().',uuid'],
            'avatar' => ['nullable', 'mimes:jpg,jpeg,png,svg,gif,webp', 'max:500'],
            'name' => ['string', 'max:191'],
        ]);

        $data = $this->heroModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();
        \DB::transaction(function () use ($request, $data) {
            $avatar = null;
            if ($request->hasFile('avatar') && ! empty($request->avatar)) {
                // Upload File
                $uploaded = $this->requestUpload($request->avatar, $this->filePath, false, null);
                if (! empty($uploaded)) {
                    $avatar = $uploaded['file']['path'];
                }
            }
            // Get Faction
            $faction = $this->factionModel->where(\DB::raw('BINARY `uuid`'), $request->faction_id)
                ->firstOrFail();
            // Get Class
            $class = $this->classModel->where(\DB::raw('BINARY `uuid`'), $request->class_id)
                ->firstOrFail();

            $data->faction_id = $faction->id;
            $data->class_id = $class->id;
            $data->avatar = $avatar;
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

        $data = $this->heroModel->query()
            ->with('heroFaction', 'heroClass');
        $last_page = null;
        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
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
