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
                        data-value="importData">
                        IMPORT OPTIONS
                    </button>
                </div>
            </div>

            <!-- Hidden Input to Store Selected Tab -->
            <input type="hidden" name="selected_tab" id="selected_tab" value="contacts">

            <!-- Contacts Tab -->
            <div id="contacts" class="tab-content">
                <!-- Filters Selection -->
                <div class="grid grid-cols-12 gap-4 mb-4">
                    <!-- Search Contacts (Spans 5 out of 12 columns) -->
                    <div class="col-span-5">
                        <label for="contactsSearch" class="block text-sm font-medium text-gray-700">Search Contacts</label>
                        <input type="text" id="contactsSearch" placeholder="Search for contacts..."
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <!-- Select Campus (Spans 3 out of 12 columns) -->
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

                    <!-- Filter By (Spans 3 out of 12 columns) -->
                    <div class="col-span-3 mr-3">
                        <label for="filter" class="block text-sm font-medium text-gray-700">Filter By</label>
                        <select name="filter" id="filter"
                            class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="all">All Contacts</option>
                            <option value="students">Students</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>

                    <!-- Import Button (Spans 1 out of 12 columns, Aligned Right with Padding Adjusted) -->
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
                                <!-- Add Action Column -->
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
                <!-- Add Message Template Button -->
                <div class="mb-4 text-right">
                    <a href="{{ route('message_templates.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out">
                        Add New Template
                    </a>
                </div>

                <!-- Message Templates Table -->
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
                                            <!-- Edit Button with Icon -->
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

                                            <!-- Delete Button with Icon -->
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
                    <!-- Include the Modal Component -->
                    <x-modal modal-id="messageContentModal" title="Announcement" content="Exciting News!"></x-modal>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for Editing Contact -->
    <div id="editContactModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div
                class="inline-block overflow-hidden transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 py-4 bg-white">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Edit Contact Number</h3>
                    <div class="mt-2">
                        <label for="editContactInput" class="block text-sm font-medium text-gray-700">New Contact
                            Number</label>
                        <input type="text" id="editContactInput"
                            class="block w-full px-4 py-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <input type="hidden" id="editContactEmail" value="">
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:flex sm:flex-row-reverse">
                    <button type="button" id="saveContactBtn"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button type="button" id="cancelContactBtn"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Data Tab -->
    <div id="importData" class="tab-content hidden">
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <!-- New Section Title for Categorization -->
                <h3 class="text-lg font-bold mb-6">Student Import Options</h3>

                <!-- Vertically Aligned Import Buttons -->
                <div class="space-y-4">
                    <button type="button" onclick="openModal('collegeImportModal')"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out w-full">
                        Import College
                    </button>

                    <button type="button" onclick="openModal('programImportModal')"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out w-full">
                        Import Program
                    </button>

                    <button type="button" onclick="openModal('majorImportModal')"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out w-full">
                        Import Major
                    </button>

                    <button type="button" onclick="openModal('yearImportModal')"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out w-full">
                        Import Year
                    </button>

                    <!-- New Import Students Button -->
                    <button type="button" onclick="openModal('studentImportModal')"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out w-full">
                        Import Students
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- College Import Modal -->
    <div id="collegeImportModal"
        class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3">
            <div class="p-4">
                <h3 class="text-lg font-bold mb-4">Import Colleges</h3>
                <label for="campusSelectCollege" class="block text-sm font-medium text-gray-700">Select Campus</label>
                <select id="campusSelectCollege" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-4">
                    <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                        onclick="closeModal('collegeImportModal')">Close</button>
                    <button id="importCollegeBtn" class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
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
                <label for="campusSelectProgram" class="block text-sm font-medium text-gray-700">Select Campus</label>
                <select id="campusSelectProgram" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-4">
                    <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                        onclick="closeModal('programImportModal')">Close</button>
                    <button id="importProgramBtn" class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
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
                <label for="campusSelectMajor" class="block text-sm font-medium text-gray-700">Select Campus</label>
                <select id="campusSelectMajor" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-4">
                    <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                        onclick="closeModal('majorImportModal')">Close</button>
                    <button id="importMajorBtn" class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
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
                    <button id="importYearBtn" class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Import Modal -->
    <div id="studentImportModal"
        class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3">
            <div class="p-4">
                <h3 class="text-lg font-bold mb-4">Import Students</h3>
                <label for="campusSelectStudent" class="block text-sm font-medium text-gray-700">Select Campus</label>
                <select id="campusSelectStudent" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end mt-4">
                    <button class="bg-gray-400 px-4 py-2 rounded-lg mr-2"
                        onclick="closeModal('studentImportModal')">Close</button>
                    <button id="importStudentBtn" class="bg-green-500 px-4 py-2 text-white rounded-lg">Import</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>

    <!-- Script Section -->
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

        // Handle imports for Students
        document.getElementById('importStudentBtn').addEventListener('click', function() {
            const campusId = document.getElementById('campusSelectStudent').value;

            fetch('{{ route('import.students') }}', {
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
                        closeModal('studentImportModal');
                    } else if (data.error) {
                        alert('Import failed: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>


    @vite(['resources/js/app.css', 'resources/js/app-management.js', 'resources/js/searchMessageLogs.js', 'resources/js/modal.js'])
@endsection
