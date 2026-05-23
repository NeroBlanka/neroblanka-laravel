<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Client;
use App\Http\Controllers\DeliverableController;
use App\Http\Controllers\Freelance;
use App\Http\Controllers\ProfileController;
use App\Livewire\Public\BriefWizard;
use Illuminate\Support\Facades\Route;

// Site public
Route::get('/', fn() => view('public.home'))->name('home');
Route::get('/work', fn() => view('public.work'))->name('work');
Route::get('/services', fn() => view('public.services'))->name('services');
Route::get('/services/{slug}', fn(string $slug) => view('public.service', ['slug' => $slug]))->name('services.show');
Route::get('/brief', BriefWizard::class)
    ->middleware('throttle:10,1')
    ->name('brief');
Route::get('/brief/merci', fn() => view('public.brief-merci'))->name('brief.merci');

Route::get('/dashboard', fn() => redirect(match(auth()->user()->role) {
    'admin' => '/admin',
    'freelance' => '/freelance',
    default => '/client',
}))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads', [Admin\LeadController::class, 'index'])->name('leads');
    Route::get('/leads/{lead}', [Admin\LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads/{lead}/convert', [Admin\LeadController::class, 'convert'])->name('leads.convert');
    Route::get('/freelances', [Admin\FreelanceController::class, 'index'])->name('freelances');
    Route::post('/freelances/{user}/toggle-availability', [Admin\FreelanceController::class, 'toggleAvailability'])->name('freelances.toggle');
    Route::get('/projets/{project}', [Admin\ProjectController::class, 'show'])->name('projects.show');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::post('/deliverables/{deliverable}/approve', [DeliverableController::class, 'approve'])->name('deliverables.approve');
    Route::post('/deliverables/{deliverable}/revision', [DeliverableController::class, 'revision'])->name('deliverables.revision');
});

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', [Client\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/brief', [Client\BriefController::class, 'create'])->name('brief.create');
    Route::post('/brief', [Client\BriefController::class, 'store'])->name('brief.store');
    Route::get('/projets/{project}', [Client\ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projets/{project}/approve', [Client\ProjectController::class, 'approve'])->name('projects.approve');
    Route::post('/projets/{project}/revision', [Client\ProjectController::class, 'revision'])->name('projects.revision');
});

Route::middleware(['auth', 'freelance'])->prefix('freelance')->name('freelance.')->group(function () {
    Route::get('/', [Freelance\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/livraison/{assignment}', [Freelance\DeliverableController::class, 'create'])->name('deliverables.create');
    Route::post('/livraison/{assignment}', [Freelance\DeliverableController::class, 'store'])->name('deliverables.store');
});

require __DIR__.'/auth.php';
