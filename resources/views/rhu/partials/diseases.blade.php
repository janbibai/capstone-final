<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Disease Statistics</h2>
    <p class="text-gray-500 text-sm mt-1">Most common diagnoses across all departments</p>
</div>

@if ($topDiseases->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
            <h3 class="text-base font-bold text-indigo-800">Most Common Diseases</h3>
            <p class="text-xs text-indigo-500 mt-0.5">Top diagnoses across all departments — selected
                period</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Rank</th>
                        <th class="px-6 py-3">Disease / Diagnosis</th>
                        <th class="px-6 py-3 text-right">Total Cases</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topDiseases as $index => $disease)
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
                                {{ $disease->diagnosis_name }}</td>
                            <td class="px-6 py-3 text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $disease->total_count }}
                                    {{ $disease->total_count == 1 ? 'case' : 'cases' }}
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
        No diagnoses recorded for the selected period.
    </div>
@endif
