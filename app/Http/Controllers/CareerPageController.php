<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CareerPageController extends Controller
{
    public function index()
    {
        return view('careers.index');
    }
} 