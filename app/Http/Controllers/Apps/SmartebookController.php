<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Smartebook;

class SmartebookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Smartebook::where('deleted', 0)
            ->orderBy('id', 'asc')
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
            'nama' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);

        return Smartebook::create([ 
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'jenjang1' => $request->jenjang1,
            'jenjang2' => $request->jenjang2,
            'kurikulum' => $request->kurikulum
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
        return Smartebook::where('deleted', 0)
            ->where('id', $id)
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
            'nama' => 'required|max:500',
            'keterangan' => 'required|max:500'
        ]);

        return Smartebook::where('deleted', 0)
            ->where('id', $id)
            ->update([ 
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'jenjang1' => $request->jenjang1,
                'jenjang2' => $request->jenjang2,
                'kurikulum' => $request->kurikulum
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
        return Smartebook::where('deleted', 0)
            ->where('id', $id)
            ->update(['deleted' => 1]);
    }
}
