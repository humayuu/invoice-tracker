<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // View All Suppliers
    public function supplierAll()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.supplier_all', compact('suppliers'));
    }

    // Redirect to Add Supplier Page
    public function supplierAdd()
    {
        return view('suppliers.supplier_add');
    }

    // Store Supplier
    public function supplierStore(Request $request)
    {
        Supplier::insert([
            'supplier_name' => $request->supplier_name,
            'supplier_payment_cycle' => $request->supplier_payment_cycle,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => "Supplier Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('suppliers.all')->with($notification);
    }

    // Edit Supplier
    public function supplierEdit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.supplier_edit', compact('supplier'));
    }

    // Update Supplier
    public function supplierUpdate(Request $request)
    {
        $supplierId = $request->id;
        Supplier::findOrFail($supplierId)
            ->update([
                'supplier_name' => $request->supplier_name,
                'supplier_payment_cycle' => $request->supplier_payment_cycle,
                'updated_at' => Carbon::now()
            ]);

        $notification = [
            'message' => "Supplier Updated Successfully",
            'alert-type' => 'info'
        ];

        return redirect()->route('suppliers.all')->with($notification);
    }

    // Delete Supplier
    public function supplierDelete($id)
    {
        Supplier::findOrFail($id)->delete();
        $notification = [
            'message' => "Supplier Deleted Successfully",
            'alert-type' => 'success'
        ];
        return redirect()->route('suppliers.all')->with($notification);
    }
}
