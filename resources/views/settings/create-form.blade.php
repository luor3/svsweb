<x-jet-form-section submit="add">
    <x-slot name="title">
        {{ __('New Setting') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add a new site setting.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('New Setting') }}" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="value" value="{{ __('Value') }}" />
            <x-jet-input id="value" type="text" class="mt-1 block w-full" wire:model.defer="value" />
            <x-jet-input-error for="value" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
