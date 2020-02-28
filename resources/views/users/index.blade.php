@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        @if (session('ok'))
            <div class="alert alert-success" role="alert">
                {{ session('ok') }}
            </div>
        @endif
        <div class="d-flex justify-content-end my-2">
            @auth
                <a href="{{ route('users.create') }}">Ajouter un utilisateur</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des utilisateurs') }}</div>

            <div class="card-body">
                <div class="table-responsive">
                    {{ $users->total() }} utilisateurs
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Email</th>
                            {{-- <th scope="col">Actions</th> --}}
                        </tr>
                        </thead>
                        <tbody>

                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    {{-- <td style="width: 10px;">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-link">voir</a>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Pas encore d'utilisateur</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3" style="overflow-x: auto;">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
