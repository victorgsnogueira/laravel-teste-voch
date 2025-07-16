<?php

namespace App\Http\Controllers;

use App\Models\Bandeira;
use App\Models\GrupoEconomico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class BandeiraController extends Controller
{
    /**
     * Exibe a lista de bandeiras
     */
    public function index(): View
    {
        $bandeiras = Bandeira::with('grupoEconomico')->paginate(10);
        $gruposEconomicos = GrupoEconomico::all();
        
        return view('bandeira.index', compact('bandeiras', 'gruposEconomicos'));
    }

    /**
     * Exibe o formulário para criar uma nova bandeira
     */
    public function create(): View
    {
        $gruposEconomicos = GrupoEconomico::all();
        return view('bandeira.create', compact('gruposEconomicos'));
    }

    /**
     * Armazena uma nova bandeira no banco de dados
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255|unique:bandeiras,nome',
                'grupo_economico_id' => 'required|exists:grupo_economicos,id',
            ], [
                'nome.required' => 'O nome da bandeira é obrigatório.',
                'nome.unique' => 'Já existe uma bandeira com este nome.',
                'grupo_economico_id.required' => 'O grupo econômico é obrigatório.',
                'grupo_economico_id.exists' => 'O grupo econômico selecionado não existe.',
            ]);

            Bandeira::create($validatedData);

            return redirect()->route('bandeira.index')
                ->with('success', 'Bandeira criada com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar bandeira: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe uma bandeira específica
     */
    public function show(Bandeira $bandeira): View
    {
        $bandeira->load(['grupoEconomico', 'unidades']);
        return view('bandeira.show', compact('bandeira'));
    }

    /**
     * Exibe o formulário para editar uma bandeira
     */
    public function edit(Bandeira $bandeira): View
    {
        $gruposEconomicos = GrupoEconomico::all();
        return view('bandeira.edit', compact('bandeira', 'gruposEconomicos'));
    }

    /**
     * Atualiza uma bandeira no banco de dados
     */
    public function update(Request $request, Bandeira $bandeira): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255|unique:bandeiras,nome,' . $bandeira->id,
                'grupo_economico_id' => 'required|exists:grupo_economicos,id',
            ], [
                'nome.required' => 'O nome da bandeira é obrigatório.',
                'nome.unique' => 'Já existe uma bandeira com este nome.',
                'grupo_economico_id.required' => 'O grupo econômico é obrigatório.',
                'grupo_economico_id.exists' => 'O grupo econômico selecionado não existe.',
            ]);

            $bandeira->update($validatedData);

            return redirect()->route('bandeira.index')
                ->with('success', 'Bandeira atualizada com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar bandeira: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove uma bandeira do banco de dados
     */
    public function destroy(Bandeira $bandeira): RedirectResponse
    {
        try {
            // Verifica se a bandeira possui unidades associadas
            if ($bandeira->hasUnidades()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir esta bandeira pois ela possui unidades associadas.');
            }

            $bandeira->delete();

            return redirect()->route('bandeira.index')
                ->with('success', 'Bandeira excluída com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir bandeira: ' . $e->getMessage());
        }
    }

    /**
     * Busca bandeiras via AJAX
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search', '');
            
            $bandeiras = Bandeira::with('grupoEconomico')
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->byNome($searchTerm);
                })
                ->get()
                ->map(function ($bandeira) {
                    return [
                        'id' => $bandeira->id,
                        'nome' => $bandeira->nome,
                        'grupo_economico' => $bandeira->grupoEconomico->nome,
                        'total_unidades' => $bandeira->total_unidades,
                        'ativa' => $bandeira->isAtiva(),
                    ];
                });

            return response()->json($bandeiras);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar bandeiras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todas as bandeiras para uso em selects
     */
    public function listAll(): JsonResponse
    {
        try {
            $bandeiras = Bandeira::select('id', 'nome')
                ->orderBy('nome')
                ->get();

            return response()->json($bandeiras);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar bandeiras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra bandeiras com base nos parâmetros fornecidos
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $filtros = $request->only(['nome', 'grupo_economico_id']);
            
            $bandeiras = Bandeira::filtrar($filtros)
                ->with('grupoEconomico')
                ->paginate($request->get('per_page', 10));

            return response()->json($bandeiras);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao filtrar bandeiras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas das bandeiras
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_bandeiras' => Bandeira::count(),
                'bandeiras_ativas' => Bandeira::whereHas('unidades')->count(),
                'bandeiras_inativas' => Bandeira::whereDoesntHave('unidades')->count(),
                'total_unidades' => Bandeira::withCount('unidades')->get()->sum('unidades_count'),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}

