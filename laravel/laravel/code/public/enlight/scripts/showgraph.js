// JavaScript Document
var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '75%'],
        size: '110%',
        startAngle: -80,
        endAngle: 80,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};
/**
 * Create the chart when all data is loaded
 * @returns {undefined}
 */
function calculateStatistics() {
	var vcurr,vmax,vmin,vavg;	
  this.series.forEach(series => { 
    const data = series.data.filter(point => point.isInside).map(point => point.y); // grab only points within the visible range
    // calculate statistics for visible points
	vcurr = data[data.length - 1];
	vmax = Math.max.apply(null, data);
	vmin = Math.min.apply(null, data);
	vavg = (data.reduce((a, b) => a + b, 0) / data.length).toFixed(1);
	if(1)
	{
		vcurr = parseFloat(vcurr);
		vmax = parseFloat(vmax);
		vmin = parseFloat(vmin);
		vavg = parseFloat(vavg);
		vcurr = (((typeof vcurr) == "number") ? Math.round(vcurr * 100,2)/100 : 0);
		vmax = (((typeof vmax) == "number") ? Math.round(vmax * 100,2)/100 : 0);
		vmin = (((typeof vmin) == "number") ? Math.round(vmin * 100,2)/100 : 0);
		vavg = (((typeof vavg) == "number") ? Math.round(vavg * 100,2)/100 : 0);
	}
	else
		vcurr = vmax = vmin = vavg = '-';
	const statistics = [
      vcurr,
      vmax,
      vmin,
      vavg
    ];
    
    const legendItem = series.legendItem;
	if (typeof legendItem !== 'undefined') {
		let i = -1;
		// construct the legend string
		const text = legendItem.textStr.replace(/-?\d+\.\d/g, () => statistics[++i]);
		// set the constructed text for the legend
		legendItem.attr({
		  text: text
		});
	}
  });
} 
function createChart(graph_param, seriesOptions) {
	var param_id = graph_param.param_id;
	var type = graph_param.type;
	var tmoption = graph_param.tmoption;
	var graphdata = graph_param.graphdata,
		statistics = graph_param.statistics,
		units = graph_param.units,
		data_fetch = graph_param.data_fetch,
		at_least1_value = graph_param.at_least1_value,
		iszoomable = graph_param.iszoomable,
		param_name = graph_param.param_name,
		colors = graph_param.colors,
		gfheight = graph_param.gfheight,
		legendtoggle = graph_param.legend,
		container_id = graph_param.container_id;
		units = JSON.parse(units);
		
	Highcharts.setOptions({
	   global: {
		  useUTC: false
	   }
	});
	
	Highcharts.chart(container_id, {
		chart: {
	        zoomType: 'x',
			type:"spline",	//line,spline
			events: {
			  load: calculateStatistics,
			},
			height: gfheight ,
			spacingBottom: 5,
			spacingTop: 5,
			spacingLeft: 5,
			spacingRight: 5,
		},
		title: {
            text: param_name,
			style: {
				fontSize: '13px'
			}
		},
		legend: {
			enabled: legendtoggle,
			align: 'left',
			itemStyle: {
				fontWeight: 'normal',
				fontSize: '11px',
				color: '#444444',
			},
			
			labelFormatter: function() {
				return this.name + ' (' + units[this.name] + ')<br>' + 'Curr: ' + '0.0' + ' ' + '<br>Max: ' + '0.0' + '<br>Min: ' + '0.0' + '<br>Avg: ' + '0.0';
				}
		},
		yAxis: {
            title: {
                text: 'Values'
            }
        },
		xAxis: {
			type: 'datetime',
			events: {
				afterSetExtremes:function(event){
					$("#is_zoom").val("yes");
					$("#tf_start").val(event.min);
					$("#tf_end").val(event.max);
					//console.log("From => "+event.min+" To "+event.max);
					if(iszoomable)
					{
						setTimeout(function(){refreshgraph();}, 1000);
					
					}	
					return false;
				}
			},
		},
	
		plotOptions: {
			areaspline: {
				fillOpacity: 0.5
			},
            series: {
				marker: {
					symbol: 'circle',
					radius:1
				},
				lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },	
				showInNavigator: true,
				lineWidth: 1,
				dataGrouping: {
				  enabled: false
				},
				 
            }
        },
		tooltip: {
			shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
            valueDecimals: 2,
            split: false,
			borderWidth: 0,
		    shadow: false
        },
		credits: {
			enabled: false
		},
		series: seriesOptions,
	});
}

	
function initgraph(graph_param)
{
	var seriesOptions = [],
	seriesCounter = 0;
	var param_id = graph_param.param_id;
	var type = graph_param.type;
	var tmoption = graph_param.tmoption;
	var graphdata = graph_param.graphdata,
		statistics = graph_param.statistics,
		units = graph_param.units,
		data_fetch = graph_param.data_fetch,
		at_least1_value = graph_param.at_least1_value,
		iszoomable = graph_param.iszoomable,
		param_name = graph_param.param_name,
		colors = graph_param.colors,
		names = graph_param.names,
		container_id = graph_param.container_id;
		units = JSON.parse(units);
		colors = JSON.parse(colors);
		graphdata = JSON.parse(graphdata);
	if(data_fetch)
	{
		names = JSON.parse(names);
		$.each(names, function (i, name) {
				seriesOptions[i] = {
				name: name,
				data: graphdata[name],
				color: colors[i]
			};
			
			// As we're loading the data asynchronously, we don't know what order it will arrive. So
			// we keep a counter and create the chart when all the data is loaded.
			seriesCounter += 1;
			
			if (seriesCounter === names.length) {
				createChart(graph_param, seriesOptions);
			}
		});
	}
}
// Guage graph options
	// JavaScript Document
	var gaugeOptions = {

		chart: {
			type: 'solidgauge'
		},

		title: null,

		pane: {
			center: ['50%', '75%'],
			size: '110%',
			startAngle: -80,
			endAngle: 80,
			background: {
				backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
				innerRadius: '60%',
				outerRadius: '100%',
				shape: 'arc'
			}
		},

		tooltip: {
			enabled: false
		},

		// the value axis
		yAxis: {
			stops: [
				[0.1, '#55BF3B'], // green
				[0.5, '#DDDF0D'], // yellow
				[0.9, '#DF5353'] // red
			],
			lineWidth: 0,
			minorTickInterval: null,
			tickAmount: 2,
			title: {
				y: -70
			},
			labels: {
				y: 16
			}
		},

		plotOptions: {
			solidgauge: {
				dataLabels: {
					y: 5,
					borderWidth: 0,
					useHTML: true
				}
			}
		}
	};

function createGauge ( graph_param, seriesOptions) {
	var graphdata = graph_param.graphdata,
		min_val = graph_param.min_val,
		max_val = graph_param.max_val,
		units = graph_param.units,
		param_name = graph_param.param_name,
		container_id = graph_param.container_id;
		
	var gaugechart = Highcharts.chart(container_id, Highcharts.merge(gaugeOptions, {
		yAxis: {
			min: min_val,
			max: max_val,
			title: {
				text: param_name
			}
		},

		credits: {
			enabled: false
		},
		series: seriesOptions,

	}));
	
	//refreshdials (gaugechart,container_id);
	
}

function initgauge(graph_param)
{
	var seriesOptions = [],
	seriesCounter = 0;
	var type = graph_param.type;
	var tmoption = graph_param.tmoption;
	var graphdata = graph_param.graphdata,
		units = graph_param.units,
		data_fetch = graph_param.data_fetch,
		param_name = graph_param.param_name,
		colors = graph_param.colors,
		names = graph_param.names,
		container_id = graph_param.container_id;
		units = JSON.parse(units);
		graphdata = JSON.parse(graphdata);
		
	if(data_fetch)
	{
		names = JSON.parse(names);
		$.each(names, function (i, name) {
				seriesOptions[i] = {
				name: name,
				data: [parseInt(graphdata[name])],
				dataLabels: {
				format: '<div style="text-align:center"><span style="font-size:14px;color:' +
					((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:f} '+ units +'</span>'
					//y:.1f
				},
				tooltip: {
					valueSuffix: ' '
				}
			};
			
			// As we're loading the data asynchronously, we don't know what order it will arrive. So
			// we keep a counter and create the chart when all the data is loaded.
			seriesCounter += 1;
			
			if (seriesCounter === names.length) {
				createGauge(graph_param, seriesOptions);
				
			}
		});
	}
}

function refreshdials ( containersrc , container_id )
{
	setInterval(function () {
		var point,
			newVal, inc;
			
		if (containersrc) {
			if($('#'+container_id).length)
			{
				inc;
				point = $('#'+container_id).highcharts().series[0].points[0]; 
				inc = Math.round((Math.random() - 0.5) * 100);
				newVal = point.y + inc;

				if (newVal < 0 || newVal > 100) {
					newVal = point.y - inc;
				}
				point.update(newVal);
			}	
		}
	}, 60000);
}