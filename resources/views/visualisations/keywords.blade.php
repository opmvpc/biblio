@extends('layouts.app')

@section('content')
<div class="container h-100">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Citations entre articles</div>

                <div class="card-body d-flex justify-content-center">
                    <div
                        id="graph"
                        class="p-3 w-100 flex-grow"
                        style="height:60vh;"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @dump($articles) --}}
@endsection

@push('scripts')
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    var pertinences = [
        'red',
        'blue',
        'green'
    ];
    var myChart = echarts.init(document.getElementById('graph'));
    myChart.showLoading();
    $.getJSON('/visualisations/api/articles', function (json) {
        myChart.hideLoading();
        myChart.setOption(option = {
            title: {
                text: 'Citations'
            },
            animationDurationUpdate: 1500,
            animationEasingUpdate: 'quinticInOut',
            series : [
                {
                    type: 'graph',
                    layout: 'force',
                    progressiveThreshold: 700,
                    data: json.nodes.map(function (node) {
                        return {
                            id: node.slug,
                            name: node.titre,
                            dbId: node.id,
                            symbolSize: node.est_cite_count ? (node.est_cite_count * 4) + 10 : 10,
                            itemStyle: {
                                color: node.pertinence ? pertinences[node.pertinence] : 'grey'
                            }
                        };
                    }),
                    edges: json.edges.map(function (edge) {
                        return {
                            source: edge.source,
                            target: edge.target
                        };
                    }),
                    links: {
                        symbol: 'arrow',
                    },
                    emphasis: {
                        label: {
                            position: 'right',
                            show: true
                        }
                    },
                    roam: true,
                    focusNodeAdjacency: true,
                    lineStyle: {
                        width: 0.5,
                        curveness: 0.3,
                        opacity: 0.7
                    },
                    draggable: true,
                    force: {
                        initLayout: 'circular',
                        gravity: 0.1,
                        repulsion: 100,
                        edgeLength: 60,
                        layoutAnimation: true,
                    }
                }
            ]
        }, true);
    });
});

</script>
@endpush
