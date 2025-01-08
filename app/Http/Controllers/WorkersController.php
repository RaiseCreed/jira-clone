<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkersController extends Controller
{
    public function show(){
        $workers = User::where('role','worker')->paginate(3);
        return view('workers.show', compact('workers'));
    }
}
