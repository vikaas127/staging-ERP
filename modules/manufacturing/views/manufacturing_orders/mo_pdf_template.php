<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mo = $manufacturing_order_data['manufacturing_order'];
$details = $manufacturing_order_data['product_tab_details'];
$scrap = $manufacturing_order_data['product_for_scrap'];
$costing = $manufacturing_order_data['manufacturing_order_costing'];
$currency = $manufacturing_order_data['currency'];

$routing = $this->manufacturing_model->get_routings($mo->routing_id);
$routing_code = $routing ? $routing->routing_code : '';
$bom = $this->manufacturing_model->get_bill_of_materials($mo->bom_id);
$bom_code = $bom ? $bom->bom_code : '';



// Get page dimensions
$dimensions = $pdf->getPageDimensions();

$info_left_column = pdf_logo_url();
// $org_info = format_organization_info();

pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
$pdf->Ln(0);

// BOM Code and Date Centered
$info = '<div style="text-align:center;">
        <span style="font-weight:bold;font-size:27px;">Manufacturing Order Details</span><br />

            <b style="color:#4e4e4e;">' . html_escape($mo->manufacturing_order_code) . '</b><br />
            Date: ' . _d($mo->date_created ?? date('Y-m-d')) . '
        </div>';
$pdf->writeHTML($info, true, false, false, false, '');
$pdf->Ln(5);

$pdf->SetFont($font_name, 'B', $font_size); 
$pdf->Cell(0, 0, 'Manufactured Product Details', 0, 1, 'L');
$pdf->Ln(2);
$pdf->SetFont($font_name, '', $font_size);

$basic_details = '
<table cellpadding="10" cellspacing="0" width="100%" style="font-size:11px; border: 1px solid #000000; border-collapse: collapse;">
    <tbody>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;" width="35%"><strong>Product ID:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . html_escape($mo->product_id) . '</td>
        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Product Name:</strong></td>
            <td style="border: 1px solid #000000;">' . new_html_entity_decode(mrp_get_product_name($mo->product_id) . $mo->product_variant_name) . '</td>
        </tr>
                
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;" width="35%"><strong>Bom Code:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . html_escape($bom_code) . '</td>
        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Unit:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . mrp_get_unit_name($mo->unit_id) . '</td>

        </tr>        
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;" width="35%"><strong>Product QTY :</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . html_escape($mo->product_qty) . '</td>
        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Routing Code:</strong></td>
            
  <td style="border: 1px solid #000000;" width="65%">' . html_escape($routing_code) . '</td>

        </tr>       
         <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;" width="35%"><strong>Planned From:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . html_escape($mo->date_plan_from) . '</td>
        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Deadline:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' . html_escape($mo->date_deadline) . '</td>
        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Customer</strong></td>
                <td style="border: 1px solid #000000;" width="65%">' .html_escape($mo->contact_id)  . '</td>

        </tr>
        <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Estimate:</strong></td>
            <td style="border: 1px solid #000000;" width="65%"></td>
        </tr>
           <tr>
            <td style="background-color:#002a46; color:#fff; border: 1px solid #000000;"><strong>Status:</strong></td>
            <td style="border: 1px solid #000000;" width="65%">' .html_escape($mo->status) .'</td>
        </tr>



    </tbody>
</table>


';

$pdf->writeHTML($basic_details, true, false, false, false, '');
$pdf->Ln(5);


if (!empty($details)) {
    // Get column headers dynamically from the first row
    $columns = array_keys($details[0]);

    $product_table = '
    <h4>Product Details</h4>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="font-size:11px; border-collapse: collapse;">
        <thead>
            <tr style="background-color:#002a46; color:#fff">
                <th>#</th>';

    // Header row
    foreach ($columns as $col) {
        $product_table .= '<th>' . ucwords(str_replace('_', ' ', $col)) . '</th>';
    }

    $product_table .= '</tr></thead><tbody>';

    // Body rows
    $i = 1;
    foreach ($details as $row) {
        $product_table .= '<tr><td align="center">' . $i++ . '</td>';
        foreach ($columns as $col) {
            $value = is_numeric($row[$col]) ? number_format($row[$col], 2) : html_escape($row[$col]);
            $product_table .= '<td>' . $value . '</td>';
        }
        $product_table .= '</tr>';
    }

    $product_table .= '</tbody></table>';

    // Output to PDF
    $pdf->writeHTML($product_table, true, false, false, false, '');
    $pdf->Ln(5);
}


if (!empty($scrap)) {
    // Define only the required columns (based on the form fields)
    $required_columns = [
        'product_id',
        'unit_id',
        'estimated_quantity',
        'actual_quantity',
        'scrap_type',
        'cost_allocation',
        'reason'
    ];

    $columns = array_filter(array_keys($scrap[0]), function($key) use ($required_columns) {
        return in_array($key, $required_columns);
    });

    $scrap_table = '
    <h4>Scrap Product Details</h4>
    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="font-size:11px; border-collapse: collapse;">
        <thead>
            <tr style="background-color:#002a46; color:#fff">
                <th>#</th>';

    foreach ($columns as $col) {
        $scrap_table .= '<th>' . ucwords(str_replace('_', ' ', $col)) . '</th>';
    }

    $scrap_table .= '</tr></thead><tbody>';

    $i = 1;
    foreach ($scrap as $row) {
        $scrap_table .= '<tr><td align="center">' . $i++ . '</td>';
        foreach ($columns as $col) {
            $value = is_numeric($row[$col]) ? number_format($row[$col], 2) : html_escape($row[$col]);
            $scrap_table .= '<td>' . $value . '</td>';
        }
        $scrap_table .= '</tr>';
    }

    $scrap_table .= '</tbody></table>';

    // Output to PDF
    $pdf->writeHTML($scrap_table, true, false, false, false, '');
    $pdf->Ln(5);
}



// Additional Charges
$pdf->Cell(0, 0, 'Additional Charges', 0, 1, 'L');
$pdf->Ln(2);

$charges_table = '
<table width="100%" cellpadding="8" cellspacing="0" border="1" style="font-size:11px; border-collapse: collapse;">
    <thead>
        <tr style="background-color:#002a46; color:#fff">
            <th width="5%">#</th>
            <th width="25%">Charge Type</th>
            <th width="15%" align="right">Expected</th>
            <th width="15%" align="right">Actual</th>
            <th width="40%">Description</th>
        </tr>
    </thead>
    <tbody>';

$charges = [
    ['label' => 'Labour Charges', 'expected_amount' => $mo->expected_labour_charges, 'actual_amount' => $mo->labour_charges, 'desc' => $labour_charges_description],
    ['label' => 'Electricity Charges', 'expected_amount' => $mo->expected_electricity_charges, 'actual_amount' => $mo->electricity_charges, 'desc' => $electricity_charges_description],
    ['label' => 'Machinery Charges', 'expected_amount' => $mo->expected_machinery_charges, 'actual_amount' => $mo->machinery_charges, 'desc' => $machinery_charges_description],
    ['label' => 'Other Charges', 'expected_amount' => $mo->expected_other_charges, 'actual_amount' => $mo->other_charges, 'desc' => $other_charges_description],
];

$total_expected = 0;
$total_actual = 0;
$i = 1;

foreach ($charges as $charge) {
    $charges_table .= '
        <tr>
            <td  width="5%" align="center" valign="top">' . $i++ . '</td>
            <td  width="25%" valign="top">' . html_escape($charge['label']) . '</td>
            <td  width="15%" align="right" valign="top">' . number_format($charge['expected_amount'], 2) . '</td>
            <td  width="15%" align="right" valign="top">' . number_format($charge['actual_amount'], 2) . '</td>
            <td  width="40%" valign="top">' . html_escape($charge['desc']) . '</td>
        </tr>';

    $total_expected += $charge['expected_amount'];
    $total_actual += $charge['actual_amount'];
}

$charges_table .= '
        <tr style="background-color:#f0f0f0;">
            <td colspan="2" align="right"><strong>Total</strong></td>
            <td align="right"><strong>' . number_format($total_expected, 2) . '</strong></td>
            <td align="right"><strong>' . number_format($total_actual, 2) . '</strong></td>
            <td></td>
        </tr>
    </tbody>
</table>';



$pdf->writeHTML($charges_table, true, false, false, false, '');
$pdf->Ln(5);



$pdf->writeHTML($html, true, false, false, false, '');


$pdf->Ln(4);
?>

