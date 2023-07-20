<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdtController extends Controller
{
    public function index($token, $custid)
    {
        return Inertia::render('AjuDanaTunai', [
            'token' => $token,
            'custid' => $custid,
        ]);
    }
}
