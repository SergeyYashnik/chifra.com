<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create($request->all());

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');

    }

    public function login()
    {
        return view('user.login');
    }

    public function loginAuth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Welcome, ' . Auth::user()->name . '!');
        }
        return back()->withErrors([
            'email' => 'Вы ввели не правильно логин или пароль',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        $cities = City::all();
        return view('user.profile', compact('user', 'cities'));
    }
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        return back()->with('success', 'Имя успешно обновлено.');
    }
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'min:11'],
        ]);

        $user = Auth::user();
        $user->phone = $request->input('phone');
        $user->save();

        return back()->with('success', 'Номер телефона успешно изменен!');
    }
    public function addAddress(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'street' => 'required|string|max:255',
            'house' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
        ]);

        $address = new Address();
        $address->user_id = Auth::id();
        $address->city_id = $request->input('city_id');
        $address->street = $request->input('street');
        $address->house = $request->input('house');
        $address->apartment = $request->input('apartment');
        $address->save();

        return back()->with('success', 'Адрес доставки успешно добавлен');
    }
    public function destroyAddress(Address $address)
    {
        $address->delete();
        return back()->with('success', 'Адрес успешно удалён.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Текущий пароль неверен.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Пароль успешно обновлен.');
    }

    public function updateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->email = $request->input('email');
        $user->email_verified_at = null; // Сделать аккаунт не верифицированным
        $user->save();

        // Отправить новое письмо для верификации
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Логин успешно обновлен. Проверьте вашу почту для повторной верификации.');
    }








}
