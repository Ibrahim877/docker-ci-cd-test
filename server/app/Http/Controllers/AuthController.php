<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt($request->validated())) {
                $token = $request->user()->createToken('token');
                $this->success('Giriş etdiniz');
                $this->setData([
                    'token' => $token->plainTextToken,
                    'user' => $request->user()
                ]);
            } else {
                $this->error('Giriş məlumatları yanlışdır');
            }
        } catch (\Exception $e) {
            $this->errorWithLog($e->getMessage());
        }

        return $this->response();
    }
}
