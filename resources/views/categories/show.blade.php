<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @foreach ($categories as $category)
                <div class="max-w-9xl py-2 mx-auto sm:px-6 lg:px-8">
                    @livewire('categories.update-form', ['category' => $category])
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
