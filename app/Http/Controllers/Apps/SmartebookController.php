<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Smartebook;
use Illuminate\Support\Facades\DB;

class SmartebookController extends Controller
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
        $results = Smartebook::where("nmfile", "LIKE","%$keyword%")
                                ->orWhere("keterangan", "LIKE","%$keyword%")
                                ->orderBy('idsb', 'asc')
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
            'nmfile' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);

        return Smartebook::create([ 
            'nmfile' => $request->nmfile,
            'keterangan' => $request->keterangan,
            'jenjang' => $request->jenjang,
            'jenjang2' => $request->jenjang2,
            'kurikulum' => $request->kurikulum,
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
        return Smartebook::where('idsb', $id)
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
            'nmfile' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);
        //$a = DB::raw("CONVERT(VARBINARY(MAX), '$request->uploadFilez')");

        return Smartebook::where('idsb', $id)
            ->update([ 
                'nmfile' => $request->nmfile,
                'keterangan' => $request->keterangan,
                'jenjang' => $request->jenjang,
                'jenjang2' => $request->jenjang2,
                'kurikulum' => $request->kurikulum,
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
        return Smartebook::where('idsb', $id)->delete();
    }
}
