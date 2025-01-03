<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 *     description="Autenticação via Bearer Token"
 * )
 *
 * @OA\Tag(
 *     name="Funcionários",
 *     description="**Endpoints Relacionados ao Gerenciamento de Funcionários**"
 * )
 *
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API de Funcionários",
 *         version="1.0.0",
 *         description="Documentação da API de CRUD de Funcionários.",
 *         @OA\Contact(
 *             email="seu-email@exemplo.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000",
 *         description="Servidor local de desenvolvimento"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Funcionario",
 *     type="object",
 *     title="Funcionário",
 *     required={"nome", "email", "cargo", "salario"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome", type="string", example="João Silva"),
 *     @OA\Property(property="email", type="string", example="joao@exemplo.com"),
 *     @OA\Property(property="cargo", type="string", example="Gerente"),
 *     @OA\Property(property="salario", type="number", example=4500.50),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class FuncionarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/funcionarios",
     *     tags={"Funcionários"},
     *     summary="Listar todos os funcionários",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de funcionários",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Funcionario"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Funcionario::paginate(10), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/funcionarios",
     *     tags={"Funcionários"},
     *     summary="Cria um novo funcionário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="cargo", type="string", example="Gerente"),
     *             @OA\Property(property="salario", type="number", example=4500.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Funcionário criado com sucesso."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email',
            'cargo' => 'required|string|max:255',
            'salario' => 'required|numeric|min:0',
        ], [
            'nome.required' => 'O campo "nome" é obrigatório.',
            'email.required' => 'O campo "email" é obrigatório.',
            'email.email' => 'O campo "email" deve conter um endereço de email válido.',
            'email.unique' => 'O email informado já está em uso.',
            'cargo.required' => 'O campo "cargo" é obrigatório.',
            'salario.required' => 'O campo "salário" é obrigatório.',
            'salario.numeric' => 'O campo "salário" deve ser numérico.',
            'salario.min' => 'O campo "salário" deve ser maior ou igual a zero.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $funcionario = Funcionario::create($validator->validated());
        return response()->json($funcionario, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/funcionarios/{id}",
     *     summary="Exibe detalhes de um funcionário específico",
     *     tags={"Funcionários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do funcionário retornados com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado."
     *     )
     * )
     */
    public function show(Funcionario $funcionario)
    {
        return response()->json($funcionario, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/funcionarios/{id}",
     *     summary="Atualiza um funcionário existente",
     *     tags={"Funcionários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="cargo", type="string", example="Gerente"),
     *             @OA\Property(property="salario", type="number", example=4500.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Funcionário atualizado com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado."
     *     )
     * )
     */
    public function update(Request $request, Funcionario $funcionario)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email,' . $funcionario->id,
            'cargo' => 'required|string|max:255',
            'salario' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $funcionario->update($validator->validated());
        return response()->json($funcionario, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/funcionarios/{id}",
     *     summary="Remove um funcionário",
     *     tags={"Funcionários"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do funcionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Funcionário excluído com sucesso."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Funcionário não encontrado."
     *     )
     * )
     */
    public function destroy(Funcionario $funcionario)
    {
        $funcionario->delete();
        return response()->json(null, 204);
    }
}
