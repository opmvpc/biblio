@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Ajouter un article') }}</div>

            <div class="card-body">
                {!! Form::open()->post()->route('articles.store') !!}

                    {!! Form::text('titre', 'Titre') !!}

                    <div class="form-group">
                        <label for="inp-pertinence" class="">Pertinence</label>
                        <select name="pertinence" id="inp-pertinence" class="form-control">
                            @foreach ($pertinences as $key => $pertinence)
                                <option
                                    value="{{ $key }}"
                                    class="text-{{ $pertinence->get('couleur') }}"
                                    {{ $key == old('pertinence') ? 'selected' : '' }}
                                >{{ $pertinence->get('nom') }}</option>
                            @endforeach
                        </select>
                    </div>

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
                                    {{ in_array($key, old('categories') ?? []) ? 'selected' : '' }}
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
