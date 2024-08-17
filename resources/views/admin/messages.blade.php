@extends('layouts.admin')

@section('title', 'Broadcast Messages')

@section('content')
<!-- Display Success or Error Messages -->
@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Broadcasting Form -->
    <form action="{{ route('admin.reviewMessage') }}" method="POST">
        @csrf

        <!-- Broadcast Type Selection as Tabs -->
        <div class="mb-4">
            <div class="flex border-b border-gray-300">
                <button type="button" class="tab-button px-4 py-2 text-sm font-medium focus:outline-none"
                    data-value="all">ALL</button>
                <button type="button" class="tab-button px-4 py-2 text-sm font-medium focus:outline-none"
                    data-value="students">STUDENTS</button>
                <button type="button" class="tab-button px-4 py-2 text-sm font-medium focus:outline-none"
                    data-value="employees">EMPLOYEES</button>
            </div>
            <input type="hidden" name="broadcast_type" id="broadcast_type"
                value="{{ request('broadcast_type', 'all') }}">
        </div>

        <!-- Filters Container -->
        <div class="mb-4">
            <div class="flex space-x-4 mb-4">

                <!-- Campus Selection (Always Visible) -->
                <div class="flex-grow" id="campus_filter">
                    <label for="campus" class="block text-sm font-medium text-gray-700">Campus</label>
                    <select name="campus" id="campus" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Campus</option>
                        <option value="all">All Campuses</option>
                        @foreach ($campuses as $campus)
                        <option value="{{ $campus->campus_id }}">{{ $campus->campus_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Message Template Selection -->
                <div class="flex-grow">
                    <label for="template" class="block text-sm font-medium text-gray-700">Select Template</label>
                    <select id="template" class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select a Template</option>
                        @foreach ($messageTemplates as $template)
                        <option value="{{ $template->content }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Schedule Options -->
                <!-- <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Schedule</label>
                    <div class="flex items-center mt-2">
                        <input type="radio" id="immediate" name="schedule" value="immediate" checked>
                        <label for="immediate" class="ml-2">Send Immediately</label>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="radio" id="scheduled" name="schedule" value="scheduled">
                        <label for="scheduled" class="ml-2">Schedule for Later</label>
                    </div>
                </div> -->

                <!-- Date and Time Picker for Scheduling -->
                <!-- <div id="schedule-options" style="display: none;" class="mb-4">
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Select Date and Time</label>
                    <input type="datetime-local" id="scheduled_date" name="scheduled_date"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm">
                </div> -->
                <!-- Schedule Options -->
                <div class="flex-grow pl-12 items-center">
                    <label class="block text-sm font-medium text-gray-700">Schedule Message</label>
                    <div class="flex mt-2">
                        <input type="radio" id="immediate" name="schedule" value="immediate" checked>
                        <label for="immediate" class="m-2">Now</label>
                        <input type="radio" id="scheduled" name="schedule" value="scheduled">
                        <label for="scheduled" class="m-2">Later</label>
                    </div>
                </div>

                <!-- Date and Time Picker for Scheduling -->
                <div id="schedule-options" style="display: none;" class="flex-grow">
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Select Date and Time</label>
                    <input type="datetime-local" id="scheduled_date" name="scheduled_date"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                </div>
            </div>
            <!-- End: class flex space-x-4 mb-4 -->

            <!-- Student-specific Filters -->
            <div class="flex space-x-4 mb-4" id="student_filters" style="display: none;">
                <div class="w-1/3">
                    <label for="college" class="block text-sm font-medium text-gray-700">College</label>
                    <select name="college" id="college"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2"
                        onchange="updateProgramDropdown()">
                        <option value="" disabled selected>Select College</option>
                        <option value="all">All Colleges</option>
                    </select>
                </div>

                <div class="w-1/3">
                    <label for="program" class="block text-sm font-medium text-gray-700">Academic Program</label>
                    <select name="program" id="program"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Program</option>
                        <option value="all">All Programs</option>
                    </select>
                </div>

                <div class="w-1/3">
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <select name="year" id="year"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Year</option>
                        <option value="all">All Year Levels</option>
                        @foreach ($years as $year)
                        <option value="{{ $year->year_id }}">{{ $year->year_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Employee-specific Filters -->
            <div class="flex space-x-4 mb-4" id="employee_filters" style="display: none;">
                <div class="w-1/3">
                    <label for="office" class="block text-sm font-medium text-gray-700">Office</label>
                    <select name="office" id="office"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2"
                        onchange="updateTypeDropdown()">
                        <option value="" disabled selected>Select Office</option>
                        <option value="all">All Offices</option>
                    </select>
                </div>

                <div class="w-1/3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2"
                        onchange="updateTypeDropdown()">
                        <option value="" disabled selected>Select Status</option>
                        <option value="all">All Statuses</option>
                    </select>
                </div>

                <div class="w-1/3">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="" disabled selected>Select Type</option>
                        <option value="all">All Types</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- End: Filters Container -->

        <!-- Message Input -->
        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" id="message" placeholder="Enter your message here ..." rows="4"
                class="block w-full mt-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-indigo-300 p-2 text-sm overflow-y-auto resize-none"
                style="height: 14rem">{{ request('message') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Review Message</button>
        </div>
    </form>

    <!-- This loads the script in resources/js -->
    @vite('resources/js/messages.js')
</div>
@endsection