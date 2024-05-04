<x-filament::widget class="grid grid-cols-3 gap-3">
    <x-filament::card>
        <h2 class="text-lg sm:text-xl font-bold tracking-tight">
            Views
        </h2>
        <div class="flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{ $viewCount }}
        </div>
    </x-filament::card>
    <x-filament::card>
        <h2 class="text-lg sm:text-xl font-bold tracking-tight">
            Likes
        </h2>
        <div class="flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
            </svg>
            {{ $like }}
        </div>
    </x-filament::card>
    <x-filament::card>
        <h2 class="text-lg sm:text-xl font-bold tracking-tight">
            Bookmark
        </h2>
        <div class="flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v16l6-6 6 6V4H6z" />
        </svg>
            {{ $bookmark }}
        </div>
    </x-filament::card>
</x-filament::widget>
