<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    protected $resource = 'suppliers';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("{$this->resource}.index");
    }

    public function useDatatables()
    {
        return (new Supplier)->datatables();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("{$this->resource}.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        // Validate and store the supplier data
        $validatedData = $request->validated();
        $supplier = Supplier::create($validatedData);

        return redirect()->route("{$this->resource}.index")->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view("{$this->resource}.show", compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view("{$this->resource}.edit", compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        // Update the supplier with the validated data
        $supplier->update($request->validated());

        return redirect()->route("{$this->resource}.index")->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route("{$this->resource}.index")->with('success', 'Supplier deleted successfully.');
    }
}
