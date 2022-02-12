<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solvers') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @foreach ($solvers  as $solver)
                <div class="max-w-9xl py-2 mx-auto sm:px-6 lg:px-8">
                    @livewire('solvers.update-form', ['solver' => $solver])
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
