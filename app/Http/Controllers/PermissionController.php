<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions']);
        $permission = Permission::create($request->only('name'));
        return response()->json($permission, 201);
    }

    public function index(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }
}
