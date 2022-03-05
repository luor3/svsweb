// Load the rendering pieces we want to use (for both WebGL and WebGPU)
import '@kitware/vtk.js/Rendering/Profiles/Geometry';


import vtkFullScreenRenderWindow from '@kitware/vtk.js/Rendering/Misc/FullScreenRenderWindow';
import vtkActor from '@kitware/vtk.js/Rendering/Core/Actor';
import vtkMapper from '@kitware/vtk.js/Rendering/Core/Mapper';
import vtkColorMaps from '@kitware/vtk.js/Rendering/Core/ColorTransferFunction/ColorMaps';
import vtkColorTransferFunction from '@kitware/vtk.js/Rendering/Core/ColorTransferFunction';
import vtkXMLPolyDataReader from '@kitware/vtk.js/IO/XML/XMLPolyDataReader';
import vtkDataArray from '@kitware/vtk.js/Common/Core/DataArray';
import vtkPolyDataReader from '@kitware/vtk.js/IO/Legacy/PolyDataReader';
import vtkXMLPolyDataWriter from '@kitware/vtk.js/IO/XML/XMLPolyDataWriter';
import vtkXMLWriter from '@kitware/vtk.js/IO/XML/XMLWriter';
import vtkScalarBarActor from '@kitware/vtk.js/Rendering/Core/ScalarBarActor';
import {
  ColorMode,
  ScalarMode,
} from '@kitware/vtk.js/Rendering/Core/Mapper/Constants';

// ----------------------------------------------------------------------------
// Window creation
// ----------------------------------------------------------------------------

const viewContainer = document.querySelector('#custom-vtk-viewer');
const controlContainer = document.querySelector('#custom-control');
const jobContent = document.querySelector('#geometric-file-path');

if(viewContainer && controlContainer) {
  cus_vtkloader();
}

function cus_vtkloader() {
  var fileName;
  if(jobContent) {
    fileName = jobContent.value;
  }


  const background = [0, 0, 0];
  //const viewContainer = document.querySelector('#custom-vtk-viewer');
  //const controlContainer = document.querySelector('#custom-control');

  const fullScreenRenderer = vtkFullScreenRenderWindow.newInstance({
    background,
    rootContainer: viewContainer,
    containerStyle: { height: '600px', width: '70%', margin: 'auto' },
  });

  const renderer = fullScreenRenderer.getRenderer();
  const renderWindow = fullScreenRenderer.getRenderWindow();
  renderWindow.getInteractor().setDesiredUpdateRate(15);


  // ----------------------------------------------------------------------------
  // UI Component creation
  // ----------------------------------------------------------------------------

  const presetSelector = document.createElement('select');
  presetSelector.innerHTML = vtkColorMaps.rgbPresetNames
    .map(
      (name) =>
        `<option value="${name}">${name}</option>`
    )
    .join('');
  presetSelector.value = 'erdc_rainbow_bright';


  const colorBySelector = document.createElement('select');

  const componentSelector = document.createElement('select');
  componentSelector.style.display = 'none';

  const representationSelector = document.createElement('select');
  representationSelector.innerHTML = [
    'Hidden',
    'Points',
    'Wireframe',
    'Surface',
    'Surface with Edge',
  ]
    .map(
      (name, idx) =>
        `<option value="${idx === 0 ? 0 : 1}:${idx < 4 ? idx - 1 : 2}:${idx === 4 ? 1 : 0
        }">${name}</option>`
    )
    .join('');
  representationSelector.value = '1:2:0';

  controlContainer.appendChild(representationSelector);
  controlContainer.appendChild(presetSelector);
  controlContainer.appendChild(colorBySelector);
  controlContainer.appendChild(componentSelector);
  

  let scalarBarActor = vtkScalarBarActor.newInstance();
  renderer.addActor(scalarBarActor);


  if(jobContent) {
    const vtpReader = vtkXMLPolyDataReader.newInstance();
    try {
      vtpReader.setUrl(fileName, { loadData: true }).then(() => {
        pipelineCore(vtpReader.getOutputData(0));
    
      });
    } catch (error) {
      var newScript = document.createElement("script");
      var inlineScript = document.createTextNode("alert('File Not Found!');");
      newScript.appendChild(inlineScript); 
      controlContainer.appendChild(newScript);
    }
  }
  else {
    var fileUpload = document.createElement('input');
    fileUpload.type = 'file';
    fileUpload.accept = '.vtp';
    controlContainer.appendChild(fileUpload);

    function uploadFileChanged(e) {
      if(e.target.files.length === 0) return;
      //const filecontent = new Blob([fileContent], { type: 'text/plain' });
      const file = e.target.files[0];
      console.log(e.target.files.length);
      const reader = new FileReader();
      reader.onload = function onLoad(e) {
        var vtpReader = vtkXMLPolyDataReader.newInstance();
        vtpReader.parseAsArrayBuffer(reader.result);
        pipelineCore(vtpReader.getOutputData(0));

        //fileUpload.parentNode.removeChild(fileUpload);
        //fileUpload = document.createElement('input');
        //controlContainer.appendChild(fileUpload);
      };
      reader.readAsArrayBuffer(file);
    }
    fileUpload.addEventListener('change', uploadFileChanged);
  }


  // ----------------------------------------------------------------------------
  // File Reading
  // ----------------------------------------------------------------------------

  //const vtpReader = vtkXMLPolyDataReader.newInstance();

  // vtpReader.setUrl('example2.vtp', { loadData: true }).then(() => {
  //   pipelineCore(vtpReader.getOutputData(0));

  // });

  //const legacyVtkReader = vtkPolyDataReader.newInstance();
  //legacyVtkReader.setUrl(`${fileName}`).then(() => {

    //const polydata = legacyVtkReader.getOutputData(0);

    // const writer = vtkXMLPolyDataWriter.newInstance();
    // writer.setFormat(vtkXMLWriter.FormatTypes.BINARY);
    // writer.setInputConnection(legacyVtkReader.getOutputPort());  

    // const fileContents = writer.write(polydata);

    // const textEncoder = new TextEncoder();
    // vtpReader.parseAsArrayBuffer(textEncoder.encode(fileContents));

    // console.log(vtpReader.getOutputData(0).getPointData().getArray());
    //pipelineCore(polydata);

    // const blob = new Blob([fileContents], { type: 'text/plain' });
    // const a = window.document.createElement('a');
    // a.href = window.URL.createObjectURL(blob, { type: 'text/plain' });
    // a.download = 'a.vtp';
    // a.text = 'Download';
    // a.style.position = 'absolute';
    // a.style.left = '50%';
    // a.style.bottom = '10px';
    // document.body.appendChild(a);
    // a.style.background = 'white';
    // a.style.padding = '5px';

  //});






  // ----------------------------------------------------------------------------
  // Pipeline function
  // ----------------------------------------------------------------------------
  function pipelineCore(polydata) {
    const lookupTable = vtkColorTransferFunction.newInstance();
    const source = polydata;
    const mapper = vtkMapper.newInstance({
      interpolateScalarsBeforeMapping: false,
      useLookupTableScalarRange: true,
      lookupTable,
      scalarVisibility: false,
    });
    const actor = vtkActor.newInstance();
    const scalars = source.getPointData().getScalars();

    const dataRange = [].concat(scalars ? scalars.getRange() : [0, 1]);
    let activeArray = vtkDataArray;

    function applyPreset() {
      const preset = vtkColorMaps.getPresetByName(presetSelector.value);
      lookupTable.applyColorMap(preset);
      lookupTable.setMappingRange(dataRange[0], dataRange[1]);
      lookupTable.updateRange();
      renderWindow.render();
    }
    applyPreset();
    presetSelector.addEventListener('change', applyPreset);


    function updateRepresentation(event) {
      const [visibility, representation, edgeVisibility] = event.target.value
        .split(':')
        .map(Number);
      actor.getProperty().set({ representation, edgeVisibility });
      actor.setVisibility(!!visibility);
      renderWindow.render();
    }
    representationSelector.addEventListener('change', updateRepresentation);



    const colorByOptions = [{ value: ':', label: 'Solid color' }].concat(
      source
        .getPointData()
        .getArrays()
        .map((a) => ({
          label: `(p) ${a.getName()}`,
          value: `PointData:${a.getName()}`,
        })),
      source
        .getCellData()
        .getArrays()
        .map((a) => ({
          label: `(c) ${a.getName()}`,
          value: `CellData:${a.getName()}`,
        }))
    );

    colorBySelector.innerHTML = colorByOptions
      .map(
        ({ label, value }) =>
          `<option value="${value}">${label}</option>`
      )
      .join('');



    function updateColorBy(event) {
      const [location, colorByArrayName] = event.target.value.split(':');
      const interpolateScalarsBeforeMapping = location === 'PointData';
      let colorMode = ColorMode.DEFAULT;
      let scalarMode = ScalarMode.DEFAULT;
      const scalarVisibility = location.length > 0;
      if (scalarVisibility) {
        const newArray =
          source[`get${location}`]().getArrayByName(colorByArrayName);
        activeArray = newArray;
        const newDataRange = activeArray.getRange();
        dataRange[0] = newDataRange[0];
        dataRange[1] = newDataRange[1];
        colorMode = ColorMode.MAP_SCALARS;
        scalarMode =
          location === 'PointData'
            ? ScalarMode.USE_POINT_FIELD_DATA
            : ScalarMode.USE_CELL_FIELD_DATA;

        const numberOfComponents = activeArray.getNumberOfComponents();
        if (numberOfComponents > 1) {
          // always start on magnitude setting
          if (mapper.getLookupTable()) {
            const lut = mapper.getLookupTable();
            lut.setVectorModeToMagnitude();
          }
          componentSelector.style.display = 'block';
          const compOpts = ['Magnitude'];
          while (compOpts.length <= numberOfComponents) {
            compOpts.push(`Component ${compOpts.length}`);
          }
          componentSelector.innerHTML = compOpts
            .map((t, index) => `<option value="${index - 1}">${t}</option>`)
            .join('');
        } else {
          componentSelector.style.display = 'none';
        }
        scalarBarActor.setAxisLabel(colorByArrayName);
        scalarBarActor.setVisibility(true);
      } else {
        componentSelector.style.display = 'none';
        scalarBarActor.setVisibility(false);
      }

      mapper.set({
        colorByArrayName,
        colorMode,
        interpolateScalarsBeforeMapping,
        scalarMode,
        scalarVisibility,
      });
      applyPreset();
    }
    colorBySelector.addEventListener('change', updateColorBy);
    updateColorBy({ target: colorBySelector });


    function updateColorByComponent(event) {
      if (mapper.getLookupTable()) {
        const lut = mapper.getLookupTable();
        if (event.target.value === -1) {
          lut.setVectorModeToMagnitude();
        } else {
          lut.setVectorModeToComponent();
          lut.setVectorComponent(Number(event.target.value));
          const newDataRange = activeArray.getRange(Number(event.target.value));
          dataRange[0] = newDataRange[0];
          dataRange[1] = newDataRange[1];
          lookupTable.setMappingRange(dataRange[0], dataRange[1]);
          lut.updateRange();
        }
        renderWindow.render();
      }
    }
    componentSelector.addEventListener('change', updateColorByComponent);



    actor.setMapper(mapper);
    mapper.setInputData(source);
    renderer.addActor(actor);

    scalarBarActor.setScalarsToColors(mapper.getLookupTable());

    renderer.resetCamera();
    renderWindow.render();



    global.mapper = mapper;
    global.actor = actor;
    global.renderer = renderer;
    global.renderWindow = renderWindow;
  }
}
