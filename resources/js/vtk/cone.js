import 'vtk.js';
/*
var fullScreenRenderer = vtk.Rendering.Misc.vtkFullScreenRenderWindow.newInstance();
var actor = vtk.Rendering.Core.vtkActor.newInstance();
var mapper = vtk.Rendering.Core.vtkMapper.newInstance();
var cone = vtk.Filters.Sources.vtkConeSource.newInstance();

actor.setMapper(mapper);
mapper.setInputConnection(cone.getOutputPort());

var renderer = fullScreenRenderer.getRenderer();
renderer.addActor(actor);
renderer.resetCamera();

var renderWindow = fullScreenRenderer.getRenderWindow();
renderWindow.render();
*/



// ============================================================

var renderWindow       = vtk.Rendering.Core.vtkRenderWindow.newInstance();
var openglRenderWindow = vtk.Rendering.OpenGL.vtkRenderWindow.newInstance();
var actor              = vtk.Rendering.Core.vtkActor.newInstance();
var mapper             = vtk.Rendering.Core.vtkMapper.newInstance();
var cone               = vtk.Filters.Sources.vtkConeSource.newInstance({ height: 100.0, radius: 50.0 });
var interactorStyle    = vtk.Interaction.Style.vtkInteractorStyleTrackballCamera.newInstance();

var container = document.getElementById('viewContainer');

openglRenderWindow.setContainer(container);
renderWindow.addView(openglRenderWindow);


actor.setMapper(mapper);
mapper.setInputConnection(cone.getOutputPort());

var renderer = vtk.Rendering.Core.vtkRenderer.newInstance();
renderer.addActor(actor);
renderer.resetCamera();
renderWindow.addRenderer(renderer);

var interactor = vtk.Rendering.Core.vtkRenderWindowInteractor.newInstance();
interactor.setView(openglRenderWindow);
interactor.initialize();
interactor.bindEvents(container);
interactor.setInteractorStyle(interactorStyle);

renderWindow.render(); 




// set resolution
var slider = document.querySelector('.slider');
slider.addEventListener('input', function (e) {
    var resolution = Number(e.target.value);
    cone.setResolution(resolution);
    renderWindow.render();
}); 