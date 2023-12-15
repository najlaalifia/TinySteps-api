<?php

namespace App\Repositories\Auth;

use App\Interfaces\RepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository implements RepositoryInterface
{
    public function show($id)
    {
        return User::find($id);
    }

    public function showAll()
    {
        return User::all();
    }

    public function delete($id)
    {
        $user = $this->show($id);
        if ($user != null) {
            return $user->delete();
        } else {
            return false;
        }
    }

    public function update($id, array $data)
    {
        $user = $this->show($id);
        if ($user != null) {
            $user->update($data);
            return $user;
        } else {
            return false;
        }
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function checkUser($phoneNumber, $password){
        $userMiddleware = auth()->user();
        $user = User::where('phone_number', $phoneNumber)->first();
        
        if ($user->phone_number != $userMiddleware->phone_number) {
            abort(406, "Nomor handphone tidak sama dengan yang login");
        }

        if (!$user || !Hash::check($password, $user->password)) {
            abort(406, "User tidak ada atau password salah");
        }
    }

    public function register(Request $request): User
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user) {
            abort(406, "Nomor handphone sudah terdaftar");
        }

        $data = [
            "name" => $request->name,
            "phone_number" => $request->phone_number,
            "password" => Hash::make($request->password),
        ];
        $user = $this->store($data);
        $user->assignRole("user");
        return $user;
    }

    public function login(Request $request): User
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(406, "Nomor handphone atau password salah");
        }

        return $user;
    }

    public function getUser(): ?User
    {
        $user = auth()->user();
        return $user;
    }

    public function createToken(Request $request): String
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        $token = $user->createToken($request->device_name ?? "token")->plainTextToken;
        return $token;
    }

    public function deleteToken(Request $request): bool
    {
        return $request->user()->currentAccessToken()->delete();
    }

    public function editUser(Request $request)
    {
        $user = auth()->user();
        $data = [
            "name" => $request->name,
            "phone_number" => $request->phone_number,
            "password" => Hash::make($request->password),
        ];
        $user = $this->update($user->id, $data);
        return $user;
    }
}
