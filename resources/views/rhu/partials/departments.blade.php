<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">By Department</h2>
    <p class="text-gray-500 text-sm mt-1">Diagnosis breakdown per health center</p>
</div>

@if ($groupedStatistics->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
        No records with diagnoses for the selected period.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($groupedStatistics as $departmentName => $diagnoses)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-800 capitalize">{{ $departmentName }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $diagnoses->sum('diagnosis_count') }}
                        total diagnoses</p>
                </div>
                <div class="overflow-y-auto" style="max-height:380px;">
                    <ul class="divide-y divide-gray-100">
                        @foreach ($diagnoses as $stat)
                            @php $uid = md5($departmentName . $stat->diagnosis_name); @endphp
                            <li class="hover:bg-gray-50 transition">
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs
                                            @if ($loop->index === 0) bg-amber-100 text-amber-600 font-bold
                                            @elseif ($loop->index === 1) bg-gray-200 text-gray-600 font-bold
                                            @elseif ($loop->index === 2) bg-orange-100 text-orange-600 font-bold
                                            @else bg-gray-50 text-gray-400 @endif">
                                            #{{ $loop->index + 1 }}
                                        </div>
                                        <p class="text-sm text-gray-800">{{ $stat->diagnosis_name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ $stat->diagnosis_count }}
                                            {{ $stat->diagnosis_count == 1 ? 'case' : 'cases' }}
                                        </span>
                                        <button type="button"
                                            onclick="togglePatients('{{ $uid }}', '{{ addslashes($stat->diagnosis_name) }}', '{{ addslashes($departmentName) }}')"
                                            id="btn-{{ $uid }}"
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span id="btn-text-{{ $uid }}">View</span>
                                        </button>
                                    </div>
                                </div>
                                {{-- Collapsible patient panel --}}
                                <div id="panel-{{ $uid }}"
                                    class="hidden bg-indigo-50/40 border-t border-indigo-100 px-6 py-3">
                                    <div id="loader-{{ $uid }}"
                                        class="flex items-center justify-center py-4 text-xs text-gray-400">
                                        <svg class="animate-spin h-4 w-4 mr-2 text-indigo-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12"
                                                r="10" stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        Loading patients…
                                    </div>
                                    <div id="content-{{ $uid }}"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endif
