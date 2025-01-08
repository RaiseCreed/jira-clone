<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordEmail extends Controller
{
    public function passwordEmail(Request $request){
        $email = $request->query('email');
        return view('password.email', compact('email'));
    }

    public function passwordEmailPost(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validated['email'];
        $token = Str::random(64);
        $createdAt = Carbon::now();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => $createdAt,
        ]);

        Mail::send("emails.reset-password", ['token' => $token], function ($message) use ($request){
            $message->to($request->email);
            $message->subject("Reset password");
        });

        return redirect()->route('workers.show')->with('success', 'Email send.');
    }

    public function passwordReset($token){
        return view('password.reset', compact('token'));
    }

    public function passwordResetPost(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $update_password = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if(!$update_password){
            return redirect()->route('password.reset')->with('error', 'Invalid');
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect()->route('login')->with('success', 'New password set.');
    }
}
