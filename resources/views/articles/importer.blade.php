@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Importer des articles</div>

                <div class="card-body">

                    {!! Form::open()->post()->route('articles.importer.store') !!}

                        {!! Form::text('doi', 'Doi')->placeholder('doi de la citation')->help('<a target="_blank" href="https://citation.crosscite.org/">Vérifier doi</a>') !!}

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
                        </div>

                        {!! Form::submit('Importer') !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
