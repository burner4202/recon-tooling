as.upwell_dist = {};

as.upwell_dist.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Value of Upwell Structures Per Type",
			borderColor: 'rgba(0, 153, 204, 1)',
			backgroundColor: 'rgba(0, 153, 204, 1)',
			data: value,
			fill: 'false'
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

			tooltips: {
				enabled: false,
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
	as.upwell_dist.initChart();
});