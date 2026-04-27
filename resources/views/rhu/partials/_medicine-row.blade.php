{{-- Single medicine row + expandable batch rows --}}
@php
    $isExpired = $medicine->expiry_date && $medicine->expiry_date->isPast();
    $isExpiringSoon = $medicine->expiry_date && !$isExpired && now()->diffInDays($medicine->expiry_date) <= 30;
    $isOutOfStock = $medicine->quantity === 0;
    $isLowStock = $medicine->quantity > 0 && $medicine->quantity <= 10;
    $activeBatches = $medicine->batches->where('quantity', '>', 0);
    $batchCount = $activeBatches->count();
@endphp
<tr class="border-t border-gray-100 hover:bg-gray-50">
    <td class="px-6 py-4 text-gray-400">{{ $index + 1 }}</td>
    <td class="px-6 py-4 font-medium text-gray-900">{{ $medicine->name }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $medicine->generic_name ?? '—' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $medicine->category ?? '—' }}</td>
    <td class="px-6 py-4 text-right font-semibold {{ $isOutOfStock ? 'text-red-600' : ($isLowStock ? 'text-amber-600' : 'text-gray-800') }}">
        {{ number_format($medicine->quantity) }}
    </td>
    <td class="px-6 py-4 text-gray-600">{{ $medicine->unit }}</td>
    <td class="px-6 py-4 text-gray-600 text-xs">
        @if($medicine->expiry_date)
            <span class="{{ $isExpired ? 'text-red-600 font-semibold' : ($isExpiringSoon ? 'text-amber-600 font-semibold' : '') }}">
                {{ $medicine->expiry_date->format('M d, Y') }}
            </span>
        @else
            —
        @endif
        @if($batchCount > 1)
            <span class="text-gray-400 ml-1">({{ $batchCount }} batches)</span>
        @endif
    </td>
    <td class="px-6 py-4">
        @if($isExpired)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Expired</span>
        @elseif($isOutOfStock)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Out of Stock</span>
        @elseif($isLowStock)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Low Stock</span>
        @elseif($isExpiringSoon)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Expiring Soon</span>
        @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">In Stock</span>
        @endif
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center space-x-2">
            <button type="button" onclick="openEditModal({{ $medicine->id }}, {{ json_encode($medicine) }})"
                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>
            <button type="button" onclick="openAddStockModal({{ $medicine->id }}, '{{ addslashes($medicine->name) }}', '{{ $medicine->unit }}')"
                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-green-50 text-green-700 hover:bg-green-100 transition">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Stock
            </button>
            @if($medicine->batches->count() > 0)
                <button type="button" onclick="toggleBatches('batches-{{ $medicine->id }}')"
                    class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 hover:bg-gray-100 transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Batches
                </button>
            @endif
        </div>
    </td>
</tr>
{{-- Expandable Batch Rows --}}
@if($medicine->batches->count() > 0)
    <tr id="batches-{{ $medicine->id }}" class="hidden">
        <td colspan="9" class="px-6 py-0">
            <div class="bg-gray-50 rounded-xl border border-gray-200 my-2 overflow-hidden">
                <div class="px-4 py-2.5 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Stock Batches — {{ $medicine->name }}</p>
                    <span class="text-xs text-gray-500">{{ $medicine->batches->count() }} batch(es)</span>
                </div>
                <table class="w-full text-xs">
                    <thead class="bg-white text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Batch #</th>
                            <th class="px-4 py-2 text-right">Quantity</th>
                            <th class="px-4 py-2 text-left">Expiry Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Added</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($medicine->batches->sortBy('expiry_date') as $bIndex => $batch)
                            @php
                                $batchExpired = $batch->expiry_date && $batch->expiry_date->isPast();
                                $batchExpiringSoon = $batch->expiry_date && !$batchExpired && now()->diffInDays($batch->expiry_date) <= 30;
                                $batchEmpty = $batch->quantity === 0;
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $batchExpired ? 'bg-red-50/50' : '' }}">
                                <td class="px-4 py-2.5 text-gray-500 font-medium">#{{ $bIndex + 1 }}</td>
                                <td class="px-4 py-2.5 text-right font-bold {{ $batchEmpty ? 'text-gray-300' : ($batchExpired ? 'text-red-600' : 'text-gray-800') }}">
                                    {{ number_format($batch->quantity) }} {{ $batch->unit ?? $medicine->unit }}
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($batch->expiry_date)
                                        <span class="{{ $batchExpired ? 'text-red-600 font-semibold' : ($batchExpiringSoon ? 'text-amber-600 font-semibold' : 'text-gray-600') }}">
                                            {{ $batch->expiry_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">No expiry set</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($batchExpired)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Expired</span>
                                    @elseif($batchEmpty)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500">Depleted</span>
                                    @elseif($batchExpiringSoon)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Expiring Soon</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Active</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-gray-400">{{ $batch->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"
                                            onclick="openEditBatchModal({{ $batch->id }}, '{{ $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : '' }}')"
                                            class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition" title="Edit Expiry">
                                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Edit
                                        </button>
                                        @if($batchExpired || $batchEmpty)
                                            <form method="POST" action="{{ route('rhu.batches.delete', $batch) }}"
                                                onsubmit="return confirm('Remove this batch? This will deduct {{ $batch->quantity }} {{ $batch->unit ?? $medicine->unit }} from total stock.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition" title="Remove Batch">
                                                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
@endif
