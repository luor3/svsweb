<x-jet-form-section submit="add">
    <x-slot name="title">
        {{ __('New Solver') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add a new site solver.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('New solver') }}" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="args" value="{{ __('Args') }}" />
            <x-jet-input id="args" type="text" class="mt-1 block w-full" wire:model.defer="args" />
            <x-jet-input-error for="args" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
