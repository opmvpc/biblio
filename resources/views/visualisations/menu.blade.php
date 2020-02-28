<div class="d-flex justify-content-xl-center align-items-center mb-1 mb-md-3" style="overflow-x: auto;">
    <a
        class="btn btn-xs mr-4 mx-xl-2 {{ request()->is('visualisations/articles') ? 'btn-dark' : 'btn-light'}}"
        href="{{ route('visualisations.articles') }}"
    >
        Citations entre articles
    </a>
    <a
        class="btn btn-xs mr-4 mx-xl-2 {{ request()->is('visualisations/auteurs') ? 'btn-dark' : 'btn-light'}}"
        href="{{ route('visualisations.auteurs') }}"
    >
        Auteurs et articles
    </a>
</div>
