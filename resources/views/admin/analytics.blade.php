@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md">

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
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

            <!-- Date Range, Campus, and Recipient Type Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="col-span-1">
                    <label for="date-range" class="block text-sm font-medium text-gray-700">Select Date Range:</label>
                    <select id="date-range"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="last_7_days">Last 7 Days</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="last_3_months">Last 3 Months</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="campus" class="block text-sm font-medium text-gray-700">Select Campus:</label>
                    <select id="campus"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>Select Campus</option>
                        <option value="all">All Campuses</option>
                        @foreach ($campuses as $campus)
                            <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1">
                    <label for="recipient-type" class="block text-sm font-medium text-gray-700">Select Recipient Type:</label>
                    <select id="recipient-type"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" disabled selected>Select Recipient Type</option>
                        <option value="student">Student</option>
                        <option value="employee">Employee</option>
                        <option value="both">Both</option>
                    </select>
                </div>
            </div>

            <!-- Conditional Filters Based on Recipient Type -->
            <div id="student-filters" class="mb-4 hidden">
                <h1 class="text-lg text-black font-semibold">Student Filters</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- College Selection -->
                    <div class="col-span-1">
                        <label for="college" class="block text-sm font-medium">College</label>
                        <select name="college" id="college"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2" disabled>
                            <option value="" disabled selected>Select College</option>
                        </select>
                    </div>

                    <!-- Program Selection -->
                    <div class="col-span-1">
                        <label for="program" class="block text-sm font-medium">Academic Program</label>
                        <select name="program" id="program"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2" disabled>
                            <option value="" disabled selected>Select Program</option>
                        </select>
                    </div>

                    <!-- Year Level Selection -->
                    <div class="col-span-1">
                        <label for="year" class="block text-sm font-medium">Year Level</label>
                        <select name="year" id="year"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->year_id }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="employee-filters" class="mb-4 hidden">
                <h1 class="text-lg text-black font-semibold">Employee Filters</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Office Selection -->
                    <div class="col-span-1">
                        <label for="office" class="block text-sm font-medium">Office</label>
                        <select name="office" id="office"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Office</option>
                            <option value="all">All Offices</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->office_id }}">{{ $office->office_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Selection -->
                    <div class="col-span-1">
                        <label for="status" class="block text-sm font-medium">Status</label>
                        <select name="status" id="status"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Status</option>
                            <option value="all">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Selection -->
                    <div class="col-span-1">
                        <label for="type" class="block text-sm font-medium">Type</label>
                        <select name="type" id="type"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="" disabled selected>Select Type</option>
                            <option value="all">All Types</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Chart View -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Messages Sent Over Time</h2>
                <canvas id="messagesChart" height="100"></canvas>
                <button class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-md">Export Chart</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeSelect = document.getElementById('date-range');
            const recipientTypeSelect = document.getElementById('recipient-type');
            const studentFilters = document.getElementById('student-filters');
            const employeeFilters = document.getElementById('employee-filters');
            const campusSelect = document.getElementById('campus');
            const collegeSelect = document.getElementById('college');
            const programSelect = document.getElementById('program');
            const yearSelect = document.getElementById('year');
            const officeSelect = document.getElementById('office');
            const statusSelect = document.getElementById('status');
            const typeSelect = document.getElementById('type');

            recipientTypeSelect.addEventListener('change', function() {
                const recipientType = recipientTypeSelect.value;

                if (recipientType === 'student') {
                    studentFilters.classList.remove('hidden');
                    employeeFilters.classList.add('hidden');
                } else if (recipientType === 'employee') {
                    studentFilters.classList.add('hidden');
                    employeeFilters.classList.remove('hidden');
                } else if (recipientType === 'both') {
                    studentFilters.classList.remove('hidden');
                    employeeFilters.classList.remove('hidden');
                }
            });

            campusSelect.addEventListener('change', function() {
                const campusId = this.value;
                if (campusId) {
                    fetch(`/analytics/colleges?campus_id=${campusId}`)
                        .then(response => response.json())
                        .then(data => {
                            collegeSelect.innerHTML = '<option value="" disabled selected>Select College</option>';
                            data.forEach(college => {
                                collegeSelect.innerHTML += `<option value="${college.college_id}">${college.college_name}</option>`;
                            });
                            collegeSelect.disabled = false;
                        })
                        .catch(error => console.error('Error fetching colleges:', error));
                }
            });

            collegeSelect.addEventListener('change', function() {
                const collegeId = this.value;
                if (collegeId) {
                    fetch(`/analytics/programs?college_id=${collegeId}`)
                        .then(response => response.json())
                        .then(data => {
                            programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';
                            data.forEach(program => {
                                programSelect.innerHTML += `<option value="${program.program_id}">${program.program_name}</option>`;
                            });
                            programSelect.disabled = false;
                        })
                        .catch(error => console.error('Error fetching programs:', error));
                }
            });

            // Fetch all years on page load
            fetch('/analytics/years')
                .then(response => response.json())
                .then(data => {
                    yearSelect.innerHTML = '<option value="" disabled selected>Select Year</option>';
                    data.forEach(year => {
                        yearSelect.innerHTML += `<option value="${year.year_id}">${year.year_name}</option>`;
                    });
                })
                .catch(error => console.error('Error fetching years:', error));

            function updateAnalytics() {
                const dateRange = dateRangeSelect.value;
                const recipientType = recipientTypeSelect.value;

                fetch(`/api/analytics?date_range=${dateRange}&recipient_type=${recipientType}`)
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

            // Fetch data on date range and recipient type change
            dateRangeSelect.addEventListener('change', updateAnalytics);
            recipientTypeSelect.addEventListener('change', updateAnalytics);
        });
    </script>

@endsection
