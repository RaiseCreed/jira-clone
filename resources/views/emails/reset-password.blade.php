<h1>Reset Password</h1>
<p>Click the link below to reset the password for your account.</p>
<a href="{{route('password.reset', ['token'=>$token, 'email'=>$email])}}">Reset password</a>