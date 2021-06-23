import 'vtk.js';
import controlPanel from './controller.html';
import vtkCubeAxesActor from 'vtk.js/Sources/Rendering/Core/CubeAxesActor';

// ----------------------------------------------------------------------------
// Standard rendering code setup
// ----------------------------------------------------------------------------


const renderWindow = vtk.Rendering.Core.vtkRenderWindow.newInstance();//vtkRenderWindow.newInstance();
const renderer = vtk.Rendering.Core.vtkRenderer.newInstance({background: [0.2, 0.3, 0.4]});//vtkRenderer.newInstance({ background: [0.2, 0.3, 0.4] });
renderWindow.addRenderer(renderer);

const fpsMonitor = vtk.Interaction.UI.vtkFPSMonitor.newInstance();
const fpsElm = fpsMonitor.getFpsMonitorContainer();
fpsElm.style.position = 'relative';
fpsElm.style.bottom = '10px';
fpsElm.style.top = '10px';
fpsElm.style.background = 'rgba(255,255,255,0.5)';
fpsElm.style.borderRadius = '5px';

fpsMonitor.setContainer(document.querySelector('#controller-container'));
fpsMonitor.setRenderWindow(renderWindow);

//fullScreenRenderer.setResizeCallback(fpsMonitor.update);

// ----------------------------------------------------------------------------
// Simple pipeline ConeSource --> Mapper --> Actor
// ----------------------------------------------------------------------------
const actor = vtk.Rendering.Core.vtkActor.newInstance();
const coneSource = vtk.Filters.Sources.vtkConeSource.newInstance();
const interactorStyle = vtk.Interaction.Style.vtkInteractorStyleTrackballCamera.newInstance();
const mapper = vtk.Rendering.Core.vtkMapper.newInstance();


const filter = vtk.Filters.General.vtkCalculator.newInstance();
const AttributeTypes = vtk.Common.DataModel.vtkDataSetAttributes.AttributeTypes;
const FieldDataTypes = vtk.Common.DataModel.vtkDataSet.FieldDataTypes;

filter.setInputConnection(coneSource.getOutputPort());
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
mapper.setInputConnection(coneSource.getOutputPort());
mapper.setInputConnection(filter.getOutputPort());

actor.setMapper(mapper);

// ----------------------------------------------------------------------------
// Add the actor to the renderer and set the camera based on it
// ----------------------------------------------------------------------------

renderer.addActor(actor);
renderer.resetCamera();
fpsMonitor.update();

const cubeAxes = vtkCubeAxesActor.newInstance();
cubeAxes.setCamera(renderer.getActiveCamera());
cubeAxes.setDataBounds(actor.getBounds());
renderer.addActor(cubeAxes); 

// ----------------------------------------------------------------------------
// Use OpenGL as the backend to view the all this
// ----------------------------------------------------------------------------

//const openglRenderWindow = vtkOpenGLRenderWindow.newInstance();
const openglRenderWindow = vtk.Rendering.OpenGL.vtkRenderWindow.newInstance();
renderWindow.addView(openglRenderWindow);


// ----------------------------------------------------------------------------
// Create a div section to put this into
// ----------------------------------------------------------------------------

const container = document.getElementById('viewContainer');
//container.insertAdjacentHTML('beforeend',controlPanel);
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

interactor.setInteractorStyle(interactorStyle);


// -----------------------------------------------------------
// UI control handling
// -----------------------------------------------------------

//fullScreenRenderer.addController(controlPanel);
/*
 const resolutionChange = document.querySelector('.resolution');
 
 resolutionChange.addEventListener('input', (e) => {
 const resolution = Number(e.target.value);
 coneSource.setResolution(resolution);
 renderWindow.render();
 fpsMonitor.update();
 });
 */

const representationSelector = document.querySelector('.representations');

representationSelector.addEventListener('change', (e) => {
    const newRepValue = Number(e.target.value);
    actor.getProperty().setRepresentation(newRepValue);
    renderWindow.render();
    fpsMonitor.update();
});


['height', 'radius', 'resolution'].forEach((propertyName) => {
    document.querySelector(`.${propertyName}`).addEventListener('input', (e) => {
        const value = Number(e.target.value);
        coneSource.set({[propertyName]: value});
        renderWindow.render();
        fpsMonitor.update();
    });
});

document.querySelector('.capping').addEventListener('change', (e) => {
    const capping = !!e.target.checked;
    coneSource.set({capping});
    renderWindow.render();
    fpsMonitor.update();
});

// make the cubeAxes global visibility in case you want to try changing
// some values
global.cubeAxes = cubeAxes;