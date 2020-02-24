<div class="form-group">
    <label for="inp-pertinence" class="">Pertinence</label>
    <select name="pertinence" id="inp-pertinence" class="form-control">
        <option value="">Selectionnez une option</option>
        @foreach ($pertinences as $key => $pertinence)
            <option
                value="{{ $key }}"
                class="text-{{ $pertinence->get('couleur') }}"
                {{ $key == old('pertinence', $selected ?? '') ? 'selected' : '' }}
            >{{ $pertinence->get('nom') }}</option>
        @endforeach
    </select>
</div>
