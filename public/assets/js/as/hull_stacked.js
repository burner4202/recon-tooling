as.hull_stacked = {};

as.hull_stacked.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		{
			label: "Titan",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(170, 110, 40, 0.8)',
			backgroundColor: 'rgba(170, 110, 40, 0.8)',
			data: titan
		},
		{
			label: "Faction Titan",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(128, 128, 0, 0.8)',
			backgroundColor: 'rgba(128, 128, 0, 0.8)',
			data: faction_titan
		},
		{
			label: "Supercarrier",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 128, 128, 0.8)',
			backgroundColor: 'rgba(0, 128, 128, 0.8)',
			data: supercarrier
		},
		{
			label: "Carrier",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 128, 0.8)',
			backgroundColor: 'rgba(0, 0, 128, 0.8)',
			data: carrier
		},
		{
			label: "Force Auxillary",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 0, 0.8)',
			backgroundColor: 'rgba(0, 0, 0, 0.8)',
			data: fax
		},
		
		{
			label: "Dreadnought",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(230, 25, 75, 0.8)',
			backgroundColor: 'rgba(230, 25, 75, 0.8)',
			data: dread
		},
		
		{
			label: "Faction Dreadnought",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(245, 130, 48, 0.8)',
			backgroundColor: 'rgba(245, 130, 48, 0.8)',
			data: faction_dread
		},

		{
			label: "Monitor",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(123, 65, 23, 0.8)',
			backgroundColor: 'rgba(123, 65, 23, 0.8)',
			data: monitor
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
				position: 'right',
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
							if(parseInt(value) >= 1000){
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
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
							} else {
								return value;
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
	as.hull_stacked.initChart();
});