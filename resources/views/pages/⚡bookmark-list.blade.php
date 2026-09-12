<?php

use App\Models\Bookmark;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new #[Layout('layouts::app')] #[Title('Links · LinkBox')] class extends Component {
    use WithPagination;

    // Refreshes the list when the persisted form saves a new bookmark.
    #[On('bookmark-saved')]
    public function refreshList(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'links' => Bookmark::latest()->paginate(6),
        ];
    }
}; ?>

<div>
    <h1 class="text-base-content mb-6 text-2xl font-semibold">Links</h1>

    <div class="divide-base-300 flex flex-col divide-y">
        @forelse ($links as $link)
            <div wire:key="link-{{ $link->id }}" class="py-4">
                <h3 class="text-base-content font-medium">{{ $link->title }}</h3>
                <a href="{{ $link->url }}" target="_blank" rel="noopener"
                    class="link link-primary link-hover break-all text-sm">
                    {{ $link->url }}🤡😂
                </a>
            </div>
        @empty
            <p class="text-base-content/50 py-10 text-center">No links yet — add one on the left.</p>
        @endforelse
    </div>

    @if ($links->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $links->links() }}
        </div>
    @endif
</div>
