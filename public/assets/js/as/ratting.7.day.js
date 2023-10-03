as.ratting_per_hour = {};

as.ratting_per_hour.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Previous 24 Hours",
			borderColor: 'rgba(0, 153, 204, 0.5)',
			backgroundColor: 'rgba(0, 153, 204, 0.5)',
			data: npcs,
			fill: 'false',
		},	
		{	
			label: "48 Hours",
			borderColor: 'rgba(160, 12, 27, 0.3)',
			backgroundColor: 'rgba(160, 12, 27, 0.3)',
			data: npcs48hour,
			fill: 'false',
			borderDash: [5, 5],
		},	

		]
	};

	var ctx = document.getElementById("myChart").getContext("2d");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,

		options: {
			responsive: true,
			maintainAspectRatio: false,

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return parseInt(tooltipValue).toLocaleString() + ' rats';
					}
				}
			},

			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true,
						callback: function(value, index, values) {
							if(parseInt(value) >= 1000){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' rats';
							} else {
								return value + ' rats';
							}
						}                            
					}
				}]
			}

		},

	});
};

$(document).ready(function () {
	as.ratting_per_hour.initChart();
});