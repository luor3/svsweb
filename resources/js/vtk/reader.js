import 'vtk.js';


// ----------------------------------------------------------------------------
// Standard rendering code setup
// ----------------------------------------------------------------------------
const container = document.getElementById('view-container');
const controlPanel = document.querySelector('#main-controller');

const fileName = 'sphere-temp.vtk'; //


const renderWindow = vtk.Rendering.Core.vtkRenderWindow.newInstance();
const renderer = vtk.Rendering.Core.vtkRenderer.newInstance({background: [0.2, 0.3, 0.4]});
renderWindow.addRenderer(renderer);

const fpsMonitor = vtk.Interaction.UI.vtkFPSMonitor.newInstance();
const fpsElm = fpsMonitor.getFpsMonitorContainer();
fpsElm.style.position = 'relative';
fpsElm.style.bottom = '10px';
fpsElm.style.top = '10px';
fpsElm.style.background = 'rgba(255,255,255,0.5)';
fpsElm.style.borderRadius = '5px';

fpsMonitor.setContainer(controlPanel);
fpsMonitor.setRenderWindow(renderWindow);


async function createConePipeline(render) {
    const modelSource = vtk.IO.Legacy.vtkPolyDataReader.newInstance(); // reader
    const actor = vtk.Rendering.Core.vtkActor.newInstance();
    const mapper = vtk.Rendering.Core.vtkMapper.newInstance();
    
    const response = await modelSource.setUrl(`/storage/geometry/${fileName}`);
    const polydata = modelSource.getOutputData(0);
    
    actor.setMapper(mapper);

    const filter = vtk.Filters.General.vtkCalculator.newInstance();
    const AttributeTypes = vtk.Common.DataModel.vtkDataSetAttributes.AttributeTypes;
    const FieldDataTypes = vtk.Common.DataModel.vtkDataSet.FieldDataTypes;

    filter.setInputConnection(modelSource.getOutputPort());
    filter.setFormula({
        getArrays: inputDataSets => ({
                input: [],
                output: [
                    {location: FieldDataTypes.CELL, name: 'Random', dataType: 'Float32Array', attribute: AttributeTypes.SCALARS},
                ],
            }),
        evaluate: (arraysIn, arraysOut) => {
            const [scalars] = arraysOut.map(d => d.getData());
            for (let i = 0; i < scalars.length; i++) {
                scalars[i] = Math.random();
            }
        },
    });
    
    console.log(actor.getProperty());

    //mapper.setInputData(polydata);
    mapper.setInputConnection(filter.getOutputPort());
    
    if (render) renderer.addActor(actor);

    return {modelSource, mapper, actor};
}

(async function () { 
    const pipelines = [await createConePipeline(), await createConePipeline(true)];

    // Create red wireframe baseline
    pipelines[0].actor.getProperty().setRepresentation(1);
    pipelines[0].actor.getProperty().setColor(1, 0, 0);

    //pipelines[1].actor.getProperty().setEdgeVisibility(true);

    renderer.resetCamera();
    renderWindow.render();
    fpsMonitor.update();


    const cubeAxes = vtk.Rendering.Core.vtkCubeAxesActor.newInstance();
    cubeAxes.setCamera(renderer.getActiveCamera());
    cubeAxes.setDataBounds(pipelines[0].actor.getBounds());
    //renderer.addActor(cubeAxes);

    // ----------------------------------------------------------------------------
    // Use OpenGL as the backend to view the all this
    // ----------------------------------------------------------------------------
    const openglRenderWindow = vtk.Rendering.OpenGL.vtkRenderWindow.newInstance();
    renderWindow.addView(openglRenderWindow);


    // ----------------------------------------------------------------------------
    // Create a div section to put this into
    // ----------------------------------------------------------------------------
    openglRenderWindow.setContainer(container);

    // ----------------------------------------------------------------------------
    // Capture size of the container and set it to the renderWindow
    // ----------------------------------------------------------------------------

    const {width, height} = container.getBoundingClientRect();
    openglRenderWindow.setSize(width, height);

    // ----------------------------------------------------------------------------
    // Setup an interactor to handle mouse events
    // ----------------------------------------------------------------------------

    const interactor = vtk.Rendering.Core.vtkRenderWindowInteractor.newInstance();//vtkRenderWindowInteractor.newInstance();
    interactor.setView(openglRenderWindow);
    interactor.initialize();
    interactor.bindEvents(container);

    // ----------------------------------------------------------------------------
    // Setup interactor style to use
    // ----------------------------------------------------------------------------
    const interactorStyle = vtk.Interaction.Style.vtkInteractorStyleTrackballCamera.newInstance();
    interactor.setInteractorStyle(interactorStyle);


    // -----------------------------------------------------------
    // UI control handling
    // -----------------------------------------------------------
    document.querySelector('.representations').addEventListener('change', (e) => {
        const newRepValue = Number(e.target.value);
        pipelines[1].actor.getProperty().setRepresentation(newRepValue);
        renderWindow.render();
        fpsMonitor.update();
    });

    const props = [
        'height', 'radius', 'resolution', 'thetaResolution',
        'startTheta', 'endTheta', 'phiResolution', 'startPhi',
        'endPhi',
    ];
    props.forEach((propertyName) => {
        document.querySelector(`.${propertyName}`).addEventListener('input', (e) => {
            const value = Number(e.target.value);
            pipelines[0].modelSource.set({[propertyName]: value});
            pipelines[1].modelSource.set({[propertyName]: value});
            cubeAxes.setDataBounds(pipelines[0].actor.getBounds());
            renderWindow.render();
            fpsMonitor.update();
        });
    });

    document.querySelector('.capping').addEventListener('change', (e) => {
        const capping = !!e.target.checked;
        pipelines[0].modelSource.set({capping});
        pipelines[1].modelSource.set({capping});
        renderWindow.render();
        fpsMonitor.update();
    });

    document.querySelector('.axes').addEventListener('change', (e) => {
        !!e.target.checked ? renderer.addActor(cubeAxes) : renderer.removeActor(cubeAxes);
        renderWindow.render();
        fpsMonitor.update();
    });

    document.querySelector('.edgeVisibility').addEventListener('change', (e) => {
        const edgeVisibility = !!e.target.checked;
        pipelines[0].actor.getProperty().setEdgeVisibility(edgeVisibility);
        pipelines[1].actor.getProperty().setEdgeVisibility(edgeVisibility);
        renderWindow.render();
    });

    document.querySelector('.visibility').addEventListener('change', (e) => {
        pipelines[1].actor.setVisibility(!!e.target.checked);
        renderWindow.render();
    });

    document.querySelector('.pipeline').addEventListener('change', (e) => {
        !!e.target.checked ? renderer.addActor(pipelines[0].actor) : renderer.removeActor(pipelines[0].actor);
        renderWindow.render();
    });

    const centerElems = document.querySelectorAll('.center');
    const directionElems = document.querySelectorAll('.direction');

    function updateTransformedCone() {
        const center = [0, 0, 0];
        const direction = [1, 0, 0];
        for (let i = 0; i < 3; i++) {
            center[Number(centerElems[i].dataset.index)] = Number(centerElems[i].value);
            direction[Number(directionElems[i].dataset.index)] = Number(
                    directionElems[i].value
                    );
        }
        //console.log('updateTransformedCone', center, direction);
        pipelines[1].modelSource.set({center, direction});
        renderWindow.render();
        fpsMonitor.update();
    }

    for (let i = 0; i < 3; i++) {
        centerElems[i].addEventListener('input', updateTransformedCone);
        directionElems[i].addEventListener('input', updateTransformedCone);
    }

    // make the cubeAxes global visibility in case you want to try changing
    // some values
    global.cubeAxes = cubeAxes;
    global.pipelines = pipelines;
    global.renderer = renderer;
    global.renderWindow = renderWindow;
})();