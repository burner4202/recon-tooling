as.minerals_history = {};

as.minerals_history.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Alliance Health Index",
			yAxisID: "y-axis-2",
			borderColor: 'rgba(51, 0, 102, 0.8)',
			backgroundColor: 'rgba(51, 0, 102, 0.8)',
			data: health,
			fill: false,
			pointRadius: 3,
			type: 'line',
		},
				{
			label: "Average ADM",
			yAxisID: "y-axis-2",
			borderColor: 'rgba(0, 51, 102, 0.8)',
			backgroundColor: 'rgba(0, 51, 102, 0.8)',
			data: average_adm,
			fill: false,
			pointRadius: 3,
			type: 'line',
		},

		{
			label: "Infrastructure Hub",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(151, 158, 157, 0.4)',
			backgroundColor: 'rgba(151, 158, 157, 0.4)',
			data: ihub_count,
			type: 'bar'
		}



		]
	};

	var ctx = document.getElementById("myChart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'bar',
		data: data,

		options: {
			responsive: true,
			maintainAspectRatio: false,

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data, label) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						var label = data.datasets[tooltipItem.datasetIndex].label || '';

						if (label) {
							
							if(label == 'Alliance Health Index') {
								var si = '%'
							} else {
								var si = ''
							}

							label += ': ';
						}

						return label + tooltipValue + si;
					}
				}
			},

			scales: {

				yAxes: [{
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "left",
                        id: "y-axis-1",
                        ticks: {
                        	beginAtZero:true,
                        	callback: function(value, index, values) {
                        		//if(parseInt(value) >= 1000){
                        		//	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' %';
                        		//} else {
                        			return value;
                        		//}
                        	},


                        	suggestedMin: 0,
                        	suggestedMax: 100

                        }
                    }, {
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "right",
                        id: "y-axis-2",
                        ticks: {
                        	beginAtZero:true,
                        	callback: function(value, index, values) {
                        		//if(parseInt(value) >= 1000){
                        		//	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units';
                        		//} else {
                        			return value + '%';
                        		//}
                        	},


                        	suggestedMin: 0,
                        	suggestedMax: 100

                        },

                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],


                }


            },

        });
};

$(document).ready(function () {
	as.minerals_history.initChart();
});