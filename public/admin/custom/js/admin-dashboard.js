(function ($) {
    "use strict";
    // all department
    var allDepartmentOptionsArea = {
        chart: {
            height: 200,
            type: "area",
            toolbar: {
                show: false,
            },
        },
        stroke: {
            width: 2.5,
            curve: "smooth",
        },
        tooltip: {
            enabled: true,
        },
        colors: [],
        dataLabels: {
            enabled: false,
        },
        series: [{
            name: "Employees",
            data: allDepartmentEmployeeCountArray,
        },],
        fill: {
            type: "gradient",
            gradient: {
                gradientToColors: ["#4C40F7"],
                shadeIntensity: 1,
                type: "vertical",
                opacityFrom: 1,
                opacityTo: 0.5,
                stops: [0, 100],
            },
        },
        xaxis: {
            categories: allDepartmentNameArray,
            tickPlacement: "on",
            min: undefined,
            max: undefined,
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                style: {
                    cssClass: "revenueOverviewChart-xaxis-label",
                },
            },
        },
        yaxis: {
            tickAmount: 5,
            decimalsInFloat: 1,
            min: allDepartmentEmployeeCountMin,
            max: allDepartmentEmployeeCountMax,
            labels: {
                style: {
                    cssClass: "revenueOverviewChart-yaxis-label",
                },
            },
        },
    };

    var allDepartmentChartSelector = document.querySelector('#allDepartmentChart');
    if (allDepartmentChartSelector) {
        var allDepartmentChartArea = new ApexCharts(allDepartmentChartSelector,
            allDepartmentOptionsArea);
        allDepartmentChartArea.render();
    }

    // all kpi session
    var allKpiSessionOptionsDonut = {
        series: allKpiSessionStatusArray,
        labels: ["Pending", "Ongoing", "Completed"],
        chart: {
            width: 300,
            type: "donut",
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
        },
        colors: ["#FFD700", "#008000", "#0000FF"],
        legend: {
            position: "bottom",
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,
                },
            },
        },],
    };
    var allKpiSessionChartArea = new ApexCharts(document.querySelector("#kpiSessionSummaryChart"), allKpiSessionOptionsDonut);
    allKpiSessionChartArea.render();

    // all kpi session last year
    var kpiSessionSummaryLastYearOption = {
        chart: {
            type: "radialBar",
            width: 300,
            height: 300,
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
            offsetX: 0,
            offsetY: 0,
        },
        colors: ["#FFD700", "#008000", "#0000FF"],
        plotOptions: {
            radialBar: {
                inverseOrder: false,
                hollow: {
                    margin: 0,
                    size: "48%",
                    background: "transparent",
                },
                track: {
                    background: "#F2F6F7",
                    margin: 4,
                },
                dataLabels: {
                    name: {
                        fontSize: "16px",
                        fontWeight: 400,
                        color: "#596680",
                    },
                    value: {
                        offsetY: 5,
                        fontSize: "20px",
                        fontWeight: 700,
                        lineheight: "22px",
                        color: "#4C40F7",
                    },
                    total: {
                        show: true,
                        label: "Status",
                        fontSize: "16px",
                        fontWeight: 400,
                        color: "#596680",
                        formatter: function (w) {
                            // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                            return 100;
                        },
                    },
                },
            },
        },
        series: allKpiSessionStatusLastYearArray,
        labels: ["Pending", "Ongoing", "Completed"],
        stroke: {
            lineCap: "round",
        },
        legend: {
            formatter: function (val, opts) {
                return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
            },
            show: true,
            position: "bottom",
            horizontalAlign: "left",
            fontSize: "14px",
            fontWeight: 400,
            inverseOrder: false,
            width: undefined,
            height: undefined,
            tooltipHoverFormatter: undefined,
            customLegendItems: [],
            offsetX: 0,
            offsetY: 0,
            markers: {
                width: 12,
                height: 12,
                radius: 50,
                offsetX: 0,
                offsetY: 0,
            },
            itemMargin: {
                horizontal: 30,
                vertical: 5,
            },
            onItemClick: {
                toggleDataSeries: false,
            },
            onItemHover: {
                highlightDataSeries: true,
            },
        },
    };
    var kpiSessionSummaryLastYearArea = new ApexCharts(document.querySelector("#kpiSessionSummaryLastYearChart"), kpiSessionSummaryLastYearOption);
    kpiSessionSummaryLastYearArea.render();

    // all kpi session type
    var allKpiSessionTypeChartOption = {
        series: allSessionTypeSessionArray,
        chart: {
            width: 300,
            type: "pie",
            redrawOnWindowResize: false,
            redrawOnParentResize: false,
        },
        colors: sessionTypeColors,
        labels: allSessionTypeNameArray,
        legend: {
            position: "bottom",
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,
                },
            },
        },],
    };
    var allKpiSessionTypeChartArea = new ApexCharts(document.querySelector("#kpiSessionTypeChart"), allKpiSessionTypeChartOption);
    allKpiSessionTypeChartArea.render();
})(jQuery);
