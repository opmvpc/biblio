@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Ajouter un mot-clé') }}</div>

            <div class="card-body">
                {!! Form::open()->post()->route('keywords.store') !!}
                    {!! Form::text('nom', 'Nom') !!}
                    {!! Form::submit('Enregistrer') !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
