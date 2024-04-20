<x-app-layout :meta-title="$event->meta_title ?: $event->title" :meta-description="$event->meta_description">
    <div class="flex">
        <!-- Post Section -->
        <section class="w-full md:w-2/3 flex flex-col px-3">

            <article class="flex flex-col shadow my-4">
                <!-- Article Image -->
                <a href="#" class="hover:opacity-75">
                    <img src="{{$event->getThumbnail()}}" class="w-full">
                </a>
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
                    <!-- Event and Register Date and Time -->
                    <p class="text-sm mb-2">
                        <span class="font-semibold">
                            <i class="far fa-calendar-alt mr-1"></i>
                            Event Date and Time: </span>
                        {{$event->start_time}} | {{ $event->end_time }}
                    </p>
                    <p class="text-sm mb-2">
                        <span class="font-semibold">
                            <i class="far fa-calendar-alt mr-1"></i>
                            Register Date and Time:  </span>
                        {{$event->register_start_time}} | {{ $event->register_end_time }}
                    </p>
                    <p class="text-sm text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            {!! $event->extra_info !!}
                        </p>
                    <!-- Max User -->
                    <p class="text-sm mb-4">
                        <span class="font-semibold">
                            <i class="fas fa-users mr-1"></i>
                            Max User:
                        </span>
                        {{$event->maxUser}}
                    </p>
                    <!-- Description and Extra Info -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 mb-2">
                            {!! $event->description !!}
                        </p>
                        
                    </div>
                    <!-- Upvote and Downvote Component -->
                    <livewire:upvote-downvote :event="$event"/>
                    <!-- Join Event Button -->
                    <div class="flex justify-end">
                        @auth
                            @if(auth()->user()->hasRole('Super Admin'))
                                @php
                                    $registerStartDateTime = \Carbon\Carbon::parse($event->register_start_time);
                                    $registerEndDateTime = \Carbon\Carbon::parse($event->register_end_time);
                                    $currentDateTime = now(); // Get the current date and time
                                    $userJoined = $event->attendees()->where('user_id', auth()->user()->id)->exists();
                                @endphp
                                @if($currentDateTime >= $registerStartDateTime && $currentDateTime <= $registerEndDateTime)
                                    @if($userJoined)
                                        <span class="bg-blue-300 text-gray-600 font-bold py-2 px-4 rounded border border-blue-500 cursor-not-allowed">
                                            Joined
                                        </span>
                                    @else
                                        <a id="joinBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Join Event
                                        </a>
                                    @endif
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
                            <p class="text-lg text-blue-800 font-bold flex items-center justify-end">Next
                                <i
                                    class="fas fa-arrow-right pl-1"></i></p>
                            <p class="pt-2">
                                {{\Illuminate\Support\Str::words($next->title, 5)}}
                            </p>
                        </a>
                    @endif
                </div>
            </div>

            <livewire:comments :event="$event"/>
        </section>

        
    </div>
    
    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md">
            
            <h2 class="text-2xl font-bold mb-4">Join Event - {{ $event->title }}</h2>
            <!-- Your form fields here -->
            <form action="{{ route('joinEvent') }}" method="POST" class="flex flex-col">
                @csrf
                <!-- Hidden input field for event_id -->
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="required_transport" value=0>
                <input type="hidden" name="qrcode" value='null'>
                <input type="hidden" name="attended" value=0>
                <input type="hidden" name="approved" value=0>
                <input type="hidden" name="status" value=1>
                <select name="gender" id="gender" class="form-group flex flex-col md:flex-row" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value=1>Male</option>
                    <option value=2>Female</option>
                </select>
                
                <!-- Other form fields -->
                <div class="form-group flex flex-col md:flex-row">
                    <input type="tel" name="mobile_no" id="contact_no" placeholder="Contact No" value="{{ $attendee->mobile_no ?? '' }}" required>
                </div>
            
                <div class="form-group flex flex-col md:flex-row">
                    <input type="email" name="email" id="email" placeholder="Email" value="{{ $attendee->email ?? '' }}" required>
                </div>
            
                <div class="form-group flex flex-col md:flex-row">
                    <input type="text" name="addr_line_1" id="addr_line_1" placeholder="Address Line 1" value="{{ $attendee->addr_line_1 ?? '' }}" required>
                </div>
                
                <div class="form-group flex flex-col md:flex-row">
                    <input type="text" name="addr_line_2" id="addr_line_2" placeholder="Address Line 2" value="{{ $attendee->addr_line_2 ?? '' }}" required>
                </div>
            
                <div class="form-group flex flex-col md:flex-row">
                    <input type="number" name="postcode" id="postcode" placeholder="Postcode" value="{{ $attendee->postcode ?? '' }}" required>
                </div>
                
                <div class="form-group flex flex-col md:flex-row">
                    <input type="text" name="city" id="city" placeholder="City" value="{{ $attendee->city ?? '' }}" required>
                </div>    
            
                <div class="form-group flex flex-col md:flex-row">
                    <input type="text" name="state" id="state" placeholder="State" value="{{ $attendee->state ?? '' }}" required>
                </div>
                    
                <div class="form-group flex flex-col md:flex-row">
                    <input type="text" name="country" id="country" placeholder="Country" value="{{ $attendee->country ?? '' }}" required>
                </div>
            
                <div class="flex justify-between pt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Confirm
                    </button>
                    <button id="cancelBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to handle modal visibility -->
    <script>
        const modal = document.getElementById('modal');
        const joinBtn = document.getElementById('joinBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        joinBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    </script>
    
</x-app-layout>
