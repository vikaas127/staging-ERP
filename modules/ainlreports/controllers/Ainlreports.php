<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ainlreports extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ainlreports_model');
        hooks()->do_action('ainlreports_init');
    }

    public function index()
    {
        show_404();
    }

    public function settings()
    {
        if (!is_admin()) {
            access_denied('ainlreports');
        }

        if ($this->input->post()) {

            $this->load->model('payment_modes_model');
            $this->load->model('settings_model');

          //  publishx_handle_company_logo_upload();
          //  publishx_handle_company_favicon_upload();

            $post_data = $this->input->post();
            $tmpData = $this->input->post(null, false);

            if (isset($post_data['settings']['email_header'])) {
                $post_data['settings']['email_header'] = $tmpData['settings']['email_header'];
            }

            if (isset($post_data['settings']['email_footer'])) {
                $post_data['settings']['email_footer'] = $tmpData['settings']['email_footer'];
            }

            if (isset($post_data['settings']['email_signature'])) {
                $post_data['settings']['email_signature'] = $tmpData['settings']['email_signature'];
            }

            if (isset($post_data['settings']['smtp_password'])) {
                $post_data['settings']['smtp_password'] = $tmpData['settings']['smtp_password'];
            }

            $success = $this->settings_model->update($post_data);

            if ($success > 0) {
                set_alert('success', _l('settings_updated'));
            }

            redirect(admin_url(AINLREPORTS_MODULE_NAME . '/settings'), 'refresh');
        }

        $data['title'] = _l('ainlreports') . ' - ' . _l('settings');
        $this->load->view('settings', $data);
    }

    public function get_history()
    {
        $user_id = get_staff_user_id();

        $history = $this->db
            ->select('user_query')
            ->from(db_prefix() . 'ainlreports_query_history')
            ->where('user_id', $user_id)
            ->group_by('user_query')
            ->order_by('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($history));
    }

    public function query()
    {
        if (!has_permission('ainlreports', '', 'generate')) {
            access_denied('ainlreports');
        }

        $nlQuery = trim($this->input->post('question'));
        if ($nlQuery === '') {
            echo json_encode(['status' => 'error', 'message' => 'No question']);
            die;
        }

        if (mb_strlen($nlQuery) > 300) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Your question cannot exceed 300 characters.'
            ]);
            die;
        }

        $skipCache = (bool)$this->input->post('skip_cache');

        $apiResponse = $this->query_sqlenz($nlQuery, db_prefix(), $skipCache);

        if (isset($apiResponse['status']) && $apiResponse['status'] === 'error') {
            echo json_encode($apiResponse);
            die;
        }

        $tableName = $apiResponse['mainTable'];
        $aliasInfo = json_decode($apiResponse['aliasInfo'], true);

        $rows = $this->db->query($apiResponse['sql'])->result_array();
        $rowsPretty = $rows;

        foreach ($rowsPretty as &$r) {
            $currencyObj = null;

            if ($aliasInfo) {
                [$tbl, $currencyCol] = reset($aliasInfo);
                if ($currencyCol && isset($r[$currencyCol])) {
                    $currencyObj = get_currency($r[$currencyCol]);
                }
            }
            if (!$currencyObj) {
                $currencyObj = get_base_currency();
            }

            foreach ($aliasInfo as $alias => $meta) {
                if (isset($r[$alias])) {
                    $r[$alias] = app_format_money($r[$alias], $currencyObj);
                }
            }
        }
        unset($r);

        $rowsPretty = $this->generalFormatting($rowsPretty);
        $rows = $this->generalFormatting($rows);

        if ($tableName === db_prefix() . 'tasks') {
            $rowsPretty = $this->formTaskData($rowsPretty);
            $rows = $this->formTaskData($rows);
        }

        if ($tableName === db_prefix() . 'leads') {
            $rowsPretty = $this->formLeadData($rowsPretty);
            $rows = $this->formLeadData($rows);
        }

        if ($tableName === db_prefix() . 'projects') {
            $rowsPretty = $this->formProjectData($rowsPretty);
            $rows = $this->formProjectData($rows);
        }

        if ($tableName === db_prefix() . 'expenses') {
            $rowsPretty = $this->formExpenseData($rowsPretty);
            $rows = $this->formExpenseData($rows);
        }

        if ($tableName === db_prefix() . 'invoices') {
            $rowsPretty = $this->formInvoiceData($rowsPretty);
            $rows = $this->formInvoiceData($rows, true);
        }

        if ($tableName === db_prefix() . 'tickets') {
            $rowsPretty = $this->formTicketData($rowsPretty);
            $rows = $this->formTicketData($rows, true);
        }

        $out = [
            'chartType' => $apiResponse['chartType'],
            'xLabel' => $apiResponse['xLabel'] ?? '',
            'yLabel' => $apiResponse['yLabel'] ?? '',
            'data' => $rows,
            'generatedSql' => $apiResponse['sql'],
            'tableData' => $rowsPretty,
        ];

        $this->db->insert(db_prefix() . 'ainlreports_query_history', [
            'user_query' => $nlQuery,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => get_staff_user_id(),
        ]);

        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    // ---------- Helpers ---------------------------------------------------

    /**
     * Private helper that does the cURL call to SQLenz
     *
     * @param string $query
     * @param string $model
     * @param string $tablePrefix
     * @param bool $skipCache
     * @return array
     * @throws Exception
     */
    private function query_sqlenz(string $query, string $tablePrefix, bool $skipCache = false): array
    {
        // Build payload
        $payload = [
            'query' => $query,
            'table_prefix' => $tablePrefix,
        ];
        if ($skipCache) {
            $payload['skip_cache'] = true;
        }

        $origin = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);

        $ch = curl_init('https://sqlenz.com/app/api/query');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . get_option('ainlreports_ainlreports_sqlenz_api'),
                'X-Origin: ' . $origin,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error while calling SQLenz: {$err}");
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    private function generalFormatting(array $rows): array
    {
        foreach ($rows as &$r) {
            foreach ($r as $k => $v) {
                if ($v === 'null' || $v === null) {
                    $r[$k] = '';
                }

                if (is_numeric($v) && (is_float($v + 0) || strpos($v, '.') !== false)) {
                    $r[$k] = app_format_money($v, get_base_currency());
                }
            }
        }
        return $rows;
    }

    private function formTaskData(array $rows): array
    {
        foreach ($rows as &$r) {

            if (isset($r['id'], $r['name'])) {
                $url = admin_url('tasks/view/' . (int)$r['id']);
                $safeName = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
                $r['name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['status'])) {

                $statusText = $r['status'];

                if ($r['status'] == 1) {
                    $statusText = _l('task_status_1');
                }
                if ($r['status'] == 2) {
                    $statusText = _l('task_status_2');
                }
                if ($r['status'] == 3) {
                    $statusText = _l('task_status_3');
                }
                if ($r['status'] == 4) {
                    $statusText = _l('task_status_4');
                }
                if ($r['status'] == 5) {
                    $statusText = _l('task_status_5');
                }

                $r['status'] = $statusText;
            }

            if (isset($r['priority'])) {
                if (is_numeric($r['priority'])) {
                    $r['priority'] = '<span style="color:' . task_priority_color($r['priority']) . ';" class="inline-block">' . task_priority($r['priority']) . '</span>';
                }
            }
        }
        return $rows;
    }

    private function formLeadData(array $rows): array
    {
        foreach ($rows as &$r) {

            if (isset($r['id'], $r['name'])) {
                $url = admin_url('leads/index/' . (int)$r['id']);
                $safeName = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
                $r['name'] = '<a href="' . $url . '" onclick="init_lead(' . $r['id'] . ');return false;">' . $safeName . '</a>';

            }

            if (isset($r['source'])) {

                $this->load->model('leads_model');

//                $data['statuses'] = $this->leads_model->get_status();
                $leadSources = (array)$this->leads_model->get_source();


                $finalSource = $r['source'];

                foreach ($leadSources as $source) {
                    if ($source['id'] == $r['source']) {
                        $finalSource = $source['name'];
                    }
                }

                $r['source'] = $finalSource;
            }
        }
        return $rows;
    }

    private function formProjectData(array $rows): array
    {
        foreach ($rows as &$r) {

            if (isset($r['id'], $r['name'])) {
                $url = admin_url('projects/view/' . (int)$r['id']);
                $safeName = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
                $r['name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['project_id'], $r['project_name'])) {
                $url = admin_url('projects/view/' . (int)$r['project_id']);
                $safeName = htmlspecialchars($r['project_name'], ENT_QUOTES, 'UTF-8');
                $r['project_name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['status'])) {

                $statusText = $r['status'];

                if ($r['status'] == 1) {
                    $statusText = _l('project_status_1');
                }
                if ($r['status'] == 2) {
                    $statusText = _l('project_status_2');
                }
                if ($r['status'] == 3) {
                    $statusText = _l('project_status_3');
                }
                if ($r['status'] == 4) {
                    $statusText = _l('project_status_4');
                }

                $r['status'] = $statusText;
            }
        }
        return $rows;
    }

    private function formExpenseData(array $rows): array
    {
        foreach ($rows as &$r) {

            if (isset($r['id'], $r['name'])) {
                $url = admin_url('expenses#' . (int)$r['id']);
                $safeName = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
                $r['name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['expense_id'], $r['expense_name'])) {
                $url = admin_url('expenses#' . (int)$r['project_id']);
                $safeName = htmlspecialchars($r['expense_name'], ENT_QUOTES, 'UTF-8');
                $r['expense_name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['id'], $r['expense_name'])) {
                $url = admin_url('expenses#' . (int)$r['id']);
                $safeName = htmlspecialchars($r['expense_name'], ENT_QUOTES, 'UTF-8');
                $r['expense_name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['expense_id'], $r['name'])) {
                $url = admin_url('expenses#' . (int)$r['expense_id']);
                $safeName = htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');
                $r['name'] = '<a href="' . $url . '" target="_blank">' . $safeName . '</a>';
            }

            if (isset($r['project_id'])) {
                $this->load->model('projects_model');

                $projectData = $this->projects_model->get($r['project_id']);

                $projectId = $r['project_id'];
                $projectName = '';

                if (!empty($projectData)) {
                    $projectName = $projectData->name;
                }

                $r['project_id'] = $projectId;
                $r['project_name'] = $projectName;
            }

//            if (isset($r['status'])) {
//
//                $statusText = $r['status'];
//
//                if ($r['status'] == 1) {
//                    $statusText = _l('project_status_1');
//                }
//                if ($r['status'] == 2) {
//                    $statusText = _l('project_status_2');
//                }
//                if ($r['status'] == 3) {
//                    $statusText = _l('project_status_3');
//                }
//                if ($r['status'] == 4) {
//                    $statusText = _l('project_status_4');
//                }
//
//                $r['status'] = $statusText;
//            }
        }
        return $rows;
    }

    private function formInvoiceData(array $rows, bool $isForChart = false): array
    {
        foreach ($rows as &$r) {

            if (isset($r['total']) && is_numeric($r['total'])) {
                $r['total'] = app_format_money($r['total'], get_base_currency());
            }

            if (isset($r['number']) && is_numeric($r['number'])) {
                $formattedInvoiceNumber = format_invoice_number($r['number']);

                $r['number'] = $formattedInvoiceNumber;

                if (isset($r['id']) && is_numeric($r['id'])) {
                    $invoiceUrl = admin_url('invoices#' . $r['id']);
                    $r['number'] = '<a href="' . $invoiceUrl . '" target="_blank">' . $formattedInvoiceNumber . '</a>';
                }
            }

            if (isset($r['clientid']) && is_numeric($r['clientid'])) {
                $clientData = get_client($r['clientid']);
                $clientUrl = admin_url('clients/client/' . $r['clientid']);

                $r['clientid'] = $clientData->company;

                if (!$isForChart) {
                    $r['clientid'] = '<a href="' . $clientUrl . '" target="_blank">' . $clientData->company . '</a>';
                }
            }

        }
        return $rows;
    }

    private function formTicketData(array $rows, bool $isForChart = false): array
    {
        foreach ($rows as &$r) {

            if (isset($r['userid']) && is_numeric($r['userid'])) {
                $staffData = get_staff_full_name($r['userid']);

                $r['userid'] = $staffData;
                if (!$isForChart) {
                    $staffUrl = admin_url('staff/member/' . $r['userid']);
                    $r['userid'] = '<a href="' . $staffUrl . '" target="_blank">' . $staffData . '</a>';
                }
            }

        }
        return $rows;
    }
}
