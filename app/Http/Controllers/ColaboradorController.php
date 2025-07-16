<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ColaboradorController extends Controller
{
    /**
     * Exibe a lista de colaboradores
     */
    public function index(): View
    {
        $colaboradores = Colaborador::with(['unidade.bandeira.grupoEconomico'])->paginate(10);
        $unidades = Unidade::with(['bandeira.grupoEconomico'])->get();
        
        return view('colaborador.index', compact('colaboradores', 'unidades'));
    }

    /**
     * Exibe o formulário para criar um novo colaborador
     */
    public function create(): View
    {
        $unidades = Unidade::with(['bandeira.grupoEconomico'])->get();
        return view('colaborador.create', compact('unidades'));
    }

    /**
     * Armazena um novo colaborador no banco de dados
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:colaboradors,email',
                'cpf' => 'required|string|size:14|unique:colaboradors,cpf',
                'unidade_id' => 'required|exists:unidades,id',
            ], [
                'nome.required' => 'O nome é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ter um formato válido.',
                'email.unique' => 'Já existe um colaborador com este email.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.unique' => 'Já existe um colaborador com este CPF.',
                'cpf.size' => 'O CPF deve ter 14 caracteres (formato: XXX.XXX.XXX-XX).',
                'unidade_id.required' => 'A unidade é obrigatória.',
                'unidade_id.exists' => 'A unidade selecionada não existe.',
            ]);

            // Validação adicional de CPF
            $colaborador = new Colaborador($validatedData);
            if (!$colaborador->isValidCpf()) {
                return redirect()->back()
                    ->with('error', 'CPF inválido.')
                    ->withInput();
            }

            // Validação adicional de email
            if (!$colaborador->isValidEmail()) {
                return redirect()->back()
                    ->with('error', 'Email inválido.')
                    ->withInput();
            }

            Colaborador::create($validatedData);

            return redirect()->route('colaborador.index')
                ->with('success', 'Colaborador criado com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar colaborador: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe um colaborador específico
     */
    public function show(Colaborador $colaborador): View
    {
        $colaborador->load(['unidade.bandeira.grupoEconomico']);
        return view('colaborador.show', compact('colaborador'));
    }

    /**
     * Exibe o formulário para editar um colaborador
     */
    public function edit(Colaborador $colaborador): View
    {
        $unidades = Unidade::with(['bandeira.grupoEconomico'])->get();
        return view('colaborador.edit', compact('colaborador', 'unidades'));
    }

    /**
     * Atualiza um colaborador no banco de dados
     */
    public function update(Request $request, Colaborador $colaborador): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:colaboradors,email,' . $colaborador->id,
                'cpf' => 'required|string|size:14|unique:colaboradors,cpf,' . $colaborador->id,
                'unidade_id' => 'required|exists:unidades,id',
            ], [
                'nome.required' => 'O nome é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ter um formato válido.',
                'email.unique' => 'Já existe um colaborador com este email.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.unique' => 'Já existe um colaborador com este CPF.',
                'cpf.size' => 'O CPF deve ter 14 caracteres (formato: XXX.XXX.XXX-XX).',
                'unidade_id.required' => 'A unidade é obrigatória.',
                'unidade_id.exists' => 'A unidade selecionada não existe.',
            ]);

            // Validação adicional de CPF
            $colaboradorTemp = new Colaborador($validatedData);
            if (!$colaboradorTemp->isValidCpf()) {
                return redirect()->back()
                    ->with('error', 'CPF inválido.')
                    ->withInput();
            }

            // Validação adicional de email
            if (!$colaboradorTemp->isValidEmail()) {
                return redirect()->back()
                    ->with('error', 'Email inválido.')
                    ->withInput();
            }

            $colaborador->update($validatedData);

            return redirect()->route('colaborador.index')
                ->with('success', 'Colaborador atualizado com sucesso!');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar colaborador: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove um colaborador do banco de dados
     */
    public function destroy(Colaborador $colaborador): RedirectResponse
    {
        try {
            $colaborador->delete();

            return redirect()->route('colaborador.index')
                ->with('success', 'Colaborador excluído com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir colaborador: ' . $e->getMessage());
        }
    }

    /**
     * Busca colaboradores via AJAX
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('search', '');
            
            $colaboradores = Colaborador::with(['unidade.bandeira.grupoEconomico'])
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->where(function ($q) use ($searchTerm) {
                        $q->byNome($searchTerm)
                          ->orWhere->byEmail($searchTerm)
                          ->orWhere->byCpf($searchTerm);
                    });
                })
                ->get()
                ->map(function ($colaborador) {
                    return [
                        'id' => $colaborador->id,
                        'nome' => $colaborador->nome,
                        'email' => $colaborador->email,
                        'cpf_formatado' => $colaborador->cpf_formatado,
                        'unidade' => $colaborador->unidade->nome_fantasia,
                        'bandeira' => $colaborador->unidade->bandeira->nome,
                        'grupo_economico' => $colaborador->unidade->bandeira->grupoEconomico->nome,
                        'iniciais' => $colaborador->iniciais,
                        'primeiro_nome' => $colaborador->primeiro_nome,
                    ];
                });

            return response()->json($colaboradores);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar colaboradores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna todos os colaboradores para uso em selects
     */
    public function listAll(): JsonResponse
    {
        try {
            $colaboradores = Colaborador::select('id', 'nome', 'email')
                ->orderBy('nome')
                ->get()
                ->map(function ($colaborador) {
                    return [
                        'id' => $colaborador->id,
                        'nome' => $colaborador->nome,
                        'email' => $colaborador->email,
                        'iniciais' => $colaborador->iniciais,
                    ];
                });

            return response()->json($colaboradores);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar colaboradores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra colaboradores com base nos parâmetros fornecidos
     */
    public function filter(Request $request): JsonResponse
    {
        try {
            $filtros = $request->only(['nome', 'email', 'cpf', 'unidade_id']);
            
            $colaboradores = Colaborador::filtrar($filtros)
                ->with(['unidade.bandeira.grupoEconomico'])
                ->paginate($request->get('per_page', 10));

            return response()->json($colaboradores);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao filtrar colaboradores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas dos colaboradores
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_colaboradores' => Colaborador::count(),
                'colaboradores_por_unidade' => Colaborador::selectRaw('unidade_id, COUNT(*) as total')
                    ->with('unidade:id,nome_fantasia')
                    ->groupBy('unidade_id')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'unidade' => $item->unidade->nome_fantasia,
                            'total' => $item->total,
                        ];
                    }),
                'colaboradores_por_bandeira' => Colaborador::join('unidades', 'colaboradors.unidade_id', '=', 'unidades.id')
                    ->join('bandeiras', 'unidades.bandeira_id', '=', 'bandeiras.id')
                    ->selectRaw('bandeiras.nome as bandeira, COUNT(*) as total')
                    ->groupBy('bandeiras.id', 'bandeiras.nome')
                    ->get(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valida CPF via AJAX
     */
    public function validateCpf(Request $request): JsonResponse
    {
        try {
            $cpf = $request->get('cpf');
            $colaboradorId = $request->get('colaborador_id');
            
            // Verifica se já existe
            $exists = Colaborador::where('cpf', $cpf)
                ->when($colaboradorId, function ($query) use ($colaboradorId) {
                    return $query->where('id', '!=', $colaboradorId);
                })
                ->exists();

            if ($exists) {
                return response()->json([
                    'valid' => false,
                    'message' => 'CPF já cadastrado.'
                ]);
            }

            // Valida formato
            $colaborador = new Colaborador(['cpf' => $cpf]);
            $isValid = $colaborador->isValidCpf();

            return response()->json([
                'valid' => $isValid,
                'message' => $isValid ? 'CPF válido.' : 'CPF inválido.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Erro ao validar CPF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valida email via AJAX
     */
    public function validateEmail(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $colaboradorId = $request->get('colaborador_id');
            
            // Verifica se já existe
            $exists = Colaborador::where('email', $email)
                ->when($colaboradorId, function ($query) use ($colaboradorId) {
                    return $query->where('id', '!=', $colaboradorId);
                })
                ->exists();

            if ($exists) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Email já cadastrado.'
                ]);
            }

            // Valida formato
            $colaborador = new Colaborador(['email' => $email]);
            $isValid = $colaborador->isValidEmail();

            return response()->json([
                'valid' => $isValid,
                'message' => $isValid ? 'Email válido.' : 'Email inválido.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Erro ao validar email: ' . $e->getMessage()
            ], 500);
        }
    }
}

