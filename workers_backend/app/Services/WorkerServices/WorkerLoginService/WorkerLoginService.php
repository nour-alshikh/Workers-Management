<?php

namespace App\Services\WorkerServices\WorkerLoginService;

use App\Models\Worker;
use Illuminate\Support\Facades\Validator;

class WorkerLoginService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Worker;
    }

    public function validation($request)
    {
        $validator =  Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    public function isValidData($data)
    {
        // $credentials = $request->only('email', 'password');
        $token = auth()->guard('workers')->attempt($data->validated());

        if (!$token) {
            return response()->json([
                'message' => 'False Credentials',
            ], 401);
        }
        return $token;
    }


    public function isVerified($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $verified = $worker->verified_at;
        return $verified;
    }


    public function getStatus($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $status = $worker->status;
        return $status;
    }


    protected function createToken($token)
    {
        $worker = auth()->guard('workers')->user();
        return response()->json([
            'worker' => $worker,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function Login($request)
    {
        $data = $this->validation($request);
        $token = $this->isValidData($data);

        if ($this->isVerified($request->email) == null) {
            return response()->json([
                'message' => 'Your account is not verified'
            ], 422);
        } elseif ($this->getStatus($request->email) == 0) {
            return response()->json([
                'message' => 'Your account is pending'
            ], 422);
        }

        return $this->createToken($token);
    }
}
