<div class="form-group">
    <label for="inp-{{ $name }}" class="">{{ $label }}</label>
    <select name="{{ $name }}[]" id="inp-{{ $name }}" multiple class="form-control">
        @foreach ($datas as $id => $data)
            <option
                value="{{ $id }}"
                {{ in_array($id, old($name, $selected)) ? 'selected' : ''}}
            >{{ $data }}</option>
        @endforeach
    </select>
    @if (isset($help))
        <small class="form-text text-muted">
            {{ $help }}
        </small>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#inp-{{ $name }}').select2({
                placeholder: @json($placeholder),
                language: "fr"
            });
        });
    </script>
@endpush
