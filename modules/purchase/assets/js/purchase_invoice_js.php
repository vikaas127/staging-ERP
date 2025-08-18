<script>
function add_batch_payment() {
  console.log("add_batch_payment function called.");
console.log("admin_url:", admin_url + "purchase/invoices/batch_payment_modal");

  $("#modal-wrapper").load(
    admin_url + "purchase/pur_batch_payment_modal",
    function () {
      console.log("Batch payment modal content loaded.");

      if ($("#batch-payment-modal").is(":hidden")) {
        console.log("Opening batch payment modal.");
        $("#batch-payment-modal").modal({
          backdrop: "static",
          show: true,
        });
      }

      console.log("Initializing select picker and date picker.");
      init_selectpicker();
      init_datepicker();

      var $filterByClientSelect = $("#batch-payment-filter");
      console.log("Batch payment filter initialized.");

      $filterByClientSelect.on("changed.bs.select", function () {
        console.log("Filter selection changed. Selected value:", $filterByClientSelect.val());

        if ($filterByClientSelect.val() !== "") {
          $(".batch_payment_item").each(function () {
            if ($(this).data("clientid") == $filterByClientSelect.val()) {
              console.log("Showing batch payment item for client ID:", $(this).data("clientid"));
              $(this).find("input, select").prop("disabled", false);
              $(this).removeClass("hide");
            } else {
              console.log("Hiding batch payment item for client ID:", $(this).data("clientid"));
              $(this).addClass("hide");
              $(this).find("input, select").prop("disabled", true);
            }
          });
        } else {
          console.log("No filter selected. Showing all batch payment items.");
          $(".batch_payment_item").each(function () {
            $(this).removeClass("hide");
            $(this).find("input, select").prop("disabled", false);
          });
        }
      });

      console.log("Validating batch payment form.");
      appValidateForm($("#batch-payment-form"), {});

      $(".batch_payment_item").each(function () {
        var invoiceLine = $(this).find('[name^="invoice"]');
        console.log("Processing batch payment item.");

        invoiceLine
          .filter('select[name$="[paymentmode]"],input[name$="[amount]"]')
          .each(function () {
            var field = $(this);
            console.log("Adding validation rules to:", field.attr("name"));

            field.rules("add", {
              required: function () {
                var isRequired = false;
                var rowFields = field.closest(".batch_payment_item").find("input, select");

                rowFields
                  .filter(
                    'select[name$="[paymentmode]"],input[name$="[transactionid]"],input[name$="[amount]"]'
                  )
                  .each(function () {
                    if ($(this).val() != "") {
                      isRequired = true;
                    }

                    if ($(this).hasClass("selectpicker") && isRequired) {
                      console.log("Setting field as required:", field.attr("name"));
                      field.prop("required", true);
                      $(this).selectpicker("refresh");
                    }
                  });

                return isRequired;
              },
            });
          });
      });

      console.log("Batch payment modal setup complete.");
    }
  );
}
</script>