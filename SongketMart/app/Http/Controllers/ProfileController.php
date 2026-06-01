<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    // Dependency Injection (SOLID)
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input Data Diri
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'shop_name' => 'nullable|string|max:255',
        ]);

        // 2. Oper ke Service
        $this->profileService->updateProfile($user, $validatedData, $request->file('avatar'));

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input Password
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed', // Pastikan di form ada input 'new_password_confirmation'
        ]);

        try {
            // 2. Oper logika pergantian password ke Service
            $this->profileService->updatePassword($user, $validatedData);

            return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
