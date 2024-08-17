@extends('layouts.admin')

@section('title', 'Review Message')

@section('content')

<div class="flex items-start justify-between bg-white p-6 rounded-lg shadow-md">
    <div class="w-2/3">
        <!-- Display the filters and message for review -->
        <div class="mb-4">
            <div class="mb-4">
                <label class="block mb-2 text-md font-medium text-gray-700">Sending To</label>
                <p class="border rounded-md p-2">
                    {{ trim($campus) }},
                    @if ($data['broadcast_type'] === 'students' || $data['broadcast_type'] === 'all')
                    {{ trim($filterNames['college']) }},
                    {{ trim($filterNames['program']) }},
                    {{ trim($filterNames['year']) }},
                    @endif
                    @if ($data['broadcast_type'] === 'employees' || $data['broadcast_type'] === 'all')
                    {{ trim($filterNames['office']) }},
                    {{ trim($filterNames['status']) }},
                    {{ trim($filterNames['type']) }},
                    @endif
                </p>
                <small>Total Recipients - 400</small>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-md font-medium text-gray-700">Scheduled For</label>
                <p class="border rounded-md p-2">
                    {{ $data['schedule_type'] === 'immediate' ? 'Now' : \Carbon\Carbon::parse($data['scheduled_at'])->format('F j, Y g:i A') }}
                </p>
            </div>
        </div>

        <!-- Display the message -->
        <div class="mb-4">
            <h2 class="text-xl mb-2 font-semibold">Message</h2>
            <div class="message-content" style="line-height: 1.5;">
                {!! nl2br(e($data['message'])) !!}
            </div>
        </div>

        <!-- Form to confirm and send the message -->
        <form action="{{ route('admin.broadcastToRecipients') }}" method="POST" style="display: inline;">
            @csrf
            <!-- Hidden inputs to pass the original data -->
            <input type="hidden" name="broadcast_type" value="{{ $data['broadcast_type'] }}">
            <input type="hidden" name="campus" value="{{ $data['campus'] }}">
            <input type="hidden" name="message" value="{{ $data['message'] }}">
            <input type="hidden" name="schedule" value="{{ $data['schedule_type'] }}">

            @if ($data['schedule_type'] === 'scheduled')
            <input type="hidden" name="scheduled_date" value="{{ $data['scheduled_at'] }}">
            @endif

            <!-- Include other hidden fields as necessary -->
            @if (isset($data['college']))
            <input type="hidden" name="college" value="{{ $data['college'] }}">
            @endif

            @if (isset($data['program']))
            <input type="hidden" name="program" value="{{ $data['program'] }}">
            @endif

            @if (isset($data['year']))
            <input type="hidden" name="year" value="{{ $data['year'] }}">
            @endif

            @if (isset($data['office']))
            <input type="hidden" name="office" value="{{ $data['office'] }}">
            @endif

            @if (isset($data['status']))
            <input type="hidden" name="status" value="{{ $data['status'] }}">
            @endif

            @if (isset($data['type']))
            <input type="hidden" name="type" value="{{ $data['type'] }}">
            @endif

            <!-- Edit Message Button -->
            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg mr-2">
                <a href="{{ route('admin.messages', $data) }}">Edit Message</a>
            </button>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Confirm and Send</button>
        </form>
    </div>

    <div class="w-1/3 flex justify-end">
        <div class="relative">
            <!-- iPhone Mockup Image -->
            <img src="{{ asset('images/iphone-mockup.png') }}" alt="iPhone Mockup" class="w-full h-auto">

            <!-- User Icon and Name -->
            <div class="absolute top-[12%] left-[15%] w-[70%] h-[10%] flex items-center justify-center space-x-2">
                <!-- User Icon -->
                <img src="{{ asset('images/profile-user.png') }}" alt="User Icon" class="w-6 h-6">
                <!-- User Name -->
                <span class="text-xs font-semibold text-gray-900">USeP</span>
            </div>

            <!-- Message Content -->
            <div class="absolute top-[22%] left-[14%] w-[79%] h-[70%] p-2 text-left bg-transparent overflow-y-auto space-y-1">
                <div class="bg-gray-200 rounded-2xl p-2 text-gray-900" style="font-size: 8px; line-height: 1; max-width: 80%; display: inline-block;">
                    {!! nl2br(e($data['message'])) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection