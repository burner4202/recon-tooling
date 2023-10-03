as.moon_dist_product = {};
as.moon_dist_ore = {};

as.moon_dist_product.initChart = function () {
	var data = {
		labels: labels_product,
		datasets: [
		{	
			label: "Moon Product Distribution",
			borderColor: chartColours_product,
			backgroundColor: chartColours_product,
			data: value_product,
			fill: false,
			pointRadius: 2,
			pointHoverRadius: 6,
			steppedLine: false,
		},


		]
	};

	var ctx = document.getElementById("moon_product_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,



		options: {
			responsive: true,
			maintainAspectRatio: false,

			legend: {
						position: 'bottom',
					},

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						var label = data.datasets[tooltipItem.datasetIndex].label || '';
						return label + ' : ' + parseInt(tooltipValue).toLocaleString() + '%';
					}
				}
			},

		},

	});
};

as.moon_dist_ore.initChart = function () {
	var data = {
		labels: labels_mineral,
		datasets: [
		{	
			label: "Mineral/Goo Distribution",
			borderColor: chartColours_mineral,
			backgroundColor: chartColours_mineral,
			data: value_mineral,
			fill: false,
			pointRadius: 2,
			pointHoverRadius: 6,
			steppedLine: false,
		},


		]
	};

	var ctx = document.getElementById("moon_mineral_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,



		options: {
			responsive: true,
			maintainAspectRatio: false,

			legend: {
						position: 'bottom',
					},

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						var label = data.datasets[tooltipItem.datasetIndex].label || '';
						return label + ' : ' + parseInt(tooltipValue).toLocaleString() + ' isk';
					}
				}
			},

		},

	});
};

$(document).ready(function () {
	as.moon_dist_product.initChart();
	as.moon_dist_ore.initChart();
});