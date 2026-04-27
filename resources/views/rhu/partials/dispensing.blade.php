<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Medicine Dispensing Statistics</h2>
    <p class="text-gray-500 text-sm mt-1">Most dispensed medicines by the pharmacy — selected period</p>
</div>

@if ($topDispensedMedicines->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-emerald-50 border-b border-emerald-100">
            <h3 class="text-base font-bold text-emerald-800">Top Dispensed Medicines</h3>
            <p class="text-xs text-emerald-500 mt-0.5">Ranked by total quantity dispensed — selected period</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Rank</th>
                        <th class="px-6 py-3">Medicine Name</th>
                        <th class="px-6 py-3 text-right">Total Qty Dispensed</th>
                        <th class="px-6 py-3 text-right">Times Dispensed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topDispensedMedicines as $index => $med)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs
                                    @if ($index === 0) bg-amber-100 text-amber-600 font-bold
                                    @elseif ($index === 1) bg-gray-200 text-gray-600 font-bold
                                    @elseif ($index === 2) bg-orange-100 text-orange-600 font-bold
                                    @else bg-gray-50 text-gray-400 @endif">
                                    #{{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-900">
                                {{ $med->medicine_name }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                    {{ number_format($med->total_dispensed) }}
                                    {{ $med->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $med->dispense_count }}
                                    {{ $med->dispense_count == 1 ? 'time' : 'times' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
        No medicines have been dispensed for the selected period.
    </div>
@endif

{{-- Chronological Logs --}}
<div class="mt-8 mb-6">
    <h3 class="text-xl font-bold text-gray-800">Dispensing Logs</h3>
    <p class="text-gray-500 text-sm mt-1">Chronological history of medicines dispensed — selected period</p>
</div>

@if ($dispensingLogsTabular->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
        No dispensing events recorded for the selected period.
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Date & Time</th>
                        <th class="px-6 py-3">Patient</th>
                        <th class="px-6 py-3">Medicine Dispensed</th>
                        <th class="px-6 py-3 text-right">Quantity</th>
                        <th class="px-6 py-3">Dispensed By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dispensingLogsTabular as $log)
                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-500 text-xs whitespace-nowrap">
                                {{ $log->dispensed_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-900">
                                @if($log->prescription && $log->prescription->medicalRecord && $log->prescription->medicalRecord->patient)
                                    {{ $log->prescription->medicalRecord->patient->first_name }} {{ $log->prescription->medicalRecord->patient->last_name }}
                                @else
                                    <span class="text-gray-400 italic">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                {{ $log->medicine_name }}
                            </td>
                            <td class="px-6 py-3 text-right font-medium">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700">
                                    {{ $log->quantity_dispensed }} {{ $log->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600">
                                {{ $log->dispenser->name ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
