document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("graficoParticipantes");

  if (!canvas || typeof datosParticipantes === 'undefined') return;

  const labels = datosParticipantes.labels;
  const valores = datosParticipantes.datos;
  const hayDatos = labels.length > 0 && valores.length > 0;

  if (!hayDatos) {
    const contenedor = canvas.parentElement;
    canvas.style.display = "none";
    const mensaje = document.createElement("p");
    mensaje.textContent = "No hay datos de gastos por participante.";
    mensaje.className = "sin-datos-mensaje";
    contenedor.appendChild(mensaje);
    return;
  }

  new Chart(canvas.getContext("2d"), {
    type: "bar",
    data: {
      labels: labels,
      datasets: [{
        label: "Gasto (€)",
        data: valores,
        backgroundColor: "rgba(75, 192, 192, 0.7)"
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Importe (€)"
          }
        },
        x: {
          title: {
            display: true
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} €`
          }
        }
      }
    }
  });
});
