<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $resource = 'users';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("{$this->resource}.index");
    }

    public function useDatatables()
    {
        return (new User)->datatables();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::all();

        return view("{$this->resource}.create", compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $validatedData = $request->validated();
        $user = User::create($validatedData);
        $user->assignRole($request->input('role'));

        return redirect()->route("{$this->resource}.index")->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view("{$this->resource}.show", compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::findOrFail($id);
        $roles = Role::all();

        return view("{$this->resource}.edit", compact('users', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $validatedData = $request->validated();
        $user->update($validatedData);
        if ($request->has('role')) {
            $user->syncRoles($request->input('role'));
        }

        return redirect()->route("{$this->resource}.index")->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route("{$this->resource}.index")->with('success', 'User deleted successfully.');
    }
}
