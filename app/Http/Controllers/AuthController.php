<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function __construct(    protected AuthService $authService) {}

  public function login(LoginRequest $request)
  {
    $validated = $request->validated();
    $login = $this->authService->login($validated);
    [$token, $user] = [$login['token'], $login['user']];

    return response()->json([
      'token' => $token,
      'user' => new AuthResource($user),
    ]);
  }

  public function register(RegisterRequest $request)
  {
    $validated = $request->validated();
    $registered = $this->authService->register($validated);

    return response()->json([
      'message' => 'Usuario registrado exitosamente',
      'user' => new AuthResource($registered),
    ], 201);
  }
}
