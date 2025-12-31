<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
  public function login(array $data)
  {
    $user = User::where('email', $data['email'])->first();

    if (! $user || ! Hash::check($data['password'], $user->password)) {
      throw ValidationException::withMessages([
        'credentials' => ['Las credenciales proporcionadas son incorrectas.'],
      ]);
    }

    return [
      'user' => $user,
      'token' => $user->createToken('auth_token')->plainTextToken,
    ];
  }

  public function register(array $data)
  {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'birth_date' => $data['birth_date'],
    ]);
  }
}
