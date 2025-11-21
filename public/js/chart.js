// file public/js/chart.js

document.addEventListener('DOMContentLoaded', function() {
    const chartElement = document.querySelector('.task-chart');

    if (chartElement) {
        const totalTasks = parseInt(chartElement.getAttribute('data-total-tasks'));
        const completedTasks = parseInt(chartElement.getAttribute('data-completed-tasks'));
        const todoTasks = parseInt(chartElement.getAttribute('data-todo-tasks'));

        const ctx = chartElement.getContext('2d');

new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'To do'],
                datasets: [{
                    data: [completedTasks, todoTasks],
                    backgroundColor: [
                        getCSSVariableValue('--tg-primary'), 
                        getCSSVariableValue('--tg-secondary')
                    ],
                    hoverBackgroundColor: [
                        getCSSVariableValue('--tg-primary-light'), 
                        getCSSVariableValue('--tg-secondary-light')
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                cutout: '50%', // Esto crea el hueco del donut
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: getCSSVariableValue('--tg-white', '#ffffff'),
                            font: {
                                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                                size: 14,
                                weight: '500'
                            },
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'Task Completion Status',
                        color: getCSSVariableValue('--tg-light', '#ffffff'),
                        font: {
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    // Plugin personalizado para el texto en el centro
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                afterDraw: function(chart) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;
                    
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = 'bold 24px "Segoe UI", sans-serif';
                    ctx.fillStyle = getCSSVariableValue('--tg-light', '#ffffff');
                    ctx.fillText(totalTasks.toString(), width / 2, height / 2 - 10);
                    
                    ctx.font = '14px "Segoe UI", sans-serif';
                    ctx.fillStyle = getCSSVariableValue('--tg-white', '#ffffff');
                    ctx.fillText('Total Tasks', width / 2, height / 2 + 15);
                    ctx.restore();
                }
            }]
        });
    }
});