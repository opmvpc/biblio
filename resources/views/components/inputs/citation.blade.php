<div class="form-group">
    <label for="inp-{{ $name }}" class="">{{ $label }}</label>
    <select name="{{ $name }}[]" id="inp-{{ $name }}" multiple class="form-control">
        @foreach ($articles as $article)
            <option
                value="{{ $article->id }}"
                {{ in_array($article->id, old($name, $selected)) ? 'selected' : ''}}
            >{{ $article->titre .' | '. $article->reference }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">
        {{ $help }}
    </small>
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
