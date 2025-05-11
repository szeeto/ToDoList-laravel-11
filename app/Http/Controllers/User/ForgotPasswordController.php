<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordForm()
    {
        return view('user.forgot-password');
    }

    public function doForgotPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ],[
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email harus valid',
            'email.exists' => 'Email tidak terdaftar',
        ]);

        UserVerify::where('email', $request->input('email'))->delete();

        $token = Str::uuid();
        $data = [
            'email'=>$request->input('email'),
            'token'=>$token
        ];
        UserVerify::create($data);  

            // Kirim email verifikasi
        Mail::send('user.email-reset-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->input('email'));
            $message->subject('Reset Password'); // Subjek email diatur menjadi "Reset Password"
        });

        return redirect()->route('forgot-password')->with('success', 'Silahkan cek email anda untuk melakukan reset password')->withInput();

    }

    public function resetPassword($token)
    {
        return view('user.reset-password', compact('token'));
    }

     public function doResetPassword(request $request)
     {
        $request->validate([
        'password' => 'required|min:6|max:25',
        'password-confirmation' => 'required_with:password|same:password',
    ],[
        'password.required' => 'Password wajib diisi',
        'password.string' => 'Password harus berupa string',
        'password.min' => 'Password minimal 6 karakter',
        'password.max' => 'Password maksimal 25 karakter',
        'password-confirmation.required_with' => 'Konfirmasi password wajib diisi',
        'password-confirmation.same' => 'Konfirmasi password tidak sesuai',
    ]);
        $datauser = UserVerify::where('token', $request->input('token'))->first();
        if(!$datauser) {
            return redirect()->back()->withInput()->withErrors('Token tidak valid');
        }

        $email = $datauser->email;
        $data = [
            'password' => bcrypt($request->input('password')),
            'email_verified_at' => Carbon::now(),
        ];
        User::where('email', $email)->update($data);
        
        UserVerify::where('email', $email)->delete();
        return redirect()->route('login')->with('success', 'Password berhasil diubah, silahkan login menggunakan password baru anda');
    }
}
