<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Smartexercise;

class SmartexerciseController extends Controller
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
        $results = Smartexercise::select('idse','nmfile', 'keterangan', 'jenjang', 'jenjang2', 'kurikulum')
                                    ->where("nmfile", "LIKE","%$keyword%")
                                    ->orWhere("keterangan", "LIKE","%$keyword%")
                                    ->orderBy('idse', 'asc')
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

        if ($request->myFile['uploadFile']){
            return Smartexercise::create([ 
                'nmfile' => $request->nmfile,
                'keterangan' => $request->keterangan,
                'jenjang' => $request->jenjang,
                'jenjang2' => $request->jenjang2,
                'kurikulum' => $request->kurikulum,
                'uploadFile' => $request->myFile['uploadFile'],
                'fileName' => $request->myFile['fileName'],
                'fileType' => $request->myFile['fileType'],
            ]);
        } else {
            return Smartexercise::create([ 
                'nmfile' => $request->nmfile,
                'keterangan' => $request->keterangan,
                'jenjang' => $request->jenjang,
                'jenjang2' => $request->jenjang2,
                'kurikulum' => $request->kurikulum,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Smartexercise::where('idse', $id)
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

        if ($request->myFile['uploadFile']){
            return Smartexercise::where('idse', $id)
                ->update([ 
                    'nmfile' => $request->nmfile,
                    'keterangan' => $request->keterangan,
                    'jenjang' => $request->jenjang,
                    'jenjang2' => $request->jenjang2,
                    'kurikulum' => $request->kurikulum,
                    'uploadFile' => $request->myFile['uploadFile'],
                    'fileName' => $request->myFile['fileName'],
                    'fileType' => $request->myFile['fileType'],
                ]);
        } else {
            return Smartexercise::where('idse', $id)
                ->update([ 
                    'nmfile' => $request->nmfile,
                    'keterangan' => $request->keterangan,
                    'jenjang' => $request->jenjang,
                    'jenjang2' => $request->jenjang2,
                    'kurikulum' => $request->kurikulum,
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Smartexercise::where('idse', $id)->delete();
    }
}
