@extends('layouts.admin')

@section('title', 'App Management')

@section('content')

    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg">

            <!-- Tabs -->
            <div class="mb-4">
                <div class="flex border-b border-gray-300">
                    <button type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 focus:outline-none"
                        data-value="contacts">
                        CONTACTS
                    </button>
                    <button type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 focus:outline-none"
                        data-value="messageTemplates">
                        MESSAGE TEMPLATES
                    </button>
                    <button type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 focus:outline-none"
                        data-value="messageLogs">
                        MESSAGE LOGS
                    </button>
                    <button type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 focus:outline-none"
                        data-value="importData">
                        IMPORT DATA
                    </button>
                </div>
            </div>

            <!-- Hidden Input to Store Selected Tab -->
            <input type="hidden" name="selected_tab" id="selected_tab" value="contacts">

            <!-- Contacts Tab -->
            <div id="contacts" class="tab-content">
                <!-- Filters Selection -->
                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-5">
                        <label for="contactsSearch" class="block text-sm font-medium text-gray-700">Search Contacts</label>
                        <input type="text" id="contactsSearch" placeholder="Search for contacts..."
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="col-span-3">
                        <label for="campus" class="block text-sm font-medium text-gray-700">Select Campus</label>
                        <select name="campus" id="campus"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="all">All Campuses</option>
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label for="filter" class="block text-sm font-medium text-gray-700">Filter By</label>
                        <select name="filter" id="filter"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="all">All Contacts</option>
                            <option value="students">Students</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>

                    <div class="col-span-1 flex justify-end items-center">
                        <button type="button"
                            class="bg-green-500 py-2 px-4 mt-5 text-white font-bold rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg hover:text-gray-100">
                            Import
                        </button>
                    </div>
                </div>

                <!-- Contacts Table -->
                <div class="overflow-x-auto overflow-y-auto max-h-96 mb-8">
                    <table id="contactsTable" class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">First Name</th>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">Last Name</th>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">Middle Name</th>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">Contact</th>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">Email</th>
                                <th class="py-3 px-4 border-b font-semibold text-gray-500 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="contactsTableBody">
                            <!-- Rows will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Message Templates Tab -->
            <div id="messageTemplates" class="tab-content hidden">
                <div class="mb-4 text-right">
                    <a href="{{ route('message_templates.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out">
                        Add New Template
                    </a>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-96 mb-8">
                    <table id="messageTemplatesTable" class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border-b font-medium text-gray-700 text-left">Template Name</th>
                                <th class="py-3 px-4 border-b font-medium text-gray-700 text-left">Message Content</th>
                                <th class="py-3 px-4 border-b font-medium text-gray-700 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messageTemplates as $template)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="py-3 px-4 border-b text-gray-600">{{ $template->name }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 text-left">
                                        {{ \Illuminate\Support\Str::limit($template->content, 70, '...') }}
                                        @if (strlen($template->content) > 70)
                                            <a href="#" class="text-blue-500 hover:underline"
                                                data-modal-target="#messageContentModal"
                                                data-template-name="{{ $template->name }}"
                                                data-content="{{ $template->content }}">
                                                Read More
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 border-b text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('message_templates.edit', $template->id) }}"
                                                method="GET" class="inline">
                                                <button type="submit" class="focus:outline-none">
                                                    <div class="rounded-full bg-blue-500 p-2 hover:bg-blue-600 flex items-center justify-center"
                                                        title="Edit">
                                                        <img src="{{ asset('images/edit.png') }}" alt="Edit"
                                                            class="h-5 w-5" style="filter: brightness(0) invert(1);">
                                                    </div>
                                                </button>
                                            </form>

                                            <form action="{{ route('message_templates.destroy', $template->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="focus:outline-none">
                                                    <div class="rounded-full bg-red-500 p-2 hover:bg-red-600 flex items-center justify-center"
                                                        title="Delete">
                                                        <img src="{{ asset('images/delete.png') }}" alt="Delete"
                                                            class="h-5 w-5" style="filter: brightness(0) invert(1);">
                                                    </div>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($messageTemplates->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500">No message templates found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Message Logs Tab -->
            <div id="messageLogs" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search Logs</label>
                        <input type="text" id="search" placeholder="Search for logs..."
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                    </div>
                    <div>
                        <label for="recipientType" class="block text-sm font-medium text-gray-700">Filter
                            Recipient</label>
                        <select id="recipientType"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="all" selected>All Recipients</option>
                            <option value="students">Students</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>
                    <div>
                        <label for="messageType" class="block text-sm font-medium text-gray-700">Filter Message
                            Type</label>
                        <select id="messageType"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="all" selected>All Message Types</option>
                            <option value="immediate">Immediate</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-96 mb-8">
                    <table id="messageLogsTable"
                        class="min-w-full bg-white border border-gray-300 rounded-lg divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">User</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Recipient</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Message</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Category</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Created</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Scheduled Date</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Date Sent</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Date Cancelled</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Status</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Total Recipients</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Successful Deliveries
                                </th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Failed Messages</th>
                                <th class="py-3 px-4 border-b font-medium text-left text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messageLogs as $log)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">{{ $log->user->name }}
                                    </td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        {{ $log->recipient_type }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600">{{ $log->content }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">{{ $log->schedule }}
                                    </td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        {{ $log->created_at->format('F j, Y g:i A') }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        {{ $log->scheduled_at ? $log->scheduled_at->format('F j, Y g:i A') : 'N/A' }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        {{ $log->sent_at ? $log->sent_at->format('F j, Y g:i A') : 'N/A' }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        {{ $log->cancelled_at ? $log->cancelled_at->format('F j, Y g:i A') : 'N/A' }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">{{ $log->status }}
                                    </td>
                                    <td class="py-3 px-4 border-b text-gray-600 text-center">{{ $log->total_recipients }}
                                    </td>
                                    <td class="py-3 px-4 border-b text-gray-600 text-center">{{ $log->sent_count }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 text-center">{{ $log->failed_count }}</td>
                                    <td class="py-3 px-4 border-b text-gray-600 whitespace-nowrap">
                                        @if ($log->status === 'Pending')
                                            <form action="{{ route('admin.cancelScheduledMessage', $log->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:underline">
                                                    <div class="rounded-full bg-red-500 p-2 hover:bg-red-600"
                                                        title="Cancel Send">
                                                        <img src="/images/cancel.png" alt="Remove Access" class="h-5 w-5"
                                                            style="filter: brightness(0) invert(1);">
                                                    </div>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            @if ($messageLogs->isEmpty())
                                <tr>
                                    <td colspan="13" class="text-center py-4 text-gray-500">No message logs found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Import Data Tab -->
            <div id="importData" class="tab-content hidden">
                <div class="container mx-auto">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold mb-4">Import Data</h3>

                        <button type="button" onclick="openModal('collegeImportModal')"
                            class="btn btn-primary mb-4">Import College</button>
                        <button type="button" onclick="openModal('programImportModal')"
                            class="btn btn-primary mb-4">Import Program</button>
                        <button type="button" onclick="openModal('majorImportModal')"
                            class="btn btn-primary mb-4">Import Major</button>
                        <button type="button" onclick="openModal('yearImportModal')" class="btn btn-primary mb-4">Import
                            Year</button>
                    </div>
                </div>
            </div>

            <!-- College Import Modal -->
            <div id="collegeImportModal"
                class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-1/3">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-4">Import Colleges</h3>
                        <label for="campusSelectCollege" class="block text-sm font-medium text-gray-700">Select
                            Campus</label>
                        <select id="campusSelectCollege" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end mt-4">
                            <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                                onclick="closeModal('collegeImportModal')">Close</button>
                            <button id="importCollegeBtn"
                                class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Program Import Modal -->
            <div id="programImportModal"
                class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-1/3">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-4">Import Programs</h3>
                        <label for="campusSelectProgram" class="block text-sm font-medium text-gray-700">Select
                            Campus</label>
                        <select id="campusSelectProgram" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end mt-4">
                            <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                                onclick="closeModal('programImportModal')">Close</button>
                            <button id="importProgramBtn"
                                class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Major Import Modal -->
            <div id="majorImportModal"
                class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-1/3">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-4">Import Majors</h3>
                        <label for="campusSelectMajor" class="block text-sm font-medium text-gray-700">Select
                            Campus</label>
                        <select id="campusSelectMajor" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end mt-4">
                            <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                                onclick="closeModal('majorImportModal')">Close</button>
                            <button id="importMajorBtn"
                                class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Import Modal -->
            <div id="yearImportModal"
                class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-1/3">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-4">Import Years</h3>
                        <div class="flex justify-end mt-4">
                            <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                                onclick="closeModal('yearImportModal')">Close</button>
                            <button id="importYearBtn"
                                class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <script>
                // Tab navigation logic
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const value = button.getAttribute('data-value');
                        document.getElementById('selected_tab').value = value;

                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                        });

                        document.getElementById(value).classList.remove('hidden');
                    });
                });

                // Modal open/close logic
                function openModal(modalId) {
                    document.getElementById(modalId).classList.remove('hidden');
                }

                function closeModal(modalId) {
                    document.getElementById(modalId).classList.add('hidden');
                }

                // Handle imports for College
                document.getElementById('importCollegeBtn').addEventListener('click', function() {
                    const campusId = document.getElementById('campusSelectCollege').value;

                    fetch('{{ route('import.college') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                campus_id: campusId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeModal('collegeImportModal');
                            } else if (data.error) {
                                alert('Import failed: ' + data.error);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Handle imports for Program
                document.getElementById('importProgramBtn').addEventListener('click', function() {
                    const campusId = document.getElementById('campusSelectProgram').value;

                    fetch('{{ route('import.program') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                campus_id: campusId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeModal('programImportModal');
                            } else if (data.error) {
                                alert('Import failed: ' + data.error);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Handle imports for Major
                document.getElementById('importMajorBtn').addEventListener('click', function() {
                    const campusId = document.getElementById('campusSelectMajor').value;

                    fetch('{{ route('import.major') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                campus_id: campusId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeModal('majorImportModal');
                            } else if (data.error) {
                                alert('Import failed: ' + data.error);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });

                // Handle imports for Year
                document.getElementById('importYearBtn').addEventListener('click', function() {
                    fetch('{{ route('import.year') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                closeModal('yearImportModal');
                            } else if (data.error) {
                                alert('Import failed: ' + data.error);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>

            @vite(['resources/js/app.css', 'resources/js/app-management.js', 'resources/js/searchMessageLogs.js', 'resources/js/modal.js'])
        @endsection
