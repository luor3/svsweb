<div>
    @if( $jobID === -1)
        <div class="mx-auto overflow-auto">
            @include('frontend.jobs.filterBar')
            <table class="text-center min-w-full leading-normal border-4">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID 
                            @include('backend-order-bar', ['columnName' => 'id'] )
                        </th>

                        @if($permission == 1)
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            User 
                            @include('backend-order-bar', ['columnName' => 'user'] )
                        </th>
                        @endif

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                            @include('backend-order-bar', ['columnName' => 'name'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Category
                            @include('backend-order-bar', ['columnName' => 'category_id'] )
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Progress
                            @include('backend-order-bar', ['columnName' => 'progress'] )
                        </th>
                            
                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Submission Date 
                            @include('backend-order-bar', ['columnName' => 'created_at'] )
                            
                        </th>

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                            @include('backend-order-bar', ['columnName' => 'status'] )
                        </th>

                        <th
                            class="px-5 py-3 border-b-2 border-gray-300 bg-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $myJob)
                        <tr class="bg-white">                    
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$myJob->id}}</td>
                            @if($permission == 1)
                                <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$myJob->user_name}}</td>
                            @endif
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$myJob->name}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$this->categories[$myJob->category_id]}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$myJob->progress}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">{{$myJob->created_at}}</td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <span class="{{ $myJob->status?  'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs': 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs' }} py-1 px-3 rounded-full text-xs">
                                    {{$myJob->status?'Yes':'No' }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <div class="flex justify-around">

                                    <button class="inline-flex items-center px-1 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition" title="Edit" wire:click="redirecToJob( {{ $myJob->id }} )" wire:loading.attr="disabled">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                        
                                    </button>

                                    @if($myJob->progress === 'Pending' || $myJob->progress === 'Cancelled' || $myJob->progress === 'In Progress' )
                                        <button class="{{ $myJob->progress === 'Pending'|| $myJob->progress === 'In Progress'?  'bg-black hover:bg-gray-500':'bg-green-400 hover:border-green-500 hover:bg-green-500'}} ml-2 inline-flex items-center px-1 py-1 border text-white border-gray-300 rounded-md font-semibold tracking-widest shadow-sm focus:outline-none focus:border-black focus:ring focus:ring-black-200 active:bg-gray-50 disabled:opacity-25 transition" title="{{ $myJob->progress === 'Pending'|| $myJob->progress === 'In Progress'? 'Withdraw Job' : 'Recover Job' }}" wire:click="withdrawJob( {{ $myJob->id }} )" wire:loading.attr="disabled">
                                            @if($myJob->progress === 'Pending' || $myJob->progress === 'In Progress')
                                                
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            
                                            @elseif($myJob->progress === 'Cancelled')                                            
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                                
                                            @endif                                      
                                        </button>
                                    @endif

                                    <button class="ml-2 inline-flex items-center px-1 py-1 border border-red-300 rounded-md bg-red-500 font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-300 focus:outline-none focus:ring disabled:opacity-25 transition" title="Delete This Job" wire:click="registerJob({{$myJob->id }},true)"
                                        wire:loading.attr="disabled">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>

                                    </button>

                                    @if($myJob->progress === 'Completed')
                                    <button class="ml-2 inline-flex items-center px-1 py-1 border border-green-200 rounded-md bg-green-500 font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-green-300 focus:outline-none focus:ring transition" wire:click="downloadFile({{$myJob->id }}, false)" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>
                                    @else
                                    <button title="Not Available" class="ml-2 inline-flex items-center px-1 py-1 border border-blue-300 rounded-md bg-blue-400 font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-200 focus:outline-none focus:ring disabled:opacity-25 transition" wire:loading.attr="disabled" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $jobs->links() }} 
            </div>
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

        <!-- Withdraw Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobWithdraw">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Withdraw Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to withdraw job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobWithdraw')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="withdraw()" wire:loading.attr="disabled">
                    {{ __('Withdraw Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        <!-- Recover Page Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingJobRecover">
            <x-slot name="title">
                <span class="font-bold uppercase">
                    {{ __('Recover Job') }}
                </span>
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to recover job: ') }} <strong>{{ isset($job) ? $job->name : 'null' }}</strong> ?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingJobRecover')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="recover()" wire:loading.attr="disabled">
                    {{ __('Recover Job') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        </div>
    </div>
    @else

        <x-jet-form-section submit="update">
        <x-slot name="title">
            {{'Job Unique ID: '.$job->id}}
        </x-slot>

        <x-slot name="description">
            {{ __('Job Description: ').$job->description}}
            <br/>
            {{ __('Please Change the job submission here: ')}}
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
            <div style="text-align: left">
                <x-jet-button class="ml-2" onclick="event.preventDefault();" wire:click="clearJob" wire:loading.attr="disabled">
                        {{ __('Back') }}
                </x-jet-button> 
            </div> 
        </x-slot>
    </x-jet-form-section>

    @endif
</div>
