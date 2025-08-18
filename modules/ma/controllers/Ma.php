<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Marketing Automation Controller
 */
class Ma extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ma_model');
        hooks()->do_action('ma_init'); 
    }

    /**
     * Dashboard
     * @return view
     */
    public function dashboard(){
    
        $data['title'] = _l('dashboard');
        
        $this->load->view('dashboard/manage', $data);
    }

    /**
     * @return view
     */
    public function segments(){
        $data['title'] = _l('segments');

        $data['group'] = $this->input->get('group');

        if($data['group'] == ''){
            $data['group'] = 'list';
        }

        if ($data['group'] == 'chart') {
            $data['data_segment_pie'] = $this->ma_model->get_data_segment_pie_chart($data);
            $data['data_segment_column'] = $this->ma_model->get_data_segment_column_chart($data);
        }

        $data['categories'] = $this->ma_model->get_category('', 'segment');
        
        $data['view'] = 'segments/includes/' . $data['group'];

        $this->load->view('segments/manage', $data);
    }

    /**
     * setting
     * @return view
     */
    public function settings()
    {
        if (!has_permission('ma_setting', '', 'view')) {
            access_denied('setting');
        }
        
        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'category';
        $data['tab'][] = 'ma_email_templates';
        $data['tab'][] = 'text_messages';
        
        if ($data['group'] == '') {
            $data['group'] = 'category';
        }
        $data['title']        = _l($data['group']);
        $data['tabs']['view'] = 'settings/' . $data['group'];

        $this->load->view('settings/manage', $data);
    }

    /**
     * category table
     * @return json
     */
    public function category_table(){
        if ($this->input->is_ajax_request()) {
           
            $select = [
                'id',
                'name',
                'type',
            ];

            $where = [];
            $from_date = '';
            $to_date   = '';

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_categories';
            $join         = [];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['description']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $categoryOutput = $aRow['name'];

                $categoryOutput .= '<div class="row-options">';

                if (has_permission('ma_setting', '', 'edit')) {
                    $categoryOutput .= '<a href="#" onclick="edit_category('.$aRow['id'].'); return false;">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_setting', '', 'delete')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/delete_category/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }

                $categoryOutput .= '</div>';
                $row[] = $categoryOutput;
                $row[] = _l($aRow['type']);
                $row[] = $aRow['description'];

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or edit category
     * @return json
     */
    public function category(){
        $data = $this->input->post();
        $message = '';

        if($data['id'] == ''){
            if (!has_permission('ma_setting', '', 'create')) {
                access_denied('ma');
            }
            $success = $this->ma_model->add_category($data);
            if($success){
                $message = _l('added_successfully', _l('category'));
            }
        }else{
            if (!has_permission('ma_setting', '', 'edit')) {
                access_denied('ma');
            }
            $id = $data['id'];
            unset($data['id']);
            $success = $this->ma_model->update_category($data, $id);
            if ($success) {
                $message = _l('updated_successfully', _l('category'));
            }
        }
        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }

    /**
     * delete category
     * @param  integer $id
     * @return
     */
    public function delete_category($id)
    {
        if (!has_permission('ma_setting', '', 'delete')) {
            access_denied('ma_setting');
        }

        $success = $this->ma_model->delete_category($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('category'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/settings?group=category'));
    }

    /**
     * get data category
     * @param  integer $id 
     * @return json     
     */
    public function get_data_category($id){
        $category = $this->ma_model->get_category($id);

        echo json_encode($category);
    }

    /**
     * stage table
     * @return json
     */
    public function stage_table(){
        if ($this->input->is_ajax_request()) {
           
            $select = [
                'name',
                'category',
                'weight',
                'published',
            ];

            $where = [];

            // Filter by custom groups
            $categorys   = $this->ma_model->get_category('', 'stage');

            $categoryIds = [];
            $category_names = [];
            foreach ($categorys as $category) {
                if ($this->input->post('stage_category_' . $category['id'])) {
                    array_push($categoryIds, $category['id']);
                }
                $category_names[$category['id']] = $category['name'];
            }

            if (count($categoryIds) > 0) {
                array_push($where, 'AND category IN (' . implode(', ', $categoryIds) . ')');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_stages';
            $join         = [
            ];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_stages.id as id', 'color']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $categoryOutput = '<span style="color: '. $aRow['color'] .'">'.$aRow['name'].'</span>';

                $categoryOutput .= '<div class="row-options">';

                $categoryOutput .= '<a href="' . admin_url('ma/stage_detail/' . $aRow['id']) . '" class="">' . _l('view') . '</a>';

                if (has_permission('ma_stages', '', 'edit')) {
                    $categoryOutput .= ' | <a href="#" onclick="edit_stage('.$aRow['id'].'); return false;">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_stages', '', 'delete')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/delete_stage/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }

                $categoryOutput .= '</div>';
                $row[] = $categoryOutput;
                $row[] = $aRow['weight'];
                $row[] = ma_get_category_name($aRow['category']);

                $checked = '';
                if ($aRow['published'] == 1) {
                    $checked = 'checked';
                }

                $_data = '<div class="onoffswitch">
                    <input type="checkbox" ' . ((!has_permission('ma_setting', '', 'edit') && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'ma/change_stage_published" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                    <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                </div>';

                // For exporting
                $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
                $row[] = $_data;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or edit stage
     * @return json
     */
    public function stage(){
        $data = $this->input->post();
        $data['description'] = html_purify($this->input->post('description', false));

        $message = '';
        
        if($data['id'] == ''){
            if (!has_permission('ma_setting', '', 'create')) {
                access_denied('ma');
            }
            $success = $this->ma_model->add_stage($data);
            if($success){
                $message = _l('added_successfully', _l('stage'));
            }
        }else{
            if (!has_permission('ma_setting', '', 'edit')) {
                access_denied('ma');
            }
            $id = $data['id'];
            unset($data['id']);
            $success = $this->ma_model->update_stage($data, $id);
            if ($success) {
                $message = _l('updated_successfully', _l('stage'));
            }
        }
        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }

    /**
     * delete stage
     * @param  integer $id
     * @return
     */
    public function delete_stage($id)
    {
        if (!has_permission('ma_setting', '', 'delete')) {
            access_denied('ma_setting');
        }

        $success = $this->ma_model->delete_stage($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('stage'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/stages'));
    }

    /**
     * get data stage
     * @param  integer $id 
     * @return json     
     */
    public function get_data_stage($id){
        $stage = $this->ma_model->get_stage($id);

        echo json_encode($stage);
    }

    /**
     * stage management
     * @return view
     */
    public function stages(){
        $data['title'] = _l('stages');
        $data['group'] = $this->input->get('group');

        if($data['group'] == ''){
            $data['group'] = 'list';
        }

        if ($data['group'] == 'chart') {
            $data['data_stage_pie'] = $this->ma_model->get_data_stage_pie_chart($data);
            $data['data_stage_column'] = $this->ma_model->get_data_stage_column_chart($data);
        }

        $data['categories'] = $this->ma_model->get_category('', 'stage');
        
        $data['view'] = 'stages/includes/' . $data['group'];

        
        $this->load->view('stages/manage', $data);
    }

    /**
     * segment table
     * @return json
     */
    public function segment_table(){
        if ($this->input->is_ajax_request()) {
           
            $select = [
                'name',
                'id',
                'category',
                'published',
            ];

            $where = [];

            // Filter by custom groups
            $categorys   = $this->ma_model->get_category('', 'segment');

            $categoryIds = [];
            $category_names = [];
            foreach ($categorys as $category) {
                if ($this->input->post('segment_category_' . $category['id'])) {
                    array_push($categoryIds, $category['id']);
                }
                $category_names[$category['id']] = $category['name'];
            }

            if (count($categoryIds) > 0) {
                array_push($where, 'AND category IN (' . implode(', ', $categoryIds) . ')');
            }


            $from_date = '';
            $to_date   = '';

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_segments';
            $join         = [
        ];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['color']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $categoryOutput = '<span style="color: '. $aRow['color'] .'">'.$aRow['name'].'</span>';

                $categoryOutput .= '<div class="row-options">';
  
                $categoryOutput .= '<a href="' . admin_url('ma/segment_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';

                if (has_permission('ma_segments', '', 'edit')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/segment/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_segments', '', 'delete')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/delete_segment/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }

                $categoryOutput .= '</div>';
                $row[] = $categoryOutput;
                $row[] = count($this->ma_model->get_lead_by_segment($aRow['id']));

                if(isset($category_names[$aRow['category']])){
                    $row[] = $category_names[$aRow['category']];

                }else{
                    $row[] = '';
                }

                $checked = '';
                if ($aRow['published'] == 1) {
                    $checked = 'checked';
                }

                $_data = '<div class="onoffswitch">
                    <input type="checkbox" ' . ((!has_permission('ma_setting', '', 'edit') && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'ma/change_segment_published" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                    <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                </div>';

                // For exporting
                $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
                $row[] = $_data;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or update segment
     * @return view
     */
    public function segment($id = ''){
        if ($this->input->post()) {
            $data                = $this->input->post();
            $data['description'] = html_purify($this->input->post('description', false));

            if($id == ''){
                if (!has_permission('ma_segment', '', 'create')) {
                    access_denied('ma_segment');
                }
                $success = $this->ma_model->add_segment($data);
                if ($success) {
                    set_alert('success', _l('added_successfully', _l('segment')));
                }

                redirect(admin_url('ma/segment_detail/' . $success));
            }else{
                if (!has_permission('ma_segment', '', 'edit')) {
                    access_denied('ma_segment');
                }
                $success = $this->ma_model->update_segment($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('segment')));
                }

                redirect(admin_url('ma/segment_detail/' . $id));
            }
        }

        if($id != ''){
            $data['segment'] = $this->ma_model->get_segment($id);
        }

        $data['categories'] = $this->ma_model->get_category('', 'segment');

        $data['title'] = _l('segment');

        $this->load->view('segments/segment', $data);
    }

    /**
     * delete segment
     * @param  integer $id
     * @return
     */
    public function delete_segment($id)
    {
        if (!has_permission('ma_setting', '', 'delete')) {
            access_denied('ma_setting');
        }

        $success = $this->ma_model->delete_segment($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('segment'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/segments'));
    }

    /**
     * component
     * @return view
     */
    public function components()
    {
        if (!has_permission('ma_components', '', 'view')) {
            access_denied('setting');
        }
        
        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'assets';
        $data['tab'][] = 'forms';
        
        if ($data['group'] == '') {
            $data['group'] = 'assets';
        }

        if ($data['group'] == 'assets') {
            $data['categories'] = $this->ma_model->get_category('', 'asset');
        }else{
            $data['categories'] = $this->ma_model->get_category('', 'form');
        }

        $data['title']        = _l($data['group']);
        $data['tabs']['view'] = 'components/' . $data['group'];

        $this->load->view('components/manage', $data);
    }

    /**
     * add or edit form
     * @param  integer
     * @return view
     */
    public function form($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->ma_model->add_form($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('web_to_lead_form')));
                    redirect(admin_url('ma/form/' . $id));
                }
            } else {
                $success = $this->ma_model->update_form($id, $this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('web_to_lead_form')));
                }
                redirect(admin_url('ma/form/' . $id));
            }
        }

        $data['formData'] = [];
        $custom_fields    = get_custom_fields('leads', 'type != "link"');

        $cfields       = format_external_form_custom_fields($custom_fields);
        $data['title'] = _l('web_to_lead');

        if ($id != '') {
            $data['form'] = $this->ma_model->get_form([
                'id' => $id,
            ]);
            $data['title']    = $data['form']->name . ' - ' . _l('web_to_lead_form');
            $data['formData'] = $data['form']->form_data;
        }

        $this->load->model('roles_model');
        $this->load->model('leads_model');
        $data['roles']    = $this->roles_model->get();
        $data['sources']  = $this->leads_model->get_source();
        $data['statuses'] = $this->leads_model->get_status();

        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);

        $data['languages'] = $this->app->get_available_languages();
        $data['cfields']   = $cfields;

        $db_fields = [];
        $fields    = [
            'name',
            'title',
            'email',
            'phonenumber',
            'lead_value',
            'company',
            'address',
            'city',
            'state',
            'country',
            'zip',
            'description',
            'website',
        ];

        $fields = hooks()->apply_filters('lead_form_available_database_fields', $fields);

        $className = 'form-control';

        foreach ($fields as $f) {
            $_field_object = new stdClass();
            $type          = 'text';
            $subtype       = '';
            if ($f == 'email') {
                $subtype = 'email';
            } elseif ($f == 'description' || $f == 'address') {
                $type = 'textarea';
            } elseif ($f == 'country') {
                $type = 'select';
            }

            if ($f == 'name') {
                $label = _l('lead_add_edit_name');
            } elseif ($f == 'email') {
                $label = _l('lead_add_edit_email');
            } elseif ($f == 'phonenumber') {
                $label = _l('lead_add_edit_phonenumber');
            } elseif ($f == 'lead_value') {
                $label = _l('lead_add_edit_lead_value');
                $type  = 'number';
            } else {
                $label = _l('lead_' . $f);
            }

            $field_array = [
                'subtype'   => $subtype,
                'type'      => $type,
                'label'     => $label,
                'className' => $className,
                'name'      => $f,
            ];

            if ($f == 'country') {
                $field_array['values'] = [];

                $field_array['values'][] = [
                    'label'    => '',
                    'value'    => '',
                    'selected' => false,
                ];

                $countries = get_all_countries();
                foreach ($countries as $country) {
                    $selected = false;
                    if (get_option('customer_default_country') == $country['country_id']) {
                        $selected = true;
                    }
                    array_push($field_array['values'], [
                        'label'    => $country['short_name'],
                        'value'    => (int) $country['country_id'],
                        'selected' => $selected,
                    ]);
                }
            }

            if ($f == 'name') {
                $field_array['required'] = true;
            }

            $_field_object->label    = $label;
            $_field_object->name     = $f;
            $_field_object->fields   = [];
            $_field_object->fields[] = $field_array;
            $db_fields[]             = $_field_object;
        }
        $data['bodyclass'] = 'web-to-lead-form';
        $data['db_fields'] = $db_fields;
        $this->load->view('components/forms/formbuilder', $data);
    }

    /**
     * save form data
     * @return json
     */
    public function save_form_data()
    {
        $data = $this->input->post();

        // form data should be always sent to the request and never should be empty
        // this code is added to prevent losing the old form in case any errors
        if (!isset($data['formData']) || isset($data['formData']) && !$data['formData']) {
            echo json_encode([
                'success' => false,
            ]);
            die;
        }

        // If user paste with styling eq from some editor word and the Codeigniter XSS feature remove and apply xss=remove, may break the json.
        $data['formData'] = preg_replace('/=\\\\/m', "=''", $data['formData']);

        $this->db->where('id', $data['id']);
        $this->db->update(db_prefix() . 'ma_forms', [
            'form_data' => $data['formData'],
        ]);
        if ($this->db->affected_rows() > 0) {
            echo json_encode([
                'success' => true,
                'message' => _l('updated_successfully', _l('web_to_lead_form')),
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

    /**
     * form table
     * @return json
     */
    public function form_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = ['id', 'name', '(SELECT COUNT(id) FROM '.db_prefix().'leads WHERE '.db_prefix().'leads.from_ma_form_id = '.db_prefix().'ma_forms.id)', 'dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_forms';

            $where = [];
            
            if ($this->input->post('category')) {
                $category = $this->input->post('category');
                array_push($where, 'AND category IN (' . implode(', ', $category) . ')');
            }

            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['form_key', 'id']);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0 ; $i < count($aColumns) ; $i++) {
                    $_data = $aRow[$aColumns[$i]];
                    if ($aColumns[$i] == 'name') {
                        $_data = '<a href="' . admin_url('ma/form/' . $aRow['id']) . '">' . $_data . '</a>';
                        $_data .= '<div class="row-options">';
                        $_data .= '<a href="' . site_url('ma/ma_forms/wtl/' . $aRow['form_key']) . '" target="_blank">' . _l('view') . '</a>';
                        
                        if (has_permission('ma_components', '', 'edit')) {
                            $_data .= ' | <a href="' . admin_url('ma/form/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                        }
                        
                        if (has_permission('ma_components', '', 'delete')) {
                            $_data .= ' | <a href="' . admin_url('ma/delete_form/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                        }
                        $_data .= '</div>';
                    } elseif ($aColumns[$i] == 'dateadded') {
                        $_data = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($_data) . '">' . time_ago($_data) . '</span>';
                    }

                    $row[] = $_data;
                }
                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or edit asset
     *
     * @param      string  $id     The identifier
     */
    public function asset($id = ''){
        if ($this->input->post()) {
            $data = $this->input->post();
            
            if ($id == '') {
                if (!has_permission('ma_components', '', 'create')) {
                    set_alert('danger', _l('access_denied'));
                    echo json_encode([
                        'url' => admin_url('ma/components?group=assets'),
                    ]);
                    die;
                }
                $id = $this->ma_model->add_asset($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('asset')));
                    echo json_encode([
                        'url'       => admin_url('ma/asset_detail/'.$id),
                        'asset_id' => $id,
                    ]);
                    die;
                }
                echo json_encode([
                    'url' => admin_url('ma/components?group=assets'),
                ]);
                die;
            }
            if (!has_permission('ma_components', '', 'edit')) {
                set_alert('danger', _l('access_denied'));
                echo json_encode([
                        'url' => admin_url('ma/asset_detail/' . $id),
                    ]);
                die;
            }
            $success = $this->ma_model->update_asset($this->input->post(), $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('asset')));
            }

            echo json_encode([
                    'url' => admin_url('ma/asset_detail/' . $id),
                ]);
            die;
        }

        if ($id == '') {
            $title = _l('add_new', _l('asset'));
        } else {
            $data['asset'] = $this->ma_model->get_asset($id);

            if (!$data['asset'] || (!has_permission('ma_assets', '', 'view') && $data['asset']->addedfrom != get_staff_user_id())) {
                blank_page(_l('asset_not_found'));
            }

            $title = _l('edit', _l('asset'));
        }

        $data['category'] = $this->ma_model->get_category('', 'asset');
    

        $data['title']      = $title;
        $this->load->view('components/assets/asset', $data);
    }

    /**
     * add asset attachment
     * @param integer
     * @return json
     */
    public function add_asset_attachment($id)
    {
        ma_handle_asset_attachments($id);
        echo json_encode([
            'url' => admin_url('ma/asset_detail/' . $id),
        ]);
    }

    /**
     * download file
     * @param  string
     * @param  string
     */
    public function download_file($folder_indicator, $attachmentid = '')
    {   
        $this->load->helper('download');

        $path = '';
        if ($folder_indicator == 'ma_asset') {
            $this->db->where('rel_id', $attachmentid);
            $this->db->where('rel_type', 'ma_asset');
            $file = $this->db->get(db_prefix() . 'files')->row();
            $path = MA_MODULE_UPLOAD_FOLDER . '/assets/' . $file->rel_id . '/' . $file->file_name;
        }else {
            die('folder not specified');
        }

        force_download($path, null);
    }

    /**
     * delete asset
     * @param  integer $id
     * @return
     */
    public function delete_asset($id)
    {
        if (!has_permission('ma_components', '', 'delete')) {
            access_denied('ma_components');
        }

        $success = $this->ma_model->delete_asset($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('asset'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/components?group=assets'));
    }

    /**
     * asset table
     * @return json
     */
    public function asset_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_assets.id as id', 
                db_prefix().'ma_assets.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_assets.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_assets';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_assets.category'
            ];

            $where = [];

            if ($this->input->post('category')) {
                $category = $this->input->post('category');
                array_push($where, 'AND category IN (' . implode(', ', $category) . ')');
            }


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/asset/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/asset_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_components', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/asset/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }
                if (has_permission('ma_components', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_asset/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * point
     * @return view
     */
    public function points()
    {
        if (!has_permission('ma_points', '', 'view')) {
            access_denied('setting');
        }
        
        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'point_actions';
        
        if ($data['group'] == '') {
            $data['group'] = 'point_actions';
        }

        if ($data['group'] == 'point_actions') {
            $data['categories'] = $this->ma_model->get_category('', 'point_action');
        }else{
            $data['categories'] = $this->ma_model->get_category('', 'point_trigger');
        }
        
        $data['title']        = _l($data['group']);
        $data['tabs']['view'] = 'points/' . $data['group'];

        $this->load->view('points/point_actions', $data);
    }

    /**
     * add or edit point action
     * @param  integer
     * @return view
     */
    public function point_action($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->ma_model->add_point_action($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('point_action')));
                    redirect(admin_url('ma/point_action/' . $id));
                }
            } else {
                $success = $this->ma_model->update_point_action($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('point_action')));
                }
                redirect(admin_url('ma/point_action/' . $id));
            }
        }

        if ($id != '') {
            $data['point_action'] = $this->ma_model->get_point_action($id);
        }
        $data['title']    = _l('point_action');
        $data['bodyclass'] = 'point-action';
        $data['category'] = $this->ma_model->get_category('', 'point_action');

        $this->load->view('points/point_actions/point_action', $data);
    }

    /**
     * add or edit point trigger
     * @param  string
     * @return view
     */
    public function point_trigger($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->ma_model->add_point_trigger($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('point_trigger')));
                    redirect(admin_url('ma/point_trigger/' . $id));
                }
            } else {
                $success = $this->ma_model->update_point_trigger($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('point_trigger')));
                }
                redirect(admin_url('ma/point_trigger/' . $id));
            }
        }

        if ($id != '') {
            $data['point_trigger'] = $this->ma_model->get_point_trigger($id);
        }
        $data['title']    = _l('point_trigger');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'point_trigger');

        $this->load->view('points/point_triggers/point_trigger', $data);
    }

    /**
     * point_action table
     * @return json
     */
    public function point_actions_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_point_actions.id as id', 
                db_prefix().'ma_point_actions.name as name',
                 db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_point_actions.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_point_actions';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_point_actions.category'
            ];
            $where = [];
            if ($this->input->post('category')) {
                $category = $this->input->post('category');
                array_push($where, 'AND category IN (' . implode(', ', $category) . ')');
            }


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/point_action/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/point_action_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_points', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/point_action/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }
                if (has_permission('ma_points', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_point_action/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * point_trigger table
     * @return json
     */
    public function point_triggers_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_point_triggers.id as id', 
                db_prefix().'ma_point_triggers.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_point_triggers.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_point_triggers';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_point_triggers.category'
            ];


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/point_trigger/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/point_trigger/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                $_data .= ' | <a href="' . admin_url('ma/delete_point_trigger/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * delete point_action
     * @param  integer $id
     * @return
     */
    public function delete_point_action($id)
    {
        if (!has_permission('ma_points', '', 'delete')) {
            access_denied('ma_points');
        }

        $success = $this->ma_model->delete_point_action($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('point_action'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/points?group=point_actions'));
    }

    /**
     * delete point_trigger
     * @param  integer $id
     * @return
     */
    public function delete_point_trigger($id)
    {
        if (!has_permission('ma_points', '', 'delete')) {
            access_denied('ma_points');
        }

        $success = $this->ma_model->delete_point_trigger($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('point_trigger'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/points?group=point_triggers'));
    }

    /**
     * channel
     * @return view
     */
    public function channels()
    {
        if (!has_permission('ma_channels', '', 'view')) {
            access_denied('setting');
        }
        
        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'emails';
        $data['tab'][] = 'sms';
        
        if ($data['group'] == '') {
            $data['group'] = 'emails';
        }

        if ($data['group'] == 'emails') {
            $data['categories'] = $this->ma_model->get_category('', 'email');
        }else{
            $data['categories'] = $this->ma_model->get_category('', 'sms');
        }

        $data['title']        = _l($data['group']);
        $data['tabs']['view'] = 'channels/' . $data['group'];

        $this->load->view('channels/manage', $data);
    }

    /**
     * add or edit marketing message
     * @param  integer
     * @return view
     */
    public function marketing_message($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->ma_model->add_marketing_message($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('marketing_message')));
                    redirect(admin_url('ma/marketing_message/' . $id));
                }
            } else {
                $success = $this->ma_model->update_marketing_message($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('marketing_message')));
                }
                redirect(admin_url('ma/marketing_message/' . $id));
            }
        }

        if ($id != '') {
            $data['marketing_message'] = $this->ma_model->get_marketing_message($id);
        }
        $data['title']    = _l('marketing_message');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'marketing_message');
        $data['email_templates'] = [];

        $this->load->view('channels/marketing_messages/marketing_message', $data);
    }

    /**
     * delete marketing_message
     * @param  integer $id
     * @return
     */
    public function delete_marketing_message($id)
    {
        if (!has_permission('ma_points', '', 'delete')) {
            access_denied('ma_points');
        }

        $success = $this->ma_model->delete_marketing_message($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('marketing_message'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/channels?group=marketing_messages'));
    }

    /**
     * add or edit email
     * @param  integer
     * @return view
     */
    public function email($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['description'] = html_purify($this->input->post('description', false));

            if ($id == '') {
                $id   = $this->ma_model->add_email($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('email')));
                    redirect(admin_url('ma/email_detail/' . $id));
                }
            } else {
                $success = $this->ma_model->update_email($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('email')));
                }
                redirect(admin_url('ma/email_detail/' . $id));
            }
        }

        if ($id != '') {
            $data['email'] = $this->ma_model->get_email($id);
        }
        $data['title']    = _l('email');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'email');
        $data['segments'] = $this->ma_model->get_segment();
        $data['email_templates'] = $this->ma_model->get_email_template();
        $data['assets'] = $this->ma_model->get_asset();
        $data['languages'] = $this->app->get_available_languages();

        $this->load->view('channels/emails/email', $data);
    }

    /**
     * delete email
     * @param  integer $id
     * @return
     */
    public function delete_email($id)
    {
        if (!has_permission('ma_channels', '', 'delete')) {
            access_denied('ma_channels');
        }

        $success = $this->ma_model->delete_email($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('email'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/channels?group=emails'));
    }

    /**
     * marketing_message table
     * @return json
     */
    public function marketing_messages_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_marketing_messages.id as id', 
                db_prefix().'ma_marketing_messages.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_marketing_messages.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_marketing_messages';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_marketing_messages.category'
            ];


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/marketing_message/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/marketing_message/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                $_data .= ' | <a href="' . admin_url('ma/delete_marketing_message/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * email table
     * @return json
     */
    public function email_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_emails.id as id', 
                db_prefix().'ma_emails.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_emails.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_emails';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_emails.category'
            ];

            $where = [];

            if ($this->input->post('category')) {
                $category = $this->input->post('category');
                array_push($where, 'AND category IN (' . implode(', ', $category) . ')');
            }


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/email/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/email_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_channels', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/email/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }
                
                if (has_permission('ma_channels', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_email/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * text_message table
     * @return json
     */
    public function text_messages_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_text_messages.id as id', 
                db_prefix().'ma_text_messages.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_text_messages.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_text_messages';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_text_messages.category'
            ];


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/text_message/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/text_message_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_setting', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/text_message/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }
                if (has_permission('ma_setting', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_text_message/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or edit text message
     * @param  integer
     * @return view
     */
    public function text_message($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $id   = $this->ma_model->add_text_message($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('text_message')));
                    redirect(admin_url('ma/text_message_detail/' . $id));
                }
            } else {
                $success = $this->ma_model->update_text_message($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('text_message')));
                }
                redirect(admin_url('ma/text_message_detail/' . $id));
            }
        }

        if ($id != '') {
            $data['text_message'] = $this->ma_model->get_text_message($id);
        }

        $data['title']    = _l('text_message');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'text_message');
        $data['segments'] = $this->ma_model->get_segment();
        $data['languages'] = $this->app->get_available_languages();
        $data['available_merge_fields'] = $this->app_merge_fields->all();

        $this->load->view('settings/text_messages/text_message', $data);
    }

    /**
     * delete text_message
     * @param  integer $id
     * @return
     */
    public function delete_text_message($id)
    {
        if (!has_permission('ma_setting', '', 'delete')) {
            access_denied('ma_setting');
        }

        $success = $this->ma_model->delete_text_message($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('text_message'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/settings?group=text_messages'));
    }

    /**
     * campaign management
     * @return view
     */
    public function campaigns(){
        $data['title'] = _l('campaigns');

        $data['group'] = $this->input->get('group');

        if($data['group'] == ''){
            $data['group'] = 'list';
        }

        if ($data['group'] == 'chart') {
            $data['data_campaign_pie'] = $this->ma_model->get_data_campaign_pie_chart($data);
            $data['data_campaign_column'] = $this->ma_model->get_data_campaign_column_chart($data);
        }

        $data['categories'] = $this->ma_model->get_category('', 'campaign');
        
        $data['view'] = 'campaigns/includes/' . $data['group'];

        
        $this->load->view('campaigns/manage', $data);
    }

    /**
     * campaign table
     * @return json
     */
    public function campaign_table(){
        if ($this->input->is_ajax_request()) {
           
            $select = [
                'name',
                'category',
                'published',
            ];

            $where = [];

            // Filter by custom groups
            $categorys   = $this->ma_model->get_category('', 'campaign');

            $categoryIds = [];
            $category_names = [];
            foreach ($categorys as $category) {
                if ($this->input->post('campaign_category_' . $category['id'])) {
                    array_push($categoryIds, $category['id']);
                }
                $category_names[$category['id']] = $category['name'];
            }

            if (count($categoryIds) > 0) {
                array_push($where, 'AND category IN (' . implode(', ', $categoryIds) . ')');
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_campaigns';
            $join         = [
        ];
            $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id', 'color']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row   = [];
                $categoryOutput = '<span style="color: '. $aRow['color'] .'">'.$aRow['name'].'</span>';

                $categoryOutput .= '<div class="row-options">';
                $categoryOutput .= '<a href="' . admin_url('ma/campaign_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';

                if (has_permission('ma_campaigns', '', 'edit')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/campaign/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_campaigns', '', 'delete')) {
                    $categoryOutput .= ' | <a href="' . admin_url('ma/delete_campaign/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }

                $categoryOutput .= '</div>';
                $row[] = $categoryOutput;
                $row[] = ma_get_category_name($aRow['category']);

                $checked = '';
                if ($aRow['published'] == 1) {
                    $checked = 'checked';
                }

                $_data = '<div class="onoffswitch">
                    <input type="checkbox" ' . ((!has_permission('ma_campaigns', '', 'edit') && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'ma/change_campaign_published" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                    <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                </div>';

                // For exporting
                $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
                $row[] = $_data;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * add or update campaign
     * @return view
     */
    public function campaign($id = ''){
        if ($this->input->post()) {
            $data                = $this->input->post();
            $data['description'] = html_purify($this->input->post('description', false));

            if($id == ''){
                if (!has_permission('ma_campaigns', '', 'create')) {
                    access_denied('ma_campaign');
                }
                $success = $this->ma_model->add_campaign($data);
                if ($success) {
                    set_alert('success', _l('added_successfully', _l('campaign')));
                }

                redirect(admin_url('ma/campaign_detail/' . $success));
            }else{
                if (!has_permission('ma_campaigns', '', 'edit')) {
                    access_denied('ma_campaign');
                }
                $success = $this->ma_model->update_campaign($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('campaign')));
                }

                redirect(admin_url('ma/campaign_detail/' . $id));
            }
        }

        if($id != ''){
            $data['campaign'] = $this->ma_model->get_campaign($id);
        }

        $data['categories'] = $this->ma_model->get_category('', 'campaign');

        $data['title'] = _l('campaign');

        $this->load->view('campaigns/campaign', $data);
    }

    /**
     * add or edit email template
     * @param  integer
     * @return view
     */
    public function email_template($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                $data = $this->input->post();
                $data['description'] = html_purify($this->input->post('description', false));

                $id   = $this->ma_model->add_email_template($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('email_template')));
                    redirect(admin_url('ma/email_template_detail/' . $id));
                }
            } else {
                $success = $this->ma_model->update_email_template($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('email_template')));
                }
                redirect(admin_url('ma/email_template_detail/' . $id));
            }
        }

        if ($id != '') {
            $data['email_template'] = $this->ma_model->get_email_template($id);
        }
        $data['title']    = _l('email_template');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'email_template');
        $data['segments'] = $this->ma_model->get_segment();
        $data['languages'] = $this->app->get_available_languages();

        $this->load->view('settings/email_templates/email_template', $data);
    }

    /**
     * view segment
     * @return view
     */
    public function segment_detail($id){
        $data['segment'] = $this->ma_model->get_segment($id);
        $data['lead_by_segment'] = $this->ma_model->get_lead_by_segment($id);
        $data['campaign_by_segment'] = $this->ma_model->get_campaign_by_segment($id);
        
        $data['title'] = _l('segment');

        $this->load->view('segments/segment_detail', $data);
    }

    /**
     * view stage
     * @return view
     */
    public function stage_detail($id){
        $data['stage'] = $this->ma_model->get_stage($id);
        $data['lead_by_stage'] = $this->ma_model->get_lead_by_stage($id);
        $data['campaign_by_stage'] = $this->ma_model->get_campaign_by_stage($id);

        $data['title'] = _l('stage');

        $this->load->view('stages/stage_detail', $data);
    }

    /**
     * change segment published
     * @param  integer
     * @param  string
     */
    public function change_segment_published($id, $status)
    {
        if (has_permission('ma_segments', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->ma_model->change_segment_published($id, $status);
            }
        }
    }

    /**
     * Gets the data segment chart.
     * @return json data chart
     */
    public function get_data_segment_chart() {
        $data_segment_pie = $this->ma_model->get_data_segment_pie_chart();
        $data_segment_column = $this->ma_model->get_data_segment_column_chart();
        echo json_encode([
            'data_segment_pie' => $data_segment_pie,
            'data_segment_column' => $data_segment_column
        ]);
        die();
    }

    /**
     * segment kanban
     */
    public function segment_kanban()
    {
        $categories   = $this->ma_model->get_category('', 'segment');

        $categoryIds = [];
        $category_names = [];
        foreach ($categories as $category) {
            if ($this->input->get('segment_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_return = [];
        foreach ($categories as $key => $category) {
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }
            $node = $category;
            $node['segments'] = $this->ma_model->do_segment_kanban_query($category['id'], 1, 'category='.$category['id']);
            $node['total_pages'] = ceil($this->ma_model->do_segment_kanban_query($category['id'], 1, 'category='.$category['id'], true)/10);

            $data_return[] = $node;
        }
        echo html_entity_decode($this->load->view('segments/includes/segment_kanban', ['data' => $data_return], true));
    }

    /**
     * update segment category
     */
    public function update_segment_category()
    {
        if ($this->input->post()) {
            $this->ma_model->update_segment_category($this->input->post());
        }
    }

    /**
     * segment kanban load more }
     */
    public function segment_kanban_load_more()
    {
        $category     = $this->input->get('category');
        $page       = $this->input->get('page');
        $from_date = '';
        $to_date = '';
       
        $segments = $this->ma_model->do_segment_kanban_query($category, $page);
        foreach ($segments as $segment) {
            $this->load->view('ma/segments/includes/_segment_kanban_card', ['segment' => $segment, 'category' => $category]);
        }
    }

    /**
     * view campaign
     * @return view
     */
    public function campaign_detail($id){
        $this->ma_model->get_lead_by_campaign($id);
        $data['campaign'] = $this->ma_model->get_campaign($id);
        $data['point_actions'] = $this->ma_model->get_object_by_campaign($id, 'point_action', 'object');
        $data['emails'] = $this->ma_model->get_object_by_campaign($id, 'email', 'object');
        $data['sms'] = $this->ma_model->get_object_by_campaign($id, 'sms', 'object');

        $data['stages'] = $this->ma_model->get_object_by_campaign($id, 'stage', 'object');
        $data['segments'] = $this->ma_model->get_object_by_campaign($id, 'segment', 'object');

        $data['title'] = _l('campaign');

        $this->load->view('campaigns/campaign_detail', $data);
    }

    /**
     * workflow builder
     * @return view
     */
    public function workflow_builder($id){
        $data['campaign'] = $this->ma_model->get_campaign($id);

        $data['title'] = _l('workflow_builder');

        $data['is_edit'] = true;

        $this->load->view('campaigns/workflow_builder', $data);
    }

    /**
     * workflow builder save
     * @return redirect
     */
    public function workflow_builder_save(){
        $data = $this->input->post();
        $data['workflow'] = $this->input->post('workflow', false);
        $success = $this->ma_model->workflow_builder_save($data);
        if($success){
            $message = _l('updated_successfully', _l('workflow'));
        }

        redirect(admin_url('ma/campaign_detail/' . $data['campaign_id']));
    }

    /**
     * change campaign published
     * @param  integer
     * @param  string
     */
    public function change_campaign_published($id, $status)
    {
        if (has_permission('ma_campaigns', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->ma_model->change_campaign_published($id, $status);
            }
        }
    }
    
    /**
     * Gets the data campaign chart.
     * @return json data chart
     */
    public function get_data_campaign_chart() {
        $data_campaign_pie = $this->ma_model->get_data_campaign_pie_chart();
        $data_campaign_column = $this->ma_model->get_data_campaign_column_chart();
        echo json_encode([
            'data_campaign_pie' => $data_campaign_pie,
            'data_campaign_column' => $data_campaign_column
        ]);
        die();
    }

    /**
     * campaign kanban
     */
    public function campaign_kanban()
    {
        $categories   = $this->ma_model->get_category('', 'campaign');

        $categoryIds = [];
        $category_names = [];
        foreach ($categories as $category) {
            if ($this->input->get('campaign_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_return = [];
        foreach ($categories as $key => $category) {
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }
            $node = $category;
            $node['campaigns'] = $this->ma_model->do_campaign_kanban_query($category['id'], 1, 'category='.$category['id']);
            $node['total_pages'] = ceil($this->ma_model->do_campaign_kanban_query($category['id'], 1, 'category='.$category['id'], true)/10);

            $data_return[] = $node;
        }
        echo html_entity_decode($this->load->view('campaigns/includes/campaign_kanban', ['data' => $data_return], true));
    }

    /**
     * update campaign category
     */
    public function update_campaign_category()
    {
        if ($this->input->post()) {
            $this->ma_model->update_campaign_category($this->input->post());
        }
    }

    /**
     * campaign kanban load more }
     */
    public function campaign_kanban_load_more()
    {
        $category     = $this->input->get('category');
        $page       = $this->input->get('page');
        $from_date = '';
        $to_date = '';
       
        $campaigns = $this->ma_model->do_campaign_kanban_query($category, $page);
        foreach ($campaigns as $campaign) {
            $this->load->view('ma/campaigns/includes/_campaign_kanban_card', ['campaign' => $campaign, 'category' => $category]);
        }
    }

    /**
     * change stage published
     * @param  integer
     * @param  string
     */
    public function change_stage_published($id, $status)
    {
        if (has_permission('ma_stages', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->ma_model->change_stage_published($id, $status);
            }
        }
    }
    
    /**
     * Gets the data stage chart.
     * @return json data chart
     */
    public function get_data_stage_chart() {
        $data_stage_pie = $this->ma_model->get_data_stage_pie_chart();
        $data_stage_column = $this->ma_model->get_data_stage_column_chart();
        echo json_encode([
            'data_stage_pie' => $data_stage_pie,
            'data_stage_column' => $data_stage_column
        ]);
        die();
    }

    /**
     * stage kanban
     */
    public function stage_kanban()
    {
        $categories   = $this->ma_model->get_category('', 'stage');

        $categoryIds = [];
        $category_names = [];
        foreach ($categories as $category) {
            if ($this->input->get('stage_category_' . $category['id'])) {
                array_push($categoryIds, $category['id']);
            }
        }

        $data_return = [];
        foreach ($categories as $key => $category) {
            if (count($categoryIds) > 0 && !in_array($category['id'], $categoryIds)) {
                continue;
            }
            $node = $category;
            $node['stages'] = $this->ma_model->do_stage_kanban_query($category['id'], 1, 'category='.$category['id']);
            $node['total_pages'] = ceil($this->ma_model->do_stage_kanban_query($category['id'], 1, 'category='.$category['id'], true)/10);

            $data_return[] = $node;
        }
        echo html_entity_decode($this->load->view('stages/includes/stage_kanban', ['data' => $data_return], true));
    }

    /**
     * update stage category
     */
    public function update_stage_category()
    {
        if ($this->input->post()) {
            $this->ma_model->update_stage_category($this->input->post());
        }
    }

    /**
     * stage kanban load more }
     */
    public function stage_kanban_load_more()
    {
        $category     = $this->input->get('category');
        $page       = $this->input->get('page');
        $from_date = '';
        $to_date = '';
       
        $stages = $this->ma_model->do_stage_kanban_query($category, $page);
        foreach ($stages as $stage) {
            $this->load->view('ma/stages/includes/_stage_kanban_card', ['stage' => $stage, 'category' => $category]);
        }
    }

    /**
     * get workflow node html
     * @return view
     */
    public function get_workflow_node_html(){
        $data = $this->input->post();

        switch ($data['type']) {
            case 'flow_start':
                $data['segments'] = $this->ma_model->get_segment('', 'published = 1');
                $data['forms'] = $this->ma_model->get_forms();
                break;
            case 'sms':
                $data['sms'] = $this->ma_model->get_sms();
                break;
            case 'email':
                $data['emails'] = $this->ma_model->get_email();
                break;
            case 'action':
                $data['segments'] = $this->ma_model->get_segment('', 'published = 1');
                $data['stages'] = $this->ma_model->get_stage();
                $data['point_actions'] = $this->ma_model->get_point_action();
                break;
            default:
                // code...
                break;
        }


        $this->load->view('campaigns/workflow_node/'.$data['type'], $data);
    }

    /**
     * delete campaign
     * @param  integer $id
     * @return
     */
    public function delete_campaign($id)
    {
        if (!has_permission('ma_campaigns', '', 'delete')) {
            access_denied('ma_campaigns');
        }

        $success = $this->ma_model->delete_campaign($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('campaign'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/campaigns'));
    }

    /**
     * delete form
     * @param  integer $id
     * @return
     */
    public function delete_form($id)
    {
        if (!has_permission('ma_components', '', 'delete')) {
            access_denied('ma_components');
        }

        $success = $this->ma_model->delete_form($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('form'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/components?group=forms'));
    }

    /**
     * email template design save
     * @return redirect
     */
    public function email_template_design_save(){
        $data = $this->input->post();
        $data['data_html'] = $this->input->post('data_html', false);
        $data['data_design'] = $this->input->post('data_design', false);
        
        $success = $this->ma_model->email_template_design_save($data);
        if($success){
            $message = _l('updated_successfully', _l('template'));
        }

        redirect(admin_url('ma/email_template_detail/' . $data['email_template_id']));
    }

    /**
     * workflow builder
     * @return view
     */
    public function email_template_design($id){
        $data['email_template'] = $this->ma_model->get_email_template($id);
        $data['available_merge_fields'] = $this->app_merge_fields->all();

        $data['title'] = _l('email_template');

        $data['is_edit'] = true;

        $this->load->view('settings/email_templates/email_template_design', $data);
    }

    /**
     * view email template
     * @return view
     */
    public function email_template_detail($id){
        $data['email_template'] = $this->ma_model->get_email_template($id);
        $data['lead_by_email_template'] = $this->ma_model->get_lead_by_email_template($id);
        $data['campaign_by_email_template'] = $this->ma_model->get_campaign_by_email_template($id);


        $data['title'] = _l('email_template');

        $this->load->view('settings/email_templates/email_template_detail', $data);
    }

    /**
     * email templates table
     * @return json
     */
    public function email_templates_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_email_templates.id as id', 
                db_prefix().'ma_email_templates.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_email_templates.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_email_templates';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_email_templates.category'
            ];


            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/email_template/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/email_template_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_setting', '', 'create')) {
                    $_data .= ' | <a href="#" class="text-success" onclick="clone_template('.$aRow['id'].'); return false;">' . _l('clone') . '</a>';
                }
                if (has_permission('ma_setting', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/email_template/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }
                if (has_permission('ma_setting', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_email_template/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * delete email template
     * @param  integer $id
     * @return
     */
    public function delete_email_template($id)
    {
        if (!has_permission('ma_setting', '', 'delete')) {
            access_denied('ma_setting');
        }

        $success = $this->ma_model->delete_email_template($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('email_template'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/settings?group=ma_email_templates'));
    }

    /**
     * leads table
     * @return json
     */
    public function leads_table(){
        if ($this->input->is_ajax_request()) {
           $this->load->model('gdpr_model');
           $this->load->model('leads_model');
            $lockAfterConvert      = get_option('lead_lock_after_convert_to_customer');
            $has_permission_delete = has_permission('leads', '', 'delete');
            $custom_fields         = get_table_custom_fields('leads');
            $consentLeads          = get_option('gdpr_enable_consent_for_leads');
            $statuses              = $this->leads_model->get_status();

            $aColumns = [
                '1',
                db_prefix() . 'leads.id as id',
                db_prefix() . 'leads.name as name',
                ];
            $aColumns = array_merge($aColumns, ['company',
                db_prefix() . 'leads.email as email',
                db_prefix() . 'leads.phonenumber as phonenumber',
                'lead_value',
                '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'leads.id and rel_type="lead" ORDER by tag_order ASC LIMIT 1) as tags',
                'firstname as assigned_firstname',
                db_prefix() . 'leads_status.name as status_name',
                db_prefix() . 'leads_sources.name as source_name',
                'ma_point',
            ]);

            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'leads';

            $join = [
                'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'leads.assigned',
                'LEFT JOIN ' . db_prefix() . 'leads_status ON ' . db_prefix() . 'leads_status.id = ' . db_prefix() . 'leads.status',
                'JOIN ' . db_prefix() . 'leads_sources ON ' . db_prefix() . 'leads_sources.id = ' . db_prefix() . 'leads.source',
            ];

            $where  = [];
            $filter = false;

            if ($this->input->post('stage_id')) {
                $stage_id = $this->input->post('stage_id');
                $where_stage = $this->ma_model->get_lead_by_stage($stage_id, 'where');
                
                if($where_stage != ''){
                    array_push($where, 'AND '.$where_stage);
                }
            }

            if ($this->input->post('segment_id')) {
                $segment_id = $this->input->post('segment_id');
                $where_segment = $this->ma_model->get_lead_by_segment($segment_id, 'where');
                
                if($where_segment != ''){
                    array_push($where, 'AND '.$where_segment);
                }
            }

            if ($this->input->post('campaign_id')) {
                $campaign_id = $this->input->post('campaign_id');
                $where_campaign = $this->ma_model->get_lead_by_campaign($campaign_id, 'where');
                
                if($where_campaign != ''){
                    array_push($where, 'AND '.$where_campaign);
                }
            }

            if ($this->input->post('email_template_id')) {
                $email_template_id = $this->input->post('email_template_id');
                $where_email_template = $this->ma_model->get_lead_by_email_template($email_template_id, 'where');
                
                if($where_email_template != ''){
                    array_push($where, 'AND '.$where_email_template);
                }
            }

            if ($this->input->post('email_id')) {
                $email_id = $this->input->post('email_id');
                $where_email = $this->ma_model->get_lead_by_email($email_id, 'where');
                
                if($where_email != ''){
                    array_push($where, 'AND '.$where_email);
                }
            }

            if ($this->input->post('sms_id')) {
                $sms_id = $this->input->post('sms_id');
                $where_sms = $this->ma_model->get_lead_by_sms($sms_id, 'where');
                highlight_string("<?php\n" . var_export($where_sms, true) . ";\n?>"); die;
                if($where_sms != ''){
                    array_push($where, 'AND '.$where_sms);
                }
            }

            if ($this->input->post('point_action_id')) {
                $point_action_id = $this->input->post('point_action_id');
                $where_point_action = $this->ma_model->get_lead_by_point_action($point_action_id, 'where');
                
                if($where_point_action != ''){
                    array_push($where, 'AND '.$where_point_action);
                }
            }

            if ($this->input->post('text_message_id')) {
                $text_message_id = $this->input->post('text_message_id');
                $where_text_message = $this->ma_model->get_lead_by_text_message($text_message_id, 'where');
                
                if($where_text_message != ''){
                    array_push($where, 'AND '.$where_text_message);
                }
            }

            $aColumns = hooks()->apply_filters('leads_table_sql_columns', $aColumns);

            $additionalColumns = [
                'junk',
                'lost',
                'color',
                'status',
                'assigned',
                'lastname as assigned_lastname',
                db_prefix() . 'leads.addedfrom as addedfrom',
                '(SELECT count(leadid) FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.leadid=' . db_prefix() . 'leads.id) as is_converted',
                'zip',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalColumns);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';

                $hrefAttr = 'href="' . admin_url('leads/index/' . $aRow['id']) . '" onclick="init_lead(' . $aRow['id'] . ');return false;"';
                $row[]    = '<a ' . $hrefAttr . '>' . $aRow['id'] . '</a>';

                $nameRow = '<a ' . $hrefAttr . '>' . $aRow['name'] . '</a>';

                $nameRow .= '<div class="row-options">';
                $nameRow .= '<a ' . $hrefAttr . '>' . _l('view') . '</a>';

                $locked = false;

                if ($aRow['is_converted'] > 0) {
                    $locked = ((!is_admin() && $lockAfterConvert == 1) ? true : false);
                }

                if (!$locked) {
                    $nameRow .= ' | <a href="' . admin_url('leads/index/' . $aRow['id'] . '?edit=true') . '" onclick="init_lead(' . $aRow['id'] . ', true);return false;">' . _l('edit') . '</a>';
                }

                if ($aRow['addedfrom'] == get_staff_user_id() || $has_permission_delete) {
                    $nameRow .= ' | <a href="' . admin_url('leads/delete/' . $aRow['id']) . '" class="_delete text-danger">' . _l('delete') . '</a>';
                }
                $nameRow .= '</div>';


                $row[] = $nameRow;

                $row[] = $aRow['company'];

                $row[] = ($aRow['email'] != '' ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '');

                $row[] = ($aRow['phonenumber'] != '' ? '<a href="tel:' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');

                $base_currency = get_base_currency();
                $row[] = ($aRow['lead_value'] != 0 ? app_format_money($aRow['lead_value'],$base_currency->symbol) : '');

                $row[] .= render_tags($aRow['tags']);

                $assignedOutput = '';
                if ($aRow['assigned'] != 0) {
                    $full_name = $aRow['assigned_firstname'] . ' ' . $aRow['assigned_lastname'];

                    $assignedOutput = '<a data-toggle="tooltip" data-title="' . $full_name . '" href="' . admin_url('profile/' . $aRow['assigned']) . '">' . staff_profile_image($aRow['assigned'], [
                        'staff-profile-image-small',
                        ]) . '</a>';

                    // For exporting
                    $assignedOutput .= '<span class="hide">' . $full_name . '</span>';
                }

                $row[] = $assignedOutput;

                if ($aRow['status_name'] == null) {
                    if ($aRow['lost'] == 1) {
                        $outputStatus = '<span class="label label-danger inline-block">' . _l('lead_lost') . '</span>';
                    } elseif ($aRow['junk'] == 1) {
                        $outputStatus = '<span class="label label-warning inline-block">' . _l('lead_junk') . '</span>';
                    }
                } else {
                    $outputStatus = '<span class="inline-block lead-status-'.$aRow['status'].' label label-' . (empty($aRow['color']) ? 'default': '') . '" style="color:' . $aRow['color'] . ';border:1px solid ' . $aRow['color'] . '">' . $aRow['status_name'];
                    if (!$locked) {
                        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                        $outputStatus .= '<a href="#" class="dropdown-toggle text-dark font-size-14 vertical-align-middle" id="tableLeadsStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                        $outputStatus .= '</a>';

                        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableLeadsStatus-' . $aRow['id'] . '">';
                        foreach ($statuses as $leadChangeStatus) {
                            if ($aRow['status'] != $leadChangeStatus['id']) {
                                $outputStatus .= '<li>
                              <a href="#" onclick="lead_mark_as(' . $leadChangeStatus['id'] . ',' . $aRow['id'] . '); return false;">
                                 ' . $leadChangeStatus['name'] . '
                              </a>
                           </li>';
                            }
                        }
                        $outputStatus .= '</ul>';
                        $outputStatus .= '</div>';
                    }
                    $outputStatus .= '</span>';
                }

                $row[] = $outputStatus;

                $row[] = $aRow['source_name'];

                $row[] = number_format($aRow['ma_point']);
               
                $row['DT_RowId'] = 'lead_' . $aRow['id'];

                if ($aRow['assigned'] == get_staff_user_id()) {
                    $row['DT_RowClass'] = 'alert-info';
                }

                if (isset($row['DT_RowClass'])) {
                    $row['DT_RowClass'] .= ' has-row-options';
                } else {
                    $row['DT_RowClass'] = 'has-row-options';
                }

                $output['aaData'][] = $row;
            }


            echo json_encode($output);
            die();
        }
    }

    /**
     * view asset
     * @return view
     */
    public function asset_detail($id){
        $data['asset'] = $this->ma_model->get_asset($id);
        $data['title'] = _l('asset');

        $this->load->view('components/assets/asset_detail', $data);
    }

    /**
     * Gets the data asset chart.
     * @return json data chart
     */
    public function get_data_asset_chart($asset_id = '') {
        $data_asset_download = $this->ma_model->get_data_asset_download_chart($asset_id);
        echo json_encode([
            'data_asset_download' => $data_asset_download
        ]);
        die();
    }

    /**
     * Gets the data email template chart.
     * @return json data chart
     */
    public function get_data_email_template_chart($email_template_id = '') {
        $data_email_template = $this->ma_model->get_data_email_template_chart($email_template_id);

        $data_email_template_by_campaign = [];
        if($email_template_id != ''){
            $data_email_template_by_campaign = $this->ma_model->get_data_email_template_by_campaign_chart($email_template_id);
        }

        echo json_encode([
            'data_email_template' => $data_email_template,
            'data_email_template_by_campaign' => $data_email_template_by_campaign,
        ]);
        die();
    }

    /**
     * view point action
     * @return view
     */
    public function point_action_detail($id){
        $data['point_action'] = $this->ma_model->get_point_action($id);
        $data['title'] = _l('point_action');

        $this->load->view('points/point_actions/point_action_detail', $data);
    }

     /**
     * Gets the data point action chart.
     * @return json data chart
     */
    public function get_data_point_action_chart($point_action_id = '') {
        $data_point_action = $this->ma_model->get_data_point_action_chart($point_action_id);
        $data_point_action_by_campaign = [];
        if($point_action_id != ''){
            $data_point_action_by_campaign = $this->ma_model->get_data_point_action_by_campaign_chart($point_action_id);
        }
        echo json_encode([
            'data_point_action' => $data_point_action,
            'data_point_action_by_campaign' => $data_point_action_by_campaign,
        ]);
        die();
    }

    /**
     * get data dashboard
     * @return json
     */
    public function get_data_dashboard(){
        $data_filter = $this->input->get();

        $data['data_form_submit'] = $this->ma_model->get_data_form_submit_chart('', $data_filter);
        $data['data_email_template'] = $this->ma_model->get_data_email_chart('', $data_filter);
        $data['data_lead'] = $this->ma_model->get_data_lead_chart($data_filter);

        echo json_encode($data);
    }

    /**
     * Gets the data segment chart.
     * @return json data chart
     */
    public function get_data_segment_detail_chart($segment_id) {
        $data_segment_detail = $this->ma_model->get_data_segment_detail_chart($segment_id);
        $data_segment_campaign_detail = $this->ma_model->get_data_segment_by_campaign_chart($segment_id);
        echo json_encode([
            'data_segment_detail' => $data_segment_detail,
            'data_segment_campaign_detail' => $data_segment_campaign_detail,
        ]);
        die();
    }

    /**
     * Gets the data stage chart.
     * @return json data chart
     */
    public function get_data_stage_detail_chart($stage_id) {
        $data_stage_detail = $this->ma_model->get_data_stage_detail_chart($stage_id);
        $data_stage_campaign_detail = $this->ma_model->get_data_stage_by_campaign_chart($stage_id);
        echo json_encode([
            'data_stage_detail' => $data_stage_detail,
            'data_stage_campaign_detail' => $data_stage_campaign_detail,
        ]);
        die();
    }

    /**
     * Gets the data campaign chart.
     * @return json data chart
     */
    public function get_data_campaign_detail_chart($campaign_id = '') {
        $data_email = $this->ma_model->get_data_campaign_email_chart($campaign_id);
        $data_segment = $this->ma_model->get_data_campaign_segment_chart($campaign_id);
        $data_stage = $this->ma_model->get_data_campaign_stage_chart($campaign_id);
        $data_text_message = $this->ma_model->get_data_campaign_text_message_chart($campaign_id);
        $data_point_action = $this->ma_model->get_data_campaign_point_action_chart($campaign_id);
        echo json_encode([
            'data_email' => $data_email,
            'data_segment' => $data_segment,
            'data_stage' => $data_stage,
            'data_text_message' => $data_text_message,
            'data_point_action' => $data_point_action,
        ]);
        die();
    }

    /**
     * view text message
     * @return view
     */
    public function text_message_detail($id){
        $data['text_message'] = $this->ma_model->get_text_message($id);
        $data['lead_by_text_message'] = $this->ma_model->get_lead_by_text_message($id);
        $data['campaign_by_text_message'] = $this->ma_model->get_campaign_by_text_message($id);
        

        $data['title'] = _l('text_message');

        $this->load->view('settings/text_messages/text_message_detail', $data);
    }

    /**
     * Gets the data text message chart.
     * @return json data chart
     */
    public function get_data_text_message_chart($text_message_id = '') {
        $data_text_message = $this->ma_model->get_data_text_message_chart($text_message_id);

        $data_text_message_by_campaign = [];
        if($text_message_id != ''){
            $data_text_message_by_campaign = $this->ma_model->get_data_text_message_by_campaign_chart($text_message_id);
        }

        echo json_encode([
            'data_text_message' => $data_text_message,
            'data_text_message_by_campaign' => $data_text_message_by_campaign,
        ]);
        die();
    }

    /**
     * Reports
     * @return 
     */
    public function reports(){
        $data['title'] = _l('reports');
        
        $this->load->view('reports/manage', $data);
    }

    /**
     * report campaign
     * @return view
     */
    public function campaign_report(){
        $data['title'] = _l('campaign_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/campaign_report', $data);
    }

    /**
     * report asset
     * @return view
     */
    public function asset_report(){
        $data['title'] = _l('asset_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/asset_report', $data);
    }

    /**
     * asset download table
     * @return json
     */
    public function asset_download_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'ma_assets.name as name',
                'ip',
                'browser_name',
                'time',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_asset_download_logs';
            $join         = ['JOIN ' . db_prefix() . 'ma_assets ON ' . db_prefix() . 'ma_assets.id = ' . db_prefix() . 'ma_asset_download_logs.asset_id'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_assets.id as id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('ma/asset_detail/' . $aRow['id']) . '" class="">' . $aRow['name'] . '</a>';
                $row[] = '<span class="text text-success">' . $aRow['ip'] . '</span>';
                $row[] = '<span class="text text-success">' . $aRow['browser_name'] . '</span>';
                $row[] = '<span class="text text-success">' . _dt($aRow['time']) . '</span>';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * report lead and point
     * @return view
     */
    public function lead_and_point_report(){
        $data['title'] = _l('lead_and_point_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/lead_and_point_report', $data);
    }

    /**
     * point action log table
     * @return json
     */
    public function point_action_log_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'ma_point_actions.name as name',
                db_prefix() . 'leads.name as lead_name',
                db_prefix() . 'leads.email as email',
                'point',
                db_prefix() . 'ma_point_action_logs.dateadded as dateadded',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_point_action_logs';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'ma_point_actions ON ' . db_prefix() . 'ma_point_actions.id = ' . db_prefix() . 'ma_point_action_logs.point_action_id',
                'LEFT JOIN ' . db_prefix() . 'leads ON ' . db_prefix() . 'leads.id = ' . db_prefix() . 'ma_point_action_logs.lead_id'];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_point_actions.id as id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('ma/point_action_detail/' . $aRow['id']) . '" class="">' . $aRow['name'] . '</a>';
                $row[] = $aRow['lead_name'];
                $row[] = $aRow['email'];
                
                $text_class = 'text-success';
                if($aRow['point'] < 0){
                    $text_class = 'text-danger';
                }

                $row[] = '<span class="text '.$text_class.'">' . $aRow['point'] . '</span>';

                $row[] = _dt($aRow['dateadded']);
                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * form log table
     * @return json
     */
    public function form_log_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'ma_forms.name as name',
                db_prefix() . 'leads.name as lead_name',
                db_prefix() . 'leads.email as email',
                db_prefix() . 'leads.dateadded as dateadded',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'leads';
            $join         = [
                'JOIN ' . db_prefix() . 'ma_forms ON ' . db_prefix() . 'ma_forms.id = ' . db_prefix() . 'leads.from_ma_form_id',
                ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_forms.id as id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('ma/form/' . $aRow['id']) . '" class="">' . $aRow['name'] . '</a>';
                $row[] = $aRow['lead_name'];
                $row[] = $aRow['email'];
                
                $row[] = _dt($aRow['dateadded']);
                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * Gets the data form chart.
     * @return json data chart
     */
    public function get_data_form_chart($form_id = '') {
        $data_form = $this->ma_model->get_data_form_chart($form_id);
       
        echo json_encode([
            'data_form' => $data_form,
        ]);
        die();
    }

    /**
     * report form
     * @return view
     */
    public function form_report(){
        $data['title'] = _l('form_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/form_report', $data);
    }

    /**
     * report email
     * @return view
     */
    public function email_report(){
        $data['title'] = _l('email_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/email_report', $data);
    }

    /**
     * email log table
     * @return json
     */
    public function email_log_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'ma_email_logs.dateadded as dateadded',
                db_prefix() . 'ma_email_templates.name as name',
                db_prefix() . 'leads.name as lead_name',
                db_prefix() . 'leads.email as email',
                'delivery',
                'open',
                'click',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_email_logs';
            $join         = [
                'JOIN ' . db_prefix() . 'ma_email_templates ON ' . db_prefix() . 'ma_email_templates.id = ' . db_prefix() . 'ma_email_logs.email_template_id',
                'JOIN ' . db_prefix() . 'leads ON ' . db_prefix() . 'leads.id = ' . db_prefix() . 'ma_email_logs.lead_id',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_email_templates.id as id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = _dt($aRow['dateadded']);
                $row[] = '<a href="' . admin_url('ma/email_template_detail/' . $aRow['id']) . '" class="">' . $aRow['name'] . '</a>';
                $row[] = $aRow['lead_name'];
                $row[] = $aRow['email'];

                $value = (($aRow['delivery'] == 1) ? _l('yes') : _l('no'));
                $text_class = (($aRow['delivery'] == 1) ? 'text-success' : 'text-danger');
                $row[] = '<span class="text '.$text_class.'">' . $value . '</span>';

                $value = (($aRow['open'] == 1) ? _l('yes') : _l('no'));
                $text_class = (($aRow['open'] == 1) ? 'text-success' : 'text-danger');
                $row[] = '<span class="text '.$text_class.'">' . $value . '</span>';

                $value = (($aRow['click'] == 1) ? _l('yes') : _l('no'));
                $text_class = (($aRow['click'] == 1) ? 'text-success' : 'text-danger');
                $row[] = '<span class="text '.$text_class.'">' . $value . '</span>';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * report sms
     * @return view
     */
    public function sms_report(){
        $data['title'] = _l('sms_report');
        $data['from_date'] = date('Y-01-01');
        $data['to_date'] = date('Y-m-d');
        $this->load->view('reports/includes/sms_report', $data);
    }

    /**
     * sms log table
     * @return json
     */
    public function sms_log_table()
    {
        if ($this->input->is_ajax_request()) {

            $select = [
                db_prefix() . 'ma_sms_logs.dateadded as dateadded',
                db_prefix() . 'ma_text_messages.name as name',
                db_prefix() . 'leads.name as lead_name',
                db_prefix() . 'leads.phonenumber as phonenumber',
            ];
            $where = [];

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'ma_sms_logs';
            $join         = [
                'JOIN ' . db_prefix() . 'ma_text_messages ON ' . db_prefix() . 'ma_text_messages.id = ' . db_prefix() . 'ma_sms_logs.text_message_id',
                'JOIN ' . db_prefix() . 'leads ON ' . db_prefix() . 'leads.id = ' . db_prefix() . 'ma_sms_logs.lead_id',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'ma_text_messages.id as id']);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = _dt($aRow['dateadded']);
                $row[] = '<a href="' . admin_url('ma/text_message_detail/' . $aRow['id']) . '" class="">' . $aRow['name'] . '</a>';
                $row[] = $aRow['lead_name'];
                $row[] = $aRow['phonenumber'];

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * { ma_sms setting by admin }
     * 
     * @return redirect
     */
    public function ma_sms_setting(){
        
            $data = $this->input->post();
            $success = $this->ma_model->ma_sms_setting($data);
            if($success){
                set_alert('success', _l('updated_successfully'));
            }else{
                set_alert('warning', $mess = _l('no_data_has_been_updated'));
            }
            redirect(admin_url('ma/settings?group=sms'));
        
    }

    /**
     * Gets the preview.
     *
     * @param        $id     The identifier
     */
    public function get_email_template_preview($id = ''){
        $html = '';
        if($id != ''){
            $email_template = $this->ma_model->get_email_template($id);
            if($email_template){
                $html = json_decode($email_template->data_html);
            }
        }
        
        echo html_entity_decode($html);
        die;
    }

    /**
     * email builder
     * @return view
     */
    public function email_design($id){
        $data['email'] = $this->ma_model->get_email($id);
        $data['available_merge_fields'] = $this->app_merge_fields->all();

        $data['title'] = _l('email');

        $data['is_edit'] = true;

        $this->load->view('channels/emails/email_design', $data);
    }

    /**
     * view email template
     * @return view
     */
    public function email_detail($id){
        $data['email'] = $this->ma_model->get_email($id);
        $data['lead_by_email'] = $this->ma_model->get_lead_by_email($id);
        $data['campaign_by_email'] = $this->ma_model->get_campaign_by_email($id);

        $data['title'] = _l('email');

        $this->load->view('channels/emails/email_detail', $data);
    }

    /**
     * Gets the data email chart.
     * @return json data chart
     */
    public function get_data_email_chart($email_id = '') {
        $data_email = $this->ma_model->get_data_email_chart($email_id);

        $data_email_by_campaign = [];
        if($email_id != ''){
            $data_email_by_campaign = $this->ma_model->get_data_email_by_campaign_chart($email_id);
        }

        echo json_encode([
            'data_email' => $data_email,
            'data_email_by_campaign' => $data_email_by_campaign,
        ]);
        die();
    }

    /**
     * email design save
     * @return redirect
     */
    public function email_design_save(){
        $data = $this->input->post();
        $data['data_html'] = $this->input->post('data_html', false);
        $data['data_design'] = $this->input->post('data_design', false);
        
        $success = $this->ma_model->email_design_save($data);
        if($success){
            $message = _l('updated_successfully', _l('template'));
        }

        redirect(admin_url('ma/email_detail/' . $data['email_id']));
    }

    /**
     * add or edit sms
     * @param  integer
     * @return view
     */
    public function sms($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['description'] = html_purify($this->input->post('description', false));

            if ($id == '') {
                $id   = $this->ma_model->add_sms($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('sms')));
                    redirect(admin_url('ma/sms_detail/' . $id));
                }
            } else {
                $success = $this->ma_model->update_sms($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('sms')));
                }
                redirect(admin_url('ma/sms_detail/' . $id));
            }
        }

        if ($id != '') {
            $data['sms'] = $this->ma_model->get_sms($id);
        }
        $data['title']    = _l('sms');
        $data['bodyclass'] = 'point-trigger';
        $data['category'] = $this->ma_model->get_category('', 'sms');
        $data['segments'] = $this->ma_model->get_segment();
        $data['text_messages'] = $this->ma_model->get_text_message();
        $data['languages'] = $this->app->get_available_languages();

        $this->load->view('channels/sms/sms', $data);
    }

    /**
     * Gets the preview.
     *
     * @param        $id     The identifier
     */
    public function get_sms_template_preview($id = ''){
        $html = '';
        if($id != ''){
            $text_message = $this->ma_model->get_text_message($id);
            if($text_message){
                $html = $text_message->description;
            }
        }
        
        echo html_entity_decode($html);
        die;
    }

    /**
     * delete sms
     * @param  integer $id
     * @return
     */
    public function delete_sms($id)
    {
        if (!has_permission('ma_channels', '', 'delete')) {
            access_denied('ma_channels');
        }

        $success = $this->ma_model->delete_sms($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('sms'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('ma/channels?group=sms'));
    }

    /**
     * view sms template
     * @return view
     */
    public function sms_detail($id){
        $data['sms'] = $this->ma_model->get_sms($id);
        $data['lead_by_sms'] = $this->ma_model->get_lead_by_sms($id);
        $data['campaign_by_sms'] = $this->ma_model->get_campaign_by_sms($id);


        $data['title'] = _l('sms');

        $this->load->view('channels/sms/sms_detail', $data);
    }

    /**
     * Gets the data sms chart.
     * @return json data chart
     */
    public function get_data_sms_chart($sms_id = '') {
        $data_sms = $this->ma_model->get_data_sms_chart($sms_id);

        $data_sms_by_campaign = [];
        if($sms_id != ''){
            $data_sms_by_campaign = $this->ma_model->get_data_sms_by_campaign_chart($sms_id);
        }

        echo json_encode([
            'data_sms' => $data_sms,
            'data_sms_by_campaign' => $data_sms_by_campaign,
        ]);
        die();
    }

    /**
     * sms table
     * @return json
     */
    public function sms_table(){
        if ($this->input->is_ajax_request()) {
           
            $aColumns = [
                db_prefix().'ma_sms.id as id', 
                db_prefix().'ma_sms.name as name', 
                db_prefix().'ma_categories.name as category_name', 
                db_prefix().'ma_sms.dateadded as dateadded'];

            $sIndexColumn = 'id';
            $sTable       = db_prefix().'ma_sms';
            $join         = [
            'LEFT JOIN ' . db_prefix() . 'ma_categories ON ' . db_prefix() . 'ma_categories.id = ' . db_prefix() . 'ma_sms.category'
            ];

            $where = [];

            if ($this->input->post('category')) {
                $category = $this->input->post('category');
                array_push($where, 'AND category IN (' . implode(', ', $category) . ')');
            }

            $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
            $output  = $result['output'];
            $rResult = $result['rResult'];

            foreach ($rResult as $aRow) {
                $row = [];
                $row[] = $aRow['id'];

                $_data = $aRow['name'];
                $_data = '<a href="' . admin_url('ma/sms/' . $aRow['id']) . '">' . $_data . '</a>';
                $_data .= '<div class="row-options">';
                $_data .= '<a href="' . admin_url('ma/sms_detail/' . $aRow['id']) . '">' . _l('view') . '</a>';
                if (has_permission('ma_channels', '', 'edit')) {
                    $_data .= ' | <a href="' . admin_url('ma/sms/' . $aRow['id']) . '">' . _l('edit') . '</a>';
                }

                if (has_permission('ma_channels', '', 'delete')) {
                    $_data .= ' | <a href="' . admin_url('ma/delete_sms/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $_data .= '</div>';
               

                $row[] = $_data;
                $row[] = $aRow['category_name'];

                $row[] = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['dateadded']) . '">' . time_ago($aRow['dateadded']) . '</span>';

                $row['DT_RowClass'] = 'has-row-options';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);
            die();
        }
    }

    /**
     * send example email
     * @param  integer
     * @return redirect
     */
    public function send_example_email($email_id){
        $sent_to_email = $this->input->post('send_to_email');
        $email = $this->ma_model->get_email($email_id);
        $success = $this->ma_model->ma_send_email($sent_to_email, $email);
        
        if($success){
            set_alert('success', _l('send_email_successfully'));
        }else{
            set_alert('warning', _l('send_email_failed'));
        }

        redirect(admin_url('ma/email_detail/' . $email_id));
    }

    /**
     * clone email template
     * @return redirect
     */
    public function clone_email_template(){
        $data = $this->input->post();
        $id = $this->ma_model->clone_email_template($data);

        if($id){
            set_alert('success', _l('clone_successfully'));

            redirect(admin_url('ma/email_template_detail/' . $id));
        }

        redirect(admin_url('ma/settings?group=ma_email_templates'));
    }

    public function ma_run_campaign($id){
        $this->ma_model->run_campaigns($id);
        die;
      
    }
}  