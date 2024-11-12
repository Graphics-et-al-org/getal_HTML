<?php

use App\Http\Controllers\Auth\Auth0LoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('locallogin', [AuthenticatedSessionController::class, 'create'])
        ->name('locallogin');

    Route::post('locallogin', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Socialite
    // Route::get('auth0login', function () {
    //     $socialite = Socialite::driver('auth0');
    //     return $socialite->redirect();
    //     //return Socialite::driver('auth0')->redirect();
    // });

    // Route::get('callback', function () {
    //    // dd( Socialite::driver('auth0')->user());

    //    // $profile = Auth0::management()->users()->get(auth()->id());
    //     // get the
    //     $user = Socialite::driver('auth0')->user();
    //     $profile = Auth0::management()->users()->get($user->id);
    //     dd($profile);
    //     // $user->token
    // });
    //           Route::get('login/{provider}', [SocialLoginController::class, 'login'])->name('social.login');
    // Route::get('login/{provider}/callback', [SocialLoginController::class, 'login']);
});



Route::middleware(\Illuminate\Session\Middleware\AuthenticateSession::class)->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
});


// Route::group(['middleware' => ['guard:auth0-session']], static function (): void {
//     Route::get('/auth0login', LoginController::class)->name('auth0login');
//     Route::get('/auth0logout', LogoutController::class)->name('auth0logout');
//     Route::get('/callback', [Auth0LoginController::class,  'callback'])->name('callback');
// });
