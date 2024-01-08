<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
  $(document).ready(function () {
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($result->chart_labels) !!},
        datasets: [{
          backgroundColor: 'rgb(39, 158, 255)',
          borderColor: 'rgb(39, 158, 255)',
          label: 'Graph',
          data: {!! json_encode($result->chart_data) !!},
          fill: false,
          tension: 0
        }]
      },
      plugins: [ChartDataLabels],
      options: {
        responsive: true,
        aspectRatio: 8 / 2,
        layout: {
          padding: {
            right: 40,
            bottom: 16,
          }
        },
        plugins: {
          legend: {
            display: false
          },
          datalabels: {
            backgroundColor: function (context) {
              return context.dataset.backgroundColor;
            },
            borderRadius: 4,
            color: 'white',
            font: {
              weight: 'bold'
            },
            formatter: Math.round,
            padding: 3
          },
        },
        scales: {
          x: {
            beginAtZero: true,
            grid: {
              display: false,
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              display: false,
            },
          }
        },
      }
    });
  });
</script>
