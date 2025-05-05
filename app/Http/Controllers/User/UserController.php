<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserVerify;
use Carbon\Carbon;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    function login()
    {
        return view('user.login');
    }
    function doLogin(Request $request)
    {
      $data = [
        'email' => $request->input('email'),
        'password' => $request->input('password')
      ];

      if (Auth::attempt($data)){
        if(Auth::user()->email_verified_at == ''){
          Auth::logout();
          return redirect()->route('login')->withErrors( 'Akun anda belum diverifikasi, silahkan cek email anda untuk verifikasi akun')->withInput();
        }else{
          return redirect()->route('todo');
        }
      }else
      return redirect()->route('login')->withErrors('username dan password tidak sesuai')->withInput();
    }
    function register()
    {
        return view('user.register');
    }
    function doRegister()
    {
    $request = request();
    $request->validate([
        'email' => 'required|email|string|email:rfc,dns|max:100|unique:users,email',
        'name' => 'required|min:6|max:25',
        'password' => 'required|min:6|max:25',
        'password-confirmation' => 'required_with:password|same:password',
    ],[
        'email.required' => 'Email wajib diisi',
        'email.string' => 'Email wajib berformat string',
        'email.email' => 'Format email harus valid',
        'email.max' => 'Maksimal karakter untuk email adalah 100 karakter',
        'email.unique' => 'Email sudah terdaftar',
        'name.required' => 'Nama wajib diisi',
        'name.min' => 'Nama minimal 6 karakter',
        'name.max' => 'Nama maksimal 25 karakter',
        'password.required' => 'Password wajib diisi',
        'password.string' => 'Password harus berupa string',
        'password.min' => 'Password minimal 6 karakter',
        'password.max' => 'Password maksimal 25 karakter',
        'password-confirmation.required_with' => 'Konfirmasi password wajib diisi',
        'password-confirmation.same' => 'Konfirmasi password tidak sesuai',
    ]);

    $data = [
        'email' => $request->input('email'),
        'name' => $request->input('name'),
        'password' => bcrypt($request->input('password')),
    ];
    User::create($data);

    // Hapus token lama jika ada
    UserVerify::where('email', $request->input('email'))->delete();

    // Buat token baru
    $token = Str::uuid();

    // Simpan token ke tabel password_reset_tokens
    UserVerify::create([
        'email' => $request->input('email'),
        'token' => $token,
        'created_at' => now(),
    ]);

    // Kirim email verifikasi
    Mail::send('user.email-verification', ['token' => $token], function ($message) use ($request) {
        $message->to($request->input('email'));
        $message->subject('Verifikasi Email');
    });

    return redirect()->route('register')->with('success', 'Pendaftaran Berhasil, Silahkan Cek Email Anda Untuk Verifikasi Akun')->withInput();
}

    function updateData()
    {
        return view('user.update-data');
    }
    function doUpdateData()
    {
        $request = request();
        $request->validate([
            'name' => 'required|min:6|max:25',
            'password' => 'nullable|min:6|max:25',
            'password-confirmation' => 'required_with:password|same:password',
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 6 karakter',
            'name.max' => 'Nama maksimal 25 karakter',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 25 karakter',
            'password-confirmation.required_with' => 'Konfirmasi password wajib diisi',
            'password-confirmation.same' => 'Konfirmasi password tidak sesuai',
        ]
      );

      $data = [
        'name'=> $request->input('name'),
        'password' => $request->input('password')? bcrypt($request->input('password')) : Auth::user()->password,
      ];
      User::where('id', Auth::user()->id)->update($data);
      return redirect()->route('user.update-data')->with('success', 'Data berhasil diupdate');
    }
    function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }
    
    function verifyAccount($token){
     $cekuser = UserVerify::where('token', $token)->first();

    if (!is_null($cekuser)) {
        $email = $cekuser->email;

        $datauser = User::where('email', $email)->first();
        if ($datauser->email_verified_at) {
            $message = 'Email sudah terverifikasi';
        } else {
            $data = [
                'email_verified_at' => Carbon::now()
            ];
            User::where('email', $email)->update($data);
            UserVerify::where('email', $email)->delete();
            $message = 'Email sudah diverifikasi. Silahkan login';
        }
        return redirect()->route('login')->with('success', $message);
    } else {
        return redirect()->route('login')->with('error', 'Token tidak valid');
    }
}
}
