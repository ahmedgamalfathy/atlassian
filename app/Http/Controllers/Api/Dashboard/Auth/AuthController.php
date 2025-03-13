<?php

namespace App\Http\Controllers\Api\Dashboard\Auth;

use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    /*
    ** register method
    **
    **
    */
    public function register(RegisterRequest $registerReq)
    {
        return $this->authService->register($registerReq->validated());
    }


    /*
    ** login method
    **
    **
    */
    public function login(LoginRequest $loginReq)
    {
        return $this->authService->login($loginReq->validated());
    }


    /*
    ** logout method
    **
    **
    */

    public function logout()
    {
        return $this->authService->logout();
    }
}
