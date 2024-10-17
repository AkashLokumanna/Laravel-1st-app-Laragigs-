<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Show register/create form
    public function create()
    {
        return view('auth.register');
    }

    // Create new user
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6',
            'role' => 'required|string|in:user,admin,editor', // Validate the role
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create user
        $user = User::create($formFields);

        // Log in 
        Auth::login($user);

        // Redirect based on role
        if ($formFields['role'] === 'admin') {
            return redirect('/')->with('message', 'User created successfully and logged in as Admin');
        } elseif ($formFields['role'] === 'editor') {
            return redirect('/')->with('message', 'User created successfully and logged in as Editor');
        } else {
            return redirect('/')->with('message', 'User created successfully and logged in');
        }
    }

    //Admin page
    public function adminDashboard()
{
    return view('admin.dashboard');
}

    // Editor page
    public function editorDashboard()
    {
        return view('dashboard.editor');
    }

    //users page
    public function users()
    {
        $users = User::all();
        return view('/', compact('users'));
    }

    // Show login form
    public function login()
    {
        return view('auth.login');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate user
        if (Auth::attempt($formFields)) {
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect('/')->with('message', 'Logged in successfully as Admin');
            } elseif ($user->role === 'editor') {
                return redirect('/')->with('message', 'Logged in successfully as Editor');
            } else {
                return redirect('/')->with('message', 'Logged in successfully as User');
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->onlyInput('email');
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Logged out successfully');
    }

    // Uncomment and implement role-based dashboard methods if needed
    /*
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function editorDashboard()
    {
        return view('editor.dashboard');
    }

    public function userDashboard()
    {
        return view('user.dashboard');
    }
    */
}
