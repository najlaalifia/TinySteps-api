<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Repositories\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function editUser(Request $request)
    {
        $code = "00";
        $data = [];
        try {
            DB::beginTransaction();
            $code = "10";
            $userRepo = new UserRepository();
            $user = $userRepo->editUser($request);
            $code = "20";
            $data['user'] = $user;
            DB::commit();
            return ResponseFormatter::success($data, "Update Profile Pengguna Berhasil");
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e);
            return ResponseFormatter::error($e, "Update Profile Pengguna Gagal", $code);
        }
    }
}
