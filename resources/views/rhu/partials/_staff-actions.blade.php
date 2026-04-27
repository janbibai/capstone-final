{{-- Staff row action buttons - extracted to keep partial manageable --}}
<div class="flex items-center justify-end space-x-2">
    <button type="button" title="Edit Staff"
        onclick="openEditStaffModal({{ $staff->id }}, '{{ addslashes($staff->position) }}', '{{ $staff->department_id }}', '{{ addslashes($staff->phone) }}', '{{ addslashes($staff->user->name) }}')"
        class="inline-flex items-center p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
    </button>
    <form method="POST" action="{{ route('rhu.staff.toggleStatus', $staff) }}"
        onsubmit="return confirm('Are you sure you want to {{ $staff->is_active ? 'deactivate' : 'activate' }} this account?')">
        @csrf
        @method('PATCH')
        <button type="submit"
            title="{{ $staff->is_active ? 'Deactivate Account' : 'Activate Account' }}"
            class="inline-flex items-center p-1.5 rounded-lg {{ $staff->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition">
            @if($staff->is_active)
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </button>
    </form>
</div>
