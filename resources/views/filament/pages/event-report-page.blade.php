<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">Event Report for {{ $this->event->title }}</h1>

    <form wire:submit.prevent="save" class="space-y-6">
    {{ $this->form }}

    <div class="flex items-center gap-4">
        @if ($isEditing)
            <x-filament::button type="submit">Save</x-filament::button>

            @if (!empty($this->event->report))
                <x-filament::button color="danger" wire:click="delete" type="button">
                    Delete
                </x-filament::button>
            @endif
        @else
            <x-filament::button wire:click="enableEditing" type="button">
                Edit
            </x-filament::button>

            <x-filament::button color="danger" wire:click="delete" type="button">
                Delete
            </x-filament::button>

            <a href="{{ route('events.report.pdf', $this->event) }}" target="_blank">
                <x-filament::button color="gray" type="button">Download PDF</x-filament::button>
            </a>
        @endif
    </div>
</form>

</x-filament::page>
