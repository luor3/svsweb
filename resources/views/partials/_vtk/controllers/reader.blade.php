<div x-data="{ vtkFilter: false }"  class="grid-flow-row text-1xl">
    <div class="absolute h-120 z-20">
        <a 
            @click="vtkFilter = !vtkFilter"
            class="h-10 w-10 bg-white absolute left-0 top-2 flex 
            items-center shadow rounded-tr rounded-br justify-center
            cursor-pointer z-30 focus:ring-2 focus:ring-indigo-600 
            hover:bg-gray-400 hover:border-l-0" 
            href="#">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z"></path>
                <circle cx="6" cy="10" r="2"></circle>
                <line x1="6" y1="4" x2="6" y2="8"></line>
                <line x1="6" y1="12" x2="6" y2="20"></line>
                <circle cx="12" cy="16" r="2"></circle>
                <line x1="12" y1="4" x2="12" y2="14"></line>
                <line x1="12" y1="18" x2="12" y2="20"></line>
                <circle cx="18" cy="7" r="2"></circle>
                <line x1="18" y1="4" x2="18" y2="5"></line>
                <line x1="18" y1="9" x2="18" y2="20"></line>
            </svg>
        </a>
        <div id="main-controller" 
             :class="{ 'hidden': !vtkFilter }" 
             class="hidden overflow-auto h-120  pt-10 pb-10 pl-5 pr-5 ">
            <table>
                <tbody>
                    <tr>
                        <td>Representation</td>
                        <td>
                            <select class="representations bg-gray-700 focus:outline-none rounded w-full text-sm text-gray-300 py-2">
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
                    
                    <tr>
                        <td>Plane Visibility</td>
                        <td> <input class="visibility" type="checkbox" checked="checked"> </td>
                    </tr>


                    <tr class="font-extrabold">
                        <td>OTHERS</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Pipeline</td>
                        <td> <input class="pipeline" type="checkbox"> </td>
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
    </div>
    <div id="view-container" class="h-120 mb-5 relative"></div>
</div>