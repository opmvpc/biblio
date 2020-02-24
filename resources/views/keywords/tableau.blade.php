<div class="table-responsive">
    {{ method_exists($keywords, 'total') ? $keywords->total() : $keywords->count() }} mots-clés
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Nombre d'articles</th>
                <th scope="col" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($keywords as $keyword)
                <tr>
                    <td>{{ $keyword->nom }}</td>
                    <td>{{ $keyword->articles_count }}</td>
                    <td style="width: 10px;">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('keywords.show', $keyword) }}" class="btn btn-link">voir</a>
                            @auth
                                <a href="{{ route('keywords.edit', $keyword) }}" class="btn btn-link">modifier</a>
                                @isset($article)
                                    <a href="{{ route('articles.detach.keyword', ['article' => $article, 'keyword' => $keyword]) }}" class="btn btn-link">détacher</a>
                                @endisset
                            @endauth
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Pas encore de mot-clé</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

@if(method_exists($keywords, 'links'))
    <div class="d-flex justify-content-center">
        {!! $keywords->links() !!}
    </div>
@endif
