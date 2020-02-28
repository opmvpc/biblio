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
                <a href="{{ route('categories.create') }}">Ajouter une catégorie</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des categories') }}</div>

            <div class="card-body">
                <div class="table-responsive">
                    {{ $categories->total() }} catégories
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Nombre d'articles</th>
                            <th scope="col" class="text-center">Actions</th>
                          </tr>
                        </thead>
                        <tbody>

                            @forelse ($categories as $categorie)
                                <tr>
                                    <td>{{ $categorie->nom }}</td>
                                    <td>{{ $categorie->articles_count }}</td>
                                    <td style="width: 10px;">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('categories.show', $categorie) }}" class="btn btn-link">voir</a>
                                            @auth
                                                <a href="{{ route('categories.edit', $categorie) }}" class="btn btn-link">modifier</a>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Pas encore de catégorie</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3" style="overflow-x: auto;">
                    {!! $categories->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
