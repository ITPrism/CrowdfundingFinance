jQuery(document).ready(function() {

    /* START funds by days bar chart */
    (function() {
        var margin = {top: 20, right: 20, bottom: 80, left: 40},
            width = 1024 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var x = d3.scale.ordinal()
            .rangeRoundBands([0, width], .1);

        var y = d3.scale.linear()
            .range([height, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        var svg = d3.select("#amount-days-lines")
            .append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")")

        d3.json("index.php?option=com_crowdfundingfinance&task=statistics.getProjectTransactions&format=raw&id=" + cfProjectId, function(error, response) {

            var data = (!response.data) ? [] : response.data;

            var max = d3.max(data, function(d) {
                return parseFloat(d.amount);
            });

            x.domain(data.map(function(d) {
                return d.date;
            }));
            y.domain([0, max]);

            svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis)
                .selectAll("text")
                    .style("text-anchor", "end")
                    .attr("dx", "-.8em")
                    .attr("dy", ".15em")
                    .attr("transform", function(d) {
                        return "rotate(-65)"
                    });

            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Amount");

            svg.selectAll(".bar")
                .data(data)
                .enter().append("rect")
                .attr("class", "bar")
                .attr("x", function(d) { return x(d.date); })
                .attr("width", x.rangeBand())
                .attr("y", function(d) { return y(d.amount); })
                .attr("height", function(d) { return height - y(d.amount); });

        });
    })();
    /* END funds by days bar chart */

    /* START Pie Chart */

    (function() {
        var width = 400,
            height = 400,
            radius = Math.min(width, height) / 2;

        var color = d3.scale.ordinal()
            .range(["#98abc5", "#8a89a6", "#7b6888"]);

        var arc = d3.svg.arc()
            .outerRadius(radius - 10)
            .innerRadius(0);

        var pie = d3.layout.pie()
            .sort(null)
            .value(function(d) { return d.amount; });

        var svg = d3.select("#funded-piechart").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        d3.json("index.php?option=com_crowdfundingfinance&task=statistics.getProjectFunds&format=raw&id=" + cfProjectId, function(error, response) {

            var data = [response.data.funded, response.data.remaining];

            var g = svg.selectAll(".arc")
                .data(pie(data))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
                .attr("d", arc)
                .style("fill", function(d) { return color(d.data.amount); });

            g.append("text")
                .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
                .attr("dy", ".35em")
                .style("text-anchor", "middle")
                .text(function(d) {
                    return d.data.label;
                });

        });
    })();

    /* END Pie Chart */
});