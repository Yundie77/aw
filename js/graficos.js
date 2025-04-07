document.addEventListener("DOMContentLoaded", function() {
    // grafico 1: grafico de linea
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Gastos (€)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' €';
                        }
                    }
                }
            }
        }
    });
    
    // grafico 2: grafico comparativo (barras horizontales)
    const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'bar',
        data: comparisonChartData,
        options: {
            indexAxis: 'y',  // barras horizontales
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Importe (€)'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.x + ' €';
                        }
                    }
                }
            }
        }
    });
    
    // grafico 3: grafico de dispersion
    const scatterCtx = document.getElementById('scatterChart').getContext('2d');
    const scatterChart = new Chart(scatterCtx, {
        type: 'scatter',
        data: scatterChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ingresos (€)'
                    },
                    beginAtZero: true
                },
                y: {
                    title: {
                        display: true,
                        text: 'Gastos (€)'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const data = context.dataset.data[context.dataIndex];
                            return data.label + ' - Ingresos: ' + data.x + '€, Gastos: ' + data.y + '€';
                        }
                    }
                }
            }
        }
    });
    
    // añadir linea diagonal para equilibrio ingresos/gastos
    const scatterBackgroundPlugin = {
        id: 'scatterBackgroundPlugin',
        beforeDraw: (chart) => {
            const ctx = chart.ctx;
            const xAxis = chart.scales.x;
            const yAxis = chart.scales.y;
            
            // region de riesgo (gastos > ingresos)
            ctx.save();
            ctx.fillStyle = 'rgba(255, 0, 0, 0.1)';
            ctx.beginPath();
            ctx.moveTo(xAxis.left, yAxis.bottom);
            ctx.lineTo(xAxis.right, yAxis.bottom);
            ctx.lineTo(xAxis.right, yAxis.top);
            ctx.lineTo(xAxis.left, yAxis.bottom);
            ctx.fill();
            ctx.restore();
            
            // linea de equilibrio
            ctx.save();
            ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
            ctx.setLineDash([5, 5]);
            ctx.beginPath();
            ctx.moveTo(xAxis.left, yAxis.bottom);
            ctx.lineTo(xAxis.right, yAxis.top);
            ctx.stroke();
            ctx.restore();
        }
    };
    
    // registrar plugin para grafico de dispersion
    scatterChart.options.plugins.background = {};
    Chart.register(scatterBackgroundPlugin);
    
    // grafico 4: grafico de barras apiladas
    const stackedCtx = document.getElementById('stackedChart').getContext('2d');
    new Chart(stackedCtx, {
        type: 'bar',
        data: stackedChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Importe (€)'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' €';
                        }
                    }
                }
            }
        }
    });
});