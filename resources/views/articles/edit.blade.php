@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    {{ __('Modifier un article') }}
                    <form action="{{ route('articles.destroy', $article) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-link text-danger" onclick="confirm('Attention! Voulez-vous vraiment supprimer cet article?') ? null : event.preventDefault()">Supprimer</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                {!! Form::open()->put()->route('articles.update', [$article])->fill($article) !!}

                    {!! Form::text('titre', 'Titre') !!}

                    @component('components.inputs.pertinence', [
                        'pertinences' => $pertinences,
                        'selected' => $article->pertinence,
                    ])
                    @endcomponent

                    {!! Form::text('reference', 'Reference') !!}
                    {!! Form::text('doi', 'Doi') !!}
                    {!! Form::urlInput('url', 'Url') !!}
                    {!! Form::date('date', 'Date de publication')->value(old('date', $article->date->format('Y-m-d'))) !!}

                    <div class="form-group">
                        <label for="inp-categories" class="">Catégories</label>
                        <select name="categories[]" id="inp-categories" multiple class="form-control">
                            @foreach ($categories as $key => $categorie)
                                <option
                                    value="{{ $key }}"
                                    {{ in_array($key, $article->categories->pluck('id')->toArray()) ? 'selected' : '' }}
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

        <div class="card mb-4">
            <div class="card-header">{{ __('Fichier') }}</div>

            <div class="card-body">
                @if ($article->path_article != null)
                    <a target="_blank" href="{{ asset('storage/'.$article->path_article) }}">Lien pdf</a>
                @endif
                <div class="form-group">
                    <label for="image-upload" class="">Ajouter un pdf</label>
                    <form action="{{ route('articles.upload', [$article]) }}"
                        class="dropzone"
                        id="image-upload"
                    >
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Mots-clés</div>

            <div class="card-body">
                @component('keywords.tableau', [
                    'keywords' => $keywords,
                    'article' => $article,
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.keywords', [$article]) !!}

                    <div class="form-group">
                        <label for="inp-keywords" class="">Ajouter des mots-clés</label>
                        <select name="keywords[]" id="inp-keywords" multiple required class="form-control">
                            @foreach ($keywordsList as $keywordId => $keyword)
                                <option
                                    value="{{ $keywordId }}"
                                >{{ $keyword }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Sélectionnez des mots-clés pour cet article
                        </small>
                    </div>

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Citations</div>

            <div class="card-body">
                @component('articles.tableau', [
                    'articles' => $article->cite,
                    'detachable' => $article,
                    'detachableType' => 'cite'
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.cite', [$article]) !!}

                    <div class="form-group">
                        <label for="inp-cite" class="">Ajouter des Citations</label>
                        <select name="cite[]" id="inp-cite" multiple class="form-control">
                            @foreach ($articlesCite as $articleCite)
                                <option
                                    value="{{ $articleCite->id }}"
                                >{{ $articleCite->titre .' | '. $articleCite->reference }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Sélectionnez des articles qui sont cités par cet article
                        </small>
                    </div>

                    {!! Form::text('import_doi_cite', 'Ou importer et ajouter par Doi')->placeholder('doi de la citation')->help('<a target="_blank" href="https://citation.crosscite.org/">Vérifier doi</a>') !!}

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Cité par</div>

            <div class="card-body">
                @component('articles.tableau', [
                    'articles' => $article->estCite,
                    'detachable' => $article,
                    'detachableType' => 'estCitePar'
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.estCitePar', [$article]) !!}

                    <div class="form-group">
                        <label for="inp-cite_par" class="">Ajouter des Citations</label>
                        <select name="cite_par[]" id="inp-cite_par" multiple class="form-control">
                            @foreach ($articlesEstCite as $articleCitePar)
                                <option
                                    value="{{ $articleCitePar->id }}"
                                >{{ $articleCitePar->titre .' | '. $articleCitePar->reference }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Sélectionnez des articles qui citent cet article
                        </small>
                    </div>

                    {!! Form::text('import_doi_cite_par', 'Ou importer et ajouter par Doi')->placeholder('doi de la citation')->help('<a target="_blank" href="https://citation.crosscite.org/">Vérifier doi</a>') !!}

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Auteurs</div>

            <div class="card-body">
                @component('auteurs.tableau', [
                    'auteurs' => $auteurs,
                    'article' => $article,
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.auteurs', [$article]) !!}

                    <div class="form-group">
                        <label for="inp-auteurs" class="">Ajouter des auteurs</label>
                        <select name="auteurs[]" id="inp-auteurs" multiple required class="form-control">
                            @foreach ($auteursList as $auteursId => $auteurs)
                                <option
                                    value="{{ $auteursId }}"
                                >{{ $auteurs }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Sélectionnez des auteurs pour cet article
                        </small>
                    </div>

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>
@endsection
