<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil disimpan.');
    }
}
