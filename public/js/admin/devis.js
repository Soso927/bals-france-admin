document.addEventListener('DOMContentLoaded', function () {

    const chartDom = document.getElementById('chart-devis');
    if (!chartDom || typeof echarts === 'undefined') return;

    const myChart = echarts.init(chartDom);

    const rawData = window.devisChartData ?? [];

    myChart.setOption({
        title: {
            text: 'Demandes de devis — 7 derniers jours',
            textStyle: { fontSize: 14, fontWeight: 'bold' }
        },
        tooltip: { trigger: 'axis' },
        legend: { bottom: 0 },

        dataset: [
            {
                id: 'source',
                source: [['jour', 'statut', 'total'], ...rawData]
            },
            {
                id: 'ds_nouveau',
                fromDatasetId: 'source',
                transform: { type: 'filter', config: { dimension: 'statut', value: 'nouveau' } }
            },
            {
                id: 'ds_lu',
                fromDatasetId: 'source',
                transform: { type: 'filter', config: { dimension: 'statut', value: 'lu' } }
            },
            {
                id: 'ds_traite',
                fromDatasetId: 'source',
                transform: { type: 'filter', config: { dimension: 'statut', value: 'traité' } }
            }
        ],

        xAxis: { type: 'category' },
        yAxis: { type: 'value', minInterval: 1 },

        series: [
            {
                name: 'Nouveau',
                type: 'bar',
                datasetId: 'ds_nouveau',
                encode: { x: 'jour', y: 'total' },
                color: '#3b82f6'
            },
            {
                name: 'Lu',
                type: 'bar',
                datasetId: 'ds_lu',
                encode: { x: 'jour', y: 'total' },
                color: '#f59e0b'
            },
            {
                name: 'Traité',
                type: 'bar',
                datasetId: 'ds_traite',
                encode: { x: 'jour', y: 'total' },
                color: '#10b981'
            }
        ]
    });

    window.addEventListener('resize', () => myChart.resize());
});
