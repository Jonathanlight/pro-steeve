$(document).ready(function(){
    //Polyfill assign for IE
    if (typeof Object.assign != 'function') {
        Object.assign = function (target, varArgs) { // .length of function is 2
            'use strict';
            if (target == null) { // TypeError if undefined or null
                throw new TypeError('Cannot convert undefined or null to object');
            }

            var to = Object(target);

            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];

                if (nextSource != null) { // Skip over if undefined or null
                    for (var nextKey in nextSource) {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        };
    }

    // Définition des paramètres génériques
    var genericOptions = {
        lengthMenu: [[10, 20, 30, 50, 100, 500], [10, 20, 30, 50, 100, 500]],
        pageLength: 20,
        order: [],
        select: true,
        columnDefs: [],
        language: {
            processing: 'Traitement en cours...',
            search: '',
            searchPlaceholder: 'Filtrer',
            lengthMenu: 'Afficher _MENU_ éléments',
            emptyTable: 'Aucune donnée',
            zeroRecords: 'Aucun résultat',
            info: 'Nombre d\'élements : _TOTAL_',
            infoEmpty: 'Nombre d\'élements : _TOTAL_',
            infoFiltered: '<br/>Total : _MAX_',
            paginate: {
                first: 'Premier',
                previous: 'Précédent',
                next: 'Suivant',
                last: 'Dernier'
            },
            decimal: '.',
            thousands: ' ',
            select: {
                rows: {
                    _: '<br/>Éléments sélectionnés : %d',
                    0: '',
                    1: '<br/>Élément sélectionné : %d'
                }
            }
        }
    };


    var tableId = 0;
    // Initialisation des tables
    $('.dyn-table').each(function(){
      var options = {};
      var $tableDyn = $(this);

      // Assignation des options
      if(!$tableDyn.data('source')){
        Object.assign(options, genericOptions);
      }
      else{
        Object.assign(options, genericOptions, {
          processing: true,
          serverSide: true,
          ajax: $tableDyn.data('source')
        });
      }

      // Désactivation des tris sur colonnes
      if($tableDyn.data('disable-ordering')){
        if($tableDyn.data('disable-ordering').length > 1){
          var split = $tableDyn.data('disable-ordering').split('-');
          for(var i in split){
            options.columnDefs.push({ "orderable": false, "targets": parseInt(split[i]) });
          }
        } else {
          options.columnDefs.push({ "orderable": false, "targets": $tableDyn.data('disable-ordering') });
        }
      }

      // Override du page length par défaut
      if($tableDyn.data('page-length')){
        options.pageLength = parseInt($tableDyn.data('page-length'));
      }

      var table = $tableDyn.DataTable(options);

      // Création des filtres de colonnes
      if($tableDyn.data('filter')){
        // Désactivation du champ de recherche global
        $tableDyn.closest('.dataTables_wrapper').find('.dataTables_filter').css('display', 'none');
        var setFilterAction = function(colNumber){
          $('#dyn-filter-' + tableId + '-col-' + colNumber).on('keyup', function(){
            table.column(colNumber).search($(this).val()).draw();
          });
        };
        $tableDyn.closest('.dataTables_wrapper').before('<div class="row"><div id="dyn-filter-table-'+tableId+'" class="col-xs-12" style="margin-bottom: 30px;"><h3>Filtres</h3><form class="form-inline"><div class="form-group"></div></form></div></div>');
        var split = $tableDyn.data('filter').split('-');
        for(var i in split){
          $('#dyn-filter-table-'+tableId+' .form-group').append('<input type="text" class="form-control" style="min-width: 182px; display: inline-block; margin: 0 5px 10px 0;" placeholder="' + $tableDyn.find('th').eq(split[i]).text() + '" id="dyn-filter-' + tableId + '-col-' + split[i] + '" />');
          setFilterAction(split[i]);
        }

      }
      // Création des DateRange
      if($tableDyn.data('filter-date')){
        if($tableDyn.data('filter-date').toString().length >= 1){
          var split = $tableDyn.data('filter-date').toString().split('-');
          var initDateFilter = function(id){
            var element = $('#dyn-filter-' + tableId + '-col-' + id);
            initDateRangePicker(element);
            element.on('change', function(){table.column(id).search(element.val()).draw();});
          };
          for(var i in split){
            initDateFilter(split[i]);
          }
        } else {
          initDateRangePicker($('#dyn-filter-' + tableId + '-col-' + $tableDyn.data('filter-date')));
        }
      }

      tableId++;
    });
});

$(window).load(function(){
  //Click row
  $('.dyn-table').on('click', 'tbody td', function() {
    if($(this).closest('tr').data('href')){
      window.location = $(this).closest('tr').data('href').toString();
    }
  });
});