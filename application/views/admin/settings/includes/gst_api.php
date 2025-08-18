<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="container">

    <!-- Info Section -->
    <div id="infoSection">
        <img src="https://cdn-icons-png.flaticon.com/512/4086/4086679.png" alt="Bonus Icon" width="80">
        <h5>Are your employees eligible to receive statutory bonus?</h5>
        <p>
            According to the Payment of Bonus Act 1965, an eligible employee can receive a statutory bonus of 8.33% to 20% of salary.
            Enable this option to start paying your employees.
        </p>

        <!-- Enable Button -->
        <button id="enableBtn" class="toggle-btn">Enable Bonus</button>
    </div>

    <!-- Bonus Form -->
    <div class="row" id="easyForm" style="display: none;">
        <div class="col-4">
            <div class="form-group">
                <label for="annual_turnover">Annual Turnover <span class="model_span">*</span></label>
                <input type="text" class="form-control" id="annual_turnover" name="annual_turnover" required>
            </div>
        </div>
        <div class="col-4">
            <input type="hidden" name="einvoice_enabled" id="einvoice_enabled">
            <!-- <div class="form-group">
                <label for="einvoice_enabled">E-Invoice Enabled <span class="model_span">*</span></label>
                <input type="text" class="form-control" id="einvoice_enabled" placeholder="E-Invoice Enabled " required>
            </div> -->
        </div>
        <div class="col-4">
            <input type="hidden" name="einvoice_enforced" id="einvoice_enforced">
            <!-- <div class="form-group">
                <label for="einvoice_enforced">E-Invoice Enforced <span class="model_span">*</span></label>
                <input type="text" class="form-control" id="einvoice_enforced" placeholder="E-Invoice Enforced">
            </div> -->
        </div>

        <button type="button" class="btn btn-success" id="submitBonusForm" disabled>Submit</button>
    </div>



</div>


<ul class="nav nav-tabs initialy-hide" role="tablist">
    <li class="active">
        <a href="#eway_bills" role="tab" data-toggle="tab" class="tab-link"
            data-tab="eway_bills"><?php echo _l('eway_bills_tab'); ?></a>
    </li>
    <li>
        <a href="#eway_bill_invoice" role="tab" data-toggle="tab" class="tab-link"
            data-tab="eway_bill_invoice"><?php echo _l('eway_bill_invoice_tab'); ?></a>
    </li>
</ul>

<div class="tab-content initialy-hide">
    <!-- E-way Bills Tab -->
    <div role="tabpanel" class="tab-pane active" id="eway_bills">
        <div class="api-container">
            <button type="button" class="btn btn-primary" id="openEwayInvoiceModal" data-tab="eway_bills">+
                <?php echo _l('add_eway_bill_api_account'); ?></button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo _l('gstin'); ?></th>
                    <th><?php echo _l('gst_api_username'); ?></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="eway_table_body"></tbody>
        </table>
    </div>

    <!-- E-way Invoice Tab -->
    <div role="tabpanel" class="tab-pane" id="eway_bill_invoice">
        <div class="api-container">
            <button type="button" class="btn btn-primary" id="openEwayModal" data-tab="eway_bill_invoice">+
                <?php echo _l('add_eway_invoice_api_account'); ?></button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo _l('gstin'); ?></th>
                    <th><?php echo _l('gst_api_username'); ?></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="eway_invoice_table_body"></tbody>
        </table>
    </div>
</div>

<!-- Single Modal for Both Tabs -->
<div id="ewayModal" class="modal">
    <div class="modal-content">
        <span class="closeEway">&times;</span>
        <h4 id="modalTitle"><?php echo _l('add_api_account'); ?></h4>
        <form id="ewayForm" onsubmit="return false;">

            <!-- <?php echo form_open('GSTAPI/authenticate', ['id' => 'ewayForm']); ?> -->
            <div class="form-group">
                <label for="gstin"><?php echo _l('gstin'); ?>: <span class="model_span">*</span></label>
                <input type="text" class="form-control" name="gstin" id="gstin" required>
            </div>
            <div class="form-group">
                <label for="username"><?php echo _l('gst_api_username'); ?>: <span class="model_span">*</span></label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password"><?php echo _l('gst_api_password'); ?>: <span class="model_span">*</span></label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="button" id="saveButton" class="btn btn-success">Save</button>
            <!-- <?php echo form_close(); ?> -->
        </form>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 📌 Bonus Form Toggle
        const enableBtn = document.getElementById("enableBtn");
        const infoSection = document.getElementById("infoSection");
        const bonusForm = document.getElementById("easyForm");

        document.getElementById('annual_turnover').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        const annual_turnover = document.getElementById('annual_turnover');
        const submitBonusForm = document.getElementById('submitBonusForm');
        const form = document.getElementById('settings-form');

        annual_turnover.addEventListener('input', function () {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');

            console.log("<?php echo base_url('GSTAPI/authenticate'); ?>")

            // Enable button only if value is exactly 50000000
            if (parseInt(this.value) >= 50000000) {
              submitBonusForm.disabled = false;
            } else {
              submitBonusForm.disabled = true;
            }
        });

        submitBonusForm.addEventListener('click', function (e) {
            form.submit();  // Bypasses native validation
        });

        console.log("🧪 Form:", document.getElementById("easyForm"));
        if (enableBtn && bonusForm && infoSection) {
            enableBtn.addEventListener("click", function() {
                infoSection.style.display = "none";
                bonusForm.style.display = "block";
                console.log("Form is now visible.");
            });
        }

        // 📌 Eway Modal Logic
        var ewayModal = document.getElementById("ewayModal");
        var ewayBtn = document.getElementById("openEwayModal");
        var ewayInvoiceBtn = document.getElementById("openEwayInvoiceModal");
        var closeEway = document.querySelector(".closeEway");
        var responseMessage = document.getElementById("responseMessage");
        var gstinInput = document.getElementById("gstin");

        if (ewayBtn) {
            ewayBtn.addEventListener("click", function() {
                ewayModal.style.display = "block";
            });
        }

        if (ewayInvoiceBtn) {
            ewayInvoiceBtn.addEventListener("click", function() {
                ewayModal.style.display = "block";
            });
        }

        if (closeEway) {
            closeEway.addEventListener("click", function() {
                ewayModal.style.display = "none";
            });
        }

        window.addEventListener("click", function(event) {
            if (event.target === ewayModal) {
                ewayModal.style.display = "none";
            }
        });

        if (gstinInput) {
            gstinInput.addEventListener("input", function() {
                this.value = this.value.toUpperCase();
            });
        }

        const saveBtn = document.getElementById("saveButton");
        if (saveBtn) {
            saveBtn.addEventListener("click", function(event) {
                var gstin = document.getElementById("gstin").value;
                var username = document.getElementById("username").value;
                var password = document.getElementById("password").value;

                if (!gstin || !username || !password) {
                    responseMessage.innerHTML = "<div class='alert alert-danger'>All fields are required!</div>";
                    return;
                }

                var formData = {
                    gstin: gstin,
                    username: username,
                    password: password
                };

                fetch("<?php echo base_url('GSTAPI/authenticate'); ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            responseMessage.innerHTML = "<div class='alert alert-success'>" + data.message + "</div>";
                            setTimeout(() => {
                                ewayModal.style.display = "none";
                            }, 2000);
                        } else {
                            responseMessage.innerHTML = "<div class='alert alert-danger'>" + data.message + "</div>";
                        }
                    })
                    .catch(error => {
                        responseMessage.innerHTML = "<div class='alert alert-danger'>Invalid GST Number</div>";
                    });
            });
        }
    });
</script>


<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .initialy-hide {
        display: none;
    }

    .model_span {
        color: red;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 30%;
        margin: 10% auto;
        position: relative;
    }

    .close {
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    /* Table Styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .table th,
    .table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f4f4f4;
        text-align: left;
    }

    /* API Container */
    .api-container {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 15px;
    }


    .container {
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
        padding: 20px;
    }

    .hidden {
        display: none;
    }

    .toggle-btn {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .toggle-btn:hover {
        background-color: #218838;
    }

    form {
        text-align: left;
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0069d9;
    }
</style>