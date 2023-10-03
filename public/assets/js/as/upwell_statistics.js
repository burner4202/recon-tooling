as.upwell_statistics = {};

as.upwell_statistics.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Known Structures",
			borderColor: 'rgba(218, 117, 16, 0.9)',
			backgroundColor: 'rgba(218, 117, 16, 0.9)',
			data: online,
			borderWidth: 0.1,
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
			scales: {
				xAxes: [{ stacked: true }],
				yAxes: [{ stacked: true }]
			},
		},

	});
};

$(document).ready(function () {
	as.upwell_statistics.initChart();
});