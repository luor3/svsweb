<div class="text-center">
    @if(count($jobs) == 0)
        <h1 class="mt-10 text-center font-bold uppercase">Sorry. No Job Submission Records Found</h1>
    @else
    <div class="mb-5 text-left">
        <select
            class="bg-white border-gray-400 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            wire:model="pageNum">
            <option>5</option>
            <option>10</option>
            <option>20</option>
        </select>
    </div>
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
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $job->status?'Yes':'No' }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <x-jet-secondary-button>
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ __('Edit') }}
                        </x-jet-secondary-button>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <x-jet-danger-button class="ml-2">
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

    @endif
</div>
