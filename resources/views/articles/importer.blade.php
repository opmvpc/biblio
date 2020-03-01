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


                        @component('components.inputs.citation', [
                            'name' => 'cite_par',
                            'label' => 'Ajouter les articles importés comme références à',
                            'articles' => $articles,
                            'selected' => [],
                            'placeholder' => 'Recherchez des articles',
                            'help' => 'Sélectionnez des articles qui citent les articles importés',
                        ])
                        @endcomponent

                        @component('components.inputs.citation', [
                            'name' => 'cite',
                            'label' => 'Ajouter les articles importés comme citations de',
                            'articles' => $articles,
                            'selected' => [],
                            'placeholder' => 'Recherchez des articles',
                            'help' => 'Sélectionnez des articles qui sont des citations des articles importés',
                        ])
                        @endcomponent

                        {!! Form::submit('Importer') !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
