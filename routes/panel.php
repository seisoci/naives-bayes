<?php

use App\Http\Controllers\Panel as Panel;
use Illuminate\Support\Facades\Route;

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

Route::post('logout', [Panel\AuthController::class, 'logout'])->name('logout');
Route::get('logout', [Panel\AuthController::class, 'logout'])->name('logout');

Route::get('/', [Panel\AuthController::class, 'showLoginForm']);
Route::post('/', [Panel\AuthController::class, 'login'])->name('login');

Route::middleware('auth:panel')->group(function () {
  /* Dashboard */
  Route::resource('dashboard', Panel\DashboardContoller::class);

  /* Profile */
  Route::resource('profile', Panel\ProfileController::class);


  Route::resource('profile', Panel\ProfileController::class);
  /* Role Route */
  Route::get('roles/select2', [Panel\RoleController::class, 'select2'])->name('roles.select2');
  Route::resource('roles', Panel\RoleController::class);

  /* Menu Manager Route */
  Route::resource('menu-manager', Panel\MenuManagerController::class);
  Route::post('menu-manager/changeHierarchy', [Panel\MenuManagerController::class, 'changeHierarchy'])->name('menu-manager.changeHierarchy');

  /* User Route */
  Route::get('admins/select2', [Panel\AdminController::class, 'select2'])->name('admins.select2');
  Route::resource('admins', Panel\AdminController::class);

  Route::post('reset-password-users', [Panel\AdminController::class, 'resetpassword'])->name('admins.reset-password-users');
  Route::get('change-password', [Panel\AdminController::class, 'changepassword'])->name('change-password');
  Route::post('update-change-password', [Panel\AdminController::class, 'updatechangepassword'])->name('update-change-password');

  /* Settings */
  Route::get('settings', [Panel\SettingController::class, 'general'])->name('settings.general.index');
  Route::put('settings', [Panel\SettingController::class, 'general_update'])->name('settings.general.update');

  Route::post('naive-bayes/import', [Panel\NaiveBayesController::class, 'import'])->name('heroes.import');
  Route::get('heroes/select2', [Panel\HeroController::class, 'select2'])->name('heroes.select2');
  Route::resource('heroes', Panel\HeroController::class);
  Route::post('naive-bayes/prediksi', [Panel\NaiveBayesController::class, 'prediksi'])->name('naive-bayes.prediksi');
  Route::resource('naive-bayes', Panel\NaiveBayesController::class);
});
