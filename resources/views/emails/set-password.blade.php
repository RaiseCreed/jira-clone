<h1>Hello, {{ $name }}</h1>
<p>Click the link below to set the password for your account.</p>
<a href="{{route('password.set', ['name'=>$name, 'token'=>$token, 'email'=>$email])}}">Set password</a>