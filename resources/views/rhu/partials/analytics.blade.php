<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Patient Analytics</h2>
    <p class="text-gray-500 text-sm mt-1">Visual breakdown of appointments, patients, and diagnoses</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-sm font-semibold text-gray-600 mb-4">Patients Per Month</h4>
        <div style="position:relative;height:280px;">
            <canvas id="appointmentsPerMonthChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-sm font-semibold text-gray-600 mb-4">Patients Per Barangay</h4>
        <div style="position:relative;height:280px;">
            <canvas id="patientsPerDepartmentChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-sm font-semibold text-gray-600 mb-4">Top Diagnoses This Month</h4>
        <div style="position:relative;height:280px;">
            <canvas id="topDiagnosesChart"></canvas>
        </div>
    </div>
</div>

{{-- Embed chart data for JS to pick up after AJAX injection --}}
<script type="application/json" id="analytics-chart-data">{!! json_encode([
    'appointmentsPerMonth' => $appointmentsPerMonth,
    'patientsPerDepartment' => $patientsPerDepartment,
    'topDiagnosesThisMonth' => $topDiagnosesThisMonth,
]) !!}</script>
