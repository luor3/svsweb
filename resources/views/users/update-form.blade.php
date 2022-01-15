<div>
    @include('users.filterBar')
    <div class="flex flex-col mt-6">
        <div class="align-middle inline-block min-w-full overflow-hidden sm:rounded-lg">
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
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
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
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$user->email}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="{{ $user->team_name?  '': 'bg-red-500 text-white' }} py-1 px-3 rounded-full">
                                    {{$user->team_name? $user->team_name : 'NONE'}}
                                </span>

                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="{{ $user->role == 'user'?  'bg-purple-200 text-purple-600': 'bg-red-200 text-red-600' }} py-1 px-3 rounded-full text-xs">
                                    {{$user->role}}
                                </span>
                            </td>

                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="{{ $user->status?  'bg-green-200 text-green-600': 'bg-red-200 text-red-600' }} py-1 px-3 rounded-full text-xs">
                                    {{$user->status? 'active':'inactive'}}
                                </span>
                            </td>

                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <x-jet-secondary-button wire:click="registerUser({{$user->id}},false)" wire:loading.attr="disabled">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('Edit') }}
                                </x-jet-secondary-button>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if(auth()->user()->id !== $user->id)
                                    <x-jet-danger-button class="ml-2" wire:click="registerUser({{$user->id}},true)"
                                        wire:loading.attr="disabled">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __('Delete') }}
                                    </x-jet-danger-button>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @if($user->role == 'admin')
                                    <x-jet-secondary-button wire:click="registerUserPermission({{$user->id}})" wire:loading.attr="disabled">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __('Permission') }}
                                    </x-jet-secondary-button>
                                @endif
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
