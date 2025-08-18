<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_GET['ocr'])) {
    $apiKey = 'AIzaSyDkBHDvzYT0OtJN5dUHrwYaZ6wxcWjmpBg'; // Replace with your real Vision API key

    $imageData = file_get_contents($_FILES['image']['tmp_name']);
    $base64 = base64_encode($imageData);

    $payload = json_encode([
        'requests' => [[
            'image' => ['content' => $base64],
            'features' => [['type' => 'TEXT_DETECTION']]
        ]]
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://vision.googleapis.com/v1/images:annotate?key=' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);
    $text = $response['responses'][0]['fullTextAnnotation']['text'] ?? '';

   $lines = explode("\n", $text);
$lines = array_map('trim', array_filter($lines)); // clean up empty lines

$name = '';
$company = '';
$address = '';
$email = '';
$phone = '';
$website = '';
$companyKeywords = [
    // Legal Structures & Business Types
    'Pvt', 'Ltd', 'LLP', 'Inc', 'Private Limited', 'Pvt. Ltd.', 'Ltd.', 'LLC', 'Limited', 'Company', 'Corporation', 'Firm','Brokers',

    // Manufacturing & Industrial
    'Industries', 'Industry', 'Manufacturing', 'Manufacturers', 'Fabricators', 'Udyog', 'Works', 'Machines', 'Machine Tools',
    'Engineering', 'Equipments', 'Steel', 'Plastics', 'Chemicals', 'Packaging', 'Pumps', 'Cables', 'Metals', 'Hardware',
    'Components', 'Electric', 'Electricals', 'Switchgears', 'Motors', 'Bearings', 'Castings', 'Fittings', 'Assemblies',

    // Trading, Export, Distribution
    'Exports', 'Exporters', 'Imports', 'Importers', 'Distributors', 'Dealers', 'Traders', 'Trading', 'Merchants', 'Wholesale',

    // Tech & Service
    'Solutions', 'Technologies', 'Systems', 'Software', 'Consultants', 'Services', 'Automation', 'IT', 'Digital', 'AI', 'Analytics',
    'Cloud', 'Infotech', 'Networks', 'Solutions', 'Cyber', 'Robotics', 'Telecom', 'IoT',

    // Textiles, Fashion, Consumer Goods
    'Textiles', 'Fabrics', 'Furnishings', 'Garments', 'Apparels', 'Designs', 'Creations', 'Prints', 'Embroidery',

    // Logistics, Construction, Infrastructure
    'Logistics', 'Infrastructure', 'Construction', 'Builders', 'Developers', 'Transport', 'Warehousing', 'Realty', 'Project',

    // Agriculture & Natural Products
    'Agro', 'Farms', 'BioTech', 'Organics', 'Seeds', 'Crops', 'Irrigation', 'Fertilizers', 'Pesticides', 'Horticulture',

    // Branding Terms (Often used at the end of names)
    'Global', 'International', 'India', 'Overseas', 'Associates', 'Enterprise', 'Group', 'Hub', 'Zone', 'Point', 'Edge', 'Core',
    'Matrix', 'NextGen', 'Classic', 'Elite', 'Universal', 'Prime', 'Infinity', 'Vision', 'Smart', 'Future', 'Eco', 'Swift',

    // Odd/creative but common suffixes in SMEs/startups
    'Studio', 'Labs', 'Crafts', 'Bay', 'Nest', 'Bee', 'Mint', 'Kart', 'Techno', 'Nova', 'Pixel', 'Genix', 'Trek', 'Loop', 'Spark',
    'Magnet', 'Forge', 'Stack', 'Clouds', 'Xperts', 'Hive', 'Nation', 'Booth', 'Dock'
];

// Go through lines and extract fields
foreach ($lines as $line) {
    if (!$email && preg_match('/[\w\.-]+@[\w\.-]+\.\w+/', $line, $match)) {
        $email = $match[0];
    }
    if (!$phone && preg_match('/\+91[-\s]?[0-9]{10}/', $line, $match)) {
        $phone = $match[0];
    }
    if (!$website && preg_match('/(www\.[\w\-\.]+\.\w+)/i', $line, $match)) {
        $website = $match[0];
    }
}

// Try to guess name/company/address
foreach ($lines as $i => $line) {
    if (!$name && stripos($line, '@') === false && stripos($line, 'mobile') === false && stripos($line, 'website') === false && strlen($line) > 4) {
        $name = $line;
    }

   if (!$company && isset($lines[$i + 1])) {
    $nextLine = $lines[$i + 1];

    // Check if next line contains any company keyword
    foreach ($companyKeywords as $keyword) {
        if (stripos($nextLine, $keyword) !== false) {
            $company = $nextLine;
            break;
        }
    }

    // If no keyword match, but it's not too short or an email/phone, fallback anyway
    if (!$company && strlen($nextLine) > 4 && !preg_match('/[@+0-9]/', $nextLine)) {
        $company = $nextLine;
    }
}
if (!$position && preg_match('/\b(Director|Asst. Director|Manager|Engineer|Officer|Founder|Partner|President|CEO|CTO|COO|CFO)\b/i', $line)) {
        $position = trim(strip_tags($line));
    }
   if (!$address) {
    $addressLines = [];

    for ($j = $i + 2; $j < count($lines); $j++) {
        $line = $lines[$j];

        // Skip lines with phone, email, website, or common keywords
        if (
            preg_match('/\+?\d[\d\s-]{7,}/', $line) ||     // phone
            preg_match('/@/', $line) ||                   // email
            preg_match('/(www\.|http)/i', $line) ||       // website
            preg_match('/Director|Manager|CEO|CTO|Partner/i', $line) ||
            preg_match('/^[A-Z][a-z]+\s+[A-Z][a-z]+/', $line) // likely name
        ) {
            continue;
        }

        $addressLines[] = trim($line);

        // Limit to 3 lines max
        if (count($addressLines) >= 3) break;
    }

    if (!empty($addressLines)) {
        $address = implode(', ', $addressLines);
    }
}

}

$data = [
    'name' => $name,
    'company' => $company,
    'address' => $address,
    'email' => $email,
    'phone' => $phone,
     'position' => $position,
    'website' => $website,
    'raw_text' => $text
];


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>

<!DOCTYPE html>
<html dir="<?php echo is_rtl(true) ? 'rtl' : 'ltr'; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo e($form->name); ?></title>
    <?php app_external_form_header($form); ?>
    <?php hooks()->do_action('app_web_to_lead_form_head'); ?>
    <style>
    #form_submit:hover {
        background-color: <?php echo adjust_color_brightness($form->submit_btn_bg_color, -30) ?> !important;
    }
    </style>
</head>

<body class="web-to-lead <?php echo $form->form_key . ($this->input->get('styled') === '1' ? ' styled' : ''); ?>">
    <div class="container-fluid">
        <div class="row">
            <div
                class="<?php echo $this->input->get('col') ? $this->input->get('col') : ($this->input->get('styled') === '1' ? 'col-md-6 col-md-offset-3' : 'col-md-12'); ?>">
                <?php if ($this->input->get('with_logo')) { ?>
                <div class="text-center logo">
                    <?php get_dark_company_logo(); ?>
                </div>
                <?php } ?>
                 <button type="button" id="scanCardBtn" style="margin-bottom: 10px;">
    📷 Scan Card
</button>
<input type="file" accept="image/*" capture="environment" id="cardImageInput" style="display: none;">
<!-- Loader -->
<div id="loader" style="display: none; margin: 10px 0;">🔄 Processing...</div>

                <div class="form-col">
                    <div id="response"></div>
                    <?php echo form_open_multipart($this->uri->uri_string(), ['id' => $form->form_key, 'class' => 'disable-on-submit']); ?>
                    <?php hooks()->do_action('web_to_lead_form_start'); ?>
                    <?php echo form_hidden('key', $form->form_key); ?>
                    <div class="row [&_p]:tw-mb-5">
                        <?php foreach ($form_fields as $field) {
    render_form_builder_field($field);
} ?>
                        <?php if (show_recaptcha() && $form->recaptcha == 1) { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>">
                                </div>
                                <div id="recaptcha_response_field" class="text-danger"></div>
                            </div>
                            <?php } ?>
                            <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) { ?>
                            <div class="col-md-12">
                                <div class="checkbox chk">
                                    <input type="checkbox" name="accept_terms_and_conditions" required="true"
                                        id="accept_terms_and_conditions"
                                        <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                                    <label for="accept_terms_and_conditions">
                                        <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                                    </label>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div class="text-left col-md-12 submit-btn-wrapper">
                                <button class="btn" id="form_submit" type="submit"
                                    style="color: <?php echo $form->submit_btn_text_color ?>;background-color: <?php echo $form->submit_btn_bg_color ?>;">
                                    <i class="fa fa-spinner fa-spin hide" style="margin-right: 2px;"></i>
                                    <?php echo e($form->submit_btn_name); ?></button>
                            </div>
                        </div>

                        <?php hooks()->do_action('web_to_lead_form_end'); ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php app_external_form_footer($form); ?>
        <script>
        var form_id = '#<?php echo e($form->form_key); ?>';
        var form_redirect_url = '<?php echo $form->submit_action == 1 ? $form->submit_redirect_url : 0; ?>';
        $(function() {
            $(form_id).appFormValidator({

                onSubmit: function(form) {

                    $("input[type=file]").each(function() {
                        if ($(this).val() === "") {
                            $(this).prop('disabled', true);
                        }
                    });
                    $('#form_submit .fa-spin').removeClass('hide');

                    var formURL = $(form).attr("action");
                    var formData = new FormData($(form)[0]);

                    $.ajax({
                        type: $(form).attr('method'),
                        data: formData,
                        mimeType: $(form).attr('enctype'),
                        contentType: false,
                        cache: false,
                        processData: false,
                        url: formURL
                    }).always(function() {
                        $('#form_submit').prop('disabled', false);
                        $('#form_submit .fa-spin').addClass('hide');
                    }).done(function(response) {
                        response = JSON.parse(response);
                        if (form_redirect_url !== '0') {
                            if (window.top) {
                                window.top.location.href = form_redirect_url;
                            } else {
                                window.location.href = form_redirect_url;
                            }
                            return;
                        } else if (response.redirect_url) {
                            // In case action hook is used to redirect
                            if (window.top) {
                                window.top.location.href = response.redirect_url;
                            } else {
                                window.location.href = response.redirect_url;
                            }
                            return;
                        }
                        if (response.success == false) {
                            $('#recaptcha_response_field').html(response
                                .message); // error message
                        } else if (response.success == true) {
                            $(form_id).remove();
                            $('#response').html('<div class="alert alert-success">' +
                                response.message + '</div>');
                            $('html,body').animate({
                                scrollTop: $("#online_payment_form").offset().top
                            }, 'slow');
                        } else {
                            $('#response').html("<?php echo _l('something_went_wrong'); ?>");
                        }
                        if (typeof(grecaptcha) != 'undefined') {
                            grecaptcha.reset();
                        }
                    }).fail(function(data) {
                        if (typeof(grecaptcha) != 'undefined') {
                            grecaptcha.reset();
                        }
                        if (data.status == 422) {
                            $('#response').html(
                                '<div class="alert alert-danger">Some fields that are required are not filled properly.</div>'
                            );
                        } else {
                            $('#response').html(data.responseText);
                        }
                    });
                    return false;
                }
            });
        });
        </script>
       <script>
document.getElementById('scanCardBtn').addEventListener('click', function () {
    document.getElementById('cardImageInput').click();
});

document.getElementById('cardImageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    document.getElementById('loader').style.display = 'block';
    document.getElementById('scanCardBtn').disabled = true;
const url = new URL(window.location.href);
url.searchParams.set('ocr', '1');
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log('OCR Data:', data);
      if (data.name) {
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) nameInput.value = data.name;
}
if (data.phone) {
    const phoneInput = document.querySelector('input[name="phonenumber"]');
    if (phoneInput) phoneInput.value = data.phone;
}
if (data.email) {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) emailInput.value = data.email;
}
if (data.address) {
    const addressInput = document.querySelector('textarea[name="address"]');
    if (addressInput) addressInput.value = data.address;
}
if (data.company) {
    const companyInput = document.querySelector('input[name="company"]');
    if (companyInput) companyInput.value = data.company;
}
if (data.position) {
    const companyInput = document.querySelector('input[name="Position"]');
    if (companyInput) companyInput.value = data.position;
}
if (data.website) {
    const companyInput = document.querySelector('input[name="website"]');
    if (companyInput) companyInput.value = data.website;
}
    })
    .catch(err => {
     //  alert('Not able to read your card properly. Please re-try and keep the card focused.');
        console.error(err);
    })
    .finally(() => {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('scanCardBtn').disabled = false;
    });
});
</script>



        <?php hooks()->do_action('app_web_to_lead_form_footer'); ?>
</body>

</html>