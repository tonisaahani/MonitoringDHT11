<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    //
}; ?>

<div>
    <div class="flex flex-col border border-white p-5 rounded-2xl">
        <h1 class="font-bold text-3xl">Info Login</h1>
        <hr class="my-5">
        <div class="flex flex-row">
            <div class="max-w-3xl">
                <img src="{{ Auth::user()->avatar ?? '/empty-user.jpg' }}" class="h-36 rounded-lg" />
            </div>
            <div class="flex flex-col justify-center mx-3 *:my-2">
                <h2><b>Nama: </b>{{ Auth::user()->name }}</h2>
                <p><b>Bio: </b>{{ Auth::user()->bio }}</p>
                <p><b>Email: </b>{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>
