<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
            'permissions' => 'sometimes|array',
        ]);

        $role = Role::create($request->only('name'));

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('id');
            $role->permissions()->sync($permissions);
        }

        return response()->json($role->load('permissions'), 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'sometimes|array',
        ]);

        // Update the role name
        $role->update($request->only('name'));

        // Update permissions if provided
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('id');
            $role->permissions()->sync($permissions);
        }

        return response()->json($role->load('permissions'));
    }

    public function destroy($id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }

    public function assignPermissions(Request $request, $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $request->validate(['permissions' => 'required|array']);
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('id');
        $role->permissions()->sync($permissions);
        return response()->json($role->load('permissions'));
    }
}
