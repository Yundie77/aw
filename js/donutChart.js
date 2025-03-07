document.addEventListener("DOMContentLoaded", function () {
    var donutData = JSON.parse(document.getElementById('donutData').textContent);
    var totalExpenses = parseFloat(document.getElementById('totalExpenses').textContent);
    var labels = donutData.map(item => item.categoria);
    var dataValues = donutData.map(item => parseFloat(item.total_categoria));

    var colors = [
        "rgba(255, 99, 132, 0.6)",
        "rgba(54, 162, 235, 0.6)",
        "rgba(255, 206, 86, 0.6)",
        "rgba(75, 192, 192, 0.6)",
        "rgba(153, 102, 255, 0.6)",
        "rgba(255, 159, 64, 0.6)",
        "rgba(199, 199, 199, 0.6)",
        "rgba(83, 102, 255, 0.6)"
    ];

    var ctx = document.getElementById('donutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors.slice(0, dataValues.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' €';
                            if (totalExpenses > 0) {
                                var percentage = ((context.parsed / totalExpenses) * 100).toFixed(2);
                                label += ' (' + percentage + '%)';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
