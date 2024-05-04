<div class="flex gap-2">
    <button wire:click="bookmark" class="flex gap-2 items-center hover:text-blue-500 transition-all {{$hasBookmark ? 'text-blue-500' : ''}}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v16l6-6 6 6V4H6z" />
        </svg>
        {{ $isBookmark }}
    </button>
</div>
