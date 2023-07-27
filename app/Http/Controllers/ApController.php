<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ApController extends Controller
{
    public function index($token, $custid)
    {
        return Inertia::render('AjuProspek', [
            'token' => $token,
            'custid' => $custid,
        ]);
    }
}
