as.dashboard = {};

as.dashboard.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Activity (last 30 days)",
			borderColor: "#8e5ea2",
			data: activities
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
		}
	});
};

$(document).ready(function () {
	as.dashboard.initChart();
});