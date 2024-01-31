<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});



// Parte de GOOGLE
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->user();
    
    $existingUser = User::where('email', $user_google->email)->first();

    if ($existingUser) {
        // El usuario ya existe, puedes iniciar sesión con él
        Auth::login($existingUser);
        return redirect('/dashboard');
    }

    // El usuario no existe, así que lo creamos
    $user = User::create([
        'name' => $user_google->name,
        'email' => $user_google->email,
        'password' => bcrypt(Str::random(12)),
    ]);

    // Luego, iniciamos sesión con el nuevo usuario
    Auth::login($user);

    return redirect('/dashboard');
});


// Parte de FACEBOOK
/*
Route::get('/facebook-auth/redirect', function () {
    return Socialite::driver('facebook')->redirect();
});
 
Route::get('/facebook-auth/callback', function () {
    $user_facebook = Socialite::driver('facebook')->user();
   
    $existingUser = User::where('email', $user_facebook->email)->first();

    if ($existingUser) {
        // El usuario ya existe, puedes iniciar sesión con él
        Auth::login($existingUser);
        return redirect('/dashboard');
    }

    // El usuario no existe, así que lo creamos
    $user = User::create([
        'name' => $user_facebook->name,
        'email' => $user_facebook->email,
        'password' => bcrypt(Str::random(12)),
    ]);

    // Luego, iniciamos sesión con el nuevo usuario
    Auth::login($user);

    return redirect('/dashboard');
});*/


// parte Github

Route::get('/github-auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});
 
Route::get('/github-auth/callback', function () {
    $user_github = Socialite::driver('github')->user();
   
    $existingUser = User::where('email', $user_github->email)->first();


    if ($existingUser) {
        // El usuario ya existe, puedes iniciar sesión con él
        Auth::login($existingUser);
        return redirect('/dashboard');
    }

    // El usuario no existe, así que lo creamos
    $user = User::create([
        'name' => $user_github->name,
        'email' => $user_github->email,
        'password' => bcrypt(Str::random(12)),
    ]);
    
    // Luego, iniciamos sesión con el nuevo usuario
    Auth::login($user);


    return redirect('/dashboard');
});




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
