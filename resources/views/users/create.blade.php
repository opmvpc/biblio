@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header">{{ __('Ajouter un utilisateur') }}</div>

            <div class="card-body">
                {!! Form::open()->post()->route('users.store') !!}
                    {!! Form::text('name', 'Nom')->placeholder(__('Nom de l\'utilisateur')) !!}
                    {!! Form::text('email', __('Email'))->type('email')->placeholder(__('Email de l\'utilisateur')) !!}
                    {!! Form::text('password', __('Mot de passe'))->placeholder(__('Mot de passe de l\'utilisateur')) !!}
                    {!! Form::submit('Enregistrer') !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
