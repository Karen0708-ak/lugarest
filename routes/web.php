<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/lugares');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
})->name('login.process');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lugares', fn() => view('lugarestu.index'))->name('lugares.index');
});