@php
    $options = [
        'titre' => 'Titre',
        'type_reference_id' => 'Type',
        'pertinence' => 'Pertinence',
        'date' => 'Date',
        'cite_count' => 'Citations',
        'est_cite_count' => 'Références',
    ];
@endphp

<form class="form-inline" id="tri" action="{{ route('articles.index') }}" method="get">
    <input type="hidden" name="categorie" value="{{ request()->query('categorie') }}">
    <input type="hidden" name="categorie" value="{{ request()->query('recherche') }}">

    <select name="sort"
        id="inp-sort"
        class="form-control mr-3"
        required
        onchange="document.querySelector('#tri').submit()"
    >
        <option value="">Trier par</option>
        @foreach ($options as $id => $option)
            <option
                value="{{ $id }}"
                {{ $id == old('sort', request()->query('sort') ?? '') ? 'selected' : '' }}
            >{{ $option }}</option>
        @endforeach
    </select>

    <select name="order"
        id="inp-order"
        class="form-control"
        required
        onchange="document.querySelector('#tri').submit()"
    >
        @foreach (['DESC' => 'Desc', 'ASC' => 'Asc'] as $id => $option)
            <option
                value="{{ $id }}"
                {{ $id == old('order', request()->query('order') ?? '') ? 'selected' : '' }}
            >{{ $option }}</option>
        @endforeach
    </select>
</form>
