<div class="table-responsive">
    {{ method_exists($auteurs, 'total') ? $auteurs->total() : $auteurs->count() }} auteurs
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Nombre d'articles</th>
                <th scope="col" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($auteurs as $auteur)
                <tr>
                    <td>{{ $auteur->nom }}</td>
                    <td>{{ $auteur->articles_count }}</td>
                    <td style="width: 10px;">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('auteurs.show', $auteur) }}" class="btn btn-link">voir</a>
                            @auth
                                <a href="{{ route('auteurs.edit', $auteur) }}" class="btn btn-link">modifier</a>
                                @isset($article)
                                    <a href="{{ route('articles.detach.auteur', ['article' => $article, 'auteur' => $auteur]) }}" class="btn btn-link">détacher</a>
                                @endisset
                            @endauth
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Pas encore d'auteur</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

@if(method_exists($auteurs, 'links'))
    <div class="d-flex justify-content-center">
        {!! $auteurs->links() !!}
    </div>
@endif
