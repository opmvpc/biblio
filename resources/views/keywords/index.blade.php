@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        @if (session('ok'))
            <div class="alert alert-success" role="alert">
                {{ session('ok') }}
            </div>
        @endif
        <form class="" action="{{ route('keywords.index') }}" method="get">
            <div class="input-group mb-1">
                <input type="search" name="recherche" class="form-control" id="" value="{{ request()->query('recherche') }}">
                <div class="input-group-append">
                    <input class="btn btn-dark" type="submit" value="Rechercher">
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-end my-2">
            @auth
                <a href="{{ route('keywords.create') }}">Ajouter un mot-clé</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des mots-clés') }}</div>

            <div class="card-body">
                @include('keywords.tableau', $keywords)
            </div>
        </div>
    </div>
</div>
@endsection
