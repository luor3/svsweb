<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')
        <h2 class="text-center font-bold text-3xl mt-5">Supervisors</h2>
        <div class="flex flex-wrap m-auto"> 
            @foreach ($supervisors as $supervisor)
            <div class="flex flex-col m-5">
                <a href="/about/{{Str::slug($supervisor->name)}}/{{$supervisor->id}}">
                    @if($supervisor->profile_photo_path)
                    <img class=""
                         src="{{ asset(Storage::disk('local')->url($supervisor->profile_photo_path)) }}"
                         alt=""
                         style="width:250px">
                    @else
                    <img class=""
                         src="https://ui-avatars.com/api/?name={{$supervisor->name}}&color=7F9CF5&background=EBF4FF"
                         alt="{{$supervisor->name}}"
                         style="width:250px; height:375px">
                    @endif
                    <p>{{$supervisor->name}}</p>
                </a>
            </div>
            @endforeach
        </div>
        <h2 class="text-center font-bold text-3xl mt-16">Students</h2>
        <div class="flex flex-wrap m-auto "> 
            @foreach ($students as $student)
            <div class="flex flex-col m-5">
                <a href="/about/{{Str::slug($student->name)}}/{{$student->id}}">
                    @if($student->profile_photo_path)
                    <img class="object-contain"
                         src="{{ asset(Storage::disk('local')->url($student->profile_photo_path)) }}"
                         alt=""
                         style="width:250px; height:375px">
                    @else
                    <img class=""
                         src="https://ui-avatars.com/api/?name={{$student->name}}&color=7F9CF5&background=EBF4FF"
                         alt="{{$student->name}}"
                         style="width:250px; height:375px">
                    @endif
                    <p>{{$student->name}}</p>  
                </a>
            </div>
            @endforeach

        </div>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>
