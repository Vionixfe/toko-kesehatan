<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;



class UserProfileController extends Controller
{
    // Tampilkan form profil
    public function show()
    {
        $user = Auth::user();
        return view('backend.profiles.show', compact('user'));
    }

    // Update data profil (tanpa save())
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'tanggal_lahir' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string|max:255',
        ]);

        // Update langsung ke database
        User::where('id', $user->id)->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'gender' => $request->gender,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('profiles.show')->with('success', 'Profil berhasil diperbarui.');
    }

    // Tampilkan form ganti password
    public function editPassword()
    {
        return view('backend.profile.password');
    }

    // Update password (tanpa save())
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        // Update password langsung ke database
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profiles.show')->with('success', 'Password berhasil diubah.');
    }
}