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
                    { data: 'titre', name: 'titre', orderable: false, searchable: true,
                        render: function(data, type, row, meta) {
                            return data.length > 150 ? data.substring(0,150)+'...' : data;
                        }
                    },
                    { data: 'pertinence', name: 'Pertinence', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            if (!data) {
                                return '';
                            }
                            let badge = '';
                            badge += '<span class="badge badge-'+pertinenceDatas[data].couleur+'">'
                            badge += pertinenceDatas[data].nom
                            badge += '</span>'
                            return badge;
                        }
                    },
                    { data: 'auteurs', name: 'Auteurs', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            const auteurs = data;
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
                    { data: 'date', name: 'date', order: 'asc', orderable: false, searchable: false,
                        render: function(data, type, row, meta) {
                            const niceDate = new Date(data).toLocaleDateString();
                            return niceDate
                        }
                    },
                    { data: 'cite_count', name:'cite_count', orderable: false },
                    { data: 'est_cite_count', name:'est_cite_count', orderable: false },
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
