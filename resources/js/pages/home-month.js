document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('expensesCategoryFilter')
    const tbody  = document.getElementById('expensesBody')

    if (select && tbody) {
        select.addEventListener('change', () => {
            const cat = select.value
            tbody.querySelectorAll('tr').forEach(tr => {
                tr.style.display = (!cat || tr.dataset.category === cat) ? '' : 'none'
            })
        })
    }

    document.querySelectorAll('[data-chart]').forEach(function (el) {
        const data  = JSON.parse(el.dataset.chart);
        const total = data.series.reduce((a, b) => a + b, 0);

        new ApexCharts(el, {
            chart: { type: 'bar', height: 260, toolbar: { show: false } },
            series: [{ name: 'Importe', data: data.series }],
            xaxis: {
                categories: data.labels,
                labels: { style: { fontSize: '11px' }, trim: true, maxHeight: 60 },
            },
            yaxis: {
                labels: {
                    formatter: val => val.toLocaleString('es-ES', { minimumFractionDigits: 0 }) + ' €',
                    style: { fontSize: '10px' },
                },
            },
            plotOptions: {
                bar: { borderRadius: 4, horizontal: false, columnWidth: '55%' },
            },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: val => val.toLocaleString('es-ES', { minimumFractionDigits: 2 }) + ' €' },
            },
            legend: { show: false },
            colors: data.colors,
        }).render();
    });
});
