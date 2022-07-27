<?php

namespace App\Http\Controllers\Admin;

use App\Traits\FileUploadTraits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeroClassController extends Controller
{
    use FileUploadTraits;

    protected $classModel;
    private $basePath = 'files/hero';
    private $prefixPath = 'class';
    private $filePath;

    /**
     * Run this function on load
     */
    public function __construct()
    {
        $this->classModel = new \App\Models\HeroClass();
        $this->filePath = $this->basePath.'/'.$this->prefixPath;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.adm.hero.class.index');
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
            'icon' => ['required', 'mimes:jpg,jpeg,png,svg,gif,webp', 'max:500'],
            'name' => ['string', 'max:191'],
        ]);

        \DB::transaction(function () use ($request) {
            $icon = null;
            if ($request->hasFile('icon') && ! empty($request->icon)) {
                // Upload File
                $uploaded = $this->requestUpload($request->icon, $this->filePath, false, null);
                if (! empty($uploaded)) {
                    $icon = $uploaded['file']['path'];
                }
            }

            $data = $this->classModel;
            $data->icon = $icon;
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
        $data = $this->classModel->where(\DB::raw('BINARY `uuid`'), $id)
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
            'icon' => ['required', 'mimes:jpg,jpeg,png,svg,gif,webp', 'max:500'],
            'name' => ['string', 'max:191'],
        ]);

        $data = $this->classModel->where(\DB::raw('BINARY `uuid`'), $id)
            ->firstOrFail();
        \DB::transaction(function () use ($request, $data) {
            $icon = $data->icon ?? null;
            if ($request->hasFile('icon') && ! empty($request->icon)) {
                // Upload File
                $uploaded = $this->requestUpload($request->icon, $this->filePath, false, $icon);
                if (! empty($uploaded)) {
                    $icon = $uploaded['file']['path'];
                }
            }

            $data->icon = $icon;
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

        $data = $this->classModel->query()
            ->withCount('hero');
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
