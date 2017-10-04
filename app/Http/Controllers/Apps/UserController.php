<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where("group_id", 1)
            ->where('type', '<>', 2)
            ->where('type', '<>', 3)
            ->orderBy('id_user')->get();        
        
    }

}
