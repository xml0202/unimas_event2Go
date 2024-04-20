<article class="bg-white    flex flex-col shadow my-4">
    <!-- Article Image -->
    
    <div class="bg-white flex flex-col justify-start p-6">
        <div class="flex gap-4">
            
        </div>
        <a href="{{route('view', $event)}}" class="text-3xl font-bold hover:text-gray-700 pb-4">
            {{$event->title}}
        </a>
        @if ($showAuthor)
            <p href="#" class="text-sm pb-3">
                By <a href="#" class="font-semibold hover:text-gray-800">{{$event->user->name}}</a>
            </p>
        @endif
        <a href="{{route('view', $event)}}" class="pb-6">
            {{$event->shortBody()}}
        </a>
        <a  href="{{route('view', $event)}}" class="uppercase text-gray-800 hover:text-black">View Detail <i
                class="fas fa-arrow-right"></i></a>
    </div>
</article>
