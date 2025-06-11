<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Suppliers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        @if(auth()->user()->can('suppliers.create'))
                        <x-primary-button onclick="createSuppliers()">
                            {{ __('Add Supplier') }}
                        </x-primary-button>
                        @endif
                    </div>

                    <table id="suppliers-table" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
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
        $(document).ready(function() {
            $('#suppliers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('suppliers.datatables') }}",
                    type: 'GET',
                    data: function (d) {
                        // Add any additional parameters here if needed
                        console.log(d);
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });
        });
        
        function createSuppliers() {
            window.location.href = "{{ route('suppliers.create') }}";
        }
    </script>
    @endpush
</x-app-layout>