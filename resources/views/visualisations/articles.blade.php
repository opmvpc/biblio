@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Citations entre articles</div>

                <div class="card-body d-flex justify-content-center">
                    <div id="vis" class="p-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @dump($articles) --}}
@endsection

@push('scripts')


{{--   <script src="https://cdnjs.cloudflare.com/ajax/libs/vega/3.0.9/vega.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vega-lite/2.0.4/vega-lite.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vega-embed/3.0.0-rc7/vega-embed.js"></script>
 --}}
  <script src="https://cdn.jsdelivr.net/npm/vega@5"></script>
<script src="https://cdn.jsdelivr.net/npm/vega-lite@4"></script>
<script src="https://cdn.jsdelivr.net/npm/vega-embed@6"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
        // const datas = @json($articles);
        // console.log(datas);
        var yourVlSpec = {
                "$schema": "https://vega.github.io/schema/vega/v5.0.json",
                "config": {
                    "background": "#ffffff"
                },
                "width": 500,
                "height": 500,
                "padding": 0,
                "autosize": "none",
                "signals": [
                    {
                    "name": "cx",
                    "update": "width / 2"
                    },
                    {
                    "name": "cy",
                    "update": "height / 2"
                    },
                    {
                    "name": "nodeRadius",
                    "value": 8,
                    "bind": {
                        "input": "range",
                        "min": 1,
                        "max": 50,
                        "step": 1
                    }
                    },
                    {
                    "name": "nodeCharge",
                    "value": -30,
                    "bind": {
                        "input": "range",
                        "min": -100,
                        "max": 10,
                        "step": 1
                    }
                    },
                    {
                    "name": "linkDistance",
                    "value": 30,
                    "bind": {
                        "input": "range",
                        "min": 5,
                        "max": 100,
                        "step": 1
                    }
                    },
                    {
                    "name": "static",
                    "value": true,
                    "bind": {
                        "input": "checkbox"
                    }
                    },
                    {
                    "description": "throttles the number of nodes rendered; 77 covers 'all'",
                    "name": "numNodes",
                    "value": 200
                    },
                    {
                    "description": "State variable for active node fix status.",
                    "name": "fix",
                    "value": 0,
                    "on": [
                        {
                        "events": "symbol:mouseout[!event.buttons], window:mouseup",
                        "update": "0"
                        },
                        {
                        "events": "symbol:mouseover",
                        "update": "fix || 1"
                        },
                        {
                        "events": "[symbol:mousedown, window:mouseup] > window:mousemove!",
                        "update": "2",
                        "force": true
                        }
                    ]
                    },
                    {
                    "description": "Graph node most recently interacted with.",
                    "name": "node",
                    "value": null,
                    "on": [
                        {
                        "events": "symbol:mouseover",
                        "update": "fix === 1 ? item() : node"
                        }
                    ]
                    },
                    {
                    "description": "Flag to restart Force simulation upon data changes.",
                    "name": "restart",
                    "value": false,
                    "on": [
                        {
                        "events": {
                            "signal": "fix"
                        },
                        "update": "fix > 1"
                        }
                    ]
                    }
                ],
                "data": [
                    {
                    "name": "node-data",
                    "url": "/visualisations/api/articles",
                    "format": {
                        "type": "json",
                        "property": "nodes"
                    }
                    },
                    {
                    "name": "link-data",
                    "url": "/visualisations/api/articles",
                    "format": {
                        "type": "json",
                        "property": "links"
                    },
                    "transform": []
                    }
                ],
                "scales": [
                    {
                    "name": "color",
                    "type": "ordinal",
                    "range": {
                        "scheme": "category20"
                    }
                    }
                ],
                "marks": [
                    {
                    "name": "nodes",
                    "type": "symbol",
                    "zindex": 1,
                    "from": {
                        "data": "node-data"
                    },
                    "on": [
                        {
                        "trigger": "fix",
                        "modify": "node",
                        "values": "fix === 1 ? {fx:node.x, fy:node.y} : {fx:x(), fy:y()}"
                        },
                        {
                        "trigger": "!fix",
                        "modify": "node",
                        "values": "{fx: null, fy: null}"
                        }
                    ],
                    "encode": {
                        "enter": {
                        "fill": {
                            "scale": "color",
                            "field": "group"
                        },
                        "stroke": {
                            "value": "white"
                        }
                        },
                        "update": {
                        "size": {
                            "signal": "2 * nodeRadius * nodeRadius"
                        },
                        "cursor": {
                            "value": "pointer"
                        }
                        }
                    },
                    "transform": [
                        {
                        "type": "force",
                        "iterations": 300,
                        "restart": {
                            "signal": "restart"
                        },
                        "static": {
                            "signal": "static"
                        },
                        "forces": [
                            {
                            "force": "center",
                            "x": {
                                "signal": "cx"
                            },
                            "y": {
                                "signal": "cy"
                            }
                            },
                            {
                            "force": "collide",
                            "radius": {
                                "signal": "nodeRadius"
                            }
                            },
                            {
                            "force": "nbody",
                            "strength": {
                                "signal": "nodeCharge"
                            }
                            },
                            {
                            "force": "link",
                            "links": "link-data",
                            "distance": {
                                "signal": "linkDistance"
                            }
                            }
                        ]
                        }
                    ]
                    },
                    {
                    "type": "path",
                    "from": {
                        "data": "link-data"
                    },
                    "interactive": false,
                    "encode": {
                        "update": {
                        "stroke": {
                            "value": "#ccc"
                        },
                        "strokeWidth": {
                            "value": 0.5
                        }
                        }
                    },
                    "transform": [
                        {
                        "type": "linkpath",
                        "shape": "line",
                        "sourceX": "datum.source.x",
                        "sourceY": "datum.source.y",
                        "targetX": "datum.target.x",
                        "targetY": "datum.target.y"
                        }
                    ]
                    }
                ]
                };
        vegaEmbed('#vis', yourVlSpec, {renderer: 'svg'});
    });
</script>
@endpush
