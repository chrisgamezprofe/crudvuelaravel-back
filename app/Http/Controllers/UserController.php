<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        $inputs["password"] = Hash::make(trim($request->password));
        $e = User::create($inputs);

        $token = Auth::login($e);
        return response()->json([
            'data'=>$e,
            'mensaje'=>"Usuario registrado",
            'auth'=>[
                'token'=>$token,
                'type'=>'bearer'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $e = User::find($id);
        if(isset($e)){
            return response()->json([
                'data'=>$e,
                'mensaje'=>"Encontrado con éxito.",
            ]);
        }else{
            return response()->json([
                'error'=>true,
                'mensaje'=>"No existe.",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $e = User::find($id);
        if(isset($e)){
            $e->first_name = $request->first_name;
            $e->last_name = $request->last_name;
            $e->email = $request->email;
            $e->password = Hash::make($request->password);
            if($e->save()){
                return response()->json([
                    'data'=>$e,
                    'mensaje'=>"actualizado con éxito.",
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'mensaje'=>"No se actualizó.",
                ]);
            }
        }else{
            return response()->json([
                'error'=>true,
                'mensaje'=>"No existe el estudiante.",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $e = User::find($id);
        if(isset($e)){
            $res=User::destroy($id);
            if($res){
                return response()->json([
                    'data'=>$e,
                    'mensaje'=>"eliminado con éxito.",
                ]);
            }else{
                return response()->json([
                    'data'=>$e,
                    'mensaje'=>"no existe.",
                ]);
            }
           
        }else{
            return response()->json([
                'error'=>true,
                'mensaje'=>"No existe.",
            ]);
        }
    }

    public function login(Request $request){
        $credencials = $request->only('email','password');
        $token  = Auth::attempt($credencials);
        if(!$token){
            return response()->json([
                'error'=>true,
                'mensaje'=>"Sin autorización",
            ],401);
        }

        $user = Auth::user();
        return response()->json([
            'data'=>$user,
            'mensaje'=>"Usuario autenticado",
            'auth'=>[
                'token'=>$token,
                'type'=>'bearer'
            ]
        ]);

    }
}
