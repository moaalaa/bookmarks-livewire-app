<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts::app')] #[Title('About · LinkBox')] class extends Component {
    //
}; ?>

<div>
    <h1 class="text-base-content mb-6 text-2xl font-semibold">About</h1>
    <p class="text-base-content/70">LinkBox is a simple bookmark manager.</p>
</div>
