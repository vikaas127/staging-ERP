<?php

defined('BASEPATH') || exit('No direct script access allowed');

$aColumns = [
    'id',
    'template_name',
    'language',
    'category',
    'header_data_format',
    'status',
    'body_data',
];

$where = [];
$where[] = 'AND status = "APPROVED"';

$sIndexColumn = 'id';
$sTable = db_prefix().'wtc_templates';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['id'];

    $row[] = $aRow['template_name'];

    $row[] = $aRow['language'];

    $row[] = $aRow['category'];

    $row[] = $aRow['header_data_format'];

    if ('APPROVED' == $aRow['status']) {
        $status = '<span class="inline-block label label-success">'.$aRow['status'].'</span>';
    }
    $row[] = $status;

    $row[] = $aRow['body_data'];

    $output['aaData'][] = $row;
}
