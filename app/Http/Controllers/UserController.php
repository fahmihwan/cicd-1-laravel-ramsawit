<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function list_users()
    {

        $users = User::latest()->get();
        return view('pages.users.index', [
            'items' => $users
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // butuh input password_confirmation
        ]);

        $user =  User::where('username', $validated['username'])->exists();

        if ($user) {
            return redirect()->back()->with('error', 'username sudah tersedia');
        }

        User::create($validated);
        return redirect()->back()->with('success', 'User berhasil diupdate');
    }


    public function change_password(Request $request)
    {


        $validated =  $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', Password::min(8)->letters()->numbers()],
            'confirm_password' => ['required', 'same:new_password'],
        ]);


        $username = auth()->user()->username;
        $ifNotExist =  $this->ifUserNotExistsFromSession($username);
        if ($ifNotExist) {
            return redirect()->back()->with('error', 'User not found');
        }
        // ambil langsung user login
        $user = $request->user();

        // update via instance -> cast 'hashed' akan meng-hash otomatis
        $user->update([
            'password' => $validated['new_password'],
        ]);

        // logout semua device lain — setelah update => kirim password BARU (plain)
        Auth::logoutOtherDevices($validated['new_password']);

        // 2) regenerate session id aktif utk keamanan
        $request->session()->regenerate();
        return redirect()->back()->with('success', 'Password berhasil di perbarui');
    }

    public function update_information_user(Request $request)
    {
        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
        ]);

        $username = auth()->user()->username;

        $ifNotExist =  $this->ifUserNotExistsFromSession($username);
        if ($ifNotExist) {
            return redirect()->back()->with('error', 'User not found');
        }

        User::where('username', $username)->update($validated);
        return redirect()->back()->with('success', 'User hasbeen updated');
    }



    public function profile()
    {

        $nama = auth()->user()->nama;
        $username = auth()->user()->username;

        $ifNotExist =  $this->ifUserNotExistsFromSession($username);
        if ($ifNotExist) {
            return redirect()->back()->with('error', 'User not found');
        }

        $user = [
            "nama" => $nama,
            'username' => $username
        ];


        return view('pages.users.profile', [
            'user' => $user
        ]);
    }


    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return back()->with('error', 'Khusus user ini saja, tidak dapat di hapus');
        }

        $users = $user->delete();
        return back()->with('success', 'User telah berhasil terhapus');
    }

    private function ifUserNotExistsFromSession($username)
    {


        if (!empty($username)) {
            $user = User::where('username', $username);
            if ($user->exists()) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
