var fnServerParams = {};
(function(){
  "use strict";
    fnServerParams = {
        "email_id": '[name="email_id"]',
    }

    init_leads_table();
   $.get(admin_url + 'ma/get_data_email_chart/'+$('input[name=email_id]').val()).done(function(res) {
    res = JSON.parse(res);

    Highcharts.chart('container', {
      chart: {
          type: 'area'
      },
      title: {
          text: 'Email Stats'
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
              text: ''
          }
      },
      credits: {
          enabled: false
      },
      series: res.data_email
    });

    Highcharts.chart('container_campaign', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Email Stats by Campaign'
    },
    xAxis: {
        categories: res.data_email_by_campaign.header,
        crosshair: true
    },
    yAxis: {
        title: {
            useHTML: true,
            text: ''
        }
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
    series: res.data_email_by_campaign.data
});
  });

   appValidateForm($('#send-example-email-form'),{
    send_to_email:'required',
  });

  })(jQuery);

function init_leads_table() {
  "use strict";

  if ($.fn.DataTable.isDataTable('.table-leads-emails')) {
    $('.table-leads-emails').DataTable().destroy();
  }
  initDataTable('.table-leads-emails', admin_url + 'ma/leads_table', false, false, fnServerParams);
}

