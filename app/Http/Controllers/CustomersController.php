<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function show(){
        $customers = User::where('role', 'customer')->paginate(3);
        return view('customers.show', compact('customers'));
    }
}
