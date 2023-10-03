as.metrics_ledger_volume = {};

as.metrics_ledger_volume.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Daily Mining Volume (Units)",
			borderColor: "#a0d1ef",
			backgroundColor: "#a0d1ef",
			data: volume,
			fill: 'false',
		},	

		]
	};

	var ctx = document.getElementById("myChart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,

		options: {
			responsive: true,
			maintainAspectRatio: false,

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return parseInt(tooltipValue).toLocaleString() + ' units';
					}
				}
			},

			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true,
						callback: function(value, index, values) {
							if(parseInt(value) >= 1000){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units';
							} else {
								return value + ' units';
							}
						}                            
					}
				}]
			}

		},

	});
};

$(document).ready(function () {
	as.metrics_ledger_volume.initChart();
});