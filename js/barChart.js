(function() {
    // variable es valida
    if (typeof barData === 'undefined') {
      console.error('La variable barData no está definida');
      return;
    }
  
    // arrays
    var labels = [];
    var ingresosData = [];
    var gastosData = [];
  
    // meses
    var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                      "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  
    barData.forEach(function(item) {
      var mes = parseInt(item.mes);
      var anio = item.anio;
      labels.push(monthNames[mes - 1] + " " + anio);
      ingresosData.push(parseFloat(item.total_ingreso));
      gastosData.push(parseFloat(item.total_gasto));
    });
  
    var ctx = document.getElementById('barChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Ingresos',
            data: ingresosData,
            backgroundColor: 'rgba(0, 128, 0, 0.7)', // verde
            borderColor: 'rgba(0, 128, 0, 1)',
            borderWidth: 1
          },
          {
            label: 'Gastos',
            data: gastosData,
            backgroundColor: 'rgba(255, 0, 0, 0.7)', // rojo
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  })();
  