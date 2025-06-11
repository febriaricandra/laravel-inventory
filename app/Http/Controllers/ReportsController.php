<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionProductRequest;
use App\Http\Requests\UpdateTransactionProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TransactionProduct;

class ReportsController extends Controller
{
    protected $resource = 'reports';

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
        return (new TransactionProduct)->datatables();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // supplier, product, categories
        $products = Product::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view("{$this->resource}.create", compact('products', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionProductRequest $request)
    {
        TransactionProduct::create($request->validated());

        return redirect()->route("{$this->resource}.index")->with('success', 'Transaction Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionProduct $transactionProduct)
    {
        return view("{$this->resource}.show", compact('transactionProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionProduct $transactionProduct)
    {
        return view("{$this->resource}.edit", compact('transactionProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionProductRequest $request, TransactionProduct $transactionProduct)
    {
        $transactionProduct->update($request->validated());

        return redirect()->route("{$this->resource}.index")->with('success', 'Transaction Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionProduct $transactionProduct)
    {
        $transactionProduct->delete();

        return redirect()->route("{$this->resource}.index")->with('success', 'Transaction Product deleted successfully.');
    }
}
