<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use App\Models\Bandeira;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class UnidadeController extends Controller
{
    /**
     * Exibe a lista de unidades
     */
    public function index(): View
    {
        $unidades = Unidade::with(['bandeira.grupoEconomico'])->paginate(10);
        $bandeiras = Bandeira::all();
        
        return view('unidade.index', compact('unidades', 'bandeiras'));
    }

    /**
     * Exibe o formulário para criar uma nova unidade
     */
    public function create(): View
    {
        $bandeiras = Bandeira::with('grupoEconomico')->get();
        return view('unidade.create', compact('bandeiras'));
    }

    /**
     * Armazena uma nova unidade no banco de dados
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome_fantasia' => 'required|string|max:255',
                'razao_social' => 'required|string|max:255',
                'cnpj' => 'required|string|size:18|unique:unidades,cnpj',
                'bandeira_id' => 'required|exists:bandeiras,id',
            ], [
                'nome_fantasia.required' => 'O nome fantasia é obrigatório.',
                'razao_social.required' => 'A razão social é obrigatória.',
                'cnpj.required' => 'O CNPJ é obrigatório.',
                'cnpj.unique' => 'Já existe uma unidade com este CNPJ.',
                'cnpj.size' => 'O CNPJ deve ter 18 caracteres (formato: XX.XXX.XXX/XXXX-XX).',
                'bandeira_id.required' => 'A bandeira é obrigatória.',
                'bandeira_id.exists' => 'A bandeira selecionada não existe.',
            ]);

            // Validação adicional de CNPJ
            $unidade = new Unidade($validatedData);
            if (!$unidade->isValidCnpj()) {
                return redirect()->back()
                    ->with('error', 'CNPJ inválido.')
                    ->withInput();
            }

            Unidade::create($validatedData);

            return redirect()->route('unidade.index')
                ->with('success', 'Unidade criada com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar unidade: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe uma unidade específica
     */
    public function show(Unidade $unidade): View
    {
        $unidade->load(['bandeira.grupoEconomico', 'colaboradores']);
        return view('unidade.show', compact('unidade'));
    }

    /**
     * Exibe o formulário para editar uma unidade
     */
    public function edit(Unidade $unidade): View
    {
        $bandeiras = Bandeira::with('grupoEconomico')->get();
        return view('unidade.edit', compact('unidade', 'bandeiras'));
    }

    /**
     * Atualiza uma unidade no banco de dados
     */
    public function update(Request $request, Unidade $unidade): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome_fantasia' => 'required|string|max:255',
                'razao_social' => 'required|string|max:255',
                'cnpj' => 'required|string|size:18|unique:unidades,cnpj,' . $unidade->id,
                'bandeira_id' => 'required|exists:bandeiras,id',
            ], [
                'nome_fantasia.required' => 'O nome fantasia é obrigatório.',
                'razao_social.required' => 'A razão social é obrigatória.',
                'cnpj.required' => 'O CNPJ é obrigatório.',
                'cnpj.unique' => 'Já existe uma unidade com este CNPJ.',
                'cnpj.size' => 'O CNPJ deve ter 18 caracteres (formato: XX.XXX.XXX/XXXX-XX).',
                'bandeira_id.required' => 'A bandeira é obrigatória.',
                'bandeira_id.exists' => 'A bandeira selecionada não existe.',
            ]);

            // Validação adicional de CNPJ
            $unidadeTemp = new Unidade($validatedData);
            if (!$unidadeTemp->isValidCnpj()) {
                return redirect()->back()
                    ->with('error', 'CNPJ inválido.')
                    ->withInput();
            }

            $unidade->update($validatedData);

            return redirect()->route('unidade.index')
                ->with('success', 'Unidade atualizada com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar unidade: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove uma unidade do banco de dados
     */
    public function destroy(Unidade $unidade): RedirectResponse
    {
        try {
            // Verifica se a unidade possui colaboradores associados
            if ($unidade->hasColaboradores()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir esta unidade pois ela possui colaboradores associados.');
            }

            $unidade->delete();

            return redirect()->route('unidade.index')
                ->with('success', 'Unidade excluída com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir unidade: ' . $e->getMessage());
        }
    }

    /**
     * Busca unidades via AJAX
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search', '');
            
            $unidades = Unidade::with(['bandeira.grupoEconomico'])
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->where(function ($q) use ($searchTerm) {
                        $q->byNomeFantasia($searchTerm)
                          ->orWhere->byRazaoSocial($searchTerm)
                          ->orWhere->byCnpj($searchTerm);
                    });
                })
                ->get()
                ->map(function ($unidade) {
                    return [
                        'id' => $unidade->id,
                        'nome' => $unidade->nome_fantasia,
                        'razao_social' => $unidade->razao_social,
                        'cnpj_formatado' => $unidade->cnpj_formatado,
                        'bandeira' => $unidade->bandeira->nome,
                        'grupo_economico' => $unidade->bandeira->grupoEconomico->nome,
                        'total_colaboradores' => $unidade->total_colaboradores,
                        'ativa' => $unidade->isAtiva(),
                    ];
                });

            return response()->json($unidades);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar unidades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todas as unidades para uso em selects
     */
    public function listAll(): JsonResponse
    {
        try {
            $unidades = Unidade::select('id', 'nome_fantasia', 'razao_social')
                ->orderBy('nome_fantasia')
                ->get()
                ->map(function ($unidade) {
                    return [
                        'id' => $unidade->id,
                        'nome' => $unidade->nome_completo,
                    ];
                });

            return response()->json($unidades);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar unidades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra unidades com base nos parâmetros fornecidos
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $filtros = $request->only(['nome_fantasia', 'razao_social', 'cnpj', 'bandeira_id']);
            
            $unidades = Unidade::filtrar($filtros)
                ->with(['bandeira.grupoEconomico'])
                ->paginate($request->get('per_page', 10));

            return response()->json($unidades);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao filtrar unidades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas das unidades
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_unidades' => Unidade::count(),
                'unidades_ativas' => Unidade::whereHas('colaboradores')->count(),
                'unidades_inativas' => Unidade::whereDoesntHave('colaboradores')->count(),
                'total_colaboradores' => Unidade::withCount('colaboradores')->get()->sum('colaboradores_count'),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna os colaboradores de uma unidade específica
     */
    public function colaboradores(Unidade $unidade): JsonResponse
    {
        try {
            $colaboradores = $unidade->colaboradores()
                ->get()
                ->map(function ($colaborador) {
                    return [
                        'id' => $colaborador->id,
                        'nome' => $colaborador->nome,
                        'email' => $colaborador->email,
                        'cpf_formatado' => $colaborador->cpf_formatado,
                        'iniciais' => $colaborador->iniciais,
                    ];
                });

            return response()->json($colaboradores);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter colaboradores da unidade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valida CNPJ via AJAX
     */
    public function validateCnpj(Request $request): JsonResponse
    {
        try {
            $cnpj = $request->get('cnpj');
            $unidadeId = $request->get('unidade_id');
            
            // Verifica se já existe
            $exists = Unidade::where('cnpj', $cnpj)
                ->when($unidadeId, function ($query) use ($unidadeId) {
                    return $query->where('id', '!=', $unidadeId);
                })
                ->exists();

            if ($exists) {
                return response()->json([
                    'valid' => false,
                    'message' => 'CNPJ já cadastrado.'
                ]);
            }

            // Valida formato
            $unidade = new Unidade(['cnpj' => $cnpj]);
            $isValid = $unidade->isValidCnpj();

            return response()->json([
                'valid' => $isValid,
                'message' => $isValid ? 'CNPJ válido.' : 'CNPJ inválido.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Erro ao validar CNPJ: ' . $e->getMessage()
            ], 500);
        }
    }
}

