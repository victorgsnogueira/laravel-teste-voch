<?php

namespace App\Http\Controllers;

use App\Models\GrupoEconomico;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GrupoEconomicoController extends Controller
{
    /**
     * Exibe a lista de grupos econômicos
     */
    public function index(): View
    {
        $gruposEconomicos = GrupoEconomico::withCount('bandeiras')->paginate(10);
        return view('grupo-economico.index', compact('gruposEconomicos'));
    }

    /**
     * Exibe o formulário para criar um novo grupo econômico
     */
    public function create(): View
    {
        return view('grupo-economico.create');
    }

    /**
     * Armazena um novo grupo econômico no banco de dados
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255|unique:grupo_economicos,nome',
            ], [
                'nome.required' => 'O nome do grupo econômico é obrigatório.',
                'nome.unique' => 'Já existe um grupo econômico com este nome.',
            ]);

            GrupoEconomico::create($validatedData);

            return redirect()->route('grupo-economico.index')
                ->with('success', 'Grupo econômico criado com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar grupo econômico: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe um grupo econômico específico
     */
    public function show(GrupoEconomico $grupoEconomico): View
    {
        $grupoEconomico->load(['bandeiras.unidades']);
        return view('grupo-economico.show', compact('grupoEconomico'));
    }

    /**
     * Exibe o formulário para editar um grupo econômico
     */
    public function edit(GrupoEconomico $grupoEconomico): View
    {
        return view('grupo-economico.edit', compact('grupoEconomico'));
    }

    /**
     * Atualiza um grupo econômico no banco de dados
     */
    public function update(Request $request, GrupoEconomico $grupoEconomico): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255|unique:grupo_economicos,nome,' . $grupoEconomico->id,
            ], [
                'nome.required' => 'O nome do grupo econômico é obrigatório.',
                'nome.unique' => 'Já existe um grupo econômico com este nome.',
            ]);

            $grupoEconomico->update($validatedData);

            return redirect()->route('grupo-economico.index')
                ->with('success', 'Grupo econômico atualizado com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar grupo econômico: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove um grupo econômico do banco de dados
     */
    public function destroy(GrupoEconomico $grupoEconomico): RedirectResponse
    {
        try {
            // Verifica se o grupo econômico possui bandeiras associadas
            if ($grupoEconomico->hasBandeiras()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir este grupo econômico pois ele possui bandeiras associadas.');
            }

            $grupoEconomico->delete();

            return redirect()->route('grupo-economico.index')
                ->with('success', 'Grupo econômico excluído com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir grupo econômico: ' . $e->getMessage());
        }
    }

    /**
     * Busca grupos econômicos via AJAX
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search', '');
            
            $gruposEconomicos = GrupoEconomico::withCount('bandeiras')
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->byNome($searchTerm);
                })
                ->get()
                ->map(function ($grupoEconomico) {
                    return [
                        'id' => $grupoEconomico->id,
                        'nome' => $grupoEconomico->nome,
                        'total_bandeiras' => $grupoEconomico->total_bandeiras,
                        'total_unidades' => $grupoEconomico->total_unidades,
                        'ativo' => $grupoEconomico->isAtivo(),
                    ];
                });

            return response()->json($gruposEconomicos);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar grupos econômicos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todos os grupos econômicos para uso em selects
     */
    public function listAll(): JsonResponse
    {
        try {
            $gruposEconomicos = GrupoEconomico::select('id', 'nome')
                ->orderBy('nome')
                ->get();

            return response()->json($gruposEconomicos);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar grupos econômicos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra grupos econômicos com base nos parâmetros fornecidos
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $filtros = $request->only(['nome']);
            
            $gruposEconomicos = GrupoEconomico::filtrar($filtros)
                ->withCount('bandeiras')
                ->paginate($request->get('per_page', 10));

            return response()->json($gruposEconomicos);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao filtrar grupos econômicos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas dos grupos econômicos
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_grupos' => GrupoEconomico::count(),
                'grupos_ativos' => GrupoEconomico::whereHas('bandeiras')->count(),
                'grupos_inativos' => GrupoEconomico::whereDoesntHave('bandeiras')->count(),
                'total_bandeiras' => GrupoEconomico::withCount('bandeiras')->get()->sum('bandeiras_count'),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna as bandeiras de um grupo econômico específico
     */
    public function bandeiras(GrupoEconomico $grupoEconomico): JsonResponse
    {
        try {
            $bandeiras = $grupoEconomico->bandeiras()
                ->withCount('unidades')
                ->get()
                ->map(function ($bandeira) {
                    return [
                        'id' => $bandeira->id,
                        'nome' => $bandeira->nome,
                        'total_unidades' => $bandeira->unidades_count,
                        'ativa' => $bandeira->unidades_count > 0,
                    ];
                });

            return response()->json($bandeiras);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter bandeiras do grupo econômico: ' . $e->getMessage()
            ], 500);
        }
    }
}

