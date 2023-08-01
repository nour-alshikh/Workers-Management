<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\{WorkerLoginRequest, WorkerRegisterRequest};
use App\Models\Worker;
use App\Services\WorkerServices\WorkerLoginService\WorkerLoginService;
use App\Services\WorkerServices\WorkerRegisterService\WorkerRegisterService;
use Carbon\Carbon;

class WorkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:workers', ['except' => ['login', 'register', 'verifyEmail']]);
    }

    public function login(WorkerLoginRequest $request)
    {
        return (new WorkerLoginService())->Login($request);
    }

    public function register(WorkerRegisterRequest $request)
    {
        return (new WorkerRegisterService())->register($request);
    }

    public function logout()
    {
        auth('workers')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'worker' =>  auth('workers')->user(),
            'authorisation' => [
                'token' =>  auth('workers')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function verifyEmail($token)
    {
        $worker = Worker::where('verificationToken', '=', $token)->first();
        if (!$worker) {
            return response()->json([
                'message' => "this token is invalid"
            ]);
        }
        $worker->verificationToken = null;
        $worker->verified_at = Carbon::now();
        $worker->save();
        return response()->json([
            'message' => "Your Account has been verified"
        ]);
    }
}
