as.metrics_mining = {};

as.metrics_mining.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Ore Value Distribution",
			borderColor: chartColours,
			backgroundColor: chartColours,
			data: value,
			fill: false,
			pointRadius: 2,
			pointHoverRadius: 6,
			steppedLine: false,
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
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return parseInt(tooltipValue).toLocaleString() + ' isk';
					}
				}
			},

			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true,
						callback: function(value, index, values) {
							if(parseInt(value) >= 1000){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' isk';
							} else {
								return value + ' isk';
							}
						}                            
					}
				}]
			}


		},

	});
};

$(document).ready(function () {
	as.metrics_mining.initChart();
});