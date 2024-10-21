<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Display a listing of the users.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all users with their roles and available roles
        $users = User::with('roles')->get();
        $roles = Role::all();

        // Format the response to include id, username, email, role, and current user role with name and id
        $userList = $users->map(function ($user) use ($roles) {
            return [
                'id' => $user->id, // Include the user ID
                'username' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(', '), // Handles multiple roles
                'current_user_role' => [
                    'name' => $user->roles->pluck('name')->first(),
                    'id' => $user->roles->pluck('id')->first(),
                ],
                'available_roles' => $roles->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'id' => $role->id,
                    ];
                }),
            ];
        });

        // Return the formatted response as JSON

        $json = response()->json(data: $userList);
        // Save the JSON response to a file
        file_put_contents(storage_path('app/public/userList.json'), $json->content());
        return $json;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // This will check for password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before saving
        ]);

        // Assign the "user" role to the newly created user
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function assignRole(Request $request, int $userId): JsonResponse
    {
        // Validate the request
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Find the user by ID
        $user = User::findOrFail($userId);

        // Detach any existing roles and assign the new role
        $user->roles()->sync([$request->role_id]);

        // Return success response
        return response()->json([
            'message' => 'Role assigned successfully.',
            'user' => $user->load('roles'), // Return user with assigned role
        ]);
    }


}
