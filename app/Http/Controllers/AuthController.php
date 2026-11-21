<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
{
    Log::info('¡El método de registro ha sido llamado!');
    // 1. Definimos las reglas
    $messages = [
        'email.unique' => 'Este correo electrónico ya está registrado en nuestra plataforma.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'email.email' => 'Por favor, ingresa una dirección de correo válida.',
    ];

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'household_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ], $messages);

    // 2. Si pasa la validación, creamos el usuario
    $user = User::create([
        'name' => $validated['name'],
        'household_name' => $validated['household_name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);
    
    return response()->json(['message' => '¡Cuenta creada con éxito!'], 201);
}

public function login(Request $request) {
    $fields = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string'
    ]);

    // Revisar email
    $user = User::where('email', $fields['email'])->first();

    // Revisar password
    if (!$user || !Hash::check($fields['password'], $user->password)) {
        return response([
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    $token = $user->createToken('prospero_token')->plainTextToken;

    return response([
        'user' => $user,
        'token' => $token
    ], 200);
}

}
