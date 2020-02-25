@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex flex-column">.
            {{-- <ul
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
                        href="{{ route('articles.index', [$slug]) }}"
                    >
                        {{ $categorie }}
                    </a>
                @endforeach
            </ul> --}}
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
@endsection
