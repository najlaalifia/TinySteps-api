<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $code = "00";
        $data = [];
        try {
            DB::beginTransaction();
            $code = "10";
            $userRepo = new UserRepository();
            $user = $userRepo->register($request);
            $code = "20";
            $data['user'] = $user;
            DB::commit();
            return ResponseFormatter::success($data, "Registrasi Pengguna Berhasil");
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e);
            return ResponseFormatter::error($e, "Registrasi Pengguna Gagal", $code);
        }
    }

    public function login(LoginRequest $request)
    {
        $code = "00";
        $data = [];
        try {
            DB::beginTransaction();
            $code = "10";
            $userRepo = new UserRepository();
            $user = $userRepo->login($request);
            $token = $userRepo->createToken($request);
            $code = "20";
            $data['user'] = $user;
            $data['token'] = $token;
            DB::commit();
            return ResponseFormatter::success($data, "Login Pengguna Berhasil");
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e);
            return ResponseFormatter::error($e, "Login Pengguna Gagal", $code);
        }
    }

    public function getUser()
    {
        $code = "00";
        $data = [];
        try {
            $code = "10";
            $userRepo = new UserRepository();
            $user = $userRepo->getUser();
            $code = "20";
            $data['user'] = $user;
            return ResponseFormatter::success($data, "Data pengguna berhasil diambil");
        } catch (\Exception $e) {
            Log::debug($e);
            return ResponseFormatter::error($e, "Data pengguna gagal diambil", $code);
        }
    }

    public function logout(Request $request)
    {
        $code = "00";
        $data = [];
        try {
            $userRepo = new UserRepository();
            $token = $userRepo->deleteToken($request);
            $code = "20";
            $data['token'] = $token;
            return ResponseFormatter::success($data, 'Token Revoked');
        } catch (\Exception $e) {
            Log::debug($e);
            return ResponseFormatter::error($e, "Verifikasi Pengguna Gagal", $code);
        }
    }
}
