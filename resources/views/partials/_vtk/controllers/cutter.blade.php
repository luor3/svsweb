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
                        <td>
                            <b>Origin<b></b></b>
                        </td>
                    </tr>
                    <tr>
                        <td>X</td>
                        <td>
                            <form name="originXForm">
                                <input class="originX" id="originXInputId" type="range" min="-0.5" max="0.5" step="0.01" value="0" oninput="originXOutputId.value=originXInputId.value" /> <output id="originXOutputId">0</output>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Y</td>
                        <td>
                            <form name="originYForm">
                                <input class="originY" id="originYInputId" type="range" min="-0.5" max="0.5" step="0.01" value="0" oninput="originYOutputId.value=originYInputId.value" /> <output id="originYOutputId">-0.07</output>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Z</td>
                        <td>
                            <form name="originZForm">
                                <input class="originZ" id="originZInputId" type="range" min="-0.5" max="0.5" step="0.01" value="0" oninput="originZOutputId.value=originZInputId.value" /> <output id="originZOutputId">0</output>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Normal<b></b></b>
                        </td>
                    </tr>
                    <tr>
                        <td>X</td>
                        <td>
                            <form name="normalXForm">
                                <input class="normalX" id="normalXInputId" type="range" min="-1" max="1" step="0.01" value="1" oninput="normalXOutputId.value=normalXInputId.value" /> <output id="normalXOutputId">-0.72</output>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Y</td>
                        <td>
                            <form name="normalYForm">
                                <input class="normalY" id="normalYInputId" type="range" min="-1" max="1" step="0.01" value="0" oninput="normalYOutputId.value=normalYInputId.value" /> <output id="normalYOutputId">-0.38</output>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Z</td>
                        <td>
                            <form name="normalZForm">
                                <input class="normalZ" id="normalZInputId" type="range" min="-1" max="1" step="0.01" value="0" oninput="normalZOutputId.value=normalZInputId.value" /> <output id="normalZOutputId">-1</output>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="view-container" class="h-120 mb-5 relative"></div>
</div>
