<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\TiposController;
use App\Http\Controllers\LugaresTController;


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
        return redirect()->intended('/lugarest');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
})->name('login.process');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');



Route::middleware('auth')->group(function () {

    // Provincia
    Route::resource('provincias', ProvinciaController::class);

    // Tipos de Atracción
    Route::resource('tipos', TiposController::class);

    // Lugares Turísticos
    Route::resource('lugarest', LugaresTController::class);

});