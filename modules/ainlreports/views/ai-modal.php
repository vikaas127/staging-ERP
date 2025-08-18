<div id="ai-modal" class="ai-modal">
    <div class="ai-modal-content">

        <span id="ai-close" class="ai-close">&times;</span>

        <?php if (is_admin()) : ?>
            <a
                    href="<?php echo admin_url(AINLREPORTS_MODULE_NAME . '/settings'); ?>"
                    target="_blank"
                    class="ai-settings-btn"
                    title="<?php echo _l('settings'); ?>"
            >
                <?php echo _l('ainlreports_api_settings'); ?>

                <?php ?>
            </a>
        <?php endif; ?>

        <div class="ai-header">
            <h3><?php echo _l('ainlreports_ask_for_a_report'); ?></h3>
        </div>

        <?php
        if (empty(get_option('ainlreports_ainlreports_sqlenz_api'))) {
            ?>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <?php echo _l('ainlreports_setup_api_key'); ?>
                </div>
            </div>
            <?php
        } else {
            ?>

            <details id="ainl-history" style="margin-top:1rem;">
                <summary style="cursor:pointer; font-weight:500; color:var(--ai-color-primary);">
                    <?php echo _l('ainlreports_show_latest_queries'); ?>
                </summary>
                <ul id="ainl-history-list" style="list-style:none; padding:0; margin:0.5rem 0;"></ul>
            </details>

            <textarea id="ai-question" rows="3" maxlength="300"
                      placeholder="<?php echo _l('ainlreports_ai_question_example'); ?>"></textarea>

            <button id="ai-generate"
                    class="btn ai-btn-generate"><?php echo _l('ainlreports_generate_report_btn'); ?></button>
            <div id="ai-loader" style="display:none;">
                <div class="ai-loader-wrapper">
                    <img
                            id="api-loader"
                            src="<?php echo module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/loader.gif') ?>"
                            alt="Loading..."
                    />
                </div>
            </div>

            <div id="ai-result" style="display:none;">

                <div class="ai-chart-wrapper">
                    <div class="export-buttons pull-right" style="margin-top:1rem;margin-bottom: 10px;">
                        <button id="ainl-export-chart-png"
                                class="btn"><?php echo _l('ainlreports_download_chart_png'); ?></button>
                        <button id="ainl-export-chart-pdf"
                                class="btn"><?php echo _l('ainlreports_download_chart_pdf'); ?></button>
                    </div>
                    <canvas id="ai-chart" style="width:100%;max-height:300px;"></canvas>
                </div>

                <div class="ai-table-wrapper">
                    <div class="export-buttons pull-right" style="margin-top:1rem;margin-bottom: 10px;">
                        <button id="ainl-export-table-csv"
                                class="btn"><?php echo _l('ainlreports_download_table_csv'); ?></button>
                        <button id="ainl-export-table-pdf"
                                class="btn"><?php echo _l('ainlreports_download_table_pdf'); ?></button>
                    </div>
                    <table id="ai-table" class="table table-bordered"></table>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
</div>
