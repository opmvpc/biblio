@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex justify-content-end mb-3">
            @auth
                <a href="{{ route('articles.importer.index') }}" class="mr-3">Importer</a>
                <a href="{{ route('articles.create') }}">Ajouter un article</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des articles') }}</div>

            <div class="card-body">
                @include('articles.datatable', $articles)
            </div>

        </div>
    </div>
</div>
@endsection
