@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md">

            <!-- Date Range Filter -->
            <div class="mb-4">
                <label for="date-range" class="block text-sm font-medium text-gray-700">Select Date Range:</label>
                <select id="date-range"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="last_3_months">Last 3 Months</option>
                </select>
            </div>

            <!-- Number of Messages and Balance -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h2 class="text-xl font-bold">Total Messages Sent</h2>
                    <p class="text-2xl font-semibold" id="total-sent">0</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <h2 class="text-xl font-bold">Failed Messages</h2>
                    <p class="text-2xl font-semibold" id="total-failed">0</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h2 class="text-xl font-bold">Scheduled Messages</h2>
                    <p class="text-2xl font-semibold" id="total-scheduled">0</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h2 class="text-xl font-bold">Immediate Messages</h2>
                    <p class="text-2xl font-semibold" id="total-immediate">0</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-lg">
                    <h2 class="text-xl font-bold">Remaining Balance</h2>
                    <p class="text-2xl font-semibold" id="remaining-balance">{{ $balance }}</p>
                </div>
            </div>

            <!-- Tabs for Student and Employee -->
            <div class="mb-4">
                <div class="flex space-x-4 mt-5">
                    <button id="student-tab" class="px-4 py-2 bg-blue-500 text-white rounded-lg focus:outline-none">Student</button>
                    <button id="employee-tab" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg focus:outline-none">Employee</button>
                </div>
            </div>

            <!-- Filters Container -->
            <div id="student-filters" class="mb-4">
                <h1 class="text-lg text-black font-semibold">Student</h1>
                <div class="flex space-x-4 mb-4">
                    {{-- College Selection --}}
                    <div class="flex-grow">
                        <label for="college" class="block text-sm font-medium">College</label>
                        <select name="college" id="college"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select College</option>
                            <option value="all">All Colleges</option>
                            {{-- @foreach ($colleges as $college)
                                <option value="{{ $college->college_id }}">{{ $college->college_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    {{-- Program Selection --}}
                    <div class="flex-grow">
                        <label for="program" class="block text-sm font-medium">Program</label>
                        <select name="program" id="program"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Program</option>
                            <option value="all">All Programs</option>
                            {{-- @foreach ($programs as $program)
                                <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    {{-- Major Selection --}}
                    <div class="flex-grow">
                        <label for="major" class="block text-sm font-medium">Major</label>
                        <select name="major" id="major"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Major</option>
                            <option value="all">All Majors</option>
                            {{-- @foreach ($majors as $major)
                                <option value="{{ $major->major_id }}">{{ $major->major_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    {{-- Year Level Selection --}}
                    <div class="flex-grow">
                        <label for="year" class="block text-sm font-medium">Year Level</label>
                        <select name="year" id="year"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Year Level</option>
                            <option value="all">All Year Levels</option>
                            {{-- @foreach ($years as $year)
                                <option value="{{ $year->year_id }}">{{ $year->year_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
            </div>

            <div id="employee-filters" class="mb-4 hidden">
                <h1 class="text-lg text-black font-semibold">Employee</h1>
                <div class="flex space-x-4 mb-4">
                    {{-- Office Selection --}}
                    <div class="flex-grow">
                        <label for="office" class="block text-sm font-medium">Office</label>
                        <select name="office" id="office"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Office</option>
                            <option value="all">All Offices</option>
                            {{-- @foreach ($offices as $office)
                                <option value="{{ $office->office_id }}">{{ $office->office_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    {{-- Status Selection --}}
                    <div class="flex-grow">
                        <label for="status" class="block text-sm font-medium">Status</label>
                        <select name="status" id="status"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Status</option>
                            <option value="all">All Statuses</option>
                            {{-- @foreach ($statuses as $status)
                                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    {{-- Type Selection --}}
                    <div class="flex-grow">
                        <label for="type" class="block text-sm font-medium">Type</label>
                        <select name="type" id="type"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Type</option>
                            <option value="all">All Types</option>
                            {{-- @foreach ($types as $type)
                                <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
            </div>

            <!-- Chart View -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Messages Sent Over Time</h2>
                <canvas id="messagesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeSelect = document.getElementById('date-range');
            const studentTab = document.getElementById('student-tab');
            const employeeTab = document.getElementById('employee-tab');
            const studentFilters = document.getElementById('student-filters');
            const employeeFilters = document.getElementById('employee-filters');

            studentTab.addEventListener('click', function() {
                studentFilters.classList.remove('hidden');
                employeeFilters.classList.add('hidden');
                studentTab.classList.add('bg-blue-500', 'text-white');
                studentTab.classList.remove('bg-gray-300', 'text-gray-800');
                employeeTab.classList.add('bg-gray-300', 'text-gray-800');
                employeeTab.classList.remove('bg-blue-500', 'text-white');
            });

            employeeTab.addEventListener('click', function() {
                employeeFilters.classList.remove('hidden');
                studentFilters.classList.add('hidden');
                employeeTab.classList.add('bg-blue-500', 'text-white');
                employeeTab.classList.remove('bg-gray-300', 'text-gray-800');
                studentTab.classList.add('bg-gray-300', 'text-gray-800');
                studentTab.classList.remove('bg-blue-500', 'text-white');
            });

            function updateAnalytics() {
                const dateRange = dateRangeSelect.value;

                fetch(`/api/analytics?date_range=${dateRange}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update the numbers
                        document.getElementById('total-sent').textContent = data.total_sent;
                        document.getElementById('total-failed').textContent = data.total_failed;
                        document.getElementById('total-scheduled').textContent = data.total_scheduled;
                        document.getElementById('total-immediate').textContent = data.total_immediate;
                        document.getElementById('remaining-balance').textContent = data.balance;

                        // Update the chart
                        updateChart(data.chart_data);
                    })
                    .catch(error => console.error('Error fetching analytics data:', error));
            }

            const ctx = document.getElementById('messagesChart').getContext('2d');
            let messagesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Messages Sent',
                        data: [],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'category',
                            time: {
                                unit: 'day'
                            },
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            function updateChart(chartData) {
                messagesChart.data.labels = chartData.labels;
                messagesChart.data.datasets[0].data = chartData.data;
                messagesChart.update();
            }

            // Fetch data on load
            updateAnalytics();

            // Fetch data on date range change
            dateRangeSelect.addEventListener('change', updateAnalytics);
        });
    </script>

@endsection
