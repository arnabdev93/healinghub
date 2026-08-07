$(function() {
    "use strict";




    var options = {
        series: [{
            name: 'Consultation',
            data: [45, 52, 38, 24, 56, 45, 47]
        }, {
            name: 'Patients',
            data: [35, 41, 62, 42, 51, 32, 35]
        }],
        chart: {
            height: 308,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        colors: ['#1dbfc1', '#209dff'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: ['3', '3'],
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            },
        },
        legend: {
            show: true,
            position: 'top',
        },
        xaxis: {
            type: 'category',
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"]
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#apexcharts-area"), options);
    chart.render();



    // $('#listTableDataTableId').DataTable({
    //     'paging': true,
    //     'lengthChange': false,
    //     'searching': false,
    //     'ordering': true,
    //     'info': true,
    //     'autoWidth': false
    // });

    'use strict';

    var options = {
        series: [44, 55, 41, 17, 15],
        chart: {
            type: 'donut',
        },
        colors: ['#3246D3', '#00D0FF', '#ee3158', '#ffa800', '#1dbfc1'],
        legend: {
            position: 'bottom'
        },

        plotOptions: {
            pie: {
                donut: {
                    size: '45%',
                }
            }
        },
        labels: ["Operation", "Theraphy", "Mediation", "Colestrol", "Heart Beat"],
        responsive: [{
            breakpoint: 1600,
            options: {
                chart: {
                    width: 330,
                }
            }
        }, {
            breakpoint: 500,
            options: {
                chart: {
                    width: 280,
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chart123"), options);
    chart.render();



    var options = {
        series: [{
            name: 'series1',
            data: [100, 80, 110, 80, 110]
        }],
        chart: {
            height: 60,
            type: 'area',
            toolbar: {
                show: false,
            },
            offsetY: 0,
        },
        colors: ["#000000", ],
        fill: {
            colors: ["#ffffff", ],
            type: "gradient",
            gradient: {
                shade: "light",
                type: "vertical",
                shadeIntensity: 0.4,
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.1,
                stops: [0, 100],
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [0],
            curve: 'smooth'
        },
        grid: {
            show: false,
            padding: {
                left: -10,
                top: -25,
                right: -0,
                bottom: -15,
            },
        },
        markers: {
            size: 0,
        },
        xaxis: {
            type: 'datetime',
            categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        legend: {
            show: false,
        },
        tooltip: {
            enabled: false,
            marker: {
                show: false,
            },
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function(val) {
                    return val + "%";
                }
            },
        },
        xaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart-widget1"), options);
    chart.render();


    var options = {
        series: [{
            name: 'Online Sales',
            data: [44, 55, 57, 56, 61, 58, 63]
        }, {
            name: 'Offline Sales',
            data: [76, 85, 101, 98, 87, 105, 91]
        }],
        chart: {
            type: 'bar',
            height: 275,
            offsetX: -5,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        legend: {
            show: true,
            position: 'bottom',
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 3,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        },
        yaxis: {
            title: {
                text: ''
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return "" + val + "k"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#statistics-chart"), options);
    chart.render();




    var options = {
        series: [{
            name: 'Section 001',
            data: [44, 55, 41, 67, 22, 43]
        }, {
            name: 'Section 002',
            data: [13, 23, 20, 8, 13, 27]
        }, {
            name: 'Section 003',
            data: [50, 24, 30, 10, 16, 14]
        }, {
            name: 'Section 004',
            data: [30, 15, 35, 41, 20, 35]
        }, {
            name: 'Section 005',
            data: [11, 17, 15, 15, 21, 14]
        }, {
            name: 'Section 006',
            data: [21, 7, 25, 13, 22, 8]
        }],
        chart: {
            type: 'bar',
            height: 320,
            stacked: true,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'top',
                    offsetX: 0,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 0,
                columnWidth: '20%',
                dataLabels: {
                    total: {
                        enabled: false,
                        style: {
                            fontSize: '0px',
                            fontWeight: 900
                        }
                    }
                }
            },
        },
        grid: {
            show: false,
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            type: 'category',
            categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", ],
        },
        yaxis: {
            labels: {
                show: false,
            },
        },
        legend: {
            show: false,
            position: 'right',
            offsetY: 40
        },
        colors: [
            "#0d6efd",
            "#16C7F9",
            "#FFAA05",
            "#FC4438",
            "#54BA4A",
            "#6c757d",
        ],
        fill: {
            opacity: 1
        }
    };

    var chart = new ApexCharts(document.querySelector("#barchart"), options);
    chart.render();


    // ------------------------------
    // nightingale chart
    // ------------------------------
    // based on prepared DOM, initialize echarts instance
    var nightingaleChart = echarts.init(document.getElementById('nightingale-chart'));
    var option = {
        //  title: {
        //     text: 'Ningdinger Rose Map',
        //     subtext: 'Purely fictitious',
        //     x: 'center'
        // },

        // Add tooltip
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: +{c}$ ({d}%)"
        },

        // Add legend
        legend: {
            show: false,
            x: 'left',
            y: 'top',
            orient: 'vertical',
            data: ['Miami', 'New York', 'California', 'Chicago', ]
        },


        color: ["#0d6efd", "#16C7F9", "#FFAA05", "#FC4438", ],

        // Display toolbox
        toolbox: {
            show: false,
            orient: 'vertical',
            feature: {
                mark: {
                    show: false,
                    title: {
                        mark: 'Markline switch',
                        markUndo: 'Undo markline',
                        markClear: 'Clear markline'
                    }
                },
                dataView: {
                    show: false,
                    readOnly: false,
                    title: 'View data',
                    lang: ['View chart data', 'Close', 'Update']
                },
                magicType: {
                    show: false,
                    title: {
                        pie: 'Switch to pies',
                        funnel: 'Switch to funnel',
                    },
                    type: ['pie', 'funnel']
                },
                restore: {
                    show: false,
                    title: 'Restore'
                },
                saveAsImage: {
                    show: false,
                    title: 'Same as image',
                    lang: ['Save']
                }
            }
        },

        // Enable drag recalculate
        calculable: true,

        // Add series
        series: [{
            name: 'Area Mode',
            type: 'pie',
            radius: ['15%', '85%'],
            center: ['50%', '50%'],
            roseType: 'area',

            // Funnel
            width: '40%',
            height: '50%',
            x: '0%',
            y: '0%',
            max: 250,
            sort: 'ascending',

            data: [{
                    value: 440,
                    name: 'Miami'
                },
                {
                    value: 260,
                    name: 'New York'
                },
                {
                    value: 350,
                    name: 'California'
                },
                {
                    value: 250,
                    name: 'Chicago'
                },
            ],
            labelLine: {
                lineStyle: {
                    color: 'rgba(181, 181, 195, 0.8)'
                },
                smooth: 0.2,
                length: 5,
                length2: 5
            },
        }]
    };
    nightingaleChart.setOption(option);








    // bar chart
    $(".bar").peity("bar");



    // ------------------------------
    // pole chart
    // ------------------------------
    // based on prepared DOM, initialize echarts instance
    var poleChart = echarts.init(document.getElementById('pole-chart'));
    // Data style
    var dataStyle = {
        normal: {
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        }
    };

    // Placeholder style
    var placeHolderStyle = {
        normal: {
            color: 'rgba(0,0,0,0)',
            label: {
                show: false
            },
            labelLine: {
                show: false
            }
        },
        emphasis: {
            color: 'rgba(0,0,0,0)'
        }
    };
    var option = {
        // title: {
        //     text: 'Stacked histogram',
        //     subtext: 'Weekly Data',
        //     x: 'center',
        //     y: 'center',
        //     itemGap: 10,
        //     textStyle: {
        //         color: 'rgba(30,144,255,0.8)',
        //         fontSize: 19,
        //         fontWeight: '500'
        //     }
        // },

        // Add tooltip
        tooltip: {
            show: true,
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },

        // Add legend
        legend: {
            orient: 'vertical',
            x: document.getElementById('pole-chart').offsetWidth / 2,
            y: 30,
            x: '55%',
            itemGap: 15,
            data: ['Google', 'Personal', 'Outher']
        },

        // Add custom colors
        color: ['#F4972D', '#0052cc', '#898989'],

        // Add series
        series: [{
                name: '1',
                type: 'pie',
                clockWise: false,
                radius: ['75%', '100%'],
                itemStyle: dataStyle,
                data: [{
                        value: 75,
                        name: 'Google'
                    },
                    {
                        value: 25,
                        name: 'invisible',
                        itemStyle: placeHolderStyle
                    }
                ]
            },

            {
                name: '2',
                type: 'pie',
                clockWise: false,
                radius: ['60%', '85%'],
                itemStyle: dataStyle,
                data: [{
                        value: 50,
                        name: 'Personal'
                    },
                    {
                        value: 50,
                        name: 'invisible',
                        itemStyle: placeHolderStyle
                    }
                ]
            },

            {
                name: '3',
                type: 'pie',
                clockWise: false,
                radius: ['45%', '70%'],
                itemStyle: dataStyle,
                data: [{
                        value: 25,
                        name: 'Outher'
                    },
                    {
                        value: 75,
                        name: 'invisible',
                        itemStyle: placeHolderStyle
                    }
                ]
            }
        ]
    };
    poleChart.setOption(option);



    var options = {
        series: [{
            name: 'PRODUCT A',
            data: [24, 65, 31, 37, 39, 62]
        }, {
            name: 'PRODUCT B',
            data: [-24, -65, -31, -37, -39, -62]
        }],
        chart: {
            foreColor: "#bac0c7",
            type: 'bar',
            height: 265,
            stacked: true,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: true
            }
        },
        grid: {
            show: true,
            borderColor: '#f7f7f7',
        },
        colors: ['#f2426d', '#4d7cff'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '15%',
                borderRadius: 3
            },
        },
        dataLabels: {
            enabled: false
        },

        xaxis: {
            type: 'datetime',
            categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                '01/05/2011 GMT', '01/06/2011 GMT'
            ],
        },
        yaxis: {
            axisBorder: {
                show: false
            },
        },
        legend: {
            show: false,
        },
        fill: {
            opacity: 1
        }
    };

    var chart = new ApexCharts(document.querySelector("#charts_widget_1_chart"), options);
    chart.render();










    // -----------------
    // - SPARKLINE BAR -
    // -----------------
    $('.sparkbar').each(function() {
        var $this = $(this);
        $this.sparkline('html', {
            type: 'bar',
            height: $this.data('height') ? $this.data('height') : '30',
            barColor: $this.data('color')
        });
    });

    $("#discretechart").sparkline([1, 4, 3, 7, 6, 4, 8, 9, 6, 8, 12], {
        type: 'discrete',
        width: '80',
        height: '70',
        lineColor: '#745af2',
    });

    $("#baralc").sparkline([32, 24, 26, 24, 32, 26, 40, 34, 22, 24, 22, 24, 34, 32, 38, 28, 36, 36, 40, 38, 30, 34, 38], {
        type: 'bar',
        height: '80',
        barWidth: 6,
        barSpacing: 4,
        barColor: '#faa700',
    });

    $("#lineIncrease").sparkline([1, 8, 6, 5, 6, 8, 7, 9, 7, 8, 10, 16, 14, 10], {
        type: 'line',
        width: '98%',
        height: '92',
        lineWidth: 2,
        lineColor: '#ffffff',
        fillColor: "rgba(255, 255, 255, 0)",
        spotColor: '#ffffff',
        minSpotColor: '#ffffff',
        maxSpotColor: '#ffffff',
        spotRadius: 1,
    });



});


// slimScroll-------------------------------------------------
window.onload = function() {
    // Cache DOM Element
    var scrollable = $('.scrollable');

    // Keeping the Scrollable state separate
    var state = {
        pos: {
            lowest: 0,
            current: 0
        },
        offset: {
            top: [0, 0], //Old Offset, New Offset
        }
    }
    //
    scrollable.slimScroll({
        height: '284px',
        width: '',
        start: 'top'
    });
    //
    scrollable.slimScroll().bind('slimscrolling', function(e, pos) {
        // Update the Scroll Position and Offset

        // Highest Position
        state.pos.highest = pos !== state.pos.highest ?
            pos > state.pos.highest ? pos : state.pos.highest :
            state.pos.highest;

        // Update Offset State
        state.offset.top.push(pos - state.pos.lowest);
        state.offset.top.shift();

        if (state.offset.top[0] < state.offset.top[1]) {
            console.log('...Scrolling Down')
            // ... YOUR CODE ...
        } else if (state.offset.top[1] < state.offset.top[0]) {
            console.log('Scrolling Up...')
            // ... YOUR CODE ...
        } else {
            console.log('Not Scrolling')
            // ... YOUR CODE ...
        }
    });
};


// slimScroll------------------------------------------------- End