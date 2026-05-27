<?php

use App\Enums\ServiceType;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Client;
use App\Http\Controllers\DeliverableController;
use App\Http\Controllers\Freelance;
use App\Http\Controllers\ProfileController;
use App\Livewire\Public\BriefWizard;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/up', fn() => response('', 204))->name('health');

// Site public
Route::get('/', fn() => view('public.home'))->name('home');
Route::get('/work', fn() => view('public.work'))->name('work');
Route::get('/work/{slug}', function (string $slug) {
    $item = PortfolioItem::published()->where('slug', $slug)->firstOrFail();
    return view('public.work-show', compact('item'));
})->name('work.show');
Route::get('/services', fn() => view('public.services'))->name('services');
Route::get('/services/{slug}', function (string $slug) {
    $service = ServiceType::fromSlug($slug);
    abort_unless($service !== null, 404);
    return view('public.service', ['slug' => $slug, 'serviceEnum' => $service]);
})->name('services.show');
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

// MFA admin — routes hors du middleware mfa pour éviter la boucle
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/mfa', [Admin\MfaController::class, 'showVerify'])->name('mfa.verify');
    Route::post('/mfa', [Admin\MfaController::class, 'verify'])->middleware('throttle:5,1')->name('mfa.check');
    Route::get('/mfa/setup', [Admin\MfaController::class, 'showSetup'])->name('mfa.setup');
    Route::post('/mfa/enable', [Admin\MfaController::class, 'enable'])->name('mfa.enable');
    Route::post('/mfa/disable', [Admin\MfaController::class, 'disable'])->name('mfa.disable');
});

Route::middleware(['auth', 'admin', 'admin.mfa'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leads', [Admin\LeadController::class, 'index'])->name('leads');
    Route::get('/leads/{lead}', [Admin\LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads/{lead}/convert', [Admin\LeadController::class, 'convert'])->name('leads.convert');
    Route::post('/leads/{lead}/no-fit', [Admin\LeadController::class, 'markNoFit'])->name('leads.no-fit');
    Route::get('/freelances', [Admin\FreelanceController::class, 'index'])->name('freelances');
    Route::post('/freelances/{user}/toggle-availability', [Admin\FreelanceController::class, 'toggleAvailability'])->name('freelances.toggle');
    Route::get('/projets/{project}', [Admin\ProjectController::class, 'show'])->name('projects.show');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::post('/deliverables/{deliverable}/approve', [DeliverableController::class, 'approve'])->name('deliverables.approve');
    Route::post('/deliverables/{deliverable}/revision', [DeliverableController::class, 'revision'])->name('deliverables.revision');

    Route::get('/portfolio', [Admin\PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/create', [Admin\PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio', [Admin\PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolio}/edit', [Admin\PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolio}', [Admin\PortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolio}', [Admin\PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    Route::post('/portfolio/{portfolio}/toggle-published', [Admin\PortfolioController::class, 'togglePublished'])->name('portfolio.toggle-published');

    Route::get('/funnel', [Admin\FunnelController::class, 'index'])->name('funnel');

    Route::get('/services', [Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [Admin\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [Admin\ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [Admin\ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [Admin\ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [Admin\ServiceController::class, 'destroy'])->name('services.destroy');
    Route::post('/services/{service}/toggle-active', [Admin\ServiceController::class, 'toggleActive'])->name('services.toggle-active');
});

Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', [Client\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/brief', [Client\BriefController::class, 'create'])->name('brief.create');
    Route::post('/brief', [Client\BriefController::class, 'store'])->name('brief.store');
    Route::get('/projets/{project}', [Client\ProjectController::class, 'show'])->name('projects.show');
    Route::post('/livrables/{deliverable}/approve', [Client\DeliverableController::class, 'approve'])->name('deliverables.approve');
    Route::post('/livrables/{deliverable}/revision', [Client\DeliverableController::class, 'revision'])->name('deliverables.revision');
});

Route::middleware(['auth', 'freelance'])->prefix('freelance')->name('freelance.')->group(function () {
    Route::get('/', [Freelance\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/assignments/{assignment}', [Freelance\DeliverableController::class, 'create'])->name('assignments.show');
    Route::post('/assignments/{assignment}/deliverables', [Freelance\DeliverableController::class, 'store'])->name('deliverables.store');
});

require __DIR__.'/auth.php';
