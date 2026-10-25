<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataUser'] = User::all();
		return view('admin.user.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
		return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->first_name . ' ' . $request->last_name,
            'password' => Hash::make($request->password),
        ];
        User::create($data);
        return redirect()->route('user.index')
        ->with('success', 'Penambahan data berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataUser'] = User::findOrFail($id);
        return view('admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $user_id = $id;
    $user = User::findOrFail($user_id);

    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->birthday = $request->birthday;
    $user->gender = $request->gender;
    $user->email = $request->email;
    $user->phone = $request->phone;

    $user->save();
    return redirect()->route('user.index')->with('success','Perubahan Data Berhasil!');

    $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'birthday' => 'nullable|date',
        'gender' => 'nullable|string|max:50',
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($id),
        ],
        'phone' => 'nullable|string|max:50',
        'password' => 'nullable|string|min:8|confirmed',
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success','Data berhasil dihapus');
    }
}
