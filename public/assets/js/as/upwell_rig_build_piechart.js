as.upwell_rig_value = {};

as.upwell_rig_value.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Salvage Value Distribution",
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
						return parseInt(tooltipValue).toLocaleString() + ' isk';
					}
				}
			},

		},

	});
};

$(document).ready(function () {
	as.upwell_rig_value.initChart();
});