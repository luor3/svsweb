<x-jet-form-section submit="update">
    <x-slot name="title">
        User Profile
    </x-slot>

    <x-slot name="description">
        Manage User Information
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input 
                id="name" type="text" 
                class="mt-1 block w-full" 
                wire:model.defer="name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input 
                id="email" type="text" 
                class="mt-1 block w-full" 
                wire:model.defer="email" />
            <x-jet-input-error for="email" class="mt-2" />
        </div>
        
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
    </x-slot>
</x-jet-form-section>

<form class="block lg:inline-block py-2 px-4 text-white bg-red-500 hover:bg-red-900 rounded-lg" method="POST" action="{{ route('logout') }}">
    @csrf
    <a class="mx-auto" onclick="event.preventDefault();this.closest('form').submit();" href="{{ route('logout') }}">Log Out</a>
</form>
