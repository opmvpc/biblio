@extends('layouts.app')

@section('content')
<div class="container h-100">
    <div class="row justify-content-center">
        @include('visualisations.menu')
        <div class="col-12">
            <div class="card">
                <div class="card-header">Articles par auteurs</div>

                <div class="card-body d-flex justify-content-center">
                    <div
                        id="graph"
                        class="p-3 w-100 flex-grow"
                        style="height:70vh;"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    var myChart = echarts.init(document.getElementById('graph'));
    let categories = [
        {name: 'Articles', itemStyle: {color: '#81e6d9'}},
        {name: 'Auteurs', itemStyle: {color: '#9f7aea'}},
    ];

    myChart.showLoading();
    $.getJSON('/visualisations/api/auteurs', function (json) {
        myChart.hideLoading();
        myChart.setOption(option = {
            title: {
                // text: 'Citations'
            },
            legend: [{
                bottom:'0',
                right: '0',
                selectedMode: 'multiple',
                data: categories.map(function (a) {
                    return a.name;
                })
            }],
            animationDurationUpdate: 1500,
            animationEasingUpdate: 'quinticInOut',
            series : [
                {
                    type: 'graph',
                    layout: 'force',
                    progressiveThreshold: 700,
                    data: json.nodes.map(function (node) {
                        return {
                            x: null,
                            y: null,
                            id: node.slug,
                            name: node.nodeType == "Auteurs" ? node.nom : node.titre,
                            dbId: node.id,
                            category: node.nodeType,
                            symbolSize: node.nodeType == "Auteurs"? (node.articles_count ? (node.articles_count * 10) + 10 : 10) : (node.est_cite_count ? (node.est_cite_count * 10) + 10 : 10),
                        };
                    }),
                    links: json.edges.map(function (edge) {
                        return {
                            source: edge.source,
                            target: edge.target,
                            symbol: ['arrow', ''],
                            lineStyle: {
                                color: '#cbd5e0',
                                curveness: 0,
                            }
                        };
                    }),
                    categories: categories,
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
                        gravity: 0.01,
                        repulsion: 200,
                        edgeLength: 200,
                        layoutAnimation: true,
                    }
                }
            ]
        }, true);
    });

    myChart.on('click', 'series', function (params) {
        if (params.dataType == "edge") {
            return;
        }

        window.open('/'+ params.data.category.toLowerCase() +'/'+ params.data.dbId, '_blank');
    });
});
</script>
@endpush
