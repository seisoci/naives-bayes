<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web as Web;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('logout', [Web\AuthController::class, 'logout'])->name('logout');
Route::get('logout', [Web\AuthController::class, 'logout'])->name('logout');

Route::get('/', [Web\AuthController::class, 'showLoginForm']);
Route::post('/', [Web\AuthController::class, 'login'])->name('login');
Route::post('register', [Web\AuthController::class, 'storeRegister'])->name('register-store');
Route::get('register', [Web\AuthController::class, 'register'])->name('register');
Route::get('forgot-password', [Web\AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('forgot-password', [Web\ResetsPasswordsController::class, 'getResetToken'])->name('forgot-password.store');
Route::get('update-password', [Web\ResetsPasswordsController::class, 'showResetForm'])->name('password.reset');
Route::post('update-password', [Web\ResetsPasswordsController::class, 'resetPassword'])->name('update-password.update');


Route::middleware('auth:web')->group(function () {
  Route::resource('dashboard', Web\DashboardContoller::class);
  Route::resource('examinations', Web\ExaminationController::class)->parameters([
    'examinations' => 'participant'
  ])->only(['index', 'show', 'update']);

  Route::resource('examinations.sections', Web\ExaminationSectionController::class)->parameters([
    'examinations'  => 'participant',
    'sections'      => 'section'
  ])->only(['show', 'update']);

  Route::put('examinations/{participant}/sections/{participant_section}/update-answers', [Web\AnswerController::class, 'update_duration_used'])->name('examination.sections.answers.update_duration_used');
  Route::put('examinations/{participant}/sections/{participant_section}/update-status', [Web\AnswerController::class, 'update_status']);
  Route::resource('examinations.sections.answers', Web\AnswerController::class)->parameters([
    'examinations'  => 'participant',
    'sections'      => 'section'
  ]);

  Route::resource('examinations.sections.discussions', Web\DiscussionController::class)->parameters([
    'examinations'  => 'participant',
    'sections'      => 'section'
  ]);

  Route::resource('results', Web\ResultController::class)->parameters([
    'results' => 'participant'
  ])->only(['index', 'show']);

  Route::get('bulletin', Web\BulletinController::class)->name('bulletin.index');

  Route::resource('profile', Web\ProfileController::class);
  Route::resource('change-password', Web\ChangePasswordController::class);
});
