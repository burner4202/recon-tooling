as.minerals_history = {};

as.minerals_history.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Average",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 153, 0, 0.8)',
			backgroundColor: 'rgba(0, 153, 0, 0.8)',
			data: average,
			fill: false,
			pointRadius: 1,
			type: 'line',
		},
		{
			label: "Highest",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 0, 0, 0.8)',
			backgroundColor: 'rgba(255, 0, 0, 0.8)',
			data: highest,
			fill: false,
			pointRadius: 1,
			type: 'line',
		},
		{
			label: "Lowest",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 255, 0.8)',
			backgroundColor: 'rgba(0, 0, 255, 0.8)',
			data: lowest,
			fill: false,
			pointRadius: 1,
			type: 'line',
		},
		{
			label: "Volume",
			yAxisID: "y-axis-2",
			borderColor: 'rgba(151, 158, 157, 0.4)',
			backgroundColor: 'rgba(151, 158, 157, 0.4)',
			data: volume,
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
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return parseInt(tooltipValue).toLocaleString();
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
                        		if(parseInt(value) >= 1000){
                        			return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' isk';
                        		} else {
                        			return value + ' isk';
                        		}
                        	},

                        }
                    }, {
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
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


                }


            },

        });
};

$(document).ready(function () {
	as.minerals_history.initChart();
});