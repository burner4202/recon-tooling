as.metrics_ledger = {};

as.metrics_ledger.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Daily Mining Value",
			borderColor: "#d80d1d",
			backgroundColor: "#d80d1d",
			data: value,
			type: 'line',
			fill: 'false',
		},	{
			label: "Daily Tax Value",
			borderColor: "#a0d1ef",
			backgroundColor: "#a0d1ef",
			data: tax,
			fill: 'false',
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
	as.metrics_ledger.initChart();
});