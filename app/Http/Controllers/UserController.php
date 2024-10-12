<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //show register/create form
    public function create(){
        return view('auth.register');
    }

    //create new user
    public function store(Request $request){
        $formFields = $request->validate([
               'name' => ['required', 'min:3'],
               'email' => ['required', 'email', Rule::unique('users', 'email')],
               'password' => 'required|confirmed|min:6'
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // create user
        $user = User::create($formFields);

        //log in 
        Auth::login($user);

        return redirect('/')->with('message', 'User created successfully and Logged in');

       
    }

      //show login/login form
      public function login(){
        return view('auth.login');
    }

   // Authenticate user
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        //attempt to authenticate user
        if(Auth::attempt($formFields)){

            $request->session()->regenerate();
            return redirect('/')->with('message', 'Logged in successfully');
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->onlyInput('email');
    }

    //logout user
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Logged out successfully');
    } 
}


