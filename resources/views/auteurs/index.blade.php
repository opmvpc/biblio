@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if (session('ok'))
            <div class="alert alert-success" role="alert">
                {{ session('ok') }}
            </div>
        @endif
        <form class="" action="{{ route('auteurs.index') }}" method="get">
            <div class="input-group mb-1">
                <input type="search" name="recherche" class="form-control" id="" value="{{ request()->query('recherche') }}">
                <div class="input-group-append">
                    <input class="btn btn-dark" type="submit" value="Rechercher">
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-end my-2">
            @auth
                <a href="{{ route('auteurs.create') }}">Ajouter un auteur</a>
            @endauth
        </div>
        <div class="card">
            <div class="card-header">{{ __('Liste des auteurs') }}</div>

            <div class="card-body">
                @include('auteurs.tableau', $auteurs)
            </div>
        </div>
    </div>
</div>
@endsection
