@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Importer des articles</div>

                <div class="card-body">
                    <p class="alert alert-info">
                        Importez les données d'un ou plusieurs articles en introduisant un doi OU une liste de références BibTeX.
                        <br>Vous pouvez les lier à d'autres articles (citations, références).
                    </p>
                    {!! Form::open()->post()->route('articles.importer.store') !!}

                        @component('components.inputs.doi', [
                            'name' => 'doi'
                        ])
                        @endcomponent

                        {!! Form::textarea('bibtex', 'BibTeX')->placeholder('Coller une ou plusieures réferences BibTeX')->attrs(['rows' => 15]) !!}

                        <div class="form-group">
                            <label for="inp-cite" class="">Cite (Citations)</label>
                            <select name="cite[]" id="inp-cite" multiple class="form-control">
                                @foreach ($articles as $article)
                                    <option
                                        value="{{ $article->id }}"
                                        {{ in_array($article->id, old('cite', [])) ? 'selected' : ''}}
                                    >{{ $article->titre .' | '. $article->reference }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Sélectionnez des articles qui sont des citations des articles importés
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="inp-cite_par" class="">Cité par (Références)</label>
                            <select name="cite_par[]" id="inp-cite_par" multiple class="form-control">
                                @foreach ($articles as $article)
                                    <option
                                        value="{{ $article->id }}"
                                        {{ in_array($article->id, old('cite_par', [])) ? 'selected' : ''}}
                                    >{{ $article->titre .' | '. $article->reference }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Sélectionnez des articles qui citent les articles importés
                            </small>
                        </div>

                        {!! Form::submit('Importer') !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
