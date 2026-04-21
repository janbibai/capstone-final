<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Board - Rural Health Unit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Montserrat', 'Outfit', sans-serif;
            overflow: hidden;
            background-color: #0f172a;
            /* Tailwind slate-950 */
        }

        .pulse-glow {
            animation: pulse-glow 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                filter: drop-shadow(0 0 15px rgba(74, 222, 128, 0.4));
            }

            50% {
                filter: drop-shadow(0 0 35px rgba(74, 222, 128, 0.9));
            }
        }

        .number-transition {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Abstract Background Orbs */
        .orb-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            filter: blur(40px);
        }

        .orb-2 {
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            filter: blur(40px);
        }
    </style>
</head>

<body class="text-white min-h-screen flex flex-col relative">

    <!-- Background Elements -->
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div
        class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-30 z-0">
    </div>

    <!-- Header -->
    <header
        class="relative z-10 w-full p-8 flex justify-between items-center border-b border-white/10 bg-black/20 backdrop-blur-md">
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-full bg-gradient-to-tr from-green-400 to-blue-500 flex items-center justify-center shadow-lg shadow-green-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <div>
                <h2
                    class="text-3xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-400">
                    RURAL HEALTH UNIT</h2>
                <p class="text-slate-400 text-sm font-medium tracking-widest uppercase mt-1">Outpatient Department Queue
                </p>
            </div>
        </div>
        <div class="text-right">
            <div id="clockTime" class="text-4xl font-bold tracking-tight text-white mb-1">00:00:00</div>
            <div id="clockDate" class="text-slate-400 text-sm font-bold uppercase tracking-widest">Loading date...</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center p-8">

        <div class="w-full max-w-5xl relative">
            <!-- Glassmorphic Card -->
            <div
                class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent rounded-[3rem] blur-xl opacity-50">
            </div>

            <div
                class="relative bg-slate-900/60 backdrop-blur-2xl border border-white/10 rounded-[3rem] p-20 shadow-[0_0_50px_rgba(0,0,0,0.5)] flex flex-col items-center justify-center overflow-hidden">
                <!-- Subtle grid inside shadow card -->
                <div
                    class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)]">
                </div>

                <div class="relative z-10 text-center flex flex-col items-center w-full">
                    <div
                        class="inline-flex items-center gap-4 px-8 py-3 rounded-full bg-slate-800/80 border border-slate-700/50 mb-12 shadow-inner">
                        <span class="relative flex h-4 w-4">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-4 w-4 bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.8)]"></span>
                        </span>
                        <h1 class="text-2xl font-semibold tracking-[0.25em] text-slate-200">NOW SERVING</h1>
                    </div>

                    <div id="queueContainer"
                        class="number-transition transform scale-100 transition-all duration-500 ease-out flex justify-center w-full min-h-[250px] items-center">
                        <div id="queueNumber"
                            class="text-[12rem] md:text-[15rem] leading-none font-black text-slate-600 drop-shadow-2xl">
                            WAITING...
                        </div>
                    </div>

                    <div class="h-10 mt-8 mb-4">
                        <p id="queueStatus"
                            class="text-3xl font-medium text-slate-400 tracking-wide opacity-0 transition-opacity duration-500">
                            Please proceed to the staff's desk
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full p-6 text-center border-t border-white/5 bg-black/40 backdrop-blur-md">
        <p class="text-slate-400 text-sm flex items-center justify-center gap-2 font-medium tracking-wide">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Please wait for your queue number to be called before approaching the personnel.
        </p>
    </footer>

    <script>
        // Clock Script
        function updateClock() {
            const now = new Date();

            // Time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            document.getElementById('clockTime').textContent = now.toLocaleTimeString('en-US', timeOptions);

            // Date
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        setInterval(updateClock, 1000);
        updateClock();

        // SSE Script
        document.addEventListener('DOMContentLoaded', function() {
            const queueNumberElement = document.getElementById('queueNumber');
            const queueContainer = document.getElementById('queueContainer');
            const queueStatus = document.getElementById('queueStatus');

            let currentDisplayNumber = null;

            // Setup audio for notification (Web Audio API without external assets)
            const audioCtx = new(window.AudioContext || window.webkitAudioContext)();

            function playChime() {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                oscillator.frequency.setValueAtTime(880.00, audioCtx.currentTime + 0.2); // A5

                gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.8);

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 1);
            }

            if (typeof(EventSource) !== "undefined") {
                const source = new EventSource('/queue-stream');

                source.onmessage = function(event) {
                    const data = JSON.parse(event.data);
                    const qNumber = data.queue_number;

                    // Only update if number changed to avoid jitter
                    if (currentDisplayNumber !== qNumber) {

                        // Play sound on change if it's not the initial 0 state
                        if (currentDisplayNumber !== null && qNumber !== 0) {
                            try {
                                playChime();
                            } catch (e) {
                                console.log('Audio blocked by browser policy');
                            }
                        }

                        // Shrink out
                        queueContainer.classList.remove('scale-100', 'opacity-100');
                        queueContainer.classList.add('scale-75', 'opacity-0');

                        setTimeout(() => {
                            if (qNumber === 0) {
                                queueNumberElement.innerText = "prepare next number";
                                queueNumberElement.className =
                                    "text-[7rem] md:text-[9rem] leading-none font-black text-slate-700 drop-shadow-lg";
                                queueStatus.classList.remove('opacity-100');
                                queueStatus.classList.add('opacity-0');
                            } else {
                                const formattedNumber = String(qNumber).padStart(3, '0');
                                queueNumberElement.innerText = `Q-${formattedNumber}`;
                                queueNumberElement.className =
                                    "text-[12rem] md:text-[16rem] leading-[0.9] font-black text-transparent bg-clip-text bg-gradient-to-b from-green-300 to-green-600 pulse-glow drop-shadow-2xl";
                                queueStatus.classList.remove('opacity-0');
                                queueStatus.classList.add('opacity-100');
                            }

                            // Pop in
                            queueContainer.classList.remove('scale-75', 'opacity-0');
                            queueContainer.classList.add('scale-100', 'opacity-100');

                            currentDisplayNumber = qNumber;
                        }, 400); // Wait for shrink animation
                    }
                };

                source.onerror = function(error) {
                    console.error("SSE Error:", error);
                };

                // Audio contexts require user interaction first to play sound
                document.body.addEventListener('click', function() {
                    if (audioCtx.state === 'suspended') {
                        audioCtx.resume();
                    }
                }, {
                    once: true
                });

            } else {
                queueNumberElement.innerText = "NO SSE SUPPORT";
                queueNumberElement.className = "text-[5rem] font-bold text-red-500";
            }
        });
    </script>
</body>

</html>
