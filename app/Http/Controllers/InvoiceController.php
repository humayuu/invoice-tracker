<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoiceAll()
    {
        // Update overdue statuses before displaying
        $now = Carbon::now();
        Invoice::where('status', 'pending')
            ->where('due_date', '<', $now->format('Y-m-d'))
            ->update(['status' => 'overdue']);

        $invoices = Invoice::with('client')->latest()->get();
        return view('invoices.invoice_all', compact('invoices'));
    }


    // Function for Redirect to the Add Invoice Page
    public function invoiceAdd()
    {
        $clients = Client::latest()
            ->get();
        return view('invoices.invoice_add', compact('clients'));
    }


    // Function for Store Invoice Data
    public function invoiceStore(Request $request)
    {
        Invoice::insert([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'po_no' => $request->po_no,
            'invoice_no' => $request->invoice_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'created_at' => Carbon::now()
        ]);

        $notification = [
            'message' => "Invoice Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('invoice.all')->with($notification);
    }


    // Function for Edit Invoice Data
    public function invoiceEdit($id){
        $invoice = Invoice::findOrFail($id);
        $clients = Client::latest()
        ->get();
        return view('invoices.invoice_edit', compact('invoice','clients'));

    }

    // Function for Update Invoice
    public function invoiceUpdate(Request $request){
        $invoiceId = $request->id;

        Invoice::findOrFail($invoiceId)
        ->update([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'po_no' => $request->po_no,
            'invoice_no' => $request->invoice_no,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'updated_at' => Carbon::now()
        ]);

        $notification = [
            'message' => "Invoice Updated Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('invoice.all')->with($notification);
    }

    // Function for Delete Invoice
    public function invoiceDelete($id){
        Invoice::findOrFail($id)
        ->delete();

        $notification = [
            'message' => "Invoice Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('invoice.all')->with($notification);

    }

    // Function for Change the Status of Invoice
    public function invoicePaid($id){
        Invoice::findOrFail($id)
        ->update([
            'status' => 'paid',
            'updated_at' => Carbon::now()
        ]);

        $notification = [
            'message' => "Invoice Status Updated Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('invoice.all')->with($notification);
    }

    public function checkDuplicateInvoice(Request $request)
    {
        $invoice_no = $request->query('invoice_no');
        $exists = Invoice::where('invoice_no', $invoice_no)->exists();
        return response()->json(!$exists); // Return true if it DOESN'T exist (is valid)
    }

    public function checkOverdueInvoices()
    {
        $now = Carbon::now();
        $overdueInvoices = Invoice::with('client')
            ->where('status', 'pending')
            ->where('due_date', '<', $now->format('Y-m-d'))
            ->get()
            ->map(function ($invoice) {
                return [
                    'invoice_no' => $invoice->invoice_no,
                    'client_name' => $invoice->client->client_name,
                    'amount' => number_format($invoice->amount, 2),
                    'due_date' => Carbon::parse($invoice->due_date)->format('d/m/Y'),
                    'days_overdue' => Carbon::now()->diffInDays($invoice->due_date)
                ];
            });

        return response()->json($overdueInvoices);
    }
}
