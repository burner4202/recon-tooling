as.packages = {};

as.packages.initChart = function () {
    var data = {
        labels: labels,
        datasets: [
        {
            label: "Titan Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(191,0,255,0.85)',
            backgroundColor: 'rgba(191,0,255,0.85)',
            data: is_titan
        },
        {
            label: "Carrier Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(139,0,0,0.85)',
            backgroundColor: 'rgba(139,0,0,0.85)',
            data: is_carrier
        },
        {
            label: "Fax Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(50,205,50,0.85)',
            backgroundColor: 'rgba(50,205,50,0.85)',
            data: is_fax
        },
        {
            label: "Dread Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(255,165,0,0.85)',
            backgroundColor: 'rgba(255,165,0,0.85)',
            data: is_dread
        },
        {
            label: "Super Hull",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(205,92,92,0.85)',
            backgroundColor: 'rgba(205,92,92,0.85)',
            data: is_super
        },
        {
            label: "NPC Delve Contract",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(85,107,47,0.85)',
            backgroundColor: 'rgba(85,107,47,0.85)',
            data: is_npc_delve
        },
        {
            label: "Neutral Contract",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(255,230,230,0.85)',
            backgroundColor: 'rgba(255,230,230,0.85)',
            data: is_neutral_contract
        },
        {
            label: "Friendly Contract",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(0,0,255,0.85)',
            backgroundColor: 'rgba(0,0,255,0.85)',
            data: is_friendly_contract
        },
        {
            label: "Hostile Contract",
            yAxisID: "y-axis-1",
            borderColor: 'rgba(255,0,0,0.85)',
            backgroundColor: 'rgba(255,0,0,0.85)',
            data: is_hostile_contract
        }


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