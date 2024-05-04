<x-app-layout >
    <div class="container mx-auto flex flex-wrap py-6">

        <!-- Posts Section -->
        <section class="w-full md:w-2/3  px-3">
            <div class=" flex flex-col items-left">
                @foreach($events as $event)
                    <x-post-item :event="$event"/>
                @endforeach
            </div>
            {{ $events->links() }}
        </section>

        <!-- Sidebar Section -->
        <x-sidebar />

    </div>
</x-app-layout>
