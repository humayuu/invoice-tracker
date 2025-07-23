<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Payment;

class PurchaseController extends Controller
{
    // View All Purchases
    public function purchaseAll()
    {
        // Update overdue statuses before displaying
        $now = \Carbon\Carbon::now();
        \App\Models\Purchase::where('status', 'pending')
            ->where('due_date', '<', $now->format('Y-m-d'))
            ->update(['status' => 'overdue']);

        $purchases = Purchase::with('supplier')->latest()->get();
        return view('purchases.purchase_all', compact('purchases'));
    }

    // Redirect to Add Purchase Page
    public function purchaseAdd()
    {
        $suppliers = Supplier::latest()->get();
        return view('purchases.purchase_add', compact('suppliers'));
    }

    // Store Purchase
    public function purchaseStore(Request $request)
    {
        Purchase::insert([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'po_no' => $request->po_no,
            'purchase_invoice_no' => $request->purchase_invoice_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'created_at' => Carbon::now()
        ]);

        $notification = [
            'message' => "Purchase Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('purchase.all')->with($notification);
    }

    // Edit Purchase
    public function purchaseEdit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::latest()->get();
        return view('purchases.purchase_edit', compact('purchase', 'suppliers'));
    }

    // Update Purchase
    public function purchaseUpdate(Request $request)
    {
        $purchaseId = $request->id;
        $purchase = Purchase::findOrFail($purchaseId);
        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'po_no' => $request->po_no,
            'purchase_invoice_no' => $request->purchase_invoice_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'updated_at' => Carbon::now()
        ]);

        // Status logic
        $now = Carbon::now()->format('Y-m-d');
        if ($purchase->status === 'overdue' && $request->due_date >= $now) {
            $purchase->update(['status' => 'pending']);
        } elseif ($purchase->status === 'pending' && $request->due_date < $now) {
            $purchase->update(['status' => 'overdue']);
        }

        $notification = [
            'message' => "Purchase Updated Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('purchase.all')->with($notification);
    }

    // Delete Purchase
    public function purchaseDelete($id)
    {
        Purchase::findOrFail($id)->delete();
        $notification = [
            'message' => "Purchase Deleted Successfully",
            'alert-type' => 'success'
        ];
        return redirect()->route('purchase.all')->with($notification);
    }

    // Purchase Summary Report
    public function generateSummaryReport()
    {
        $suppliers = Supplier::with(['purchases'])->get();

        $suppliers = $suppliers->map(function($supplier) {
            $total_pending_amount = $supplier->purchases->sum('amount');
            // All purchases are 'pending' in this system, so overdue = due_date < today
            $overdue_amount = $supplier->purchases->filter(function($purchase) {
                return Carbon::parse($purchase->due_date)->isPast();
            })->sum('amount');

            $supplier->total_pending_amount = $total_pending_amount;
            $supplier->overdue_amount = $overdue_amount;
            return $supplier;
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchases.summary_report', [
            'suppliers' => $suppliers
        ]);

        return $pdf->download('purchase_summary_report.pdf');
    }

    public function supplierWiseView($id)
    {
        $supplier = \App\Models\Supplier::with(['payments', 'purchases.payments'])->findOrFail($id);
        $purchases = \App\Models\Purchase::where('supplier_id', $id)
            ->orderBy('purchase_date', 'asc')
            ->get();
        $total_due = $purchases->sum('amount');
        $amount_paid = $purchases->sum('amount_paid');
        $remaining_balance = $total_due - $amount_paid;
        $payment_history = $supplier->payments()->with('purchases')->latest()->get();
        return view('suppliers.supplier_wise_view', compact('supplier', 'purchases', 'total_due', 'amount_paid', 'remaining_balance', 'payment_history'));
    }

    public function storeSupplierPayment(Request $request, $supplierId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'purchase_ids' => 'required|array',
            'purchase_ids.*' => 'exists:purchases,id',
        ]);
        $supplier = \App\Models\Supplier::findOrFail($supplierId);
        $amount = $request->amount;
        $purchaseIds = $request->purchase_ids;
        $note = $request->note;
        $payment = Payment::create([
            'supplier_id' => $supplier->id,
            'amount' => $amount,
            'note' => $note,
        ]);
        // Allocate payment to purchases
        $remaining = $amount;
        foreach (\App\Models\Purchase::whereIn('id', $purchaseIds)->orderBy('due_date')->get() as $purchase) {
            $toPay = min($purchase->remaining_balance, $remaining);
            if ($toPay <= 0) continue;
            $payment->purchases()->attach($purchase->id, ['amount_applied' => $toPay]);
            $remaining -= $toPay;
            if ($remaining <= 0) break;
        }
        $notification = [
            'message' => 'Payment recorded successfully!',
            'alert-type' => 'success',
        ];
        return redirect()->route('suppliers.purchases.report', $supplier->id)->with($notification);
    }

    public function purchasePaid($id)
    {
        \App\Models\Purchase::findOrFail($id)
            ->update([
                'status' => 'paid',
                'updated_at' => \Carbon\Carbon::now()
            ]);

        $notification = [
            'message' => "Purchase Status Updated Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('purchase.all')->with($notification);
    }

    public function purchaseDetail($id)
    {
        $purchase = \App\Models\Purchase::with('supplier')->findOrFail($id);
        return view('purchases.purchase_detail', compact('purchase'));
    }

    public function generateSupplierPDF($id)
    {
        $supplier = \App\Models\Supplier::findOrFail($id);
        $purchases = \App\Models\Purchase::where('supplier_id', $id)
            ->orderBy('purchase_date', 'asc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('suppliers.supplier_pdf', [
            'supplier' => $supplier,
            'purchases' => $purchases
        ]);

        return $pdf->download($supplier->supplier_name . '_purchase_statement.pdf');
    }

    public function clearSupplierPayments($supplierId)
    {
        $supplier = \App\Models\Supplier::findOrFail($supplierId);
        // Hide payment history instead of deleting payments
        foreach ($supplier->payments as $payment) {
            $payment->hidden = true;
            $payment->save();
        }
        $notification = [
            'message' => 'Payment history hidden successfully! Balances remain reduced.',
            'alert-type' => 'success',
        ];
        return redirect()->route('suppliers.purchases.report', $supplier->id)->with($notification);
    }
    // Route: Route::get('/supplier/{id}/purchase-pdf', [PurchaseController::class, 'generateSupplierPDF'])->name('suppliers.purchase.pdf');
}
