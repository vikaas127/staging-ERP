<script type="text/javascript">
    var fnServerParams = {};

    (function($) {
    	"use strict";

        $.each($('._hidden_inputs._filters input'),function(){
            fnServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
        });

        
        init_stage_manage();

        $('.add-new-stage').on('click', function(){

          $('#stage-modal').find('button[type="submit"]').prop('disabled', false);
          $('#stage-modal').modal('show');
          $('input[name="id"]').val('');
          $('input[name="name"]').val('');
          $('input[name="weight"]').val('');
          $('input[name="color"]').val('');
          $('textarea[name="description"]').val('');
        });

        appValidateForm($('#stage-form'), {
          name: 'required',
          weight: 'required',
          category: 'required',
        },stage_form_handler);

    })(jQuery);

    function edit_stage(id) {
      "use strict";
        $('#stage-modal').find('button[type="submit"]').prop('disabled', false);

      requestGetJSON(admin_url + 'ma/get_data_stage/'+id).done(function(response) {
          $('select[name="category"]').val(response.category).change();
          $('input[name="name"]').val(response.name);
          $('input[name="weight"]').val(response.weight);
          $('.colorpicker-input').colorpicker('setValue', response.color);
          $('input[name="id"]').val(id);
            $('textarea[name="description"]').val(response.description.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g, " "));

          $('#stage-modal').modal('show');

      });
    }

    function stage_form_handler(form) {
        "use strict";
        $('#stage-modal').find('button[type="submit"]').prop('disabled', true);

        var formURL = form.action;
        var formData = new FormData($(form)[0]);

        $.ajax({
            type: $(form).attr('method'),
            data: formData,
            mimeType: $(form).attr('enctype'),
            contentType: false,
            cache: false,
            processData: false,
            url: formURL
        }).done(function(response) {
            response = JSON.parse(response);
            if (response.success === true || response.success == 'true' || $.isNumeric(response.success)) {
              alert_float('success', response.message);
              init_stage_table();
            }
            $('#stage-modal').modal('hide');
        }).fail(function(error) {
            alert_float('danger', JSON.parse(error.mesage));
        });

        return false;
    }

    // custom view will fill input with the value
    function dt_stage_custom_view(value, table, custom_input_name, clear_other_filters) {
    "use strict";
        var name = typeof (custom_input_name) == 'undefined' ? 'custom_view' : custom_input_name;
        if (typeof (clear_other_filters) != 'undefined') {
            var filters = $('._filter_data li.active').not('.clear-all-prevent');
            filters.removeClass('active');
            $.each(filters, function () {
                var input_name = $(this).find('a').attr('data-cview');
                $('._filters input[name="' + input_name + '"]').val('');
            });
        }
        var _cinput = do_filter_active(name);
        if (_cinput != name) {
            value = "";
        }
        $('input[name="' + name + '"]').val(value);

        <?php if($group == 'list'){ ?>
            $(table).DataTable().ajax.reload();
        <?php }elseif($group == 'chart'){ ?>
            init_stage_chart();
        <?php }else{ ?>
            stage_kanban();
        <?php } ?>
    }

    function init_stage_manage(){
    "use strict";
        <?php if($group == 'list'){ ?>
            init_stage_table();
        <?php }elseif($group == 'chart'){ ?>
            init_stage_chart();
        <?php }else{ ?>
            stage_kanban();
        <?php } ?>
    }

    function init_stage_table() {
      "use strict";

      if ($.fn.DataTable.isDataTable('.table-stages')) {
        $('.table-stages').DataTable().destroy();
      }
      initDataTable('.table-stages', admin_url + 'ma/stage_table', false, false, fnServerParams);
    }

    function init_stage_chart() {
    "use strict";

        $.each($('._hidden_inputs._filters input'),function(){
            fnServerParams[$(this).attr('name')] = $(this).val();
        });

        fnServerParams[$('input[name=csrf_token_name]').val()] = $('input[name=csrf_token_hash]').val();

        $.post(admin_url + 'ma/get_data_stage_chart', fnServerParams).done(function(res) {
        res = JSON.parse(res);
        
          Highcharts.chart('container_pie', {
            chart: {
              type: 'pie',
              options3d: {
                enabled: true,
                alpha: 45
              }
            },
            title: {
              text: '<?php echo _l('pie_statistics'); ?>'
            },
            plotOptions: {
              pie: {
                innerSize: 100,
                depth: 45
              }
            },
            credits: {
                enabled: false
            },
            series: [{
                innerSize: '20%',
                name: '<?php echo _l('stage'); ?>',
                data: res.data_stage_pie
              }]
          });

          Highcharts.chart('container_column', {
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo _l('column_statistics'); ?>'
            },
            xAxis: {
                categories: res.data_stage_column.header
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: '<?php echo _l('total'); ?>'
                }
            },
            legend: {
        enabled: false
    },
            tooltip: {
                headerFormat: '<span class="font-size-10">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};" class="no-padding">{series.name}: </td>' +
                    '<td class="no-padding"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: "stage",
                colorByPoint: true,
                data: res.data_stage_column.data}
            ]
          });
        });
}


function stage_kanban_update(ui, object) {
  "use strict";
  if (object === ui.item.parent()[0]) {
      var data = {};
      data.category = $(ui.item.parent()[0]).parents('.stage-column').data('col-category-id');
      data.stage_id = $(ui.item).data('stage-id');

      check_stage_kanban_empty_col('[data-stage-id]');

      setTimeout(function() {
          $.post(admin_url + 'ma/update_stage_category', data)
      }, 50);
  }
}

function check_stage_kanban_empty_col(selector) {
    "use strict";
    var statuses = $('[data-col-category-id]');
    $.each(statuses, function (i, obj) {
        var total = $(obj).find(selector).length;
        if (total == 0) {
            $(obj).find('.kanban-empty').removeClass('hide');
            $(obj).find('.kanban-load-more').addClass('hide');
        } else {
            $(obj).find('.kanban-empty').addClass('hide');
        }
    });
}

function stage_kanban() {
  "use strict";
  init_stage_kanban('ma/stage_kanban', stage_kanban_update, '.stage-kanban', 445, 360, after_stage_kanban);
}

function after_stage_kanban() {
  "use strict";
  for (var i = -10; i < $('.task-phase').not('.color-not-auto-adjusted').length / 2; i++) {
      var r = 120;
      var g = 169;
      var b = 56;
      $('.task-phase:eq(' + (i + 10) + ')').not('.color-not-auto-adjusted').css('background', color(r - (i * 13), g - (i * 13), b - (i * 13))).css('border', '1px solid ' + color(r - (i * 12), g - (i * 12), b - (i * 12)));
  };
}

// General function to init kan ban based on settings
function init_stage_kanban(url, callbackUpdate, connect_with, column_px, container_px, callback_after_load) {
  "use strict";
    if ($('#kan-ban').length === 0) { return; }
    var parameters = [];
    var _kanban_param_val;

    $.each($('#kanban-params input'), function() {
        if ($(this).attr('type') == 'checkbox') {
            _kanban_param_val = $(this).prop('checked') === true ? $(this).val() : '';
        } else {
            _kanban_param_val = $(this).val();
        }
        if (_kanban_param_val !== '') {
            parameters[$(this).attr('name')] = _kanban_param_val;
        }
    });


    $.each($('#kanban-params select'), function() {
        _kanban_param_val = $(this).val();

        if (_kanban_param_val !== '') {
            parameters[$(this).attr('name')] = _kanban_param_val;
        }
    });

    var search = $('input[name="search"]').val();
    if (typeof(search) != 'undefined' && search !== '') { parameters['search'] = search; }

    var sort_type = $('input[name="sort_type"]');
    var sort = $('input[name="sort"]').val();
    if (sort_type.length != 0 && sort_type.val() !== '') {
        parameters['sort_by'] = sort_type.val();
        parameters['sort'] = sort;
    }

    parameters['kanban'] = true;
    url = admin_url + url;
    url = buildUrl(url, parameters);
    delay(function() {
        $("body").append('<div class="dt-loader"></div>');
        $('#kan-ban').load(url, function() {

            fix_kanban_height(column_px, container_px);
            var scrollingSensitivity = 20,
                scrollingSpeed = 60;

            if (typeof(callback_after_load) != 'undefined') { callback_after_load(); }

            $(".status").sortable({
                connectWith: connect_with,
                helper: 'clone',
                appendTo: '#kan-ban',
                placeholder: "ui-state-highlight-card",
                revert: 'invalid',
                scrollingSensitivity: 50,
                scrollingSpeed: 70,
                sort: function(event, uiHash) {
                    var scrollContainer = uiHash.placeholder[0].parentNode;
                    // Get the scrolling parent container
                    scrollContainer = $(scrollContainer).parents('.kan-ban-content-wrapper')[0];
                    var overflowOffset = $(scrollContainer).offset();
                    if ((overflowOffset.top + scrollContainer.offsetHeight) - event.pageY < scrollingSensitivity) {
                        scrollContainer.scrollTop = scrollContainer.scrollTop + scrollingSpeed;
                    } else if (event.pageY - overflowOffset.top < scrollingSensitivity) {
                        scrollContainer.scrollTop = scrollContainer.scrollTop - scrollingSpeed;
                    }
                    if ((overflowOffset.left + scrollContainer.offsetWidth) - event.pageX < scrollingSensitivity) {
                        scrollContainer.scrollLeft = scrollContainer.scrollLeft + scrollingSpeed;
                    } else if (event.pageX - overflowOffset.left < scrollingSensitivity) {
                        scrollContainer.scrollLeft = scrollContainer.scrollLeft - scrollingSpeed;

                    }
                },
                change: function() {
                    var list = $(this).closest('ul');
                    var KanbanLoadMore = $(list).find('.kanban-load-more');
                    $(list).append($(KanbanLoadMore).detach());
                },
                start: function(event, ui) {
                    $('body').css('overflow', 'hidden');

                    $(ui.helper).addClass('tilt');
                    $(ui.helper).find('.panel-body').css('background', '#fbfbfb');
                    // Start monitoring tilt direction
                    tilt_direction($(ui.helper));
                },
                stop: function(event, ui) {
                    $('body').removeAttr('style');
                    $(ui.helper).removeClass("tilt");
                    // Unbind temporary handlers and excess data
                    $("html").off('mousemove', $(ui.helper).data("move_handler"));
                    $(ui.helper).removeData("move_handler");
                },
                update: function(event, ui) {
                    callbackUpdate(ui, this);
                }
            });

            $('.status').sortable({
                cancel: '.not-sortable'
            });

        });

    }, 200);
}
</script>