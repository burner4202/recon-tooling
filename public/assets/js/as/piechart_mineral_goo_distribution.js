as.upwell_rig_value = {};

as.upwell_rig_value.initChart = function () {
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
						return label + ':' + parseInt(tooltipValue).toLocaleString() + 'isk';
					}
				}
			},

		},

	});
};

$(document).ready(function () {
	as.upwell_rig_value.initChart();
});