<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAllUser = User::orderBy('id', 'desc')->get();
        return response()->json([
            "message" => "Get all users successfully !",
            "results" => $getAllUser,
            "status" => "success"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user  = $request->all();
        $user = User::create($user);
        return response()->json(['message' => 'User created successfully', 'results' => $user, 'status' => 'success'], 201);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Request $request, String $userId)
    {

        $user = User::find($userId);
        $user->update($request->all());
        return response()
            ->json(
                ['message' => 'User updated successfully', 'results' => $user, 'status' => 'success'],
                200
            );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user) {}
    public function getOne(String $userId)
    {
        $user = User::find($userId);
        return response()
            ->json(
                ['message' => 'User fetched successfully', 'results' => $user, 'status' => 'success'],
                200
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, String $userId)
    {

        $user = User::find($userId);
        $user->delete();
        return response()
            ->json(
                ['message' => 'User deleted successfully', 'results' => $user, 'status' => 'success'],
                200
            );
    }
}
