<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SshServer Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @foreach ($sshservers as $sshserver)
                <div class="max-w-9xl py-2 mx-auto sm:px-9 lg:px-9">
                    @livewire('sshservers.update-form', ['sshserver' => $sshserver])
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
