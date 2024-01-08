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

  /* Category */
  Route::get('categories/select2', [Panel\CategoryController::class, 'select2'])->name('categories.select2');
  Route::resource('categories', Panel\CategoryController::class);

  /* Sub Category */
  Route::get('sub-categories/select2', [Panel\SubCategoryController::class, 'select2'])->name('sub-categories.select2');
  Route::resource('sub-categories', Panel\SubCategoryController::class);

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

  /* Examinations */
  Route::get('users/select2', [Panel\UserController::class, 'select2'])->name('users.select2');
  Route::resource('users', Panel\UserController::class);

  Route::post('reset-password-users', [Panel\AdminController::class, 'resetpassword'])->name('admins.reset-password-users');
  Route::get('change-password', [Panel\AdminController::class, 'changepassword'])->name('change-password');
  Route::post('update-change-password', [Panel\AdminController::class, 'updatechangepassword'])->name('update-change-password');

  /* Scripts */
  Route::post('scripts/{script}/questions/import-answers', [Panel\ScriptQuestionController::class, 'importAnswers'])->name('scripts.questions.import-answers');
  Route::get('scripts/{script}/questions/export-answers', [Panel\ScriptQuestionController::class, 'exportAnswers'])->name('scripts.questions.export-answers');
  Route::post('scripts/{script}/questions/import', [Panel\ScriptQuestionController::class, 'import'])->name('scripts.questions.import');
  Route::get('scripts/{script}/questions/export', [Panel\ScriptQuestionController::class, 'export'])->name('scripts.questions.export');
  Route::get('scripts/select2', [Panel\ScriptController::class, 'select2'])->name('scripts.select2');
  Route::resource('scripts', Panel\ScriptController::class);
  Route::resource('scripts.questions', Panel\ScriptQuestionController::class);
  Route::resource('scripts.questions.answers', Panel\ScriptQuestionAnswerController::class);

  /* Scripts Example */
  Route::get('scripts-example/select2', [Panel\ScriptExampleController::class, 'select2'])->name('scripts-example.select2');
  Route::resource('scripts-example', Panel\ScriptExampleController::class)->parameters(['scripts-example'  => 'script']);
  Route::resource('scripts-example.questions', Panel\ScriptExampleQuestionController::class)->parameters([
    'scripts-example'  => 'script',
    'question' => 'question'
  ]);
  Route::resource('scripts-example.questions.answers', Panel\ScriptExampleQuestionAnswerController::class)->parameters([
    'scripts-example'  => 'script',
    'question' => 'question'
  ]);
  Route::resource('scripts-example.questions.discussions', Panel\ScriptExampleQuestionDiscussionController::class)->parameters([
    'scripts-example'  => 'script',
    'question' => 'question'
  ]);

  /* Examinations */
  Route::resource('examinations', Panel\ExaminationsController::class);
  Route::resource('examinations.sections', Panel\ExaminationSectionController::class);
  Route::get('examinations/{examination}/tickets/{ticket}/enroll', [Panel\ExaminationTicketController::class, 'enroll'])->name('examinations.tickets.enroll');
  Route::resource('examinations.tickets', Panel\ExaminationTicketController::class);
  Route::post('examinations/{examination_id}/participants/{id}/reset-answers', [Panel\ExaminationParticipantController::class, 'resetAnswers']);
  Route::resource('examinations.participants', Panel\ExaminationParticipantController::class);
  Route::resource('examinations.results', Panel\ExaminationResultController::class)->parameters([
    'examination'  => 'examination',
    'results' => 'participant'
  ]);
  Route::resource('examinations.results.sections', Panel\ExaminationResultSectionController::class)->parameters([
    'examination'  => 'examination',
    'results' => 'participant',
    'sections' => 'participant_section',
  ]);

  /* Ticket */
  Route::resource('tickets', Panel\TicketController::class);

  /* Bulletin */
  Route::resource('bulletin', Panel\BulletinController::class);

  /* Normalization */
  Route::post('normalization/import', [Panel\NormalizationController::class, 'import'])->name('normalization.import');
  Route::get('normalization/export-template', [Panel\NormalizationController::class, 'exportTemplate'])->name('normalization.export-template');
  Route::resource('normalization', Panel\NormalizationController::class);

  /* Settings */
  Route::get('settings', [Panel\SettingController::class, 'general'])->name('settings.general.index');
  Route::put('settings', [Panel\SettingController::class, 'general_update'])->name('settings.general.update');

  Route::get('heroes/select2', [Panel\HeroController::class, 'select2'])->name('heroes.select2');
  Route::resource('heroes', Panel\HeroController::class);
  Route::resource('naive-bayes', Panel\NaiveBayesController::class);
});
