@php
    $period = collect();
    $attendee = null;
    $userJoined = false;
    $userCanJoin = false;

    if (auth()->check() && auth()->user()->hasRole('User')) {
        $startDate = \Carbon\Carbon::parse($event->start_datetime)->startOfDay();
        $endDate = \Carbon\Carbon::parse($event->end_datetime)->startOfDay();
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
    }
@endphp

<x-app-layout :meta-title="$event->meta_title ?: $event->title" :meta-description="$event->introduction">
    <div class="flex">
        <!-- Post Section -->
        <section class="w-full md:w-2/3 flex flex-col px-3">

            <article class="flex flex-col shadow my-4">
                <!-- Article Image -->
                <img src="{{$event->getThumbnail()}}" class="w-full">
                <div class="bg-white flex flex-col justify-start p-6">
                    <!-- Category -->
                    <div class="text-gray-600 mb-2">
                        {{$event->category}}
                    </div>
                    <!-- Title and Price -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-3xl font-bold hover:text-gray-700">
                            {{$event->title}}
                        </h1>
                        <h1 class="text-3xl text-blue-600 font-bold">
                            {{ $event->price !== null ? $event->price . ' Points' : '0 Point' }}
                        </h1>
                    </div>
                    <!-- Author -->
                    <p class="text-sm mb-2">
                        By <a href="#" class="font-semibold hover:text-gray-800">{{$event->user->name}}</a>
                    </p>
                    <!-- Location -->
                    <p class="text-sm mb-2">
                        @if($event->online == 1)
                            <i class="fas fa-external-link-alt mr-1"></i>
                            <span class="font-semibold">URL:</span>
                            <a href="{{$event->url}}" class="text-blue-500 hover:underline" target="_blank">{{$event->url}}</a>
                        @else
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <span class="font-semibold">Location:</span> {{$event->location}}
                        @endif
                    </p>
                    <!-- Event Date and Time -->
                    <p class="text-sm mb-2">
                        <span class="font-semibold">
                            <i class="far fa-calendar-alt mr-1"></i>
                            Event Date and Time:
                        </span>
                        {{$event->start_datetime}} | {{ $event->end_datetime }}
                    </p>
                    <!-- Max User -->
                    <p class="text-sm mb-4">
                        <span class="font-semibold">
                            <i class="fas fa-users mr-1"></i>
                            Max User:
                        </span>
                        {{$event->max_user}}
                    </p>
                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 mb-2">
                            {!! $event->introduction !!}
                        </p>
                    </div>
                    <!-- Upvote/Downvote and Bookmark -->
                    <div class="flex">
                        <div class="mr-4">
                            <livewire:upvote-downvote :event="$event"/>
                        </div>
                        <div>
                            <livewire:bookmark :event="$event"/>
                        </div>
                    </div>
                    <!-- Join Event Button -->
                    <div class="flex justify-end mt-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}&quote={{ urlencode($event->title) }}"
                           target="_blank"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center mr-2">
                           <i class="fab fa-facebook mr-2"></i>
                           Share
                        </a>

                        @auth
                            @if(auth()->user()->hasRole('User'))
                                @php
                                    $registerStartDateTime = \Carbon\Carbon::parse($event->start_datetime);
                                    $registerEndDateTime = \Carbon\Carbon::parse($event->end_datetime);
                                    $currentDateTime = now();
                                    $userJoined = $event->attendees()->where('user_id', auth()->user()->id)->exists();
                                    $userPoints = auth()->user()->total_points;
                                    $eventPrice = $event->price;
                                    $userCanJoin = $userPoints >= $eventPrice;
                                @endphp

                                @if($userJoined)
                                    <a id="showModalBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                                        Joined
                                    </a>
                                @elseif($userCanJoin)
                                    <a id="joinBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer">
                                        Join Event
                                    </a>
                                @else
                                    <span class="bg-red-300 text-gray-600 font-bold py-2 px-4 rounded border border-red-500 cursor-not-allowed">
                                        Not Enough Points
                                    </span>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            </article>

            <div class="w-full flex pt-6">
                <div class="w-1/2">
                    @if($prev)
                        <a href="{{route('view', $prev)}}"
                           class="block w-full bg-white shadow hover:shadow-md text-left p-6">
                            <p class="text-lg text-blue-800 font-bold flex items-center">
                                <i class="fas fa-arrow-left pr-1"></i>
                                Previous
                            </p>
                            <p class="pt-2">{{\Illuminate\Support\Str::words($prev->title, 5)}}</p>
                        </a>
                    @endif
                </div>
                <div class="w-1/2">
                    @if($next)
                        <a href="{{route('view', $next)}}"
                           class="block w-full bg-white shadow hover:shadow-md text-right p-6">
                            <p class="text-lg text-blue-800 font-bold flex items-center justify-end">
                                Next
                                <i class="fas fa-arrow-right pl-1"></i>
                            </p>
                            <p class="pt-2">
                                {{\Illuminate\Support\Str::words($next->title, 5)}}
                            </p>
                        </a>
                    @endif
                </div>
            </div>

            @php
                $comment_enabled = $event->comment_enabled;
            @endphp
            @if($comment_enabled == 1)
                <livewire:comments :event="$event"/>
            @endif
        </section>
    </div>

    @auth
        @if(auth()->user()->hasRole('User'))
            <!-- Join Modal -->
            <div id="modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
                <div class="bg-white p-8 rounded shadow-md w-full max-w-lg my-8 mx-auto">
                    <h2 class="text-2xl font-bold mb-4">Join Event - {{ $event->title }}</h2>
                    <form id="join-event-form" class="flex flex-col">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="required_transport" value="0">
                        <input type="hidden" name="qrcode" value="null">
                        <input type="hidden" name="attended" value="0">
                        <input type="hidden" name="approved" value="0">
                        <input type="hidden" name="status" value="1">
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <label class="font-semibold mb-1">Select Event Date</label>
                            <select name="selected_date" id="selected_date" required class="border p-2 rounded">
                                <option value="">-- Select a date --</option>
                                @foreach ($period as $date)
                                    <option value="{{ $date->format('Y-m-d') }}">
                                        {{ $date->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <select name="gender" id="gender" class="form-group flex flex-col md:flex-row mb-3" required>
                            <option value="">Select Gender</option>
                            <option value="1" {{ !is_null($attendee) && $attendee->gender == '1' ? 'selected' : '' }}>Male</option>
                            <option value="2" {{ !is_null($attendee) && $attendee->gender == '2' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="tel" name="mobile_no" id="contact_no" placeholder="Contact No" value="{{ $attendee->mobile_no ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="email" name="email" placeholder="Email" value="{{ $attendee->email ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="text" name="addr_line_1" id="addr_line_1" placeholder="Address Line 1" value="{{ $attendee->addr_line_1 ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="text" name="addr_line_2" id="addr_line_2" placeholder="Address Line 2" value="{{ $attendee->addr_line_2 ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="number" name="postcode" id="postcode" placeholder="Postcode" value="{{ $attendee->postcode ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="text" name="city" id="city" placeholder="City" value="{{ $attendee->city ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="text" name="state" id="state" placeholder="State" value="{{ $attendee->state ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div class="form-group flex flex-col md:flex-row mb-3">
                            <input type="text" name="country" id="country" placeholder="Country" value="{{ $attendee->country ?? '' }}" required class="border p-2 rounded">
                        </div>
                        <div id="error-container" class="text-red-500"></div>
                        <div class="flex justify-between pt-4">
                            <button id="joinSubmitBtn" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Confirm
                                <span id="loadingSpinner" class="hidden ml-2">⏳ Loading...</span>
                            </button>
                            <button id="cancelBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Unjoin Modal -->
            <div id="modal2" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                <div class="bg-white p-8 rounded shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Unjoin Event - {{ $event->title }}</h2>
                    <p>Are you sure you want to unjoin this event?</p>
                    <div class="flex justify-between pt-4">
                        <form id="unjoinForm" action="{{ route('unjoinEvent', ['price' => $event->price]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Confirm Unjoin
                                <span id="unjoinLoadingSpinner" class="hidden ml-2">⏳ Loading...</span>
                            </button>
                        </form>
                        <button id="cancelBtn2" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Unjoin modal open
                    const showModalBtn = document.getElementById('showModalBtn');
                    if (showModalBtn) {
                        showModalBtn.addEventListener('click', function () {
                            document.getElementById('modal2').classList.remove('hidden');
                        });
                    }

                    // Join modal open
                    const joinBtn = document.getElementById('joinBtn');
                    if (joinBtn) {
                        joinBtn.addEventListener('click', function () {
                            document.getElementById('modal').classList.remove('hidden');
                        });
                    }

                    // Cancel buttons
                    const cancelBtn = document.getElementById('cancelBtn');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', function () {
                            document.getElementById('modal').classList.add('hidden');
                        });
                    }

                    const cancelBtn2 = document.getElementById('cancelBtn2');
                    if (cancelBtn2) {
                        cancelBtn2.addEventListener('click', function () {
                            document.getElementById('modal2').classList.add('hidden');
                        });
                    }
                });

                $(document).ready(function () {
                    // Join form AJAX
                    $('#join-event-form').submit(function (event) {
                        event.preventDefault();
                        $('#joinSubmitBtn').prop('disabled', true);
                        $('#loadingSpinner').removeClass('hidden');

                        var formData = $(this).serialize();

                        $.ajax({
                            type: 'POST',
                            url: '{{ route("joinEvent", ["price" => $event->price]) }}',
                            data: formData,
                            headers: {
                                'Authorization': 'Bearer 10|SHpp3nPLRuBJqQG7cFhV4vGU6a5nITkSqASwCk17f54cf611',
                                'Accept': 'application/json'
                            },
                            success: function (response) {
                                window.location.reload();
                            },
                            error: function (response) {
                                var errorContainer = $('#error-container');
                                errorContainer.empty();
                                if (response.status === 422) {
                                    var errors = response.responseJSON.errors;
                                    if (errors && errors.mobile_no) {
                                        errorContainer.append('<p class="text-red-500">' + errors.mobile_no[0] + '</p>');
                                    } else {
                                        errorContainer.append('<p class="text-red-500">Mobile number is already in use.</p>');
                                    }
                                } else {
                                    errorContainer.append('<p>Something went wrong. Please try again later.</p>');
                                }
                            },
                            complete: function () {
                                $('#joinSubmitBtn').prop('disabled', false);
                                $('#loadingSpinner').addClass('hidden');
                            }
                        });
                    });

                    // Unjoin form AJAX
                    $('#unjoinForm').submit(function (event) {
                        event.preventDefault();
                        $('#unjoinForm button[type="submit"]').prop('disabled', true);
                        $('#unjoinLoadingSpinner').removeClass('hidden');

                        var formData = $(this).serialize();

                        $.ajax({
                            type: 'POST',
                            url: $(this).attr('action'),
                            data: formData,
                            headers: {
                                'Authorization': 'Bearer 10|SHpp3nPLRuBJqQG7cFhV4vGU6a5nITkSqASwCk17f54cf611',
                                'Accept': 'application/json'
                            },
                            success: function (response) {
                                window.location.reload();
                            },
                            error: function (response) {
                                alert('Something went wrong. Please try again.');
                            },
                            complete: function () {
                                $('#unjoinForm button[type="submit"]').prop('disabled', false);
                                $('#unjoinLoadingSpinner').addClass('hidden');
                            }
                        });
                    });
                });
            </script>
        @endif
    @endauth

</x-app-layout>
