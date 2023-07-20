<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index($token,$custid){ 

        // return view('Test', [
        //     'key' => $key,
        //     'custid' => $custid
        // ]);

        return Inertia::render('Test', [
            'token' => $token,
            'custid' => $custid,
        ]);
    }
}
