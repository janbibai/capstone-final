<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Board — Now Serving</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(74, 222, 128, 0.3); }
            50% { box-shadow: 0 0 40px rgba(74, 222, 128, 0.6); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        .slide-up { animation: slide-up 0.5s ease-out; }
        .queue-number-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-gray-900/80 border-b border-gray-800 px-8 py-4 flex items-center justify-between backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <div class="bg-green-500 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white">Queue Board</h1>
                <p class="text-xs text-gray-400">Rural Health Unit — Zamboanguita</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div id="liveIndicator" class="flex items-center gap-2 bg-green-500/10 border border-green-500/30 text-green-400 text-xs font-semibold px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                LIVE
            </div>
            <div class="text-right">
                <p id="currentTime" class="text-sm font-mono text-gray-300"></p>
                <p id="currentDate" class="text-xs text-gray-500"></p>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col lg:flex-row gap-6 p-6 lg:p-10">

        {{-- NOW SERVING (Main Area) --}}
        <div class="flex-1 flex items-center justify-center">
            <div class="text-center w-full max-w-xl slide-up">
                <div class="mb-4">
                    <span class="inline-flex items-center gap-2 bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold uppercase tracking-widest px-5 py-2 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Now Serving
                    </span>
                </div>

                <div id="nowServingNumber" class="text-[10rem] lg:text-[12rem] font-black leading-none text-green-400 pulse-glow rounded-3xl py-6 queue-number-transition">
                    @if($nowServing)
                        Q-{{ str_pad($nowServing->queue_number, 3, '0', STR_PAD_LEFT) }}
                    @else
                        ---
                    @endif
                </div>

                <div id="nowServingDetails" class="mt-6 space-y-2">
                    @if($nowServing)
                        <p class="text-xl text-gray-300 font-medium">
                            <span id="nowServingPatient">
                                {{ $nowServing->patient ? $nowServing->patient->first_name . ' ' . $nowServing->patient->last_name : 'N/A' }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500">
                            <span id="nowServingService">{{ optional($nowServing->service)->name ?? '' }}</span>
                        </p>
                    @else
                        <p class="text-xl text-gray-500 font-medium">No patient being served</p>
                        <p class="text-sm text-gray-600">Waiting for next appointment...</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- UPCOMING QUEUE (Sidebar) --}}
        <div class="w-full lg:w-80 shrink-0">
            <div class="bg-gray-900/60 border border-gray-800 rounded-2xl overflow-hidden backdrop-blur-sm h-full">
                <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <h2 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Up Next</h2>
                    </div>
                    <span id="upcomingCount" class="bg-gray-800 text-gray-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                        {{ $upcoming->count() }}
                    </span>
                </div>

                <div id="upcomingList" class="divide-y divide-gray-800/50">
                    @forelse($upcoming as $index => $appointment)
                        <div class="px-5 py-4 flex items-center gap-4 {{ $index === 0 ? 'bg-gray-800/30' : '' }}">
                            <div class="w-12 h-12 rounded-xl {{ $index === 0 ? 'bg-green-500/20 text-green-400' : 'bg-gray-800 text-gray-400' }} flex items-center justify-center font-bold text-sm shrink-0">
                                Q-{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-200 truncate">
                                    @if($appointment->patient)
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ optional($appointment->service)->name ?? '—' }}</p>
                            </div>
                            @if($index === 0)
                                <span class="ml-auto text-xs text-green-400 font-semibold whitespace-nowrap">Next</span>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 text-sm">No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    {{-- Auto-refresh script --}}
    <script>
        // Update clock
        function updateClock() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Poll the API every 5 seconds to refresh queue data
        function padQueue(num) {
            return 'Q-' + String(num).padStart(3, '0');
        }

        async function refreshQueue() {
            try {
                const res = await fetch('/api/queue-board');
                const data = await res.json();

                // Update NOW SERVING
                const numEl = document.getElementById('nowServingNumber');
                const detailsEl = document.getElementById('nowServingDetails');

                if (data.now_serving) {
                    const newNum = padQueue(data.now_serving.queue_number);
                    if (numEl.textContent.trim() !== newNum) {
                        numEl.style.transform = 'scale(1.05)';
                        setTimeout(() => numEl.style.transform = 'scale(1)', 300);
                    }
                    numEl.textContent = newNum;
                    detailsEl.innerHTML = `
                        <p class="text-xl text-gray-300 font-medium">${data.now_serving.patient_name}</p>
                        <p class="text-sm text-gray-500">${data.now_serving.service}</p>
                    `;
                } else {
                    numEl.textContent = '---';
                    detailsEl.innerHTML = `
                        <p class="text-xl text-gray-500 font-medium">No patient being served</p>
                        <p class="text-sm text-gray-600">Waiting for next appointment...</p>
                    `;
                }

                // Update UPCOMING list
                const listEl = document.getElementById('upcomingList');
                const countEl = document.getElementById('upcomingCount');
                countEl.textContent = data.upcoming.length;

                if (data.upcoming.length > 0) {
                    listEl.innerHTML = data.upcoming.map((a, i) => `
                        <div class="px-5 py-4 flex items-center gap-4 ${i === 0 ? 'bg-gray-800/30' : ''}">
                            <div class="w-12 h-12 rounded-xl ${i === 0 ? 'bg-green-500/20 text-green-400' : 'bg-gray-800 text-gray-400'} flex items-center justify-center font-bold text-sm shrink-0">
                                ${padQueue(a.queue_number)}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-200 truncate">${a.patient_name}</p>
                                <p class="text-xs text-gray-500 truncate">${a.service}</p>
                            </div>
                            ${i === 0 ? '<span class="ml-auto text-xs text-green-400 font-semibold whitespace-nowrap">Next</span>' : ''}
                        </div>
                    `).join('');
                } else {
                    listEl.innerHTML = `
                        <div class="px-5 py-10 text-center">
                            <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 text-sm">No upcoming appointments</p>
                        </div>
                    `;
                }
            } catch (err) {
                console.error('Queue refresh failed:', err);
            }
        }

        // Poll every 5 seconds
        setInterval(refreshQueue, 5000);
    </script>
</body>
</html>
