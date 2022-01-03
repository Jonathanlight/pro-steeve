$(document).ready(function(){
    tinyMCEInit('.wysiwyg');
});
function tinyMCEInit(selector){
    tinymce.init({
        selector: selector,
        height: 100,
        language: 'fr_FR',
        menubar: false,
        statusbar: false,
        browser_spellcheck: true,
        plugins: [
            'fullscreen nonbreaking',
            'table contextmenu paste textcolor'
        ],
        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | fullpage | forecolor backcolor | fullscreen'
    });
}