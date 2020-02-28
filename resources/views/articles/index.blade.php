{{-- @extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex flex-column">.
            <ul
                class="px-0 d-flex justify-content-center align-items-center flex-wrap"
            >
                <a
                    class="btn btn-xs mx-2 mb-2 mb-md-0 {{ request()->query('categorie') == false ? 'btn-dark' : 'btn-light' }}"
                    href="{{ route('articles.index') }}"
                >
                    Tous
                </a>
                @foreach ($categories as $slug => $categorie)
                    <a
                        class="btn btn-xs mx-2 mb-2 mb-md-0 {{ request()->query('categorie') == $slug ? 'btn-dark' : 'btn-light' }}"
                        href="{{ route('articles.index', ['categorie' => $slug]) }}"
                    >
                        {{ $categorie }}
                    </a>
                @endforeach
            </ul>
            <div class="d-flex justify-content-end mb-3">
                @auth
                    <a href="{{ route('articles.importer.index') }}" class="mr-3">Importer</a>
                    <a href="{{ route('articles.create') }}">Ajouter un article</a>
                @endauth
            </div>
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des articles') }}</div>

            <div class="card-body">
                @include('articles.datatable')
            </div>

        </div>
    </div>
</div>
@endsection --}}


@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex justify-content-xl-center align-items-center mb-1 mb-md-3" style="overflow-x: auto;">
            <a
                class="btn btn-xs mr-4 mx-xl-2 {{ request()->query('categorie') == false ? 'btn-dark' : 'btn-light' }}"
                href="{{ route('articles.index', ['recherche' => request()->query('recherche'), 'sort' => request()->query('sort'), 'order' => request()->query('order')]) }}"
            >
                Tous
            </a>
            @foreach ($categories as $slug => $categorie)
                <a
                    class="btn btn-xs mr-4 mx-xl-2 {{ request()->query('categorie') == $slug ? 'btn-dark' : 'btn-light' }}"
                    href="{{ route('articles.index', ['categorie' => $slug, 'recherche' => request()->query('recherche'), 'sort' => request()->query('sort'), 'order' => request()->query('order')]) }}"
                >
                    {{ $categorie }}
                </a>
            @endforeach
        </div>
        <form class="" action="{{ route('articles.index') }}" method="get">
            <input type="hidden" name="categorie" value="{{ request()->query('categorie') }}">
            <input type="hidden" name="sort" value="{{ request()->query('sort') }}">
            <input type="hidden" name="order" value="{{ request()->query('order') }}">
            <div class="input-group mb-1">
                <input type="search" name="recherche" class="form-control" id="" value="{{ request()->query('recherche') }}">
                <div class="input-group-append">
                    <input class="btn btn-dark" type="submit" value="Rechercher">
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-between my-3 align-items-center">
            <div class="d-flex justify-content-start mr-3 mr-sm-0">
                @component('components.inputs.tri')
                @endcomponent
            </div>
            <div class="d-flex flex-column flex-sm-row text-center justify-content-sm-end">
                @auth
                    <a href="{{ route('articles.importer.index') }}" class="mb-3 mb-sm-0 mr-sm-3">Importer</a>
                    <a href="{{ route('articles.create') }}">Ajouter un article</a>
                @endauth
            </div>
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des articles') }}</div>

            <div class="card-body">
                @include('articles.tableau', $articles)
                {{-- @include('articles.datatable', $articles) --}}
            </div>
        </div>
    </div>
</div>
@endsection
