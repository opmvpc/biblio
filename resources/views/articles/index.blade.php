@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex justify-content-center my-3">
            <a
                class="btn btn-xs mx-2 {{ request()->query('categorie') == false ? 'btn-dark' : 'btn-light' }}"
                href="{{ route('articles.index', ['recherche' => request()->query('recherche')]) }}"
            >
                Tous
            </a>
            @foreach ($categories as $slug => $categorie)
                <a
                    class="btn btn-xs mx-2 {{ request()->query('categorie') == $slug ? 'btn-dark' : 'btn-light' }}"
                    href="{{ route('articles.index', ['categorie' => $slug, 'recherche' => request()->query('recherche')]) }}"
                >
                    {{ $categorie }}
                </a>
            @endforeach
        </div>
        <form class="" action="{{ route('articles.index') }}" method="get">
            <input type="hidden" name="categorie" value="{{ request()->query('categorie') }}">
            <div class="input-group mb-1">
                <input type="search" name="recherche" class="form-control" id="" value="{{ request()->query('recherche') }}">
                <div class="input-group-append">
                    <input class="btn btn-dark" type="submit" value="Rechercher">
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-end my-3">
            @auth
                <a href="{{ route('articles.importer.index') }}" class="mr-3">Importer</a>
                <a href="{{ route('articles.create') }}">Ajouter un article</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des articles') }}</div>

            <div class="card-body">
                @include('articles.tableau', $articles)
            </div>
        </div>
    </div>
</div>
@endsection
