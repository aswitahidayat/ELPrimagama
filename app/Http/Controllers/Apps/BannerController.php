<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     *
     * Constructor
     *
     */
    protected $request;
    
    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword  = $this->request->input('keyword');
        $results = Banner::where("judul", "LIKE","%$keyword%")
                            ->orWhere("keterangan", "LIKE","%$keyword%")
                            ->orderBy('RecID', 'asc')
                            ->paginate(10);

        $response = [
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem()
            ],
            'data' => $results->all()
        ];

        return $response;
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
        $this->validate($request, [
            'judul' => 'required|max:500',
            'keterangan' => 'required|max:500',
        ]);

        return Banner::create([ 
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'uploadFile' => $request->uploadFile,
            'fileName' => $request->fileName,
            'fileType' => $request->fileType,
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
        return Banner::where('RecID', $id)
                        ->first();
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
        $this->validate($request, [
            'judul' => 'required|max:500',
            'keterangan' => 'required|max:500',
        ]);

        return Banner::where('RecID', $id)
                        ->update([ 
                            'judul' => $request->judul,
                            'keterangan' => $request->keterangan,
                            'uploadFile' => $request->uploadFile,
                            'fileName' => $request->fileName,
                            'fileType' => $request->fileType,
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
        return Banner::where('RecID', $id)->delete();
    }
}
