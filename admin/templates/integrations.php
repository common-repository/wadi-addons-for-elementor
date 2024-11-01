<form action="" method="POST" id="wadi_settings_integrations" name="wadi_settings_integrations" class="wadi_settings_integrations_form">
            <div class="wadi_admin_settings_content">
                <div class="wadi_paypal_integration">
                    <h2>PayPal</h2>
                    <div class="wadi_paypal_client_id">
                    <label class="wadi_paypal_client_id_item" for="wadi_paypal_client_id">
                        Client ID
                    </label>
                    <input name="wadi_paypal_client_id" id="wadi_paypal_client_id" type="text" placeholder="Paypal Client ID" value="<?php echo esc_attr( $settings['wadi_paypal_client_id'] ); ?>">
                    
                    </div>
                    <div class="wadi_paypal_currency">
                        <label for="wadi_paypal_amount_currency">Currency</label>
                        <select name="wadi_paypal_currency" class="wadi_paypal_amount" id="wadi_paypal_amount_currency">
                            <?php foreach($currencies as $key => $item) {
                                ?>
                                <option value="<?php echo $key; ?>"
                                <?php 
                                if( empty ($settings['wadi_paypal_currency'] ) ) {
                                    $settings['wadi_paypal_currency'] = 'USD';
                                }
                                
                                if($settings['wadi_paypal_currency'] === $key ) {
                                    echo 'selected';
                                }
                                ?>
                                ><?php echo $item . ' ' . '( '.$key .' )'; ?></option>
                                <?php
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>