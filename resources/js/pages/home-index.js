document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('annualChart');
    if (!el) return;

    const labels   = JSON.parse(el.dataset.labels);
    const income   = JSON.parse(el.dataset.income);
    const expenses = JSON.parse(el.dataset.expenses);
    const savings  = JSON.parse(el.dataset.savings);

    new ApexCharts(el, {
        chart: {
            type: 'line',
            height: 300,
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        series: [
            { name: 'Ingreso',  data: income },
            { name: 'Gasto',    data: expenses },
            { name: 'Ahorro acumulado', data: savings },
        ],
        xaxis: {
            categories: labels,
            labels: { style: { fontSize: '11px' } },
        },
        yaxis: {
            labels: {
                formatter: val => val !== null ? val.toLocaleString('es-ES', { minimumFractionDigits: 0 }) + '€' : '',
                style: { fontSize: '11px' },
            },
        },
        colors: ['#198754', '#dc3545', '#6ea8fe'],
        stroke: { curve: 'smooth', width: 2 },
        markers: { size: 4 },
        legend: { position: 'top', fontSize: '12px' },
        tooltip: {
            y: { formatter: val => val !== null ? val.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €' : '—' },
        },
        grid: { borderColor: '#f1f1f1' },
    }).render();
});
