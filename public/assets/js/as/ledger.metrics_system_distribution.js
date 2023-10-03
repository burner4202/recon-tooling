as.metrics_mining = {};

as.metrics_mining.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Value of Ore per System",
			borderColor: 'rgba(0, 153, 204, 0.5)',
			backgroundColor: 'rgba(0, 153, 204, 0.5)',
			data: value,
			fill: 'false'
		},


		]
	};

	var ctx = document.getElementById("myChart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'horizontalBar',
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
				xAxes: [{
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