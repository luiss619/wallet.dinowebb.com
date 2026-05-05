document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-chart]').forEach(function (el) {
        const data  = JSON.parse(el.dataset.chart);
        const total = data.series.reduce((a, b) => a + b, 0);

        new ApexCharts(el, {
            chart: { type: 'donut', height: 260, toolbar: { show: false } },
            series: data.series,
            labels: data.labels,
            legend: {
                position: 'bottom',
                fontSize: '11px',
                formatter: (label, opts) =>
                    label + ' (' + ((opts.w.globals.series[opts.seriesIndex] / total) * 100).toFixed(1) + '%)',
            },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: val => val.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €' },
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: () => total.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €',
                            },
                        },
                    },
                },
            },
            colors: data.colors,
        }).render();
    });
});
