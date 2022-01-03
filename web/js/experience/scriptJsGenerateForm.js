$(document).ready(function(){
    $('.slider-cursor').each(function(){
        var unit = $(this).data('unit');
        var id = $(this).data('slider-id');

        $(this).bootstrapSlider({
            formatter: function(value) {
                $('#' + id + '_value').remove();
                return value + ' ' + unit;
            }
        });
    });

    var showHideChild = function($parent){
       var targetValue = $parent.val();
       if($parent.is('[type="checkbox"]')){
           if($parent.is(':checked')){
               targetValue = "True";
           }else{
             targetValue = "False";
           }
       }
      //On cache les dépendances
      $('.child[data-parent="'+$parent.attr('id').replace('experience_','')+'"]').closest('.container_child').hide();
      //Trouve les enfants à afficher
      $('.child[data-parent="'+$parent.attr('id').replace('experience_','')+'"]').each(function(index, elm){
        var possibilities = $(elm).data('parentvalue').toString().split(',');
        console.log(targetValue.toString() +  ' = ' + possibilities);
        if(possibilities.indexOf(targetValue.toString()) !== -1 || possibilities[0] === ""){
          $(elm).closest('.container_child').show();
        }
      });
    };

    $('.parent').each(function(index, elm){
        showHideChild($(elm));
        //Enregistrement des évenements parent
        $(elm).off('change').on('change', function(){
            showHideChild($(this));
        });
    });

});