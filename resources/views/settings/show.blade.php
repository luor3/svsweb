<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @foreach ($settings as $setting)
                <div class="max-w-9xl py-2 mx-auto sm:px-6 lg:px-8">
                    @livewire('settings.update-form', ['setting' => $setting])
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
