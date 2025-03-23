<?php

// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use id;
use Illuminate\Http\Request;
use App\Services\UserService;

class ProfileController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . auth()->id(),
            'password' => 'sometimes|string|min:6',
        ]);

        $user = $this->userService->updateUserProfile(auth()->id(), $request->all());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    public function deleteProfile()
    {
        $this->userService->deleteUserAccount(auth()->id());

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}