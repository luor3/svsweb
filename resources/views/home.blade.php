<x-guest-layout>
    
    <div>
        <div x-data="{ open: false }" class="bg-indigo-900 px-4 py-4">
            <div class="md:w-1xl lg:mx-auto lg:flex md:items-center md:justify-between">
                <div class="flex justify-between items-center">
                    <a href="/" class="inline-block py-2 text-white text-4xl font-bold">
                        <h1>SVS Solver</h1>
                    </a>
                    <div class="inline-block cursor-pointer lg:hidden" @click="open = !open">
                        <div class="bg-gray-400 w-8 mb-2" style="height: 2px;"></div>
                        <div class="bg-gray-400 w-8 mb-2" style="height: 2px;"></div>
                        <div class="bg-gray-400 w-8" style="height: 2px;"></div>
                    </div>
                </div>

                
                <div :class="{ 'hidden': !open }" class="hidden lg:block">
                    <a href="#" class="block lg:inline-block py-1 md:py-4 text-gray-100 mr-6 font-bold">Submit a Job</a>
                    <a href="#" class="block lg:inline-block py-1 md:py-4 text-gray-400 hover:text-gray-100 mr-6">About</a>
                    <a href="#" class="block lg:inline-block py-1 md:py-4 text-gray-400 hover:text-gray-100 mr-6">Service</a>
                    <a class="block lg:inline-block mb-2 lg:mb-0 py-2 px-4 text-gray-700 bg-white hover:bg-gray-300 rounded-lg mr-2" href="#">Login</a>
                    <a href="#" class="block lg:inline-block py-2 px-4 text-white bg-blue-500 hover:bg-gray-900 rounded-lg">Register</a>
                </div>
            </div>
        </div>

        <div class="bg-indigo-900 md:overflow-hidden">
            <div class="px-4 py-10 md:py-0">
                <div class="md:max-w-6xl md:mx-auto">
                    <div class="md:flex md:flex-wrap">
                        <div class="md:w-1/1 text-center md:text-left md:pt-6">
                            <h2 class="font-bold text-white text-2xl md:text-5xl leading-tight mb-4">
                                SVS-EFIE Solver
                            </h2>

                            <p class="text-indigo-200 md:text-xl md:pr-48">
                                The Surface-Volume-Surface Electric Field Integral Equation (SVS-EFIE) is 
                                applicable to the solution of the scattering problems on both lossless 
                                dielectric and highly conducting metal objects.
                            </p>

                            <a href="#" class="mt-6 mb-12 md:mb-0 md:mt-10 inline-block py-3 px-8 text-white bg-red-500 hover:bg-red-600 rounded-lg shadow">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
            <svg class="fill-current text-white hidden md:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill-opacity="1" d="M0,224L1440,32L1440,320L0,320Z"></path>
            </svg>
        </div>

        <p class="text-center p-4 text-gray-600 pt-20 lg:pt-0">Copyright © 2021</p>
    </div>

</x-guest-layout>