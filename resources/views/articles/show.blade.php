@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        <h5 class="mb-3">{{ $article->titre }}</h5>

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="">Informations</span>
                    @auth
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-link float-right py-0">modifier</a>
                    @endauth
                </div>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-3 text-right">
                        Auteurs:
                    </dt>
                    <dd class="col-9">
                        {{ Str::limit($article->auteurs->implode('nom', ', '), 100) }}
                    </dd>

                    <dt class="col-3 text-right">
                        Pertinence:
                    </dt>
                    <dd class="col-9">
                        <div>
                            <span class="badge badge-{{ $article->getPertinenceData('couleur') }}">
                                {{ $article->getPertinenceData('nom') }}
                            </span>
                        </div>
                    </dd>

                    <dt class="col-3 text-right">
                        Référence:
                    </dt>
                    <dd class="col-9">
                        {{ $article->reference }}
                    </dd>

                    <dt class="col-3 text-right">
                        Doi:
                    </dt>
                    <dd class="col-9">
                        {{ $article->doi }}
                    </dd>

                    <dt class="col-3 text-right">
                        Mots-clés:
                    </dt>
                    <dd class="col-9">
                        {{ Str::limit($article->keywords->implode('nom', ', '), 100) }}
                    </dd>

                    <dt class="col-3 text-right">
                        Url:
                    </dt>
                    <dd class="col-9">
                        <a target="_blank" href="{{ $article->url }}">{{ $article->url }}</a>
                    </dd>

                    <dt class="col-3 text-right">
                        Date de publication:
                    </dt>
                    <dd class="col-9">
                        {{ $article->date }}
                    </dd>


                    <dt class="col-3 text-right">
                        Résumé:
                    </dt>
                    <dd class="col-9">
                        {!! nl2br($article->resume) !!}
                    </dd>

                    <dt class="col-3 text-right">
                        Abstract:
                    </dt>
                    <dd class="col-9">
                        {!! nl2br($article->abstract) !!}
                    </dd>

                    @if ($article->path_article != null)
                        <dt class="col-3 text-right">
                            Pdf:
                        </dt>
                        <dd class="col-9">
                            <a target="_blank" href="{{ asset('storage/'.$article->path_article) }}">Lien pdf</a>
                        </dd>
                    @endif

                </dl>
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
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Citations</div>

            <div class="card-body">
                @component('articles.tableau', ['articles' => $article->cite])
                @endcomponent
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Cité par</div>

            <div class="card-body">
                @component('articles.tableau', ['articles' => $article->estCite])
                @endcomponent
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
            </div>
        </div>

    </div>
</div>
@endsection
