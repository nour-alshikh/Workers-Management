<?php

namespace App\Services\WorkerServices\WorkerRegisterService;

use App\Mail\EmailVerification;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WorkerRegisterService
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


    public function store($data, $request)
    {
        $worker = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => Hash::make($request->password),
                'photo' => 'Worker-' . uniqid() . "." . $request->file('photo')->getClientOriginalExtension()
            ]
        ));

        $photo = $request->file('photo');
        $fileName = 'Worker-' . uniqid() . "." . $photo->getClientOriginalExtension();
        $photo->storeAs("Workers", $fileName, "Media");

        return $worker->email;
    }


    public function generateToken($email)
    {
        $token = substr(md5(rand(0, 9) . $email . time()), 0, 32);
        $worker = $this->model->whereEmail($email)->first();
        $worker->verificationToken = $token;
        $worker->save();
        return $worker;
    }


    public function sendEmail($worker)
    {
        Mail::to($worker->email)->send(new EmailVerification($worker->name, $worker->verificationToken));
    }


    public function register($request)
    {
        try {
            DB::beginTransaction();
            $data = $this->validation($request);
            $email = $this->store($data, $request);
            $worker = $this->generateToken($email);
            // $this->sendEmail($worker);
            DB::commit();
            return response()->json([
                'message' => "account has been created . please check your inbox"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
