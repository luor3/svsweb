<div>
    @include('users.filterBar')
    <div class="flex flex-col mt-6">
        <div class="align-middle inline-block min-w-full overflow-auto sm:rounded-lg">
            <table class="min-w-full leading-normal border-4">
                <thead>
                    <tr>
                        <th
                            wire:click="demoOrder('name')" class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name 
                            @include('backend-order-bar', ['columnName' => 'name'] )
                        </th>
                        <th
                            wire:click="demoOrder('email')" class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Email 
                            @include('backend-order-bar', ['columnName' => 'email'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Team
                            @include('backend-order-bar', ['columnName' => 'team_name'] )
                        </th>
                            
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Role 
                            @include('backend-order-bar', ['columnName' => 'role'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status 
                            @include('backend-order-bar', ['columnName' => 'status'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="bg-white">
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        @if($user->profile_photo_path)
                                        <img class="w-full h-full rounded-full"
                                            src="{{ asset(Storage::disk('local')->url($user->profile_photo_path)) }}"
                                            alt="">
                                        @else
                                        <img class="w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{$user->name}}&color=7F9CF5&background=EBF4FF" alt="{{$user->name}}">
                                        @endif
                                    </div>

                                    <div class="ml-3">{{$user->name}}</div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$user->email}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <span class="{{ $user->team_name?  '': 'bg-red-500 text-white' }} py-1 px-3 rounded-full">
                                    {{$user->team_name? $user->team_name : 'NONE'}}
                                </span>

                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <span class="{{ $user->role == 'user'?  'bg-purple-200 text-purple-600': 'bg-red-200 text-red-600' }} py-1 px-3 rounded-full text-xs">
                                    {{$user->role}}
                                </span>
                            </td>

                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <span class="{{ $user->status?  'bg-green-200 text-green-600': 'bg-red-200 text-red-600' }} py-1 px-3 rounded-full text-xs">
                                    {{$user->status? 'active':'inactive'}}
                                </span>
                            </td>

                            <td class="px-5 py-5 border-b border-gray-200 text-sm">

                                <div class="flex justify-center">

                                    @if(auth()->user()->hasPermission(0))
                                    <button class="inline-flex items-center px-1 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition" title="Edit User" wire:click="registerUser({{$user->id}},false)" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @endif


                                    @if(auth()->user()->id !== $user->id && auth()->user()->hasPermission(1))
                                    <button class="ml-2 inline-flex items-center px-1 py-1 border border-red-300 rounded-md bg-red-500 font-semibold text-xs text-white tracking-widest shadow-sm hover:bg-red-300 focus:outline-none focus:ring disabled:opacity-25 transition" title="Delete This User" wire:click="registerUser({{$user->id}},true)"
                                        wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    @endif


                                    @if(auth()->user()->id == 1 && $user->id != 1 && $user->role == 'admin')
                                    <button class="ml-2 inline-flex items-center px-1 py-1 border border-white rounded-md bg-black font-semibold text-xs text-white tracking-widest shadow-sm hover:bg-gray-500 focus:outline-none focus:ring disabled:opacity-25 transition" title="Edit User Permission" wire:click="registerUserPermission({{$user->id}})" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Delete Setting Confirmation Modal -->
            <x-jet-confirmation-modal wire:model="confirmingSettingDeletion">
                <x-slot name="title">
                    <span class="font-bold uppercase">
                        {{ __('Delete User') }}
                    </span>
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete User') }} <strong>{{ $name }}</strong> {{ __('with Email Address') }} <strong>{{ $email }}</strong>?
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingSettingDeletion')"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                        {{ __('Delete User') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>

            <!-- Updating Confirmation Modal -->
            <x-jet-dialog-modal wire:model="confirmingSettingUpdation">
                <x-slot name="title">
                    <span class="font-bold uppercase">
                        {{ __('Updating User Information') }}<br />                       
                    </span>
                    <span>
                        {{__('Unique ID')}}: {{ $user_id }}<br />
                    </span>
                </x-slot>

                <x-slot name="content">

                    <div class="col-span-6 sm:col-span-3 mt-2">
                        <x-jet-label for="name" value="{{ __('Name') }}" />
                        <x-jet-input 
                            id="name" type="text" 
                            class="mt-1 block w-full" 
                            wire:model.defer="name" autofocus />
                        <x-jet-input-error for="name" class="mt-1" />
                    </div>

                    <div class="col-span-6 sm:col-span-3 mt-2">
                        <x-jet-label for="email" value="{{ __('Email') }}" />
                        <x-jet-input 
                            id="email" type="text" 
                            class="mt-1 block w-full" 
                            wire:model.defer="email" />
                        <x-jet-input-error for="email" class="mt-1" />
                    </div>

                    <div class="col-span-6 sm:col-span-3 mt-2">
                        <x-jet-label for="current_team_id" value="{{ __('Team') }}" />
 
                        <select id="current_team_id" wire:model.defer="current_team_id" class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                            @foreach ($teamList as $teamID => $team_name)
                                <option value="{{ $teamID }}">
                                    {{ $team_name }}
                                </option>
                            @endforeach
                        </select>

                        <x-jet-input-error for="current_team_id" class="mt-1" />
                    </div>

                    <div class="col-span-6 sm:col-span-6 mt-2">
                        <x-jet-label for="role" value="{{ __('Role') }}" />
                        <select id="role" wire:model.defer="role" class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                            <option>admin</option>
                            <option>user</option>
                        </select>
                        <x-jet-input-error for="role" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-6 mt-2">
                        <x-jet-label for="status" value="{{ __('Status') }}" />
                        <select id="status" wire:model.defer="status" class="mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg">
                            <option value="1">active</option>
                            <option value="0">inactive</option>
                        </select>
                        <x-jet-input-error for="status" class="mt-2" />
                    </div>

                </x-slot>

                <x-slot name="footer">

                    <x-jet-action-message class="mr-5 inline-flex font-bold" on="saved">
                        {{ __('Saved.') }}
                    </x-jet-action-message>

                    <x-jet-secondary-button wire:click="$toggle('confirmingSettingUpdation')"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-jet-danger-button>
          
                </x-slot>
            </x-jet-dialog-modal>

            <!-- Updating Confirmation Modal -->
            <x-jet-dialog-modal wire:model="confirmingPermissionUpdation">
                <x-slot name="title">
                    <span class="font-bold uppercase">
                        {{ __('Updating Admin Permission') }}<br />                       
                    </span>
                    <span>
                        {{__('Unique ID')}}: {{ $user_id }}<br />
                    </span>
                </x-slot>

                <x-slot name="content">

                    <div class="max-w-lg mx-auto">
                    <link rel="stylesheet" href="https://unpkg.com/@themesberg/flowbite@1.1.0/dist/flowbite.min.css" />


                        <label for="permission.edit_user" class="flex items-center cursor-pointer relative mb-4">
                        <input type="checkbox" id="permission.edit_user" class="sr-only" wire:model.defer="permission.edit_user">
                        <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        <span class="ml-3 text-gray-900 text-sm font-medium">Edit User Permission</span>
                        </label>

                        <label for="permission.delete_user" class="flex items-center cursor-pointer relative mb-4">
                        <input type="checkbox" id="permission.delete_user" class="sr-only" checked="" wire:model.defer="permission.delete_user">
                        <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        <span class="ml-3 text-gray-900 text-sm font-medium">Delete User Permission</span>
                        </label>

                        <label for="permission.edit_demo" class="flex items-center cursor-pointer relative mb-4">
                        <input type="checkbox" id="permission.edit_demo" class="sr-only" wire:model.defer="permission.edit_demo">
                        <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        <span class="ml-3 text-gray-900 text-sm font-medium">Edit Demo Permission</span>
                        </label>

                        <label for="permission.delete_demo" class="flex items-center cursor-pointer relative mb-4">
                        <input type="checkbox" id="permission.delete_demo" class="sr-only" wire:model.defer="permission.delete_demo">
                        <div class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full"></div>
                        <span class="ml-3 text-gray-900 text-sm font-medium">Delete Demo Permission</span>
                        </label>


                    </div>     
                    <script src="https://unpkg.com/@themesberg/flowbite@1.1.0/dist/flowbite.bundle.js"></script>

                </x-slot>

                <x-slot name="footer">

                    <x-jet-action-message class="mr-5 inline-flex font-bold" on="saved">
                        {{ __('Saved.') }}
                    </x-jet-action-message>

                    <x-jet-secondary-button wire:click="$toggle('confirmingPermissionUpdation')"
                        wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="updatePermission" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-jet-danger-button>
          
                </x-slot>
            </x-jet-dialog-modal>

        </div>
        <div class="mt-3">
           {{ $users->links() }} 
        </div>
    </div>
</div>
