<x-app-layout meta-title="Events"
              meta-description="Events">
    <div class="container max-w-4xl mx-auto py-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Latest Event -->
            <div class="col-span-2">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Latest Event
                </h2>

                @if ($latestEvent)
                    <x-post-item :event="$latestEvent"/>
                @endif
            </div>

            <!-- Popular 3 event -->
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Popular Events
                </h2>
                @foreach($popularEvents as $event)
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <a href="{{route('view', $event)}}" class="pt-1">
                            <img src="{{$event->getThumbnail()}}" alt="{{$event->title}}"/>
                        </a>
                        <div class="col-span-3">
                            <a href="{{route('view', $event)}}">
                                <h3 class="text-sm uppercase whitespace-nowrap truncate">{{$event->title}}</h3>
                            </a>
                            <div class="flex gap-4 mb-2">
                                
                            </div>
                            <div class="text-xs">
                                {{$event->shortBody(10)}}
                            </div>
                            <a href="{{route('view', $event)}}" class="text-xs uppercase text-gray-800 hover:text-black">View Detail <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recommended events -->
        <div class="mb-8">
            <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                Recommended Events
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($recommendedEvents as $event)
                    <x-post-item :event="$event" :show-author="false"/>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
