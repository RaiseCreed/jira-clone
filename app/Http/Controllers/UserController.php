<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function addUser(){
        return view('users.add');
    }

    public function addUserPost(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,customer,worker',
        ]);

        $temporaryPassword = Str::random(60);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => $temporaryPassword, 
        ]);

        Mail::send("emails.set-password", ['name' => $user->name, 'token' => $temporaryPassword, 'email' => $user->email], function ($message) use ($user){
            $message->to($user->email);
            $message->subject("New registration");
        });

        return redirect()->route('home')->with('success', 'User created.');
    }

    public function passwordSet($name, $token, $email){
        return view('password.set', compact('name','token','email'));
    }

    public function passwordSetPost(Request $request){
        $request->validate([
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();
        
        if($user){
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return redirect()->route('login');
        }
    }
}
