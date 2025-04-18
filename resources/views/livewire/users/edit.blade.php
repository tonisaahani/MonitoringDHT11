<?php

use Livewire\Volt\Component;
use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Attributes\Rule;
use App\Models\Country;

new class extends Component {
    use Toast;
    //
    public User $user;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    // #[Rule('required|max:10')]
    // public $file; // A separated rule to make it required
    // // Notice `*` syntax for validate each file

    // #[Rule(['photos' => 'required'])]
    // #[Rule(['photos.*' => 'image|max:100'])]
    // public array $photos = [];

    // Optional
    #[Rule('sometimes')]
    public ?int $country_id = null;

    // We also need this to fill Countries combobox on upcoming form
    public function with(): array
    {
        return [
            'countries' => Country::all(),
        ];
    }

    public function mount(): void
    {
        $this->fill($this->user);
    }

    public function save(): void
    {
        // Validate
        $data = $this->validate();

        // Update
        $this->user->update($data);

        // You can toast and redirect to any route
        $this->success('User updated with success.', redirectTo: '/users');

        // try {
        //     $data = $this->validate();
        //     dd('Lolos validasi', $data);
        // } catch (\Throwable $e) {
        //     dd('Validasi gagal', $e->getMessage());
        // }
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Update {{ $user->name }}" separator />

    {{-- FORM --}}
    <x-form wire:submit="save">
        {{--
        @php
            $config = ['guides' => false];
        @endphp

        <x-file wire:model="photo" accept="image/png, image/jpeg">
            <img src="{{ $user->avatar ?? '/empty-user.jpg' }}" class="h-40 rounded-lg" />
        </x-file> --}}
        <x-input label="Name" wire:model="name" />
        <x-input label="Email" wire:model="email" />
        <x-select label="Country" wire:model="country_id" :options="$countries" placeholder="---" />

        <x-slot:actions>
            <x-button label="Cancel" link="/users" />
            {{-- The important thing here is `type="submit"` --}}
            {{-- The spinner property is nice! --}}
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
