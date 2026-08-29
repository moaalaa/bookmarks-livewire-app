<?php

use App\Models\Bookmark;
use Livewire\Component;

new class extends Component {
    public string $title = '';
    public string $url = '';
    public ?string $successMessage = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|min:2|max:100',
            'url' => 'required|url|max:255',
        ];
    }

    public function save(): void
    {
        $this->successMessage = null;

        $this->validate();

        Bookmark::create([
            'name' => $this->title,
            'url' => $this->url,
        ]);

        $this->reset(['title', 'url']);
        $this->resetErrorBag();

        $this->successMessage = 'Link saved.';

        // Let any listening page (e.g. the links list) know it should refresh.
        $this->dispatch('bookmark-saved');
    }
}; ?>

<div class="flex flex-col gap-4">
    <h2 class="text-base-content text-lg font-semibold">Add a link</h2>

    @if ($successMessage)
        <div wire:transition wire:key="add-link-success" class="alert alert-success alert-soft" role="alert">
            <span>{{ $successMessage }}</span>
        </div>
    @endif

    <form wire:submit="save" class="flex flex-col gap-4">
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Title</legend>
            <input type="text" wire:model="title" class="input @error('title') input-error @enderror w-full"
                placeholder="e.g. Laravel Docs">
            @error('title')
                <p class="label text-error">{{ $message }}</p>
            @enderror
        </fieldset>

        <fieldset class="fieldset">
            <legend class="fieldset-legend">URL</legend>
            <input type="text" wire:model="url" class="input @error('url') input-error @enderror w-full"
                placeholder="https://example.com">
            @error('url')
                <p class="label text-error">{{ $message }}</p>
            @enderror
        </fieldset>

        <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Save link</span>
            <span wire:loading wire:target="save" class="loading loading-spinner loading-sm"></span>
        </button>
    </form>
</div>
