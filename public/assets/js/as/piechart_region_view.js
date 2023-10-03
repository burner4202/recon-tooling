as.r64_dist_product = {};
as.r32_dist_product = {};
as.r16_dist_product = {};
as.r8_dist_product = {};
as.r4_dist_product = {};

as.r64_dist_product.initChart = function () {
	var data = {
		labels: r64_labels,
		datasets: [
		{	
			label: "R64 Product Distribution",
			backgroundColor: r64_colours,
			data: r64_values,
			fill: false,
			borderAlign: 'inner',
		},


		]
	};

	var ctx = document.getElementById("r64_dist_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,

		options: {
			maintainAspectRatio: false,

			responsive: true,
			legend: {
				position: 'bottom',
			},
			title: {
				display: false,
				text: 'R64 Product Distribution'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},

			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
					}
				}

			},
		}

	});
};

as.r32_dist_product.initChart = function () {
	var data = {
		labels: r32_labels,
		datasets: [
		{	
			label: "R32 Product Distribution",
			backgroundColor: r32_colours,
			data: r32_values,
			fill: false,
			borderAlign: 'inner',
		},


		]
	};

	var ctx = document.getElementById("r32_dist_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,

		options: {
			maintainAspectRatio: false,

			responsive: true,
			legend: {
				position: 'bottom',
			},
			title: {
				display: false,
				text: 'R32 Product Distribution'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},

			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
					}
				}

			},
		}

	});
};

as.r16_dist_product.initChart = function () {
	var data = {
		labels: r16_labels,
		datasets: [
		{	
			label: "R16 Product Distribution",
			backgroundColor: r16_colours,
			data: r16_values,
			fill: false,
			borderAlign: 'inner',
		},


		]
	};

	var ctx = document.getElementById("r16_dist_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,

		options: {
			maintainAspectRatio: false,

			responsive: true,
			legend: {
				position: 'bottom',
			},
			title: {
				display: false,
				text: 'R16 Product Distribution'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},

			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
					}
				}

			},
		}

	});
};

as.r8_dist_product.initChart = function () {
	var data = {
		labels: r8_labels,
		datasets: [
		{	
			label: "R8 Product Distribution",
			backgroundColor: r8_colours,
			data: r8_values,
			fill: false,
			borderAlign: 'inner',
		},


		]
	};

	var ctx = document.getElementById("r8_dist_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,

		options: {
			maintainAspectRatio: false,

			responsive: true,
			legend: {
				position: 'bottom',
			},
			title: {
				display: false,
				text: 'R8 Product Distribution'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},

			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
					}
				}

			},
		}

	});
};

as.r4_dist_product.initChart = function () {
	var data = {
		labels: r4_labels,
		datasets: [
		{	
			label: "R4 Product Distribution",
			backgroundColor: r4_colours,
			data: r4_values,
			fill: false,
			borderAlign: 'inner',
		},


		]
	};

	var ctx = document.getElementById("r4_dist_chart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'doughnut',
		data: data,

		options: {
			maintainAspectRatio: false,

			responsive: true,
			legend: {
				position: 'bottom',
			},
			title: {
				display: false,
				text: 'R4 Product Distribution'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},

			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
					}
				}

			},
		}

	});
};


$(document).ready(function () {
	as.r64_dist_product.initChart();
	as.r32_dist_product.initChart();
	as.r16_dist_product.initChart();
	as.r8_dist_product.initChart();
	as.r4_dist_product.initChart();
});