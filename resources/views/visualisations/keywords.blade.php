@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Visualisations</div>

                <div class="card-body">
                    <div id="vis"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@dump($keywords)
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vega@5.9.1"></script>
<script src="https://cdn.jsdelivr.net/npm/vega-lite@4.4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/vega-embed@6.2.2"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        const datas = @json($keywords);
        console.log(datas);
        var yourVlSpec = {
            $schema: 'https://vega.github.io/schema/vega-lite/v2.0.json',
            description: 'A simple bar chart with embedded data.',
            data: {
                values: [
                    {a: 'A', b: 28},
                    {a: 'B', b: 55},
                    {a: 'C', b: 43},
                    {a: 'D', b: 91},
                    {a: 'E', b: 81},
                    {a: 'F', b: 53},
                    {a: 'G', b: 19},
                    {a: 'H', b: 87},
                    {a: 'I', b: 52}
                ]
            },
            mark: 'bar',
            encoding: {
                x: {field: 'a', type: 'ordinal'},
                y: {field: 'b', type: 'quantitative'}
            }
        };
        vegaEmbed('#vis', yourVlSpec);
    });
</script>
@endpush
