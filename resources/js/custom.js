// custom scripts
import tinymce from 'tinymce';

tinymce.baseURL = "/node_modules/tinymce/";

(window.activateTinyMCE = function () {
    tinymce.init({
        selector: '.tinymce',
        height: 300,
        menubar: true,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount',
            'image'
        ],
        toolbar:
                'undo redo | formatselect | bold italic backcolor | \
          alignleft aligncenter alignright alignjustify | \
          bullist numlist outdent indent | removeformat | help | image',
        setup: function (inst) {
            inst.on("change", function (ev) {
                var txtFld = document.getElementById(inst.id);
                txtFld.value = inst.getBody().innerHTML;
                txtFld.dispatchEvent(new Event('input', {bubbles: true}));
                //tinymce.get(inst.id).setContent(inst.getBody().innerHTML);
            });
        }
    });
})();

// append tinymce refresh on submit buttons
var buttons = document.querySelectorAll('.btn-submit-form-has-tinymce');

buttons.forEach(function (button) {
    button.onclick = function (ev) {
        setTimeout(function () {
            activateTinyMCE();
        }, 1000);
    };
});