<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    public function index(){
        return response()->json(User::where('email', '<>', "luis.manrique@link2b.com.br")->get());
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();

            $userAlreadyExists = User::where('email', $request->email)->first();
            if ($userAlreadyExists)
                return response()->json([
                    'error' => true,
                    'message' => "Usuário já cadastrado."
                ], 400);

            User::create([
                'email' => $request->email,
                'active' => true
            ]);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => "Usuário cadastrado"
            ]);
        }catch(Throwable $error){
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id){
        try{
            DB::beginTransaction();

            User::where('id', $id)->update([
                'active' => $request->active
            ]);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => "Usuário atualizado"
            ]);
        }catch(Throwable $error){
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function verify(Request $request){
        $user = User::where('email', $request->email)
                    ->where('active', true)
                    ->first();

        if (!$user)
            return response()->json([
                'error' => true,
                'message' => "Usuário não encontrado ou não está ativo"
            ], 404);

        return response()->json([
            'error' => false,
            'message' => "Usuário encontrado"
        ]);
    }
}
