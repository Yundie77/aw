(function() {
    if (typeof barDataEstados === 'undefined') {
        console.error('La variable barDataEstados no está definida');
        return;
    }

    var labels = [];
    var data = [];

    barDataEstados.forEach(function(item) {
        labels.push(item.estado);
        data.push(item.total);
    });

    var ctx = document.getElementById('barChartEstados');
    if (!ctx) return;
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Usuarios',
                data: data,
                backgroundColor: [
                    'rgba(46, 204, 113, 0.7)',
                    'rgba(241, 196, 15, 0.7)',
                    'rgba(231, 76, 60, 0.7)'
                ],
                borderColor: [
                    'rgba(46, 204, 113, 1)',
                    'rgba(241, 196, 15, 1)',
                    'rgba(231, 76, 60, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
})();
