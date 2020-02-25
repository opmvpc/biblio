@component('components.table', [
    'id' => 'articles',
    'thead' => [
        'Titre',
        'Pertinence',
        'Auteurs',
        'Date Publication',
        'Citations',
        'Cité par',
    ]
])
@endcomponent

@push('scripts')
    @inject('articleClass', 'App\Article')
    <script>
        const pertinenceDatas = @json($articleClass::getPertinenceDatas())

        if (window.tables == undefined) {
            window.tables = [];
        }

        window.tables.push(
            {
                'id': 'articles',
                'url': @json(route('api.articles')),
                'cols': [
                    { data: null, name: 'titre', orderable: true, searchable: true,
                        render: function(data, type, row, meta) {
                            return data.titre.length > 150 ? data.titre.substring(0,150)+'...' : data.titre;
                        }
                    },
                    { data: null, name: 'Pertinence', orderable: true, searchable: false,
                        render: function(data, type, row, meta) {
                            if (!data.pertinence) {
                                return '';
                            }
                            let badge = '';
                            badge += '<span class="badge badge-'+pertinenceDatas[data.pertinence].couleur+'">'
                            badge += pertinenceDatas[data.pertinence].nom
                            badge += '</span>'
                            return badge;
                        }
                    },
                    { data: null, name: 'Auteurs', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            let auteurs = data.auteurs;
                            let auteursString = '';
                            for (let index = 0; index < auteurs.length; index++) {
                                const element = auteurs[index].nom;
                                auteursString += element;
                                if (index < auteurs.length - 1) {
                                    auteursString += ', ';
                                }
                            }
                            return auteursString.length > 150 ? auteursString.substring(0,150)+'...' : auteursString;
                        }
                    },
                    { data: null, name: 'date', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            const niceDate = new Date(data.date).toLocaleDateString();
                            return niceDate
                        }
                    },
                    { data: 'cite_count', name:'cite_count' },
                    { data: 'est_cite_count', name:'est_cite_count' },
                    { data: null, name: 'Actions', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            let actions = '';
                            actions += '<div class="text-center"><a href="/articles/'+row.id+' class="btn btn-link">Voir</a>'
                            actions += '<div class="text-center"><a href="/articles/'+row.id+'/edit" class="btn btn-link">Éditer</a>'
                            return actions;
                        }
                    }
                ],
            }
        );
    </script>
@endpush
