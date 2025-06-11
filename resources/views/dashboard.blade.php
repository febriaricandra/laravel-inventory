<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-black overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    {{ auth()->user()->hasRole('admin') ? __('You have admin privileges.') : __('You are a regular user.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Transactions Chart -->
                <div class="w-1/2 px-2 mb-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">In/out Overview</h3>
                            <canvas id="transactionsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Product Movement Chart -->
                <div class="w-1/2 px-2 mb-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Product Movement</h3>
                            <canvas id="productMovementChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Supplier Performance -->
                <div class="w-1/2 px-2 mb-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Top Suppliers</h3>
                            <canvas id="supplierChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="w-1/2 px-2 mb-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">User Activity</h3>
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let charts = {
                transactions: null,
                productMovement: null,
                supplier: null,
                userActivity: null
            };

            function initializeCharts() {
                // Initialize Transaction Chart
                charts.transactions = new Chart(document.getElementById('transactionsChart'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Stock In',
                            borderColor: '#10B981',
                            tension: 0.3,
                            data: []
                        }, {
                            label: 'Stock Out',
                            borderColor: '#EF4444',
                            tension: 0.3,
                            data: []
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });

                // Initialize Product Movement Chart
                charts.productMovement = new Chart(document.getElementById('productMovementChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Net Movement',
                            backgroundColor: '#60A5FA',
                            data: []
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });

                // Initialize Supplier Chart
                charts.supplier = new Chart(document.getElementById('supplierChart'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: [
                                '#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });

                // Initialize User Activity Chart
                charts.userActivity = new Chart(document.getElementById('userActivityChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'User Activity',
                            backgroundColor: '#6366F1',
                            borderColor: '#4F46E5',
                            borderWidth: 1,
                            data: []
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Transactions'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Users'
                                }
                            }
                        }
                    }
                });
            }

            function updateCharts(data) {
                // Update Transactions Chart
                console.log('Updating charts with data:', data);
                charts.transactions.data.labels = data.total_transactions.map(t => t.month);
                charts.transactions.data.datasets[0].data = data.total_transactions
                    .filter(t => t.type === 'in')
                    .map(t => t.total_quantity);
                charts.transactions.data.datasets[1].data = data.total_transactions
                    .filter(t => t.type === 'out')
                    .map(t => t.total_quantity);
                charts.transactions.update();

                // Update Product Movement Chart
                charts.productMovement.data.labels = data.product_movement.map(p => p.product.name);
                charts.productMovement.data.datasets[0].data = data.product_movement.map(p => p.net_movement);
                charts.productMovement.update();

                // Update Supplier Chart
                charts.supplier.data.labels = data.supplier_stats.map(s => s.supplier.name);
                charts.supplier.data.datasets[0].data = data.supplier_stats.map(s => s.total_quantity);
                charts.supplier.update();

                // Update User Activity Chart
                charts.userActivity.data.labels = data.user_activity.map(u => u.user.name);
                charts.userActivity.data.datasets[0].data = data.user_activity.map(u => u.transaction_count);
                charts.userActivity.update();
            }

            // Initialize and fetch data when page loads
            document.addEventListener('DOMContentLoaded', function() {
                initializeCharts();

                // Fetch initial data
                fetchData();
            });

            function fetchData() {
                fetch('{{ route('analytics.index') }}')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched analytics data:', data);
                        updateCharts(data);
                    })
                    .catch(error => console.error('Error fetching analytics data:', error));
            }
        </script>
    @endpush
</x-app-layout>
