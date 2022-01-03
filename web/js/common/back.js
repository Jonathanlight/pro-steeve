/**
 * Window history back for general backButton
 */
var goBack = function(){
  window.history.back();
};

/**
 * Set href for backButton in editForm -> this link fire onBeforeUnload
 */
(function($){
  $(document).ready(function(){
    $('.backButton').each(function(index, elm){
      $(elm).attr('href', document.referrer);
    });
  });
})(jQuery);