<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientController extends Controller
{
    // Function for View All Clients
    public function clientAll()
    {
        $clients = Client::latest()
            ->get();
        return view('clients.client_all', compact('clients'));
    }
    // Function for Redirect to the Add page
    public function clientAdd()
    {

        return view('clients.client_add');
    }

    // Function For Store Info
    public function clientStore(Request $request)
    {
        Client::insert([
            'client_name' => $request->client_name,
            'client_payment_cycle' => $request->client_payment_cycle,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => "Client Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('clients.all')->with($notification);
    }

    // Function for Edit Client
    public function clientEdit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.client_edit', compact('client'));
    }

    // Function for Update CLient
    public function clientUpdate(Request $request)
    {
        $clintId = $request->id;
        Client::findOrFail($clintId)
            ->update([
                'client_name' => $request->client_name,
                'client_payment_cycle' => $request->client_payment_cycle,
                'updated_at' => Carbon::now()
            ]);

        $notification = [
            'message' => "Client Update Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('clients.all')->with($notification);
    }

    // Function for Delete Client
    public function clientDelete($id)
    {
        Client::findOrFail($id)
            ->delete();
        $notification = [
            'message' => "Client Delete Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('clients.all')->with($notification);
    }

    public function clientWiseView($id)
    {
        $client = Client::findOrFail($id);
        $invoices = Invoice::where('client_id', $id)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('invoice_date', 'asc')
            ->get();

        return view('clients.client_wise_view', compact('client', 'invoices'));
    }

    public function generateInvoicePDF($id)
    {
        $client = Client::findOrFail($id);
        $invoices = Invoice::where('client_id', $id)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('invoice_date', 'asc')
            ->get();

        $pdf = PDF::loadView('clients.invoice_pdf', [
            'client' => $client,
            'invoices' => $invoices
        ]);

        return $pdf->download($client->client_name . '_invoice_statement.pdf');
    }

    public function generateSummaryReport()
    {
        $clients = Client::with(['invoices' => function($query) {
            $query->whereIn('status', ['pending', 'overdue']);
        }])->get();

        $clients = $clients->map(function($client) {
            $total_pending_amount = $client->invoices->sum('amount');
            $overdue_amount = $client->invoices->filter(function($invoice) {
                return $invoice->status === 'overdue' || Carbon::parse($invoice->due_date)->isPast();
            })->sum('amount');

            $client->total_pending_amount = $total_pending_amount;
            $client->overdue_amount = $overdue_amount;
            return $client;
        });

        $pdf = PDF::loadView('clients.summary_report', [
            'clients' => $clients
        ]);

        return $pdf->download('client_summary_report.pdf');
    }
}
