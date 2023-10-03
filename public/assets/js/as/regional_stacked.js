as.minerals_history = {};

as.minerals_history.initChart = function () {
	var data = {
		labels: labels,
		datasets: [
		/*
		{
			label: "Atmospheric Gases",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(128, 0, 0, 0.8)',
			backgroundColor: 'rgba(128, 0, 0, 0.8)',
			data: atmo_gases
		},
		*/
		{
			label: "Cadmium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(170, 110, 40, 0.8)',
			backgroundColor: 'rgba(170, 110, 40, 0.8)',
			data: cadmium
		},
		{
			label: "Caesium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(128, 128, 0, 0.8)',
			backgroundColor: 'rgba(128, 128, 0, 0.8)',
			data: caesium
		},
		{
			label: "Chromium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 128, 128, 0.8)',
			backgroundColor: 'rgba(0, 128, 128, 0.8)',
			data: chromium
		},
		{
			label: "Cobalt",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 128, 0.8)',
			backgroundColor: 'rgba(0, 0, 128, 0.8)',
			data: cobalt
		},
		{
			label: "Dysprosium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 0, 0, 0.8)',
			backgroundColor: 'rgba(0, 0, 0, 0.8)',
			data: dysprosium
		},
		/*
		{
			label: "Evaporite Deposits",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(230, 25, 75, 0.8)',
			backgroundColor: 'rgba(230, 25, 75, 0.8)',
			data: eva_depo
		},
		*/
		{
			label: "Hafnium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(245, 130, 48, 0.8)',
			backgroundColor: 'rgba(245, 130, 48, 0.8)',
			data: hafnium
		},
		/*
		{
			label: "Hydrocarbons",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 225, 25, 0.8)',
			backgroundColor: 'rgba(255, 225, 25, 0.8)',
			data: hydrocarbons
		},
		*/
		{
			label: "Mercury",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(210, 245, 60, 0.8)',
			backgroundColor: 'rgba(210, 245, 60, 0.8)',
			data: mercury
		},
		{
			label: "Neodymium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(60, 180, 75, 0.8)',
			backgroundColor: 'rgba(60, 180, 75, 0.8)',
			data: neodymium
		},
		{
			label: "Platinum",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(70, 240, 240, 0.8)',
			backgroundColor: 'rgba(70, 240, 240, 0.8)',
			data: platinum
		},
		{
			label: "Promethium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(0, 130, 200, 0.8)',
			backgroundColor: 'rgba(0, 130, 200, 0.8)',
			data: promethium
		},
		{
			label: "Scandium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(145, 30, 180, 0.8)',
			backgroundColor: 'rgba(145, 30, 180, 0.8)',
			data: scandium
		},
		/*{
			label: "Silicates",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(240, 50, 230, 0.8)',
			backgroundColor: 'rgba(240, 50, 230, 0.8)',
			data: silicates
		},
		*/
		{
			label: "Technetium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(128, 128, 128, 0.8)',
			backgroundColor: 'rgba(128, 128, 128, 0.8)',
			data: technetium
		},
		
		{
			label: "Thulium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(250, 190, 190, 0.8)',
			backgroundColor: 'rgba(250, 190, 190, 0.8)',
			data: thulium
		},
		
		{
			label: "Titanium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(170, 255, 195, 0.8)',
			backgroundColor: 'rgba(170, 255, 195, 0.8)',
			data: titanium
		},
		{
			label: "Tungsten",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(230, 190, 255, 0.8)',
			backgroundColor: 'rgba(230, 190, 255, 0.8)',
			data: tungsten
		},
		{
			label: "Vanadium",
			yAxisID: "y-axis-1",
			borderColor: 'rgba(255, 215, 180, 0.8)',
			backgroundColor: 'rgba(255, 215, 180, 0.8)',
			data: vanadium
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
							if(parseInt(value) >= 1000){
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' moons';
							} else {
								return value + ' moons';
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
	as.minerals_history.initChart();
});