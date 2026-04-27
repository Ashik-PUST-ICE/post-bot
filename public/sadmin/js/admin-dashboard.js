(function ($) {
    ("use strict");

    //chart start
    $(document).ready(function () {
        commonAjax('GET', $('#user-overview-chart-data-route').val(), userOverviewChartDataRes, userOverviewChartDataRes);
    });

    function userOverviewChartDataRes(response) {
        if (response.status == true) {
            var monthList = [];
            var dataList = [];
            $.each(response.data, function (index, item) {
                monthList.push(index);
                dataList.push(item);
            });

            //client overview
            const ctx = document.getElementById("myChart");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: monthList,
                    datasets: [
                        {
                            backgroundColor: "#7A5AF820",
                            borderRadius: 50,
                            hoverBackgroundColor: "#7a5af8",
                            data: dataList,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: 0,
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            enabled: true,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            stacked: true,
                            grid: {
                                display: true,
                                color: "#F2F2F290",
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        }
    }

})(jQuery);
