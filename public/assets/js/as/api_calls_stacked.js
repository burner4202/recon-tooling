as.packages = {};

as.packages.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Success",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 255, 0, 0.6)',
			backgroundColor: 'rgba(0, 255, 0, 0.6)',
			data: success
		},
		{
			label: "Errors",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 128, 0, 0.6)',
			backgroundColor: 'rgba(255, 128, 0, 0.6)',
			data: error
		},
		{
			label: "Unauthorized",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 0, 0, 0.6)',
			backgroundColor: 'rgba(255, 0, 0, 0.6)',
			data: unauthorized
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

			legend: {
				position: 'bottom',
			},

			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var label = data.datasets[tooltipItem.datasetIndex].label || '';
						var tooltipValue = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return label + ' : ' + parseInt(tooltipValue).toLocaleString();
					}
				}
			},

			scales: {

				yAxes: [{
					stacked: true,
					display: true,
					position: "left",
					id: "y-axis-1",
					ticks: {
						beginAtZero:true,
						callback: function(value, index, values) {
							if(parseInt(value) >= 1){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
							} else {
								return value;
							}
						},

					}
				}, {
					stacked: true,
					display: false,
					position: "right",
					id: "y-axis-2",
					ticks: {
						beginAtZero:true,
						callback: function(value, index, values) {
							if(parseInt(value) >= 1000){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units';
							} else {
								return value + ' units';
							}
						},

					},

                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],

                    xAxes: [{
                    	stacked: true,
                    }],


                }


            },

        });
};

$(document).ready(function () {
	as.packages.initChart();
});