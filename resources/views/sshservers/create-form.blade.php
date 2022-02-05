<x-jet-form-section submit="add">
    <x-slot name="title">
        {{ __('New Ssh server') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Add a new ssh server setting.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('New SSH Server') }}" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="server_name" value="{{ __('Server_name') }}" />
            <x-jet-input id="server_name" type="text" class="mt-1 block w-full" wire:model.defer="server_name" autofocus />
            <x-jet-input-error for="server_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="host" value="{{ __('Host') }}" />
            <x-jet-input id="host" type="text" class="mt-1 block w-full" wire:model.defer="host" />
            <x-jet-input-error for="host" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="port" value="{{ __('Port') }}" />
            <x-jet-input id="port" type="text" class="mt-1 block w-full" wire:model.defer="port" />
            <x-jet-input-error for="port" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="username" value="{{ __('Username') }}" />
            <x-jet-input id="username" type="text" class="mt-1 block w-full" wire:model.defer="username" />
            <x-jet-input-error for="username" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="password" value="{{ __('Password') }}" />
            <x-jet-input id="password" type="text" class="mt-1 block w-full" wire:model.defer="password" />
            <x-jet-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="active" value="{{ __('Active') }}" />
            <select
                    class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg"
                    id="active" wire:model.defer="active">
                    <option value="0" selected>inactive</option>
                    <option value="1">active</option>
            </select>
            <x-jet-input-error for="active" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
