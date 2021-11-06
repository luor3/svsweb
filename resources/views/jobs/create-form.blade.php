<x-jet-form-section submit="create">
    <x-slot name="title">
        {{ __('New Job') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Submit a new job.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('New Job') }}" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="configuration" value="{{ __('Configuration') }}" />
            <x-jet-input id="configuration" type="text" class="mt-1 block w-full" wire:model.defer="configuration" />
            <x-jet-input-error for="configuration" class="mt-2" />
        </div>
     
        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="fld-status" value="{{ __('Display') }}" />
            <select id="fld-status" wire:model.defer="status" class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                <option value="">Select display status</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>

            <x-jet-input-error for="status" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button class="btn-submit-form-has-tinymce">
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
