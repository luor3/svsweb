<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')

        <section>
            <!-- Supervisors-->
            <h2 class="mt-5 text-center font-bold text-2xl sm:text-3xl ">Supervisors</h2>
            <div class="flex flex-wrap m-auto items-center justify-center"> 
                @foreach ($supervisors as $supervisor)
                <div class="flex flex-col w-64 m-5">
                    <a href="/about/{{Str::slug($supervisor->name)}}/{{$supervisor->id}}">
                        @if($supervisor->profile_photo_path)
                        <img class="w-full h-64 object-cover rounded-2xl"
                             src="{{ asset(Storage::disk('local')->url($supervisor->profile_photo_path)) }}"
                             alt=""
                             >
                        @else
                        <img class="w-full rounded-2xl"
                             src="https://ui-avatars.com/api/?name={{$supervisor->name}}&color=7F9CF5&background=EBF4FF"
                             alt="{{$supervisor->name}}"
                             >
                        @endif
                    
                    </a>
                    <a href="/about/{{Str::slug($supervisor->name)}}/{{$supervisor->id}}">
                        <p class="mt-1 text-center font-bold sm:text-base ">{{$supervisor->name}}</p>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Students-->
            <h2 class="mt-16 text-center font-bold text-2xl sm:text-3xl">Students</h2>
            <div class="flex flex-wrap m-auto items-center justify-center"> 
                @foreach ($students as $student)
                <div class="flex flex-col w-64 m-5">
                    <a href="/about/{{Str::slug($student->name)}}/{{$student->id}}">
                        @if($student->profile_photo_path)
                        <img class="w-full h-64 object-cover rounded-2xl"
                             src="{{ asset(Storage::disk('local')->url($student->profile_photo_path)) }}"
                             alt=""
                             >
                        @else
                        <img class="w-full rounded-2xl"
                             src="https://ui-avatars.com/api/?name={{$student->name}}&color=7F9CF5&background=EBF4FF"
                             alt="{{$student->name}}"
                             >
                        @endif 
                    </a>
                    <a href="/about/{{Str::slug($student->name)}}/{{$student->id}}">
                        <p class="mt-1 text-center font-bold text-lg">{{$student->name}}</p>  
                    </a>
                </div>
                @endforeach

            </div>
        </section>
        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>
