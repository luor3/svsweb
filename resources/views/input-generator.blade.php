<x-guest-layout>
    @push('metas')
    @include('partials.metas')
    @endpush

    <div class="min-h-screen flex flex-col">
        @include('partials.site-navigation')

        <div class="min-h-screen flex flex-col mt-16">

            <!-- Remove class [ h-64 ] when adding a card block -->
            <div class="container mx-auto">

                <div class="grid-flow-row p-8 text-1xl">
                    <div id="controller-container">
                        <table id="controller">
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <select class='representations'>
                                            <option value='0'>Points</option>
                                            <option value='1'>Wireframe</option>
                                            <option value='2' selected>Surface</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Height</td>
                                    <td colspan="3"> <input class="height" type="range" min="0.5" max="2.0" step="0.1" value="1.0"> </td>
                                </tr>
                                <tr>
                                    <td>Radius</td>
                                    <td colspan="3"> <input class="radius" type="range" min="0.5" max="2.0" step="0.1" value="1.0"> </td>
                                </tr>
                                <tr>
                                    <td>Resolution</td>
                                    <td colspan="3"> <input class="resolution" type="range" min="4" max="100" step="1" value="6"> </td>
                                </tr>
                                <tr>
                                    <td>Capping</td>
                                    <td colspan="3"> <input class="capping" type="checkbox" checked="checked"> </td>
                                </tr>
                                <tr>
                                    <td>Axes</td>
                                    <td colspan="3"> <input class="axes" type="checkbox"> </td>
                                </tr>

                                <tr>
                                    <td>Edge Visibility</td>
                                    <td> <input class="edgeVisibility" type="checkbox"> </td>
                                </tr>

                                <tr class="font-extrabold mt-4">
                                    <td>SPHERE</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Theta Resolution</td>
                                    <td> <input class="thetaResolution" type="range" min="4" max="100" step="1" value="8"> </td>
                                </tr>
                                <tr>
                                    <td>Start Theta</td>
                                    <td> <input class="startTheta" type="range" min="0" max="360" step="1" value="0"> </td>
                                </tr>
                                <tr>
                                    <td>End Theta</td>
                                    <td> <input class="endTheta" type="range" min="0" max="360" step="1" value="360"> </td>
                                </tr>
                                <tr>
                                    <td>Phi Resolution</td>
                                    <td> <input class="phiResolution" type="range" min="4" max="100" step="1" value="8"> </td>
                                </tr>
                                <tr>
                                    <td>Start Phi</td>
                                    <td> <input class="startPhi" type="range" min="0" max="180" step="1" value="0"> </td>
                                </tr>
                                <tr>
                                    <td>End Phi</td>
                                    <td> <input class="endPhi" type="range" min="0" max="180" step="1" value="180"> </td>
                                </tr>

                                <tr class="font-extrabold mt-4">
                                    <td>LINE</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>X Resolution</td>
                                    <td> <input class="xResolution" type="range" min="2" max="100" step="1" value="50"> </td>
                                </tr>
                                <tr>
                                    <td>Y Resolution</td>
                                    <td> <input class="yResolution" type="range" min="2" max="100" step="1" value="50"> </td>
                                </tr>
                                <tr>
                                    <td>Plane Visibility</td>
                                    <td> <input class="visibility" type="checkbox" checked="checked"> </td>
                                </tr>


                                <tr class="font-extrabold">
                                    <td>OTHERS</td>
                                    <td></td>
                                </tr>

                                <tr style="text-align:center">
                                    <td></td>
                                    <td>X</td>
                                    <td>Y</td>
                                    <td>Z</td>
                                </tr>
                                <tr>
                                    <td>Origin</td>
                                    <td> <input style="width:50px" class="center" data-index="0" type="range" min="-1" max="1" step="0.1" value="0"> </td>
                                    <td> <input style="width:50px" class="center" data-index="1" type="range" min="-1" max="1" step="0.1" value="0"> </td>
                                    <td> <input style="width:50px" class="center" data-index="2" type="range" min="-1" max="1" step="0.1" value="0"> </td>
                                </tr>
                                <tr>
                                    <td>Direction</td>
                                    <td> <input style="width:50px" class="direction" data-index="0" type="range" min="-1" max="1" step="0.1" value="1"> </td>
                                    <td> <input style="width:50px" class="direction" data-index="1" type="range" min="-1" max="1" step="0.1" value="0"> </td>
                                    <td> <input style="width:50px" class="direction" data-index="2" type="range" min="-1" max="1" step="0.1" value="0"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="viewContainer" class="h-960 mb-5"></div>
                </div>
            </div>
        </div>

        <div class="bg-indigo-900 text-gray-400 px-4 py-4 font-normal">
            <p class="text-center">Copyright © {{ date('Y') }} {{ $app->author }}. All rights reserved.</p>
        </div>
    </div>

</x-guest-layout>