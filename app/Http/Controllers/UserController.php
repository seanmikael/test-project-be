<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(){
        $user = User::All();
        return $user;
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'status' => 'nullable|in:Active,Inactive',
        ]);
    
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
    
        if (!empty($request->status)) {
            $user->status = $request->status;
        }
    
        $user->save();
    
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        if(!$user) return response()->json(['message' => "User does not exist!"], 404);
    
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
