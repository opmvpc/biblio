@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    {{ __('Modifier un auteur') }}
                    <form action="{{ route('auteurs.destroy', $auteur) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-link text-danger" onclick="confirm('Attention! Voulez-vous vraiment supprimer cet article?') ? null : event.preventDefault()">Supprimer</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open()->put()->route('auteurs.update', [$auteur])->fill($auteur) !!}
                    {!! Form::text('nom', 'Nom') !!}
                    {!! Form::submit('Enregistrer') !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
