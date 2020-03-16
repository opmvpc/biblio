@extends('layouts.app')

@section('content')
<div class="container h-100">
    <div class="row justify-content-center">
        @include('visualisations.menu')
        <div class="col-12">
            <div class="card">
                <div class="card-header">Articles par categories</div>

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
@inject('categorieClass', 'App\Categorie')
@endsection

@push('scripts')
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    var myChart = echarts.init(document.getElementById('graph'));
    let dbCategories = @json($categorieClass::pluck('nom', 'slug'));
    console.log(dbCategories);


    let categories = [{slug:'articles', name:'Articles', itemStyle: {color: '#81e6d9'}}];

    for (const key in dbCategories) {
        if (dbCategories.hasOwnProperty(key)) {
            const element = dbCategories[key];
            categories.push({slug:key, name: element});
        }
    }
    console.log(categories);


    myChart.showLoading();
    $.getJSON('/visualisations/api/categories', function (json) {
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
            // animationDurationUpdate: 1500,
            // animationEasingUpdate: 'quinticInOut',
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
                            nodeType: node.nodeType,
                            name: node.nodeType == "Categories" ? node.nom : node.titre,
                            dbId: node.id,
                            category: node.nodeType == "Categories" ? categories[node.id].name : categories[0].name,
                            symbolSize: node.nodeType == "Categories"? (node.articles_count ? (node.articles_count * 2) + 2 : 2) : (node.est_cite_count ? (node.est_cite_count * 3) + 5 : 5),
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
                        width: 0.7,
                        curveness: 0.3,
                        opacity: 0.7
                    },
                    draggable: true,
                    force: {
                        // initLayout: 'circular',
                        gravity: 0,
                        repulsion: 100,
                        edgeLength: 500,
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

        console.log(params.data.nodeType);

        let url = '/';
        url += params.data.nodeType == "Articles" ? 'articles' : 'categories';
        url += '/';
        url += params.data.dbId;

        console.log(url);

        window.open(url, '_blank');
    });
});
</script>
@endpush
