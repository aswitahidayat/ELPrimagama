<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function notification()
    {
        session()->set('success','Item created successfully.');

        return view('notification-check');
    }

	
}