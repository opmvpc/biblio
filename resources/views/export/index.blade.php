@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        @if (session('ok'))
            <div class="alert alert-success" role="alert">
                {{ session('ok') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">{{ __('Export') }}</div>

            <div class="card-body">
                <h5>Export au format BibTeX</h5>
                <p class="pt-2">Attention! Seuls les articles dont la pertinence est <span class="badge badge-success">Haute</span> seront exportés.</p>
                <a class="btn btn-primary mt-2" target="_blank" href="{{ route('export.bibtex') }}">Générer la bibliographie</a>
            </div>
        </div>
    </div>
</div>
@endsection
