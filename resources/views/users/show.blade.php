<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Information') }}
        </h2>
    </x-slot>
    
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @livewire('users.update-form')
    </div>

</x-app-layout>