as.moons_history = {};

as.moons_history.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Average",
			borderColor: 'rgba(142, 28, 219, 0.8)',
			backgroundColor: 'rgba(142, 28, 219, 0.8)',
			data: average,
			fill: false,
			pointRadius: 2
		}


		]
	};

	var ctx = document.getElementById("myChart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,

		options: {
			responsive: true,
			maintainAspectRatio: false,


		},

	});
};

$(document).ready(function () {
	as.moons_history.initChart();
});