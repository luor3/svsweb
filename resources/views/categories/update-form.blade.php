<x-jet-form-section submit="update">
    <x-slot name="title">
    </x-slot>

    <x-slot name="description">
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input 
                id="name" type="text" 
                class="mt-1 block w-full" 
                wire:model.defer="name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="description" value="{{ __('Description') }}" />
            <x-jet-input 
                id="description" type="text" 
                class="mt-1 block w-full" 
                wire:model.defer="description" />
            <x-jet-input-error for="description" class="mt-2" />
        </div>
        
        <!-- Delete Setting Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingSettingDeletion">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Delete Category') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete category') }} <strong>{{ $name }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingSettingDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete Category') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            <svg 
                viewBox="0 0 20 20" 
                fill="currentColor" 
                class="w-4 h-4 mr-1">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg> 
            {{ __('Save') }}
        </x-jet-button>
        
        <x-jet-danger-button class="ml-2" wire:click="$toggle('confirmingSettingDeletion')" wire:loading.attr="disabled">
            <svg 
                class="w-4 h-4" 
                fill="currentColor" 
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ __('Delete') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-form-section>
