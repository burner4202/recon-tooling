as.upwell_rig_value = {};

as.upwell_rig_value.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{	
			label: "Structure Rig Value",
			borderColor: 'rgba(0, 0, 255, 0.6)',
			backgroundColor: 'rgba(0, 0, 255, 0.6)',
			data: value,
			fill: 'false',
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

			tooltips: {
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
				}],

				xAxes: [{
					ticks: {
						display: false
					}
				}],

			}

		},

	});
};

$(document).ready(function () {
	as.upwell_rig_value.initChart();
});