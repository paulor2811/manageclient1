<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Adicione essa linha para usar o cliente HTTP
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        // Recupera todos os usuários do banco de dados
        $users = User::all();
        return response()->json($users);
    }

    public function searchByName(Request $request) 
    { 
        $request->validate([ 
            'nome' => 'required|string' 
        ]); 
        
        // Buscar pelo nome 
        $users = User::where('nome', 'LIKE', '%' . $request->nome . '%')->get(); 
        return response()->json($users); 
    } 
    
    public function searchByPhone(Request $request) 
    { 
        $request->validate([ 
            'telefone' => 'required|string' 
        ]); 
        
        // Buscar pelo telefone 
        $users = User::where('telefone', 'LIKE', '%' . $request->telefone . '%')->get(); 
        return response()->json($users); 
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:50',
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:50',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'uf' => 'required|string|max:2',
            'telefone' => 'required|string|max:20',
            'email' => 'required|string|email|max:50',
        ]);

        Log::info('Dados recebidos para inserção:', $request->all());

        try {
            // Criação do usuário
            $user = User::create($request->all());
            Log::info('Usuário criado com sucesso:', $user->toArray());
            return response()->json($user, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao criar usuário.'], 500);
        }
    }
        
    public function updateByPhone(Request $request, $telefone) { 
        // Validação dos dados 
        $request->validate([ 
            'nome' => 'sometimes|required|string|max:50', 
            'cep' => 'sometimes|required|string|max:10', 
            'endereco' => 'sometimes|required|string|max:50', 
            'bairro' => 'sometimes|required|string|max:50', 
            'cidade' => 'sometimes|required|string|max:50', 
            'uf' => 'sometimes|required|string|max:2', 
            'telefone' => 'sometimes|required|string|max:20', 
            'email' => 'sometimes|required|string|email|max:50', 
        ]); 
        // Encontra o usuário pelo telefone e atualiza os dados 
        $user = User::where('telefone', $telefone)->firstOrFail();
        $user->update($request->all()); 
        return response()->json($user, 200);
    }

    public function destroy($id) 
    { 
        // Encontra o usuário pelo ID e deleta 
        $user = User::findOrFail($id);
        $user->delete(); 
        
        return response()->json(null, 204); 
    } 

    public function getCepDetails($cep) 
    {
        // Valida o formato do CEP 
        if (!preg_match('/^\d{8}$/', $cep)) {
            return response()->json(['error' => 'Formato de CEP inválido.'], 400);
        } 

        // Faz a requisição à API ViaCEP 
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
             
        // Verifica se a requisição foi bem-sucedida 
        if ($response->failed()) {
            return response()->json(['error' => 'Erro ao consultar o CEP.'], $response->status());
        } 
            
        // Verifica se o CEP existe 
        $data = $response->json(); 
        if (isset($data['erro']) && $data['erro'] === true) { 
            return response()->json(['error' => 'CEP não encontrado.'], 404); 
        } 
            
        return response()->json($data);
    }
}
