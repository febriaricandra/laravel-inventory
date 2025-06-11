<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        @if(auth()->user()->can('products.create'))
                        <x-primary-button onclick="createProduct()">
                            {{ __('Add Product') }}
                        </x-primary-button>
                        @endif
                    </div>

                    <table id="products-table" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Image</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.datatables') }}",
            columns: [
                {data: 'name', name: 'name', orderable: true, searchable: true},
                {data: 'categories', name: 'categories', orderable: true, searchable: true},
                {data: 'image', name: 'image', orderable: false, searchable: false},
                {data: 'price', name: 'price'},
                {data: 'stock', name: 'stock'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });

        function createProduct() {
            window.location.href = "{{ route('products.create') }}";
        }
    </script>
    @endpush
</x-app-layout>