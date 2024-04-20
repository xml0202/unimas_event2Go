<x-app-layout>
    <div class="container mx-auto flex flex-wrap py-6">

        <!-- Events Section -->
        <section class="w-full md:w-2/3  px-3">
            <div class=" flex flex-col">
                @foreach($events as $event)
                    <div>
                        <a href="{{route('view', $event)}}">
                            <h2 class="text-blue-500 font-bold text-lg sm:text-xl mb-2">
                                {!! str_replace(request()->get('q'), '<span class="bg-yellow-300">'.request()->get('q').'</span>', $event->title) !!}
                            </h2>
                        </a>
                        <div>
                            <a href="{{route('view', $event)}}" class="hover:opacity-75">
                                <img src="{{$event->getThumbnail()}}">
                            </a>
                            {{$event->shortBody()}}
                        </div>
                    </div>
                    <hr class="my-4">
                @endforeach
            </div>
            {{ $events->links() }}
        </section>

        <!-- Sidebar Section -->
        <x-sidebar/>

    </div>
</x-app-layout>
