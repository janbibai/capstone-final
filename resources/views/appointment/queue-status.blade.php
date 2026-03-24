@extends('layouts.app')

@section('title', 'Queue Status')

@section('content')
    <div class="min-h-screen bg-green-50 py-12 px-4">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 border border-green-100">

            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-green-700">
                    Queue Status
                </h2>
                <p class="text-gray-500 mt-2">
                    Check the current serving queue and your queue status for a specific date.
                </p>
            </div>

            <div class="mb-8 grid md:grid-cols-2 gap-4">
                <div>
                    <label for="queue_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Date
                    </label>
                    <input type="date" id="queue_date" name="queue_date" value="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                </div>
            </div>

            <div id="loading-state" class="hidden text-center py-8">
                <svg class="animate-spin h-8 w-8 text-green-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="text-gray-500 mt-2">Loading queue data...</p>
            </div>

            <div id="queue-data-container">
                <!-- Current Serving Section -->
                <div class="mb-8 bg-green-50 rounded-xl p-6 border-2 border-green-200 text-center">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Currently Serving</h3>
                    <div id="current-serving-number" class="text-5xl font-extrabold text-green-600">
                        --
                    </div>
                    <div id="current-serving-details" class="text-sm font-medium text-green-700 mt-3">
                        Select a date to see the status.
                    </div>
                </div>

                <!-- Queue List Section -->
                <h3 class="text-xl font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-200">
                    Queue List
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="py-3 px-4 rounded-tl-lg font-semibold">Queue No.</th>
                                <th class="py-3 px-4 font-semibold">Time</th>
                                <th class="py-3 px-4 rounded-tr-lg text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody id="queue-list-body" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">
                                    Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div id="pagination-container" class="mt-6 flex justify-between items-center hidden">
                    <button id="prev-page" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Previous
                    </button>
                    <span id="page-info" class="text-sm font-medium text-gray-600">
                        Page <span id="current-page-display">1</span> of <span id="last-page-display">1</span>
                    </span>
                    <button id="next-page" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Next
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('queue_date');
            const loadingState = document.getElementById('loading-state');
            const queueContainer = document.getElementById('queue-data-container');
            const currentServingNumber = document.getElementById('current-serving-number');
            const currentServingDetails = document.getElementById('current-serving-details');
            const queueListBody = document.getElementById('queue-list-body');
            
            // Pagination elements
            const paginationContainer = document.getElementById('pagination-container');
            const prevPageBtn = document.getElementById('prev-page');
            const nextPageBtn = document.getElementById('next-page');
            const currentPageDisplay = document.getElementById('current-page-display');
            const lastPageDisplay = document.getElementById('last-page-display');
            
            let currentPage = 1;

            function getStatusBadge(status) {
                const badges = {
                    'pending': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>',
                    'started': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 opacity-90 animate-pulse">Serving</span>',
                    'completed': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Completed</span>',
                    'cancelled': '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Cancelled</span>'
                };
                return badges[status] ||
                    `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">${status}</span>`;
            }

            async function fetchQueueData(date, page = 1) {
                if (!date) return;

                // Show loading state implicitly or by partial fade
                queueContainer.classList.add('opacity-50', 'pointer-events-none');

                try {
                    const response = await fetch(`/appointments/queue-status-data?date=${date}&page=${page}`);
                    const data = await response.json();

                    // Update Current Serving
                    if (data.current_serving) {
                        currentServingNumber.textContent = data.current_serving.queue_number;
                        currentServingDetails.innerHTML =
                            `<span class="opacity-80">Time:</span> <span class="font-bold">${data.current_serving.schedule_time}</span>`;
                    } else {
                        currentServingNumber.textContent = '--';
                        // Check if there are any appointments in the paginated data
                        if (data.appointments && data.appointments.data && data.appointments.data.length > 0) {
                            currentServingDetails.textContent = 'Queue has not started or all completed.';
                        } else {
                            currentServingDetails.textContent = 'No appointments for this date.';
                        }
                    }

                    // Update Queue List
                    queueListBody.innerHTML = '';
                    
                    const appointmentsList = data.appointments ? data.appointments.data : [];

                    if (appointmentsList && appointmentsList.length > 0) {
                        appointmentsList.forEach(app => {
                            const tr = document.createElement('tr');

                            // Highlight current serving row
                            if (app.status === 'started') {
                                tr.className =
                                    'bg-green-50/60 font-medium scale-[1.01] shadow-sm rounded-lg';
                            } else {
                                tr.className = 'hover:bg-gray-50 transition-colors duration-150';
                            }

                            tr.innerHTML = `
                            <td class="py-4 px-4 font-bold text-gray-800"><span class="${app.status === 'started' ? 'text-green-700' : ''}">${app.queue_number}</span></td>
                            <td class="py-4 px-4 text-gray-600">${app.schedule_time}</td>
                            <td class="py-4 px-4 text-center">${getStatusBadge(app.status)}</td>
                        `;
                            queueListBody.appendChild(tr);
                        });
                        
                        // Show and update pagination controls
                        if (data.appointments.last_page > 1) {
                            paginationContainer.classList.remove('hidden');
                            currentPageDisplay.textContent = data.appointments.current_page;
                            lastPageDisplay.textContent = data.appointments.last_page;
                            
                            prevPageBtn.disabled = data.appointments.current_page <= 1;
                            nextPageBtn.disabled = data.appointments.current_page >= data.appointments.last_page;
                            
                            currentPage = data.appointments.current_page;
                        } else {
                            paginationContainer.classList.add('hidden');
                        }
                    } else {
                        queueListBody.innerHTML = `
                        <tr>
                            <td colspan="3" class="py-12 text-center text-gray-500 bg-gray-50/50 rounded-b-lg">
                                <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">event_busy</span><br>
                                No appointments found for this date.
                            </td>
                        </tr>
                    `;
                        paginationContainer.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error fetching queue data:', error);
                    queueListBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="py-8 text-center text-red-500">
                            Failed to load queue data. Please try again or check your connection.
                        </td>
                    </tr>
                `;
                } finally {
                    queueContainer.classList.remove('opacity-50', 'pointer-events-none');
                }
            }

            // Pagination event listeners
            prevPageBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    fetchQueueData(dateInput.value, currentPage - 1);
                }
            });

            nextPageBtn.addEventListener('click', () => {
                fetchQueueData(dateInput.value, currentPage + 1);
            });

            // Fetch on date change
            dateInput.addEventListener('change', (e) => {
                currentPage = 1; // Reset to page 1 on date change
                fetchQueueData(e.target.value, currentPage);
            });

            // Initial fetch
            if (dateInput.value) {
                fetchQueueData(dateInput.value, currentPage);
            }

            // Setup SSE for real-time updates for today
            const getIsoDate = () => {
                // Returns YYYY-MM-DD in local time
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            let todayStr = getIsoDate();

            const eventSource = new EventSource('/queue-stream');

            eventSource.onmessage = function(event) {
                try {
                    const eventData = JSON.parse(event.data);
                    const currentQueueNum = eventData.queue_number;

                    // Re-evaluate today string in case day changed
                    todayStr = getIsoDate();

                    // Only auto-refresh if we are viewing today's date
                    if (dateInput.value === todayStr && currentQueueNum) {
                        const currentDisplayedStr = currentServingNumber.textContent.replace('Q-', '').trim();
                        const currentDisplayed = parseInt(currentDisplayedStr) || 0;

                        if (currentDisplayed !== currentQueueNum && currentQueueNum > 0) {
                            // Queue changed, fetch new data for today, keep current page
                            fetchQueueData(todayStr, currentPage);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing SSE data:', e);
                }
            };

        });
    </script>
@endsection
