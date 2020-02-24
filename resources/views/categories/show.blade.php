@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="">Informations</span>
                    @auth
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-link float-right py-0">modifier</a>
                    @endauth
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-3 text-right">
                        Nom:
                    </dt>
                    <dd class="col-9">
                        {{ $category->nom }}
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    {{ __('Articles') }}
                </div>
            </div>
            <div class="card-body">
                @component('articles.tableau', ['articles' => $articles])
                @endcomponent
            </div>
        </div>
    </div>
</div>
@endsection
