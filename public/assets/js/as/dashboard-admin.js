as.dashboard = {};

as.dashboard.initChart = function () {
	var data = {
		labels: months,
		datasets: [
		{
			label: trans.chartLabel,
			borderColor: "#8e5ea2",
			data: users
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

			title: {
				display: true,
				text: trans.chartLabel
			},
		}
	});
};

$(document).ready(function () {
	as.dashboard.initChart();
});