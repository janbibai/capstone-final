{{-- Reusable AJAX Pagination Component --}}
{{-- Expects: $paginator (LengthAwarePaginator), $section (string) --}}
@if ($paginator->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white">
        <p class="text-xs text-gray-500">
            Showing <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
            to <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
            of <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span> results
        </p>
        <div class="flex items-center space-x-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-300 bg-gray-50 cursor-not-allowed">← Prev</span>
            @else
                <button type="button" onclick="paginateSection('{{ $section }}', {{ $paginator->currentPage() - 1 }})"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">← Prev</button>
            @endif

            {{-- Page Numbers --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
            @endphp

            @if ($start > 1)
                <button type="button" onclick="paginateSection('{{ $section }}', 1)"
                    class="w-8 h-8 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 transition flex items-center justify-center">1</button>
                @if ($start > 2)
                    <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <span class="w-8 h-8 rounded-lg text-xs font-semibold bg-indigo-600 text-white flex items-center justify-center">{{ $page }}</span>
                @else
                    <button type="button" onclick="paginateSection('{{ $section }}', {{ $page }})"
                        class="w-8 h-8 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 transition flex items-center justify-center cursor-pointer">{{ $page }}</button>
                @endif
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">…</span>
                @endif
                <button type="button" onclick="paginateSection('{{ $section }}', {{ $lastPage }})"
                    class="w-8 h-8 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 transition flex items-center justify-center">{{ $lastPage }}</button>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button type="button" onclick="paginateSection('{{ $section }}', {{ $paginator->currentPage() + 1 }})"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">Next →</button>
            @else
                <span class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-300 bg-gray-50 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
@endif
