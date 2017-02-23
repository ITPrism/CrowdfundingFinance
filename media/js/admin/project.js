;jQuery(document).ready(function() {

    var projectCharts = {

        projectId: 0,
        fundedChartElement: {},
        txnChartElement: {},
        fundedChart: {},
        txnChart: {},

        init: function() {
            this.projectId            = crowdfundingPlatform.projectId;
            this.fundedChartElement   = document.getElementById("js-funded-chart");
            this.txnChartElement      = document.getElementById("js-transactions-chart");

            this.buildFundedChart();
            this.buildDailyFundsChart();
        },

        buildFundedChart: function() {

            var $this = this;

            var fields = {
                'id': this.projectId,
                'task': 'statistics.getProjectFunds',
                'format': 'raw'
            };

            jQuery.ajax({
                url: 'index.php?option=com_crowdfundingfinance',
                type: "GET",
                dataType: "text json",
                data: fields
            }).done(function(response) {

                if (response.success) {
                    var data = {
                        'labels': response.data.labels,
                        'datasets': [
                            {
                                data: response.data.datasets.data,
                                hoverBackgroundColor: [
                                    "#36A2EB",
                                    "#24be18",
                                    "#FFCE56"
                                ],
                                backgroundColor: [
                                    "#36A2EB",
                                    "#24be18",
                                    "#FFCE56"
                                ]
                            }
                        ]
                    };

                    $this.fundedChart = new Chart($this.fundedChartElement, {
                        type: 'pie',
                        data: data,
                        options: {
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        return data.labels[tooltipItem.index];
                                    }
                                }
                            }
                        }
                    });

                } else {
                    PrismUIHelper.displayMessageFailure(response.title, response.text);
                }
            });
        },

        buildDailyFundsChart: function() {

            var $this = this;

            var fields = {
                'id': this.projectId,
                'task': 'statistics.getDailyFunds',
                'format': 'raw'
            };

            jQuery.ajax({
                url: 'index.php?option=com_crowdfundingfinance',
                type: "GET",
                dataType: "text json",
                data: fields
            }).done(function(response) {

                if (response.success) {
                    var data = {
                        'labels': response.data.labels,
                        'datasets': [
                            {
                                label: Joomla.JText._('COM_CROWDFUNDINGFINANCE_DAILY_FUNDS'),
                                data: response.data.data,
                                hoverBackgroundColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 1,
                            }
                        ],
                        tooltips: response.data.tooltips,
                    };

                    $this.txnChart = new Chart($this.txnChartElement, {
                        type: 'bar',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        return data.tooltips[tooltipItem.index];
                                    }
                                }
                            }
                        }

                    });

                } else {
                    PrismUIHelper.displayMessageFailure(response.title, response.text);
                }
            });
        }
    };

    projectCharts.init();
});