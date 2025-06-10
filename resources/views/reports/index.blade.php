<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Transaction Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <x-primary-button onclick="createTransaction()">
                            {{ __('Add Transaction') }}
                        </x-primary-button>
                    </div>

                    <table id="reports-table" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Supplier</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
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
        let table = $('#reports-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('reports.datatables') }}",
            dom: 'Bfrtip',
            buttons: [
                'copy', 
                'csv', 
                'excel', 
                'pdf', 
                'print',
                {
                    text: 'Refresh',
                    className: 'btn btn-primary',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload();
                    }
                }
            ],
            columns: [
                {
                    data: 'transaction_date', 
                    name: 'transaction_date'
                },
                {data: 'product', name: 'product', searchable: true},
                {data: 'type', name: 'type', searchable: true},
                {data: 'quantity', name: 'quantity'},
                {data: 'supplier', name: 'supplier'},
                {data: 'user', name: 'user'},
            ],
            order: [[0, 'desc']], // Sort by transaction_date descending
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('hover:bg-gray-50');
            }
        });
    
        function createTransaction() {
            window.location.href = "{{ route('reports.create') }}";
        }
    </script>
    @endpush
</x-app-layout>