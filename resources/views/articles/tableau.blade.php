<div class="table-responsive">
    {{ method_exists($articles, 'total') ? $articles->total() : $articles->count() }} articles
    <table class="table table-striped">
        <thead>
            <tr>
                <th
                    scope="col"
                >
                    Titre
                </th>
                <th
                    scope="col"
                >
                    Type
                </th>
                <th
                    scope="col"
                    class="text-center"
                >
                    Pertinence
                </th>
                <th
                    scope="col"
                >
                    Auteurs
                </th>
                <th
                    scope="col"
                >
                    Date Publication
                </th>
                <th
                    scope="col"
                >
                    Références
                </th>
                <th
                    scope="col"
                >
                    Citations</th>
                <th
                    scope="col"
                    class="text-center"
                >
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>

            @forelse ($articles as $article)
                <tr>
                    <td>{{ Str::limit($article->titre, 100) }}</td>
                    <td>{{ $article->type->nom }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $article->getPertinenceData('couleur') }}">
                            {{ $article->getPertinenceData('nom') }}
                        </span>
                    </td>
                    <td>{{ Str::limit($article->auteurs->implode('nom', ', '), 100) }}</td>
                    <td>{{ $article->date->format('m/Y') }}</td>
                    <td>{{ $article->cite()->count() }}</td>
                    <td>{{ $article->estCite()->count() }}</td>
                    <td style="width: 10px;">
                        <div class="d-flex flex-column justify-content-center">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-link">voir</a>
                            @auth
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-link">modifier</a>
                                @isset($detachable)
                                    <a href="{{ route('articles.detach.'. $detachableType, ['article' => $detachable, 'detachable' => $article]) }}" class="btn btn-link">détacher</a>
                                @endisset
                            @endauth
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Pas encore d'article</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

@if(method_exists($articles, 'links'))
    <div class="d-flex justify-content-center mt-3" style="overflow-x: auto;">
        {!! $articles->links() !!}
    </div>
@endif
