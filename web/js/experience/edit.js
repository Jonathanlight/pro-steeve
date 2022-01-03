$(document).ready(function(){

  //Modulo function who accept float
  var modulo = function(x, y){
    var resultat = Math.floor (x / y);
    return x - (resultat * y);
  };

  //Parameter consistency
  var isNumberConsistency = function(min, max, step, values){
    var consistency = true;
    $.each(values.split(','), function(index, value){
      if((value < min || value > max || modulo(value,step) !== 0) && value !== min && value !== max){
        consistency = false;
      }
    });
    return consistency;
  };
  var numberConsistency = function(){
    $('.form-parameterValueFloat .defaultValue').off('focusout');
    $('.form-parameterValueFloat .defaultValue').on('focusout', function(){
      var $defaultValue = $(this);
      if($defaultValue.val() != ''){
        var defaultValue = parseFloat($defaultValue.val());
        var min = parseFloat($defaultValue.closest('.form-parameterValueFloat').find('.min').val());
        var max = parseFloat($defaultValue.closest('.form-parameterValueFloat').find('.max').val());
        var step = parseFloat($defaultValue.closest('.form-parameterValueFloat').find('.step').val());
        if(isNumberConsistency(min, max, step, defaultValue) === false){
          alert("La valeur par défaut choisie n'est pas cohérente.");
          $defaultValue.val('');
          $defaultValue.focus();
        }
      }
    });
  };
  var listConsistency = function(){
    $('.value .defaultValue').off();
    $('.value input.value').on('keyup', function(){
      initFieldInterface($(this).closest('.parameter'), '1');
    });
    $('.value .defaultValue').on('change', function(){
      var $select = $(this);
      if($select.find('option:selected').val() == '1'){
        $select.closest('.values').find('.defaultValue').not($select).find('option[value="0"]').prop('selected', true);
      }
    });
  };

  /*
    Gestion de l'activation d'une dépendance en fonction de la valeur de son parent.
    Il s'agit de créer une interface (Float, List ou bool) en fonction du type du parent.
    L'initialisation de cette interface se fera au changement de type du parent.
   */
  var initFieldInterface = function($parameterDiv, type, init){
    if(typeof init == 'undefined'){
      init = false;
    }
    var $childParameterDiv = $parameterDiv.find('.children_parameters');
    var $field = null;

    switch(type){
      case '0'://Type Float
        $field = $('<input>');
        break;
      case '1'://Type List
        $field = $('<select multiple>');
        $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueList input.value').each(function() {
          $field.append($("<option>").attr('value',$(this).val()).text($(this).val()));
        });
        break;
      case '2'://Type Bool
        $field = $('<select>');
        $field.append($("<option>").attr('value','True').text('True'));
        $field.append($("<option>").attr('value','False').text('False'));
        break;
    }

    if($field !== null) {
      $field.addClass('form-control');
      $childParameterDiv.find('.parameter-parentValue .field-interface').html($field);
      $('.parameter-parentValue .field-interface select').off().on('change', function () {
        var value = '';
        $(this).find('option:selected').each(function(){
          value = value + $(this).val() + ',';
        });
        value = value.slice(0, -1);
        $(this).parent().parent().find('input.parent-value').val(value);
      });
      $('.parameter-parentValue .field-interface input').off().on('focusout', function () {
        $(this).parent().parent().find('input.parent-value').val($(this).val());
        if($(this).val() != '') {
          var $containerParentValues = $(this).closest('.parameter.parent').find('.parameterValues');
          var min = parseFloat($containerParentValues.find('.form-parameterValueFloat .min').val());
          var max = parseFloat($containerParentValues.find('.form-parameterValueFloat .max').val());
          var step = parseFloat($containerParentValues.find('.form-parameterValueFloat .step').val());
          if (isNumberConsistency(min, max, step, $(this).val()) === false) {
            alert("La valeur par défaut choisie n'est pas cohérente.");
            $(this).val('');
            $(this).focus();
          }
        }
      });
      $childParameterDiv.find('.parameter.child').each(function(index, elm){
        var $parentValueDiv = $(elm).find('.parameter-parentValue');

        if(init === true){ //Permet d'initialiser les interfaces en fonction de la valeur par défault transmi par le formType.
          if ($parentValueDiv.find('input.parent-value').val() != '') {
            if ($parentValueDiv.find('.field-interface .form-control').is('select')) {
              $.each($parentValueDiv.find('input.parent-value').val().split(','), function(index, value){
                $parentValueDiv.find('.field-interface option[value="' + value + '"]').attr('selected', true);
              });
            }
            else if ($parentValueDiv.find('.field-interface .form-control').is('input')) {
              $parentValueDiv.find('.field-interface input').val($parentValueDiv.find('input.parent-value').val());
            }
          }
        }

        var interfaceValue = '';
        if($parentValueDiv.find('.field-interface .form-control').is('select')){
          $parentValueDiv.find('.field-interface option:selected').each(function(){
            interfaceValue = interfaceValue + $(this).val() + ',';
          });
          interfaceValue = interfaceValue.slice(0, -1);
        }else if($parentValueDiv.find('.field-interface .form-control').is('input')){
          interfaceValue = $parentValueDiv.find('.field-interface input').val();
        }
        $parentValueDiv.find('input.parent-value').val(interfaceValue);
      });
    }
  };

  // Gestion de l'ajout/suppression des valeurs d'un paramètre
  var initPrototypeValueList = function(){
    $('.value-add').off().click(function(){
      var $clicked = $(this);
      var prototype = $clicked.parent().find('.values').data('prototype');
      var maxIndex = $clicked.parent().find('.values .value').length;
      var newForm = prototype.replace(/__list__/g, maxIndex);
      $clicked.parent().find('.values').append(newForm);
      maxIndex++;
      $clicked.parent().find('.value-delete').click(function(){
        $(this).closest('.value').remove();
      });
      $('#experience_parameters_' + $(this).closest('.parameter').index() + '_parameterValuesList_' + (maxIndex - 1) + '_value').focus();
      defineWeight();
      initSortable($clicked.parent().find('.values'));
      listConsistency();
      initFieldInterface($clicked.closest('.parameter'), '1');
    });
    $('.value-delete').click(function(){
      $(this).closest('.value').remove();
      defineWeight();
    });
  };
  initPrototypeValueList();

  //Sortable parameters in experience form
  var defineWeightValues = function($parameter){
    var i = 0;
    $parameter.find('.values .value').each(function(index, elm){
      $parameter.find('.value-weight input').val(i++);
    });
  };
  var defineWeight = function(){
    var i = 0;
    $('.parameters > .parameter').each(function(index, elm){
      $(elm).find('.parameter-weight input').val(i++);
      defineWeightValues($(elm));//Value list order for parent parameter
      var j = 0;
      $(elm).find('.children_parameters > .parameter').each(function(index, child){
        $(child).find('.parameter-weight input').val(j++);
        defineWeightValues($(child));//Value list order for child parameter
      });
    });
  };
  var initSortable = function($container){
    $container.sortable({
      cursor: "move",
      forcePlaceholderSize: true,
      placeholder: "sortable-placeholder",
      stop: function( event, ui ) {
        defineWeight();
      }
    }).disableSelection();
  };
  initSortable($(".parameters"));
  initSortable($(".children_parameters"));
  initSortable($(".values"));

  //Display parameterValueForm by parameterType selected + init Change function
  var showHideParameterValueForm = function($parameterType, init){
    if(typeof init == 'undefined'){
      init = false;
    }
    var $parameterDiv = $parameterType.closest('.parameter');
    switch($parameterType.find('option:selected').val()){
      case '0': $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueFloat').show(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueList').hide(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueBool').hide(); numberConsistency(); initFieldInterface($parameterDiv, '0', init); break;
      case '1': $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueFloat').hide(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueList').show(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueBool').hide(); initPrototypeValueList(); listConsistency(); initFieldInterface($parameterDiv, '1', init); break;
      case '2': $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueFloat').hide(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueList').hide(); $parameterDiv.find('> .panel > .panel-body > .parameterValues .form-parameterValueBool').show(); initFieldInterface($parameterDiv, '2', init); break;
    }
  };
  var initParameterTypeChange = function($elm){
    $elm.change(function(){
      showHideParameterValueForm($(this));
    });
    showHideParameterValueForm($elm, true);
  };
  $('.parameter').each(function(index, elm){
    initParameterTypeChange($(elm).find('.parameterType'));
  });

  // Gestion de l'ajout/suppression des parametres d'une expérience
  $('.parameter-add').click(function(){
      var prototype = $('.parameters').data('prototype');
      var maxIndex = $('.parameters .parameter').length;
      var newForm = prototype.replace(/__parameter__/g, maxIndex);
      $('.parameters').append(newForm);
      maxIndex++;
      $('.parameter-delete').click(function(){
          $(this).closest('.parameter').remove();
      });
      $('#experience_parameters_' + (maxIndex - 1) + '_name').focus();
      defineWeight();
      initParameterTypeChange($('#experience_parameters_' + (maxIndex - 1) + '_parameterType'));
      initPrototypeParameterChild();
  });
  $('.parameter-delete').click(function(){
      $(this).closest('.parameter').remove();
      defineWeight();
  });

  //Gestion de l'ajout/suppression des parametres ENFANT d'un parametre
  var initPrototypeParameterChild = function() {
    $('.parameter-child-add').click(function () {
      var $clicked = $(this);
      var prototype = $clicked.parent().find('.children_parameters').data('prototype');
      var maxIndex = $clicked.parent().find('.children_parameters .parameter').length;
      var newForm = prototype.replace(/__children__/g, maxIndex);
      $clicked.parent().find('.children_parameters').append(newForm);
      maxIndex++;
      $('.parameter-child-delete').click(function () {
        $(this).closest('.parameter').remove();
      });
      $('#experience_parameters_' + $clicked.closest('.parameter.parent').index() + '_children_' + (maxIndex - 1) + '_name').focus();
      defineWeight();
      initParameterTypeChange($('#experience_parameters_' + $clicked.closest('.parameter.parent').index() + '_children_' + (maxIndex - 1) + '_parameterType'));//Initialisation de l'enfant
      initFieldInterface($clicked.closest('.parameter.parent'), $clicked.closest('.parameter.parent').find('.parameterType').find('option:selected').val(), true);//Initialisation de la valeur par défaut du parent
    });
    $('.parameter-child-delete').click(function () {
      $(this).closest('.parameter').remove();
      defineWeight();
    });
  };
  initPrototypeParameterChild();


  // Gestion de l'ajout/suppression des images d'une expérience
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