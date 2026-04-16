<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PharmacyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $search = $request->query('search');

        // ── Medicine Inventory ─────────────────────────────────────────
        $medicines = Medicine::orderBy('name')->get();

        // ── Prescriptions (filtered by date and optional patient search) ──
        $prescriptionsQuery = Prescription::with(['medicalRecord.patient', 'medicalRecord.diagnosis', 'medicalRecord.creator'])
            ->whereHas('medicalRecord', function ($q) use ($date, $search) {
                $q->whereDate('created_on', $date);

                if ($search) {
                    $q->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%");
                    });
                }
            })
            ->orderByDesc('id');

        $prescriptions = $prescriptionsQuery->paginate(20)->withQueryString();

        // ── Summary KPIs ──────────────────────────────────────────────
        $totalMedicines = $medicines->count();
        $lowStock = $medicines->where('quantity', '<=', 10)->where('quantity', '>', 0)->count();
        $outOfStock = $medicines->where('quantity', 0)->count();
        $todayPrescriptionCount = Prescription::whereHas('medicalRecord', function ($q) {
            $q->whereDate('created_on', Carbon::today());
        })->count();

        return view('pharmacy.dashboard', [
            'medicines' => $medicines,
            'prescriptions' => $prescriptions,
            'date' => $date,
            'search' => $search,
            'totalMedicines' => $totalMedicines,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'todayPrescriptionCount' => $todayPrescriptionCount,
        ]);
    }

    /**
     * Dispense medicine — deduct the given quantity using FEFO (First Expiry, First Out).
     */
    public function dispense(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $qty = (int) $request->quantity;

        if ($qty > $medicine->quantity) {
            return back()->withErrors([
                'quantity' => "Insufficient stock for \"{$medicine->name}\". Available: {$medicine->quantity} {$medicine->unit}.",
            ]);
        }

        // FEFO: deduct from earliest-expiring batches first
        $batches = $medicine->batches()
            ->where('quantity', '>', 0)
            ->orderByRaw('expiry_date IS NULL, expiry_date ASC')
            ->get();

        $remaining = $qty;
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $deduct = min($remaining, $batch->quantity);
            $batch->decrement('quantity', $deduct);
            $remaining -= $deduct;
        }

        $medicine->syncStockFromBatches();

        return back()->with('success', "Dispensed {$qty} {$medicine->unit} of \"{$medicine->name}\". Remaining stock: {$medicine->quantity} {$medicine->unit}.");
    }
}

