<?php

namespace App\Http\Controllers;

use App\FileProcessingTrait;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use FileProcessingTrait;

    protected $resource = 'products';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("{$this->resource}.index");
    }

    public function useDatatables()
    {
        return (new Product)->datatables();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();

        return view("{$this->resource}.create", compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $filename = $this->uploadFile($request->file('image'), 'products');
            $validatedData['image'] = $filename;
        }

        Product::create($validatedData);

        return redirect()->route("{$this->resource}.index")->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view("{$this->resource}.show", compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view("{$this->resource}.edit", compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image) {
                $this->deleteFile($product->image, 'products');
            }
            // Upload the new image
            $filename = $this->uploadFile($request->file('image'), 'products');
            $validatedData['image'] = $filename;
        }
        $product->update($validatedData);

        return redirect()->route("{$this->resource}.index")->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Add logic to delete the product
        if ($product->image) {
            $this->deleteFile($product->image, 'products');
        }
        $product->delete();

        return redirect()->route("{$this->resource}.index")->with('success', 'Product deleted successfully.');
    }
}
