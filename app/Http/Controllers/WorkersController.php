<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkersController extends Controller
{
    public function show(){
        $workers = User::all()->where('role','worker');
        return view('workers.show', compact('workers'));
    }
}
