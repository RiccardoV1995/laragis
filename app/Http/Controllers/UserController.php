<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User;

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Hash password
        $cryptPassword = bcrypt($request->input('password'));
        $user->password = $cryptPassword;

        $res = $user->save();

        auth()->login($user);

        $message = $res ? 'User created' : 'Error on creation user';

        session()->flash('message', $message);

        return redirect('/');
    }

    /**
     * Show the form for login.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('users/login');
    }

    /**
     * Authorize a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function auth(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            session()->flash('message', 'You are logged in!');

            return redirect('/');
        }

        return back()
            ->withErrors(['email' => 'Invalid credetials'])
            ->onlyInput('email');
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('message', 'You have been logged out!');

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
