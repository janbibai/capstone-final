import './bootstrap';
import Chart from 'chart.js/auto';
window.Chart = Chart;
window.Echo.channel('queue-channel')
    .listen('.queue.updated', (e) => {
        let number = String(e.queueNumber).padStart(3, '0');
        document.getElementById('queueNumber').innerText = "Q-" + number;
    });