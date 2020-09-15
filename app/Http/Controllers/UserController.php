<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtilisateurRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $users = User
            ::paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UtilisateurRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UtilisateurRequest $request): \Illuminate\Http\RedirectResponse
    {
        $datas = collect($request->all())
            ->put('password', bcrypt($request->password))
            ->toArray();

        User::create($datas);

        return redirect()
            ->route('users.index')
            ->with('ok', 'Utilisateur ajouté!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User  $user
     *
     * @return void
     */
    public function show(User $user): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User  $user
     *
     * @return void
     */
    public function edit(User $user): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\User  $user
     *
     * @return void
     */
    public function update(Request $request, User $user): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User  $user
     *
     * @return void
     */
    public function destroy(User $user): void
    {
        //
    }
}
