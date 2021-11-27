<div class="text-center">
    @if( $jobID === -1)
    @include('frontend.jobs.filterBar')

   
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    ID 
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User 
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Progress
                </th>
                    
                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Submission Date 
                </th>

                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Status
                </th>

                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$job->id}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$job->user_name}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$job->progress}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$job->created_at}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$job->status?'Yes':'No' }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">

                        <x-jet-secondary-button wire:click="redirecToJob( {{ $job->id }} )" wire:loading.attr="disabled">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ __('Edit') }}
                        </x-jet-secondary-button>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <x-jet-danger-button class="ml-2" wire:click="registerJob({{$job->id }},true)"
                            wire:loading.attr="disabled">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ __('Delete') }}
                        </x-jet-danger-button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

   <div class="mt-3">
           {{ $jobs->links() }} 
        </div>

        <!-- Delete Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobDeletion">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Delete Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

    </div>
</div>
    @else

            <x-jet-form-section submit="update">
            <x-slot name="title">
                {{'Unique ID: '.$job->id}}
            </x-slot>

            <x-slot name="description">
                {{ __('Description: ').$job->description}}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <x-jet-label class="text-2xl text-indigo-500" value="{{ $job->name }}" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="jobAttr.name" />
                    <x-jet-input-error for="jobAttr.name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="jobAttr.category_id" value="{{ __('Category') }}" />
                    <select
                        class="block mt-1 w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" id="jobAttr.category_id"
                        wire:model.defer="jobAttr.category_id">
                        <option>Select a Category</option>
                        @foreach($categories as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select> 
                    <x-jet-input-error for="jobAttr.category_id" class="mt-2" />
                </div>


                <div class="col-span-6">
                    <x-jet-label for="description" value="{{ __('Description') }}" />


                    <textarea
                        name="description"
                        class="tinymce mt-1 block w-full textarea border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg" 
                        rows="6"
                        wire:model.defer="jobAttr.description">
                    </textarea>

                    
                    <x-jet-input-error for="jobAttr.description" class="mt-2" />
                </div>

                


                @foreach($uploadFields as $fileType => $extension)
                    <div class="col-span-6 sm:col-span-full" style="text-align: left">
                        <x-jet-label class="mt-5" for="{{'inputFiles.'.$fileType}}" value="{{  'Input '.$fileType.' File'  }}" />
                        <x-jet-input id="{{'inputFiles.'.$fileType}}" type="file" class="mt-1 block w-full" wire:model="inputFiles.{{$fileType}}" enctype='multipart/form-data' accept="{{'.'.$extension}}"/>
                        <div class="text-black-500" wire:loading wire:target="inputFiles.{{$fileType}}">Uploading...</div>
                        <x-jet-input-error for="inputFiles.{{$fileType}}" class="mt-2" />

                        @if(isset($inputFileJson['fileName'][$fileType]))
                            <x-jet-label value="{{ $inputFileJson['fileName'][$fileType] }}" />
                        @endif
                    </div>
                @endforeach



            </x-slot>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button class="btn-submit-form-has-tinymce">
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

        <div style="text-align: left">
           <x-jet-button class="ml-2" wire:click="clearJob" wire:loading.attr="disabled">
                    {{ __('Back') }}
            </x-jet-button> 
        </div>   
            @endif
        </div>
