<?php

namespace Sayed\SayedDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $users = $userModel::paginate(10);
        
        return view('sayed-dashboard::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('sayed-dashboard::users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $userModel = config('auth.providers.users.model', 'App\Models\User');
        
        $user = $userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? false,
            'is_active' => $request->is_active ?? true,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('sayed.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $user = $userModel::findOrFail($id);
        
        return view('sayed-dashboard::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $user = $userModel::findOrFail($id);
        
        return view('sayed-dashboard::users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $user = $userModel::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin ?? false;
        $user->is_active = $request->is_active ?? true;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('sayed.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $user = $userModel::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }
        
        $user->delete();
        
        return redirect()->route('sayed.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus($id)
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');
        $user = $userModel::findOrFail($id);
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "User {$status} successfully!");
    }
}