<!DOCTYPE html>
<html>
<head>
    <title>Queue Board</title>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-black text-white flex items-center justify-center h-screen">

    <div class="text-center">
        <h1 class="text-5xl font-bold mb-6">
            NOW SERVING
        </h1>

        <div id="queueNumber" class="text-9xl font-extrabold text-green-400">
            Q-000
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const queueNumberElement = document.getElementById('queueNumber');

            if (typeof(EventSource) !== "undefined") {
                const source = new EventSource('/queue-stream');

                source.onmessage = function(event) {
                    const data = JSON.parse(event.data);
                    const qNumber = data.queue_number;
                    
                    if (qNumber === 0) {
                        queueNumberElement.innerText = "WAITING...";
                        queueNumberElement.classList.replace('text-green-400', 'text-gray-500');
                        queueNumberElement.classList.replace('text-9xl', 'text-7xl');
                    } else {
                        const formattedNumber = String(qNumber).padStart(3, '0');
                        queueNumberElement.innerText = `Q-${formattedNumber}`;
                        queueNumberElement.classList.replace('text-gray-500', 'text-green-400');
                        queueNumberElement.classList.replace('text-7xl', 'text-9xl');
                    }
                };
            } else {
                queueNumberElement.innerText = "No SSE Support";
            }
        });
    </script>
</body>
</html>
