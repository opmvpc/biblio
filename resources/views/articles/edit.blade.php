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

                    @component('components.inputs.type_reference', [
                        'selected' => $article->type_reference_id,
                    ])
                    @endcomponent

                    @component('components.inputs.pertinence', [
                        'pertinences' => $pertinences,
                        'selected' => $article->pertinence,
                    ])
                    @endcomponent

                    {!! Form::text('reference', 'Reference') !!}
                    {!! Form::text('doi', 'Doi') !!}
                    {!! Form::urlInput('url', 'Url') !!}
                    {!! Form::date('date', 'Date de publication')->value(old('date', $article->date->format('Y-m-d'))) !!}

                    @component('components.inputs.select2', [
                        'name' => 'categories',
                        'label' => 'Catégories',
                        'datas' => $categories,
                        'selected' => $article->categories->pluck('id')->toArray(),
                        'placeholder' => 'Recherchez des catégories',
                    ])
                    @endcomponent

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

        <div id="form-references" class="card mb-4">
            <div class="card-header">Références</div>

            <div class="card-body">
                @component('articles.tableau', [
                    'articles' => $article->cite,
                    'detachable' => $article,
                    'detachableType' => 'cite'
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.cite', [$article]) !!}

                    @component('components.inputs.citation', [
                        'name' => 'cite',
                        'label' => 'Ajouter des références',
                        'articles' => $articlesCite,
                        'selected' => [],
                        'placeholder' => 'Recherchez des articles',
                        'help' => 'Sélectionnez des articles qui sont cités par cet article',
                    ])
                    @endcomponent

                    @component('components.inputs.doi', [
                        'name' => 'import_doi_cite',
                        'placeholder' => 'Ou importer et ajouter par Doi',
                    ])
                    @endcomponent

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div id="form-citations" class="card mb-4">
            <div class="card-header">Citations</div>

            <div class="card-body">
                @component('articles.tableau', [
                    'articles' => $article->estCite,
                    'detachable' => $article,
                    'detachableType' => 'estCitePar'
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.estCitePar', [$article]) !!}

                    @component('components.inputs.citation', [
                        'name' => 'cite_par',
                        'label' => 'Ajouter citations',
                        'articles' => $articlesEstCite,
                        'selected' => [],
                        'placeholder' => 'Recherchez des articles',
                        'help' => 'Sélectionnez des articles qui citent cet article',
                    ])
                    @endcomponent

                    @component('components.inputs.doi', [
                        'name' => 'import_doi_cite_par',
                        'placeholder' => 'Ou importer et ajouter par Doi',
                    ])
                    @endcomponent

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div id="form-keywords" class="card mb-4">
            <div class="card-header">Mots-clés</div>

            <div class="card-body">
                @component('keywords.tableau', [
                    'keywords' => $keywords,
                    'article' => $article,
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.keywords', [$article]) !!}

                    @component('components.inputs.select2', [
                        'name' => 'keywords',
                        'label' => 'Ajouter des mots-clés',
                        'datas' => $keywordsList,
                        'selected' => [],
                        'placeholder' => 'Recherchez des mots-clés',
                        'help' => 'Sélectionnez des mots-clés pour cet article',
                    ])
                    @endcomponent

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

        <div id="form-auteurs" class="card mb-4">
            <div class="card-header">Auteurs</div>

            <div class="card-body">
                @component('auteurs.tableau', [
                    'auteurs' => $auteurs,
                    'article' => $article,
                ])
                @endcomponent

                {!! Form::open()->post()->route('articles.attach.auteurs', [$article]) !!}

                    @component('components.inputs.select2', [
                        'name' => 'auteurs',
                        'label' => 'Ajouter des auteurs',
                        'datas' => $auteursList,
                        'selected' => [],
                        'placeholder' => 'Recherchez des auteurs',
                        'help' => 'Sélectionnez des auteurs pour cet article',
                    ])
                    @endcomponent

                    {!! Form::submit('Ajouter') !!}

                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>
@endsection
