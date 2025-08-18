<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#discount_modal">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_discount'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                            _l('id'),
                            _l('discount_name'),
                            _l('discount_type'),
                            _l('discount_value'),
                            _l('start_date'),
                            _l('end_date'),
                            _l('active'),
                            _l('options'),
                        ], 'discounts'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="discount_modal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('admin/discounts/manage', ['id' => 'discount_form']); ?>
            <?php echo form_hidden('discountid'); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="discountModalLabel">
                    <span class="edit-title"><?php echo _l('edit_discount'); ?></span>
                    <span class="add-title"><?php echo _l('new_discount'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <?php echo render_input('name', 'discount_name'); ?>
                <?php echo render_textarea('description', 'description'); ?>
                <div class="form-group">
                    <label for="discount_type"><?php echo _l('discount_type'); ?></label>
                    <select name="discount_type" class="form-control" required>
                        <option value="percent"><?php echo _l('percent'); ?></option>
                        <option value="fixed"><?php echo _l('fixed'); ?></option>
                    </select>
                </div>
                <?php echo render_input('discount_value', 'discount_value', '', 'number'); ?>
                <?php echo render_date_input('start_date', 'start_date'); ?>
                <?php echo render_date_input('end_date', 'end_date'); ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="active" id="active" value="1" checked>
                    <label for="active"><?php echo _l('active'); ?></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(function() {
    initDataTable('.table-discounts', admin_url + 'discounts/table', [6], [6]);

    appValidateForm($('#discount_form'), {
        name: 'required',
        discount_value: 'required',
    }, manage_discount);

    $('#discount_modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        $('#discount_form')[0].reset();
        $('#discount_form input[name="discountid"]').val('');
        $('#discount_modal .add-title').removeClass('hide');
        $('#discount_modal .edit-title').addClass('hide');

        if (id !== undefined) {
            var row = $(button).closest('tr');
            $('#discount_form input[name="discountid"]').val(id);
            $('#discount_form input[name="name"]').val(row.find('td').eq(1).text().trim());
            $('#discount_form select[name="discount_type"]').val(row.find('td').eq(2).text().trim());
            $('#discount_form input[name="discount_value"]').val(row.find('td').eq(3).text().trim());
            $('#discount_form input[name="start_date"]').val(row.find('td').eq(4).text().trim());
            $('#discount_form input[name="end_date"]').val(row.find('td').eq(5).text().trim());
            $('#discount_form input[name="active"]').prop('checked', row.find('td').eq(6).text().trim() == 'Yes');

            $('#discount_modal .add-title').addClass('hide');
            $('#discount_modal .edit-title').removeClass('hide');
        }
    });
});

function manage_discount(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success) {
            $('.table-discounts').DataTable().ajax.reload();
            alert_float('success', response.message);
        } else {
            alert_float('warning', response.message || 'Something went wrong');
        }
        $('#discount_modal').modal('hide');
    });
    return false;
}
</script>
</body>
</html>
