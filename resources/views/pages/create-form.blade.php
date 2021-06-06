<x-jet-form-section submit="add">
    <x-slot name="title">
        {{ __('New Page') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add a new CMS page.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('New Page') }}" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="link" value="{{ __('Link') }}" />
            <x-jet-input id="link" type="text" class="mt-1 block w-full" wire:model.defer="link" />
            <x-jet-input-error for="link" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="title" value="{{ __('Title') }}" />
            <x-jet-input id="title" type="text" class="mt-1 block w-full" wire:model.defer="title" />
            <x-jet-input-error for="title" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="description" value="{{ __('Description') }}" />
            <x-jet-input id="description" type="text" class="mt-1 block w-full" wire:model.defer="description" />
            <x-jet-input-error for="description" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="keywords" value="{{ __('Keywords') }}" />
            <x-jet-input id="keywords" type="text" class="mt-1 block w-full" wire:model.defer="keywords" />
            <x-jet-input-error for="keywords" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="content" value="{{ __('Content') }}" />
            {{-- <editor 
                name="content"
                class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" 
                rows="6" 
                wire:model.defer="content"
                :api-key="tinyMCEKey" 
                :init="tinyMCEConfig"></editor> --}}
            <textarea
                class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" 
                rows="6" 
                id="content" 
                wire:model.defer="content"></textarea>
            <x-jet-input-error for="content" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="fld-status" value="{{ __('Display') }}" />
            <select id="fld-status" wire:model.defer="status" class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <x-jet-input-error for="status" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
