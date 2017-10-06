<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\SemuaPesan;

class SemuaPesanController extends Controller
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
        $results = Pesan::select('tblberita.RecID','tblberita.judul', 'tblberita.keterangan', 'tblberita.tanggal', 'tblberita.otori', 'tr_berita.id_berita')
                ->leftJoin('tr_berita', 'tblberita.RecID', '=', 'tr_berita.id_berita')
                ->where("tr_berita.id_berita", "=", NULL)
                ->Where("tblberita.judul", "LIKE","%$keyword%")
                ->orWhere("keterangan", "LIKE","%$keyword%")
                //->orWhere("tblberita.otori", "=","1")
                ->orderBy('tblberita.RecID', 'asc')
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
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
     public function hitungPesan()
     {
        return Pesan::leftJoin('tr_berita', 'tblberita.RecID', '=', 'tr_berita.id_berita')
        ->where("tr_berita.id_berita", "=", NULL)
        ->where("tblberita.otori", "=","4")
        ->count();
        
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
        // $this->validate($request, [
        //     'judul' => 'required|max:500',
        //     'keterangan' => 'required|max:500'
        // ]);

        return PesanTerbaca::create([ 
            'otori' => $request->otori,
            'id_berita' => $request->RecID,
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
        return Pesan::where('RecID', $id)
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
        // $this->validate($request, [
        //     'Judul' => 'required|max:500',
        //     'Keterangan' => 'required|max:500'
        // ]);

        
        // return Pesan::where('RecID', $id)
        // ->update([
        //     'Judul' => $request->Judul,
        //     'Keterangan' => $request->Keterangan,
        //     'Tanggal' => $request->Tanggal,
        //     'otori' =>$request->otori,
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return PesanTerbaca::where('RecID', $id)->update(['deleted' => 1,]);
    }
}
