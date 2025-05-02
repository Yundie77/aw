document.addEventListener("DOMContentLoaded", function () {
  // Gráfico de barras (usuarios por mes)
  const labelsUsuarios = datosUsuariosMes.map(d => d.mes);
  const valoresUsuarios = datosUsuariosMes.map(d => parseInt(d.total));

  new Chart(document.getElementById('usuariosChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labelsUsuarios,
      datasets: [{
        label: 'Nuevos usuarios',
        data: valoresUsuarios,
        backgroundColor: 'rgba(54, 162, 235, 0.6)'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Gráfico de donut (categorías)
  const labelsCategorias = datosGastoCategorias.map(d => d.categoria);
  const valoresCategorias = datosGastoCategorias.map(d => parseFloat(d.total_categoria));

  new Chart(document.getElementById('donutChart').getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: labelsCategorias,
      datasets: [{
        data: valoresCategorias,
        backgroundColor: [
          '#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0',
          '#9966ff', '#ff9f40', '#c9cbcf', '#007bff'
        ]
      }]
    },
    options: {
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              const value = context.parsed;
              const percent = ((value / totalGlobal) * 100).toFixed(2);
              return `${context.label}: ${value.toFixed(2)} € (${percent}%)`;
            }
          }
        }
      }
    }
  });
});
