{{-- Staff Management Partial - Loaded via AJAX --}}
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
        <p class="text-gray-500 text-sm mt-1">Manage staff accounts</p>
    </div>
    <button type="button" onclick="document.getElementById('create-staff-modal').classList.remove('hidden')"
        class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create Account
    </button>
</div>

@if ($staffAccounts->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-500 font-medium">No staff accounts found</p>
        <p class="text-gray-400 text-sm mt-1">Click "Create Account" to add a new staff member.</p>
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-indigo-800">Staff Accounts</h3>
                <p class="text-xs text-indigo-600 mt-0.5">{{ $staffAccounts->total() }} account(s)</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Position</th>
                        <th class="px-6 py-3">Department</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Employee ID</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Added</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffAccounts as $index => $staff)
                        <tr id="staff-row-{{ $staff->id }}"
                            class="border-t border-gray-100 hover:bg-gray-50 {{ $index === 0 && $staffAccounts->onFirstPage() && $highlightNewest ? 'staff-row-highlight' : '' }}">
                            <td class="px-6 py-4 text-gray-400">{{ $staffAccounts->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $staff->user->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $staff->user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($staff->position) === 'doctor' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $staff->position }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $staff->department->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $staff->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $staff->employee_id }}</td>
                            <td class="px-6 py-4">
                                @if($staff->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $staff->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                @include('rhu.partials._staff-actions', ['staff' => $staff])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('rhu.partials._pagination', ['paginator' => $staffAccounts, 'section' => 'staff-approvals'])
    </div>
@endif
