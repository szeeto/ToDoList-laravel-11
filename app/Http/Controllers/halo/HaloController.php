<?php

namespace App\Http\Controllers\halo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HaloController extends Controller
{
    public function index()
    {
        $nama = "jokowi";
        $data = [
            'nama' => $nama,
        ];

        return view('coba.halo',$data);
    
    }
}
