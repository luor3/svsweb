import 'vtk.js';
import controlPanel from './controlPanel.html';

// ----------------------------------------------------------------------------
// Standard rendering code setup
// ----------------------------------------------------------------------------

const fullScreenRenderer = vtk.Rendering.Misc.vtkFullScreenRenderWindow.newInstance({
    background: [0, 0, 0],
});
const renderer = fullScreenRenderer.getRenderer();
const renderWindow = fullScreenRenderer.getRenderWindow();

// ----------------------------------------------------------------------------
// Example code
// ----------------------------------------------------------------------------

const plane = vtk.Common.DataModel.vtkPlane.newInstance();
const cube = vtk.Filters.Sources.vtkConeSource.newInstance();

const cutter = vtk.Filters.Core.vtkCutter.newInstance();
cutter.setCutFunction(plane);
cutter.setInputConnection(cube.getOutputPort());

const cutMapper = vtk.Rendering.Core.vtkMapper.newInstance();
cutMapper.setInputConnection(cutter.getOutputPort());
const cutActor = vtk.Rendering.Core.vtkActor.newInstance();
cutActor.setMapper(cutMapper);
const cutProperty = cutActor.getProperty();
cutProperty.setRepresentation(vtk.Rendering.Core.vtkProperty.Representation.WIREFRAME);
cutProperty.setLighting(false);
cutProperty.setColor(0, 1, 0);
renderer.addActor(cutActor);

const cubeMapper = vtk.Rendering.Core.vtkMapper.newInstance();
cubeMapper.setInputConnection(cube.getOutputPort());
const cubeActor = vtk.Rendering.Core.vtkActor.newInstance();
cubeActor.setMapper(cubeMapper);
const cubeProperty = cubeActor.getProperty();
//cubeProperty.setRepresentation(vtk.Rendering.Core.vtkProperty.Representation.WIREFRAME);
cubeProperty.setEdgeVisibility(true);
cubeProperty.setLighting(false);
renderer.addActor(cubeActor);

renderer.resetCamera();

// -----------------------------------------------------------
// UI control handling
// -----------------------------------------------------------

fullScreenRenderer.addController(controlPanel);

const state = {
    originX: 0,
    originY: 0,
    originZ: 0,
    normalX: 1,
    normalY: 0,
    normalZ: 0,
};

const updatePlaneFunction = () => {
    plane.setOrigin(state.originX, state.originY, state.originZ);
    plane.setNormal(state.normalX, state.normalY, state.normalZ);
    renderWindow.render();
};

// Update now
updatePlaneFunction();

// Update when changing UI
['originX', 'originY', 'originZ', 'normalX', 'normalY', 'normalZ'].forEach(
        (propertyName) => {
    const elem = document.querySelector(`.${propertyName}`);
    elem.onclick = function (e) {
        const value = Number(e.target.value);
        state[propertyName] = value;
        updatePlaneFunction();
    };
}
);