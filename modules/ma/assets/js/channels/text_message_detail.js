var fnServerParams = {};
(function(){
  "use strict";
    fnServerParams = {
        "text_message_id": '[name="text_message_id"]',
    }

    init_leads_table();
   $.get(admin_url + 'ma/get_data_text_message_chart/'+$('input[name=text_message_id]').val()).done(function(res) {
    res = JSON.parse(res);

    Highcharts.chart('container_chart', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Text message over time'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        time: {
            timezone: $('input[name=timezone]').val()
        },
        xAxis: {
            type: 'datetime',
            labels: {
              format: '{value:%Y-%m-%d}',
              rotation: 45,
              align: 'left'
            }
        },
        yAxis: {
            title: {
                text: 'Text message'
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Text message',
            data: res.data_text_message
        }]
    });

    Highcharts.chart('container_campaign_chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Text message Stats by Campaign'
        },
        xAxis: {
            categories: res.data_text_message_by_campaign.header,
            crosshair: true
        },
        yAxis: {
            title: {
                useHTML: true,
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            headerFormat: '<span class="font-size-10">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};" class="no-padding">{series.name}: </td>' +
                '<td class="no-padding"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: res.data_text_message_by_campaign.data
    });
  });

  })(jQuery);

function init_leads_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-leads-email-template')) {
    $('.table-leads-email-template').DataTable().destroy();
  }
  initDataTable('.table-leads-email-template', admin_url + 'ma/leads_table', false, false, fnServerParams);
}