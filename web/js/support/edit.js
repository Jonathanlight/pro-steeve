$(document).ready(function(){

    // Gestion de l'ajout/suppression des images d'un support
    $('.image-add').click(function(){
        var prototype = $('.images').data('prototype');
        var maxIndex = $('.images .image').length;
        var newForm = prototype.replace(/__name__/g, maxIndex);
        $('.images').append(newForm);
        maxIndex++;
        $('.image-delete').click(function(){
            $(this).closest('.image').remove();
        });
        $('#support_images_' + (maxIndex - 1) + '_name').focus();
        $('.image-add').hide();
    });
    $('.image-delete').click(function(){
        $(this).closest('.image').remove();
        $('.image-add').show();
    });
    if($('.images .image').length >= 1){
      $('.image-add').hide();
    }
});