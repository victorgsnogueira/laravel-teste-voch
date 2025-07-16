<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BandeiraController;
use App\Http\Controllers\GrupoEconomicoController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\ColaboradorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Grupo Econômico
    Route::resource('grupo-economico', GrupoEconomicoController::class);
    Route::get('/grupo-economico/{grupoEconomico}/bandeiras', [GrupoEconomicoController::class, 'bandeiras'])->name('grupo-economico.bandeiras');
    Route::get('/api/grupo-economico/search', [GrupoEconomicoController::class, 'search'])->name('grupo-economico.search');
    Route::get('/api/grupo-economico/list-all', [GrupoEconomicoController::class, 'listAll'])->name('grupo-economico.list-all');
    Route::get('/api/grupo-economico/filter', [GrupoEconomicoController::class, 'filter'])->name('grupo-economico.filter');
    Route::get('/api/grupo-economico/stats', [GrupoEconomicoController::class, 'stats'])->name('grupo-economico.stats');

    // Rotas para Bandeira
    Route::resource('bandeira', BandeiraController::class);
    Route::get('/api/bandeira/search', [BandeiraController::class, 'search'])->name('bandeira.search');
    Route::get('/api/bandeira/list-all', [BandeiraController::class, 'listAll'])->name('bandeira.list-all');
    Route::get('/api/bandeira/filter', [BandeiraController::class, 'filter'])->name('bandeira.filter');
    Route::get('/api/bandeira/stats', [BandeiraController::class, 'stats'])->name('bandeira.stats');

    // Rotas para Unidade
    Route::resource('unidade', UnidadeController::class);
    Route::get('/unidade/{unidade}/colaboradores', [UnidadeController::class, 'colaboradores'])->name('unidade.colaboradores');
    Route::get('/api/unidade/search', [UnidadeController::class, 'search'])->name('unidade.search');
    Route::get('/api/unidade/list-all', [UnidadeController::class, 'listAll'])->name('unidade.list-all');
    Route::get('/api/unidade/filter', [UnidadeController::class, 'filter'])->name('unidade.filter');
    Route::get('/api/unidade/stats', [UnidadeController::class, 'stats'])->name('unidade.stats');
    Route::post('/api/unidade/validate-cnpj', [UnidadeController::class, 'validateCnpj'])->name('unidade.validate-cnpj');

    // Rotas para Colaborador
    Route::resource('colaborador', ColaboradorController::class);
    Route::get('/api/colaborador/search', [ColaboradorController::class, 'search'])->name('colaborador.search');
    Route::get('/api/colaborador/list-all', [ColaboradorController::class, 'listAll'])->name('colaborador.list-all');
    Route::get('/api/colaborador/filter', [ColaboradorController::class, 'filter'])->name('colaborador.filter');
    Route::get('/api/colaborador/stats', [ColaboradorController::class, 'stats'])->name('colaborador.stats');
    Route::post('/api/colaborador/validate-cpf', [ColaboradorController::class, 'validateCpf'])->name('colaborador.validate-cpf');
    Route::post('/api/colaborador/validate-email', [ColaboradorController::class, 'validateEmail'])->name('colaborador.validate-email');
});

require __DIR__.'/auth.php';

