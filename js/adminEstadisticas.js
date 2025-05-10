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

  // Gráfico de barras horizontales (usuarios por estado)
  const labelsEstados = barDataEstados.map(d => d.estado);
  const valoresEstados = barDataEstados.map(d => d.total);

  new Chart(document.getElementById('barChartEstados').getContext('2d'), {
    type: 'bar',
    data: {
      labels: labelsEstados,
      datasets: [{
        label: 'Usuarios',
        data: valoresEstados,
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

  // Gráfico de donut (usuarios por rol)
  const labelsRoles = datosUsuariosRol.labels;
  const valoresRoles = datosUsuariosRol.datos;

  new Chart(document.getElementById('usuariosRolChart').getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: labelsRoles,
      datasets: [{
        label: 'Usuarios por rol',
        data: valoresRoles,
        backgroundColor: [
          'rgba(52, 152, 219, 0.7)',
          'rgba(155, 89, 182, 0.7)',
          'rgba(26, 188, 156, 0.7)',
          'rgba(230, 126, 34, 0.7)',
          'rgba(231, 76, 60, 0.7)' 
        ],
        borderColor: [
          'rgba(52, 152, 219, 1)',
          'rgba(155, 89, 182, 1)',
          'rgba(26, 188, 156, 1)',
          'rgba(230, 126, 34, 1)',
          'rgba(231, 76, 60, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        }
      }
    }
  });
});
