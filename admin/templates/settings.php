<?php

use WadiAddons\Admin\Admin_Helper;
use WadiAddons\Includes\WadiHelpers;


$currencies = WadiHelpers::WADI_CURRENCIES;
$settings = Admin_Helper::get_integrations_settings();


?>



<div class="wrapper">
    <div class="tabs">
      <div role="tablist" aria-label="Wadi Settings Tabs">
        <a href="#tab=integrations" role="tab" aria-selected="true" id="integrations">
          Integrations
        </a>
        <!-- <a href="#tab=white_label" role="tab" aria-selected="false" id="white_label">White Label
        </a> -->
        <a href="#tab=system_info" role="tab" aria-selected="false" id="system_info">
          System Info
        </a>
      </div>
      <div class="wadi_tabpanel" role="tabpanel" aria-labelledby="integrations">
        <?php require_once WADI_ADDONS_ADMIN_TEMPLATES_PATH . 'integrations.php'; ?>
      </div>
      <!-- <div class="wadi_tabpanel" role="tabpanel" aria-labelledby="white_label" hidden>
        <p>White Label</p>
      </div> -->
      <div class="wadi_tabpanel" role="tabpanel" aria-labelledby="system_info" hidden>
        <!-- <p>System Info</p> -->
        <p>Coming Soon !</p>
      </div>
    </div>
  </div>


