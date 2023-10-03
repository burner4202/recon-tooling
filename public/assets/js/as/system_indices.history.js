as.minerals_history = {};

as.minerals_history.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Manufacturing",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 0, 0, 0.9)',
			backgroundColor: 'rgba(255, 0, 0, 0.9)',
			data: manu,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},
		{
			label: "Research TE",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 191, 0, 0.75)',
			backgroundColor: 'rgba(255, 191, 0, 0.75)',
			data: rte,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},
		{
			label: "Research ME",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 255, 64, 0.75)',
			backgroundColor: 'rgba(0, 255, 64, 0.75)',
			data: rme,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},
		{
			label: "Copying",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 128, 255, 0.75)',
			backgroundColor: 'rgba(0, 128, 255, 0.75)',
			data: copy,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},
		{
			label: "Invention",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(191, 0, 255, 0.75)',
			backgroundColor: 'rgba(191, 0, 255, 0.75)',
			data: inv,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},
		{
			label: "Reactions",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 102, 0.75)',
			backgroundColor: 'rgba(0, 0, 102, 0.75)',
			data: react,
			fill: false,
			pointRadius: 2,
			type: 'line',
			borderWidth: 1.5,
		},



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
					label: function(tooltipItem, data) {
						var label = data.datasets[tooltipItem.datasetIndex].label || '';

						if (label) {
							label += ': ';
						}
						label += parseFloat((tooltipItem.yLabel) * 100).toFixed(2) + '%';
						return label;
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

                        		return (value*100).toFixed(2) + '%';

                        	},

                        }
                    }, {
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: false,
                        position: "right",
                        id: "y-axis-2",
                        ticks: {
                        	beginAtZero:true,
                        	callback: function(value, index, values) {
                        		if(parseInt(value) >= 1000){
                        			return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units';
                        		} else {
                        			return value + ' units';
                        		}
                        	},

                        },

                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],


                },

                legend: {
                	position: 'right',
                },


            },

        });
};

$(document).ready(function () {
	as.minerals_history.initChart();
});