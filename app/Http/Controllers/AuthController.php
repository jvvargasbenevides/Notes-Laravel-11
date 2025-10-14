<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        // Form Validation
        $request->validate([
            'text_username' => 'required|email',
            'text_password' => 'required|min:8|max:100',
        ],
        [
            'text_username.required' => 'O username é obrigatório.',
            'text_username.email' => 'O username deve ser um e-mail válido.',
            'text_password.required' => 'A senha é obrigatória.',
            'text_password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'text_password.max' => 'A senha deve ter menos de :max caracteres.',
        ]);

        $username = $request->get('text_username');
        $password = $request->get('text_password');

        // Check if user exists;

        $user = User::where('username', $username)
                ->where('deleted_at', NULL)
                ->first();

        if(!$user) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Username ou password incorretos.');
        }

        // Check if password is correct
        if(!password_verify($password, $user->password)) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Username ou password incorretos.');
        }

        // Update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // Login user
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ]
        ]);

    }

    public function logout()
    {
       // Logout from the application
        session()->forget('user');

        return redirect()->to('/login');
    }
}
