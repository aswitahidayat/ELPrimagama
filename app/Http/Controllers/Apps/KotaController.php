<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kota;

class KotaController extends Controller
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
        $propinsi  = $this->request->input('propinsi');
        if ($propinsi) {
            return Kota::where("Propinsi", "LIKE","$propinsi")
            ->orderBy('RecID')->get();
        } else {
            return Kota::orderBy('RecID')->get();
        }
        
    }

}
