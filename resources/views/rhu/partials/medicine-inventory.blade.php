{{-- Medicine Inventory Partial - Loaded via AJAX --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Medicine Inventory</h2>
        <p class="text-gray-500 text-sm mt-1">Manage available medicines and supplies at the RHU</p>
    </div>
    <button type="button" onclick="document.getElementById('add-medicine-modal').classList.remove('hidden')"
        class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Medicine
    </button>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Total Medicines</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalMedicines }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Low Stock</p>
            <p class="text-3xl font-bold text-amber-700">{{ $lowStockCount }}</p>
            <p class="text-xs text-gray-400 mt-0.5">≤ 10 units</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Out of Stock</p>
            <p class="text-3xl font-bold text-red-700">{{ $outOfStockCount }}</p>
        </div>
    </div>
</div>

{{-- Medicine Table --}}
@if ($medicines->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <p class="text-gray-500 font-medium">No medicines in inventory</p>
        <p class="text-gray-400 text-sm mt-1">Click "Add Medicine" to start building your inventory.</p>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Medicine Name</th>
                        <th class="px-6 py-3">Generic Name</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3 text-right">Stock</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Nearest Expiry</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $index => $medicine)
                        @include('rhu.partials._medicine-row', ['medicine' => $medicine, 'index' => $medicines->firstItem() - 1 + $index])
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('rhu.partials._pagination', ['paginator' => $medicines, 'section' => 'medicine-inventory'])
    </div>
@endif
