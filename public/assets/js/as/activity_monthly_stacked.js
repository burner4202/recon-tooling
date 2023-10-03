as.packages = {};

as.packages.initChart = function () {
    var data = {
        labels: labels,
        datasets: [
        {
            label: "Structure Meta Data",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(153, 51, 255, 0.6)',
            backgroundColor: 'rgba(153, 51, 255, 0.6)',
            data: structure_meta_data_added
        },
        {
            label: "Structure Destroyed",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(139,0,0, 0.6)',
            backgroundColor: 'rgba(139,0,0, 0.6)',
            data: structure_destroyed
        },
        {
            label: "Structure has Fit",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(50,205,50, 0.6)',
            backgroundColor: 'rgba(50,205,50, 0.6)',
            data: structure_has_fit
        },
        {
            label: "Structure Fitting Stored",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(255,165,0, 0.6)',
            backgroundColor: 'rgba(255,165,0, 0.6)',
            data: structure_fitting_stored
        },
        {
            label: "Structure Has No Fitting",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(205,92,92, 0.6)',
            backgroundColor: 'rgba(205,92,92, 0.6)',
            data: structure_has_no_ftting
        },
        {
            label: "Structure Reinforced Armor",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(85,107,47, 0.6)',
            backgroundColor: 'rgba(85,107,47, 0.6)',
            data: structure_reinforced_armor
        },
        {
            label: "Structure Reinforced Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(240,128,128, 0.6)',
            backgroundColor: 'rgba(240,128,128, 0.6)',
            data: structure_reinforced_hull
        },
        {
            label: "Structure Anchoring",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(0,139,139, 0.6)',
            backgroundColor: 'rgba(0,139,139, 0.6)',
            data: structure_anchoring
        },
        {
            label: "Structure High Power",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(25,25,112, 0.6)',
            backgroundColor: 'rgba(25,25,112, 0.6)',
            data: structure_high_power
        },

        {
            label: "Structure Low Power",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(0,100,0, 0.6)',
            backgroundColor: 'rgba(0,100,0, 0.6)',
            data: structure_low_power
        },

        {
            label: "Structure Reinforced",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(240,128,128, 0.6)',
            backgroundColor: 'rgba(240,128,128, 0.6)',
            data: structure_reinforced
        },

        {
            label: "Structure Unanchoring",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(128,0,128, 0.6)',
            backgroundColor: 'rgba(128,0,128, 0.6)',
            data: structure_unanchoring
        },

        {
            label: "Structure Status Cleared",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(176,224,230, 0.6)',
            backgroundColor: 'rgba(176,224,230, 0.6)',
            data: structure_status_clear
        },

        {
            label: "Packages Delivered",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(51, 153, 255, 0.6)',
            backgroundColor: 'rgba(51, 153, 255, 0.6)',
            data: packages_delivered
        },
        {
            label: "Packages Removed",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(255, 51, 51, 0.6)',
            backgroundColor: 'rgba(255, 51, 51, 0.6)',
            data: packages_removed
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
                            drawOnChartArea: true, // only want the grid lines for one axis to show up
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