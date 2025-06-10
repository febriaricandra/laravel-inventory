<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Create Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('reports.store') }}">
                        @csrf
                        
                        {{-- <div class="mb-6">
                            <x-input-label for="transaction_date" :value="__('Transaction Date')" />
                            <x-text-input id="transaction_date" 
                                        name="transaction_date" 
                                        type="datetime-local" 
                                        class="mt-1 block w-full" 
                                        :value="old('transaction_date')" 
                                        required />
                            <x-input-error class="mt-2" :messages="$errors->get('transaction_date')" />
                        </div> --}}

                        <div class="mb-6">
                            <x-input-label for="product_id" :value="__('Product')" />
                            <select id="product_id" 
                                    name="product_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('product_id')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="type" :value="__('Transaction Type')" />
                            <select id="type" 
                                    name="type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    onchange="toggleSupplierField()">
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" 
                                        name="quantity" 
                                        type="number" 
                                        class="mt-1 block w-full" 
                                        :value="old('quantity')" 
                                        required 
                                        min="1" />
                            <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                        </div>

                        <div id="supplier-field" class="mb-6">
                            <x-input-label for="supplier_id" :value="__('Supplier')" />
                            <select id="supplier_id" 
                                    name="supplier_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" 
                                    name="description" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    rows="3">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Transaction') }}</x-primary-button>
                            <a href="{{ route('reports.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleSupplierField() {
            const type = document.getElementById('type').value;
            const supplierField = document.getElementById('supplier-field');
            const supplierSelect = document.getElementById('supplier_id');
            
            if (type === 'out') {
                supplierField.style.display = 'none';
                supplierSelect.value = '';
            } else {
                supplierField.style.display = 'block';
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSupplierField();
        });
    </script>
    @endpush
</x-app-layout>