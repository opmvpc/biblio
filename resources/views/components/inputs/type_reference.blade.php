@inject('typeReferenceClass', 'App\TypeReference')
<div class="form-group">
    <label for="inp-type-reference-id" class="">Type</label>
    <select name="type_reference_id" id="inp-type-reference-id" class="form-control" required>
        <option value="">Selectionnez une option</option>
        @foreach ($typeReferenceClass->pluck('nom', 'id') as $id => $typeReference)
            <option
                value="{{ $id }}"
                {{ $id == old('type_reference_id', $selected ?? '') ? 'selected' : '' }}
            >{{ $typeReference }}</option>
        @endforeach
    </select>
</div>
