@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Ajouter un article') }}</div>

            <div class="card-body">
                {!! Form::open()->post()->route('articles.store') !!}

                    {!! Form::text('titre', 'Titre') !!}
                    {!! Form::text('reference', 'Reference') !!}
                    {!! Form::text('doi', 'Doi') !!}
                    {!! Form::urlInput('url', 'Url') !!}
                    {!! Form::date('date', 'Date de publication') !!}

                    <div class="form-group">
                        <label for="inp-categories" class="">Catégories</label>
                        <select name="categories[]" id="inp-categories" multiple required class="form-control">
                            @foreach ($categories as $key => $categorie)
                                <option
                                    value="{{ $key }}"
                                >{{ $categorie }}</option>
                            @endforeach
                        </select>
                    </div>

                    {!! Form::textarea('resume', 'Résumé')->attrs(['rows' => 5]) !!}
                    {!! Form::textarea('abstract', 'Abstract')->attrs(['rows' => 10]) !!}
                    {!! Form::submit('Enregistrer') !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
