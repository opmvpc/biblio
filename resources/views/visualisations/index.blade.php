@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Visualisations</div>

                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('visualisations.articles') }}" class="list-group-item list-group-item-action">
                          Articles
                        </a>
                        <a href="{{ route('visualisations.keywords') }}" class="list-group-item list-group-item-action">
                            Mots-clés
                        </a>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
