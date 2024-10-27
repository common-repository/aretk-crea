<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/admin/partials
 */

/**
 * create function for aretkcrea_custom_crea_plugin_html
 * 
 * @return return html for the CREA plugin first tab.
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_crea_plugin_html() 
{   
    $getSubscriptionStatus = get_option('crea_subscription_status', '');
    $getSubscriptionKey = get_option('crea_subscription_key', '');
    $key = !empty($getSubscriptionKey) ? $getSubscriptionKey : '';  ?>    
    <div class="crea-container">
        <div id="crea_loder_display" class="crea_loading_screen" style="display:none;"><div id="loadingScreen"></div></div>
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>" />      
        <div class="crea-plugin-title">
            <h2><?php echo __(ARETKCREA_SUBSCRIPTION_SETTING_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2>
        </div>
        <div class="crea-plugin-main-content">
            <p class="crea-ma-40"><?php echo __(ARETKCREA_SUBSCRIPTION_API_TITLE, ARETKCREA_PLUGIN_SLUG) ?> </p>
            <div class="crea-api-key-main">
                <h4><?php echo __(ARETKCREA_SUBSCRIPTION_API_KEY, ARETKCREA_PLUGIN_SLUG); ?></h4>
                <input type="text" name="crea-aretk-api-key" class="crea-api-key-values" value="<?php echo $key ?>">
                <input id="crea_check_subscription_button" type="button" name="crea-aretkapi-key-update" value="<?php echo __(ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG); ?>" class="crea-btn-primary button button-primary">
                <p class="blank_msg" style="display:none;color:red;"> <?php echo __(ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_EROOR_MSG_BLANK, ARETKCREA_PLUGIN_SLUG); ?></p>
                <p class="not_valid_msg" style="display:none;color:red;"><?php echo __(ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_EROOR_MSG_NOT_VALID, ARETKCREA_PLUGIN_SLUG); ?> </p>
                <p class="suceess_msg" style="display:none;color:green;"><?php echo __(ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_SUCESS, ARETKCREA_PLUGIN_SLUG); ?> </p>
            </div><?php
            if ($getSubscriptionStatus === 'not-valid' || empty($getSubscriptionStatus) || $getSubscriptionStatus == "" ) {
                $getSubscriptionStatus = __(ARETKCREA_SUBSCRIPTION_API_STATUS_INACTIVE, ARETKCREA_PLUGIN_SLUG);
                $subscription_statue_class = 'status_inactive';
            } else {
                $getSubscriptionStatus = __(ARETKCREA_SUBSCRIPTION_API_STATUS_ACTIVE, ARETKCREA_PLUGIN_SLUG);
                $subscription_statue_class = 'status_active';
            } ?>
            <p class="crea-bottom-line"><?php echo __(ARETKCREA_SUBSCRIPTION_API_ACCOUNT_STATUS, ARETKCREA_PLUGIN_SLUG); ?><b class="<?php echo $subscription_statue_class; ?>"><?php echo $getSubscriptionStatus; ?></b></p><?php
            if ($getSubscriptionStatus === 'Active' && !empty( $getSubscriptionStatus ) ) { ?>  
                <a href="https://aretk.com/my-account" target="_blank"><input type="button" name="crea-api-account-manage" value="<?php echo __(ARETKCREA_SUBSCRIPTION_MANAGE_ACCOUNT_BTN, ARETKCREA_PLUGIN_SLUG); ?>" class="button button-primary"></a><?php 
            } else { ?>
                <a href="https://aretk.com" target="_blank"><input type="button" name="crea-aretkapi-key-get" value="<?php echo __(ARETKCREA_SUBSCRIPTION_API_KEY_BTN, ARETKCREA_PLUGIN_SLUG); ?>" class="get-api-btn button button-primary"></a><?php 
            } ?>            
            <div class="Aretk_subscription_setting_content">
                <p><strong>ARETK is a CREA DDF<sup>&reg;</sup> Technology Provider</strong></p>
                <div class="aretk_subscription_setting_inner_content">
                    <p>A CREA (Canadian Real Estate Asscociation) subscription will allow Licenced Canadian REALTORS<sup>&reg;</sup> to integrate their CREA DDF<sup>&reg;</sup> (Data Distribution Facility) feed into their ARETK listing displays, including National Shared Pool (across Canada), Board Listings and Personal Listings. These listings will be automatically updated hourly and all listing information is stored on our ARETK server so they do not utilize your website hosting space and you can display listings from across Canada with ease.</p>
                <p><strong>Benefits:</strong> Website owners (and any agent you choose to include in your Aretk Plugin) receives the inquiries from all listings displayed on your site. That can result in a lot of leads!</p>
                <p><strong>Note:</strong> National Shared Pool and Board Feeds are subject to Brokerage approval. Ask your Broker of Record to see if your brokerage has opted into the National Shared Pool.</p>
                </div>
            </div>            
        </div>
    </div><?php
}

/**
 * create function for aretkcrea_custom_crea_settings_html
 * 
 * @return return html for the CREA Settings second tab.
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_crea_settings_html() {
    global $wpdb;
    $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
    $getSubscriptionStatus = get_option('crea_subscription_status', true); ?>
    <div class="crea-container">
        <div id="crea_loder_display" class="crea_loading_screen" style="display:none;"><div id="loadingScreen"></div></div>
        <div class="crea-plugin-title remove-border"><h2><?php echo __(ARETKCREA_SETTING_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2></div>
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <div class="crea-plugin-main-content ra-ma-0"><?php
    if ($getSubscriptionStatus != 'not-valid') 
    {
        $crea_user_listing_detail_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
        $sql_select = "SELECT `username`, `ddf_type` FROM `$crea_user_listing_detail_table_name`";  
        $sql_prep = $wpdb->prepare( $sql_select, NULL );
        $resultUsernameSetArr = $wpdb->get_results($sql_prep);      
        $firstUserName = isset($resultUsernameSetArr[0]->username) ? $resultUsernameSetArr[0]->username : '';
        $secondUserName = isset($resultUsernameSetArr[1]->username) ? $resultUsernameSetArr[1]->username : '';
        $thirdUserName = isset($resultUsernameSetArr[2]->username) ? $resultUsernameSetArr[2]->username : '';
        $fourthUserName = isset($resultUsernameSetArr[3]->username) ? $resultUsernameSetArr[3]->username : '';
        $fifthUserName = isset($resultUsernameSetArr[4]->username) ? $resultUsernameSetArr[4]->username : '';
        $firstUserDdf = isset($resultUsernameSetArr[0]->ddf_type) ? $resultUsernameSetArr[0]->ddf_type : '';
        $secondUserDdf = isset($resultUsernameSetArr[1]->ddf_type) ? $resultUsernameSetArr[1]->ddf_type : '';
        $thirdUserDdf = isset($resultUsernameSetArr[2]->ddf_type) ? $resultUsernameSetArr[2]->ddf_type : '';
        $fourthUserDdf = isset($resultUsernameSetArr[3]->ddf_type) ? $resultUsernameSetArr[3]->ddf_type : '';
        $fifthUserDdf = isset($resultUsernameSetArr[4]->ddf_type) ? $resultUsernameSetArr[4]->ddf_type : '';    
        
        if ($firstUserName != '') {         
            $firstUserDataSet = get_option('firstUserNameresultSet');
            if (!empty($firstUserDataSet) && isset($firstUserDataSet)) {
                $firstUserNameresultSet = $firstUserDataSet;
            } else {
                $firstUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username($firstUserName);
            }       
            $firstUserNameresultSet = json_decode(stripslashes($firstUserNameresultSet));   
            if (isset($firstUserNameresultSet) && $firstUserNameresultSet != null && $firstUserNameresultSet != '') {
                if ((int) $firstUserNameresultSet[0]->TotalRecords > 0) {
                    $firstUserNameuser_listing_details_obj_merged = (object) array_merge((array) $firstUserNameresultSet[0], (array) $firstUserNameresultSet[1]);
                    if ($firstUserNameuser_listing_details_obj_merged != '' && isset($firstUserNameuser_listing_details_obj_merged) && $firstUserNameuser_listing_details_obj_merged != null) {
                        $firstTotal = isset($firstUserNameuser_listing_details_obj_merged->ListingsTotal) ? $firstUserNameuser_listing_details_obj_merged->ListingsTotal : '-';
                        $firstLastUpdate = isset($firstUserNameuser_listing_details_obj_merged->LastUpdated) ? $firstUserNameuser_listing_details_obj_merged->LastUpdated : '-';
                        $firstStatus = isset($firstUserNameuser_listing_details_obj_merged->status) ? $firstUserNameuser_listing_details_obj_merged->status : '-';
                    }
                } else {
                    $firstTotal = isset($firstUserNameresultSet[0]->TotalRecords) ? $firstUserNameresultSet[0]->TotalRecords : '-';
                    $firstLastUpdate = '-';
                    $firstStatus = '-';
                }
            }
        } else {
            $firstTotal = '-';
            $firstLastUpdate = '-';
            $firstStatus = '-';
        }
        if ($secondUserName != '') {
            $secondUserDataSet = get_option('secondUserNameresultSet');
            if (!empty($secondUserDataSet) && isset($secondUserDataSet)) {
                $secondUserNameresultSet = $secondUserDataSet;
            } else {
                $secondUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username($secondUserName);
            }
            $secondUserNameresultSet = json_decode($secondUserNameresultSet);
            if (isset($secondUserNameresultSet) && $secondUserNameresultSet != null && $secondUserNameresultSet != '') {
                if ((int) $secondUserNameresultSet[0]->TotalRecords > 0) {
                    $secondUserNameuser_listing_details_obj_merged = (object) array_merge((array) $secondUserNameresultSet[0], (array) $secondUserNameresultSet[1]);
                    if ($secondUserNameuser_listing_details_obj_merged != '' && isset($secondUserNameuser_listing_details_obj_merged) && $secondUserNameuser_listing_details_obj_merged != null) {
                        $secondTotal = isset($secondUserNameuser_listing_details_obj_merged->ListingsTotal) ? $secondUserNameuser_listing_details_obj_merged->ListingsTotal : '-';
                        $secondLastUpdate = isset($secondUserNameuser_listing_details_obj_merged->LastUpdated) ? $secondUserNameuser_listing_details_obj_merged->LastUpdated : '-';
                        $secondStatus = isset($secondUserNameuser_listing_details_obj_merged->status) ? $secondUserNameuser_listing_details_obj_merged->status : '-';
                    }
                } else {
                    $secondTotal = isset($secondUserNameresultSet[0]->TotalRecords) ? $secondUserNameresultSet[0]->TotalRecords : '-';
                    $secondLastUpdate = '-';
                    $secondStatus = '-';
                }
            }
        } else {
            $secondTotal = '-';
            $secondLastUpdate = '-';
            $secondStatus = '-';
        }
        if ($thirdUserName != '') {
            $thirdUserDataSet = get_option('thirdUserNameresultSet');
            if (!empty($thirdUserDataSet) && isset($thirdUserDataSet)) {
                $thirdUserNameresultSet = $thirdUserDataSet;
            } else {
                $thirdUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username($thirdUserName);
            }
            $thirdUserNameresultSet = json_decode($thirdUserNameresultSet);
            if (isset($thirdUserNameresultSet) && $thirdUserNameresultSet != null && $thirdUserNameresultSet != '') {
                if ((int) $thirdUserNameresultSet[0]->TotalRecords > 0) {
                    $thirdUserNameuser_listing_details_obj_merged = (object) array_merge((array) $thirdUserNameresultSet[0], (array) $thirdUserNameresultSet[1]);
                    if ($thirdUserNameuser_listing_details_obj_merged != '' && isset($thirdUserNameuser_listing_details_obj_merged) && $thirdUserNameuser_listing_details_obj_merged != null) {
                        $thirdTotal = isset($thirdUserNameuser_listing_details_obj_merged->ListingsTotal) ? $thirdUserNameuser_listing_details_obj_merged->ListingsTotal : '-';
                        $thirdLastUpdate = isset($thirdUserNameuser_listing_details_obj_merged->LastUpdated) ? $thirdUserNameuser_listing_details_obj_merged->LastUpdated : '-';
                        $thirdStatus = isset($thirdUserNameuser_listing_details_obj_merged->status) ? $thirdUserNameuser_listing_details_obj_merged->status : '-';
                    }
                } else {
                    $thirdTotal = isset($thirdUserNameresultSet[0]->TotalRecords) ? $thirdUserNameresultSet[0]->TotalRecords : '-';
                    $thirdLastUpdate = '-';
                    $thirdStatus = '-';
                }
            }
        } else {
            $thirdTotal = '-';
            $thirdLastUpdate = '-';
            $thirdStatus = '-';
        }

        if ($fourthUserName != '') {
            $fourthUserDataSet = get_option('fourthUserNameresultSet');
            if (!empty($fourthUserDataSet) && isset($fourthUserDataSet)) {
                $fourthUserNameresultSet = $fourthUserDataSet;
            } else {
                $fourthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username($fourthUserName);
            }
            $fourthUserNameresultSet = json_decode($fourthUserNameresultSet);
            if (isset($fourthUserNameresultSet) && $fourthUserNameresultSet != null && $fourthUserNameresultSet != '') {
                if ((int) $fourthUserNameresultSet[0]->TotalRecords > 0) {
                    $fourthUserNameuser_listing_details_obj_merged = (object) array_merge((array) $fourthUserNameresultSet[0], (array) $fourthUserNameresultSet[1]);
                    if ($fourthUserNameuser_listing_details_obj_merged != '' && isset($fourthUserNameuser_listing_details_obj_merged) && $fourthUserNameuser_listing_details_obj_merged != null) {
                        $fourthTotal = isset($fourthUserNameuser_listing_details_obj_merged->ListingsTotal) ? $fourthUserNameuser_listing_details_obj_merged->ListingsTotal : '-';
                        $fourthLastUpdate = isset($fourthUserNameuser_listing_details_obj_merged->LastUpdated) ? $fourthUserNameuser_listing_details_obj_merged->LastUpdated : '-';
                        $fourthStatus = isset($fourthUserNameuser_listing_details_obj_merged->status) ? $fourthUserNameuser_listing_details_obj_merged->status : '-';
                    }
                } else {
                    $fourthTotal = isset($fourthUserNameresultSet[0]->TotalRecords) ? $fourthUserNameresultSet[0]->TotalRecords : '-';
                    $fourthLastUpdate = '-';
                    $fourthStatus = '-';
                }
            }
        } else {
            $fourthTotal = '-';
            $fourthLastUpdate = '-';
            $fourthStatus = '-';
        }
        if ($fifthUserName != '') {
            $fifthUserDataSet = get_option('fifthUserNameresultSet');
            if (!empty($fifthUserDataSet) && isset($fifthUserDataSet)) {
                $fifthUserNameresultSet = $fifthUserDataSet;
            } else {
                $fifthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username($fifthUserName);
            }
            $fifthUserNameresultSet = json_decode($fifthUserNameresultSet);
            if (isset($fifthUserNameresultSet) && $fifthUserNameresultSet != null && $fifthUserNameresultSet != '') {
                if ((int) $fifthUserNameresultSet[0]->TotalRecords > 0) {
                    $fifthUserNameuser_listing_details_obj_merged = (object) array_merge((array) $fifthUserNameresultSet[0], (array) $fifthUserNameresultSet[1]);
                    if ($fifthUserNameuser_listing_details_obj_merged != '' && isset($fifthUserNameuser_listing_details_obj_merged) && $fifthUserNameuser_listing_details_obj_merged != null) {
                        $fifthTotal = isset($fifthUserNameuser_listing_details_obj_merged->ListingsTotal) ? $fifthUserNameuser_listing_details_obj_merged->ListingsTotal : '-';
                        $fifthLastUpdate = isset($fifthUserNameuser_listing_details_obj_merged->LastUpdated) ? $fifthUserNameuser_listing_details_obj_merged->LastUpdated : '-';
                        $fifthStatus = isset($fifthUserNameuser_listing_details_obj_merged->status) ? $fifthUserNameuser_listing_details_obj_merged->status : '-';
                    }
                } else {
                    $fifthTotal = isset($fifthUserNameresultSet[0]->TotalRecords) ? $fifthUserNameresultSet[0]->TotalRecords : '-';
                    $fifthLastUpdate = '-';
                    $fithStatus = '-';
                }
            }
        } else {
            $fifthTotal = '-';
            $fifthLastUpdate = '-';
            $fithStatus = '-';
        } ?>
        <div class="crea-plugin-ddf-type-html">
            <table class="crea_table_setting" width="100%" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php echo __(ARETKCREA_SETTING_USER_ID, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_SETTING_USER_NAME, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_SETTING_DDF_TYPE, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_SETTING_DDF_LISTING, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_SETTING_LAST_UPDATED, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_SETTING_STATUS, ARETKCREA_PLUGIN_SLUG); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="username_text">
                            <input id="aretk_crea_user_name_one" type="text" name="crea_ddf1_user_name" value="<?php echo $firstUserName; ?>">
                        </td>
                        <td class="ddf_type_select">
                            <select id="aretk_crea_ddf_type_one" class="set_width" name="crea_ddf1_type">
                                <option value="" <?php echo ($firstUserDdf == '') ? 'selected' : ''; ?>>DDF<sup>&reg;</sup> Type</option>
                                <option value="My Listings" <?php echo ($firstUserDdf == 'My Listings') ? 'selected' : ''; ?>>My Listings</option>
                                <option value="Board Listings" <?php echo ($firstUserDdf == 'Board Listings') ? 'selected' : ''; ?>>Board Listings</option>
                                <option value="Office Listings" <?php echo ($firstUserDdf == 'Office Listings') ? 'selected' : ''; ?>>Office Listings</option>
                                <option value="National Pool" <?php echo ($firstUserDdf == 'National Pool') ? 'selected' : ''; ?>>National Pool</option>
                            </select>
                        </td>
                        <td><?php echo $firstTotal; ?></td>
                        <td><?php echo $firstLastUpdate; ?></td>
                        <td><?php echo $firstStatus; ?></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><input id="aretk_crea_user_name_two" type="text"  name="crea_ddf2_user_name" value="<?php echo $secondUserName; ?>"></td>
                        <td>
                            <select id="aretk_crea_ddf_type_two" class="set_width" name="crea_ddf2_type">
                                <option value="" <?php echo ($secondUserDdf == '') ? 'selected' : ''; ?>>DDF<sup>&reg;</sup> Type</option>
                                <option value="My Listings" <?php echo ($secondUserDdf == 'My Listings') ? 'selected' : ''; ?>>My Listings</option>
                                <option value="Board Listings" <?php echo ($secondUserDdf == 'Board Listings') ? 'selected' : ''; ?>>Board Listings</option>
                                <option value="Office Listings" <?php echo ($secondUserDdf == 'Office Listings') ? 'selected' : ''; ?>>Office Listings</option>
                                <option value="National Pool" <?php echo ($secondUserDdf == 'National Pool') ? 'selected' : ''; ?>>National Pool</option>
                            </select>
                        </td>
                        <td><?php echo $secondTotal; ?></td>
                        <td><?php echo $secondLastUpdate; ?></td>
                        <td><?php echo $secondStatus; ?></td>
                    </tr>                   
                    <tr>
                        <td>3</td>
                        <td><input id="aretk_crea_user_name_three" type="text" name="crea_ddf3_user_name" value="<?php echo $thirdUserName; ?>"></td>
                        <td>
                            <select id="aretk_crea_ddf_type_three" class="set_width" name="crea_ddf3_type">
                                <option value="" <?php echo ($thirdUserDdf == '') ? 'selected' : ''; ?>>DDF<sup>&reg;</sup> Type</option>
                                <option value="My Listings" <?php echo ($thirdUserDdf == 'My Listings') ? 'selected' : ''; ?>>My Listings</option>
                                <option value="Board Listings" <?php echo ($thirdUserDdf == 'Board Listings') ? 'selected' : ''; ?>>Board Listings</option>
                                <option value="Office Listings" <?php echo ($thirdUserDdf == 'Office Listings') ? 'selected' : ''; ?>>Office Listings</option>
                                <option value="National Pool" <?php echo ($thirdUserDdf == 'National Pool') ? 'selected' : ''; ?>>National Pool</option>
                            </select>
                        </td>
                        <td><?php echo $thirdTotal; ?></td>
                        <td><?php echo $thirdLastUpdate; ?></td>
                        <td><?php echo $thirdStatus; ?></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><input id="aretk_crea_user_name_four" type="text" name="crea_ddf4_user_name" value="<?php echo $fourthUserName; ?>"></td>
                        <td>
                            <select id="aretk_crea_ddf_type_four" class="set_width" name="crea_ddf4_type">
                                <option value="" <?php echo ($fourthUserDdf == '') ? 'selected' : ''; ?>>DDF<sup>&reg;</sup> Type</option>
                                <option value="My Listings" <?php echo ($fourthUserDdf == 'My Listings') ? 'selected' : ''; ?>>My Listings</option>
                                <option value="Board Listings" <?php echo ($fourthUserDdf == 'Board Listings') ? 'selected' : ''; ?>>Board Listings</option>
                                <option value="Office Listings" <?php echo ($fourthUserDdf == 'Office Listings') ? 'selected' : ''; ?>>Office Listings</option>
                                <option value="National Pool" <?php echo ($fourthUserDdf == 'National Pool') ? 'selected' : ''; ?>>National Pool</option>
                            </select>
                        </td>
                        <td><?php echo $fourthTotal; ?></td>
                        <td><?php echo $fourthLastUpdate; ?></td>
                        <td><?php echo $fourthStatus; ?></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><input id="aretk_crea_user_name_five" type="text" name="crea_ddf5_user_name" value="<?php echo $fifthUserName; ?>"></td>
                        <td>
                            <select id="aretk_crea_ddf_type_five" class="set_width" name="crea_ddf5_type">
                                <option value="" <?php echo ($fifthUserDdf == '') ? 'selected' : ''; ?>>DDF<sup>&reg;</sup> Type</option>
                                <option value="My Listings" <?php echo ($fifthUserDdf == 'My Listings') ? 'selected' : ''; ?>>My Listings</option>
                                <option value="Board Listings" <?php echo ($fifthUserDdf == 'Board Listings') ? 'selected' : ''; ?>>Board Listings</option>
                                <option value="Office Listings" <?php echo ($fifthUserDdf == 'Office Listings') ? 'selected' : ''; ?>>Office Listings</option>
                                <option value="National Pool" <?php echo ($fifthUserDdf == 'National Pool') ? 'selected' : ''; ?>>National Pool</option>
                            </select>
                        </td>
                        <td><?php echo $fifthTotal; ?></td>
                        <td><?php echo $fifthLastUpdate; ?></td>
                        <td><?php echo $fifthStatus; ?></td>
                    </tr>
                </tbody>
            </table>
            <input id="aretk_crea_ddf_update_btn" type="button" name="crea-settings-update" value="<?php echo __(ARETKCREA_SETTING_DDF_UPDATE, ARETKCREA_PLUGIN_SLUG); ?>" class="crea-btn-primary button button-primary">
        </div><?php 
    }  ?>
    <div class="accordion">
    <div class="accordion-section accordion-section-crea">
        <a class="accordion-section-title" href="#subscription-crea-listing"><?php echo __(ARETKCREA_SETTING_INFORMATION_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
        <div id="subscription-crea-listing" class="accordion-section-content">
            <div class="crea_setting_inforn">
                <p><?php echo __(ARETKCREA_SETTING_INFORMATION_TITLE, ARETKCREA_PLUGIN_SLUG); ?></p>
                <div class="crea_inform_contain">
                    <div class="set-crea-steps">                       
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_ONE, ARETKCREA_PLUGIN_SLUG); ?></h4> 
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo1.jpg'; ?>" width = "50px" height="50px"> <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo1.jpg'; ?>" alt="photo" class="crea-set-images"> </a>
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_ONE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>             
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_TWO, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo2.jpg'; ?>"> <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo2.jpg'; ?>" alt="photo" class="crea-set-images"></a>  
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_TWO_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>                        
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_THREE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo3.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo3.jpg'; ?>" alt="photo" class="crea-set-images"> <a>
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_THREE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>               
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-1.jpg'; ?>"> <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-1.jpg'; ?>" alt="photo" class="crea-set-images"> </a>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-2.jpg'; ?>">  <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-2.jpg'; ?>" alt="photo" class="crea-set-images"> </a>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_ONE, ARETKCREA_PLUGIN_SLUG); ?></p>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_TWO, ARETKCREA_PLUGIN_SLUG); ?></p>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_THREE, ARETKCREA_PLUGIN_SLUG); ?></p>
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FIVE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo5.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo5.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FIVE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>             
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SIX, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo6.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo6.jpg'; ?>" alt="photo" class="crea-set-images"> </a>
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SIX_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>                     
                    </div>
                    <div class="set-crea-steps">                        
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SEVEN, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo7.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo7.jpg'; ?>" alt="photo" class="crea-set-images">
                        </a>
                        <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SEVEN_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>                        
                    </div>
                    <div class="set-crea-steps">
                         <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_EIGHT, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8.jpg'; ?>">  <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8-2.jpg'; ?>"> <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8-2.jpg'; ?>" alt="photo" class="crea-set-images"> </a>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_EIGHT_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>                      
                    </div>
                    <div class="set-crea-steps">                         
                        <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_NINE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo9.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo9.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                        <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_NINE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                    </div>
                </div>
            </div>
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    </div><!--end .accordion-->
    <!-- start content for accordion !-->
    <div class="crea-agent-settings">
        <h3 class="crea-agent-title"><?php echo __(ARETKCREA_SETTING_AGENT_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h3>
        <div class="crea-agent-add-msg"><p style="display:none;" class="add-agent-sucessfully"><?php echo __(ARETKCREA_AGENT_ADD_SUCESSFULLY, ARETKCREA_PLUGIN_SLUG); ?></p><p style="display:none;" class="agent_id_exsits"><?php echo __(ARETKCREA_AGENT_ID_ALREADY_EXSITS, ARETKCREA_PLUGIN_SLUG); ?></p></div>
        <table class="crea_table_setting_agent" width="100%" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_AUTO_ID, ARETKCREA_PLUGIN_SLUG); ?></th>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_NAME, ARETKCREA_PLUGIN_SLUG); ?></th>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_ID, ARETKCREA_PLUGIN_SLUG); ?></th>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_EMAIL, ARETKCREA_PLUGIN_SLUG); ?></th>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_ADD_DATE, ARETKCREA_PLUGIN_SLUG); ?></th>
                    <th><?php echo __(ARETKCREA_SETTING_AGENT_ACTION, ARETKCREA_PLUGIN_SLUG); ?></th>
                </tr>
            </thead>
            <tbody class="set_table_records"><?php
                $sql_select = "SELECT `crea_id`, `crea_agent_name`, `crea_agent_id`, `crea_agent_email`, `crea_agent_modified_date` FROM `$crea_agent_table_name` ORDER BY `crea_id` ASC";  
                $sql_prep = $wpdb->prepare( $sql_select, NULL );
                $get_agents_results = $wpdb->get_results($sql_prep);
                if (!empty($get_agents_results) && $get_agents_results != '') 
                {
                    $counter = 0;
                    foreach ($get_agents_results as $get_agents_key => $get_agents_value) 
                    {
                        $counter = $counter + 1; ?>
                        <tr>
                            <td><?php echo $counter; ?></td> 
                            <td>
                                <p id="crea_update_agent_name_p_tag_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_id"><?php echo $get_agents_value->crea_agent_name; ?></p>
                                <input class="crea_update_name_class" style="display:none" type="text" value="<?php echo $get_agents_value->crea_agent_name; ?>" id="crea_setting_update_agent_name_<?php echo $get_agents_value->crea_id; ?>" name="crea_settings_agent_name<?php echo $get_agents_value->crea_id; ?>">
                                <p class="crea_not_null_agent_name" id="crea_agen_name_not_blank_<?php echo $get_agents_value->crea_id; ?>" style="display:none;"><?php echo __(ARETKCREA_AGENT_NAME_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </td>
                            <td>
                                <p id="crea_update_agent_p_tag_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_id"><?php echo $get_agents_value->crea_agent_id; ?></p>
                                <input class="crea_update_id_class" style="display:none" type="text" value="<?php echo $get_agents_value->crea_agent_id; ?>" id="crea_setting_update_agent_id_<?php echo $get_agents_value->crea_id; ?>" name="crea_settings_agent_ids<?php echo $get_agents_value->crea_id; ?>">
                                <p class="crea_not_null_agent_id" id="crea_agen_id_not_blank_<?php echo $get_agents_value->crea_id; ?>" style="display:none;"><?php echo __(ARETKCREA_AGENT_ID_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </td>
                            <td>
                                <p id="crea_update_agent_email_p_tag_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_email"><?php echo $get_agents_value->crea_agent_email; ?></p>
                                <input class="crea_update_email_class" style="display:none" type="text" value="<?php echo $get_agents_value->crea_agent_email; ?>" id="crea_setting_update_agent_email_<?php echo $get_agents_value->crea_id; ?>" name="crea_settings_agent_email<?php echo $get_agents_value->crea_id; ?>">
                                <p id="crea_agen_email_not_blank_<?php echo $get_agents_value->crea_id; ?>" class="crea_not_null_agent_email" style="display:none;"><?php echo __(ARETKCREA_AGENT_EMAIL_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
                                <p id="crea_agent_email_valid_<?php echo $get_agents_value->crea_id; ?>" class="crea_valid_agent_email" style="display:none;"><?php echo __(ARETKCREA_AGENT_EMAIL_NOT_VALID, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </td>
                            <td>
                                <p class="agent_modified_date" id="agent_modified_date_<?php echo $get_agents_value->crea_id; ?>"><?php echo $get_agents_value->crea_agent_modified_date; ?></p>
                            </td>
                            <td>
                                <a id="crea_agent_edit_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_action crea_agent_edit_action" href="javascript:void(0);">
                                    <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/edit-icon.png'; ?>" alt="edit" width="20" height="20">
                                </a>
                                <a id="crea_agent_delete_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_action crea_agent_delete_action" href="javascript:void(0);">
                                    <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20">
                                </a>
                                <input style="display:none;" type="button" id="crea_agent_setting_update_button_<?php echo $get_agents_value->crea_id; ?>" class="crea_agent_record_update button button-primary" value="<?php echo __(ARETKCREA_SETTING_POPUP_AGENT_DETAILS_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG); ?>">
                            </td>
                        </tr><?php
                    }
                } else { ?>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr><?php 
                }
                ?>
            </tbody>
        </table>
        <div class="crea-add-new-agent">
            <h3><?php echo __(ARETKCREA_SETTING_AGENT_ADD, ARETKCREA_PLUGIN_SLUG); ?></h3>
            <input id="aretk_crea_input_new_agent_name" type="text" class="crea_set_input" name="crea_agent_name" placeholder="<?php echo __(ARETKCREA_SETTING_AGENT_PLACEHOLD_NAME, ARETKCREA_PLUGIN_SLUG); ?>">
            <input id="aretk_crea_input_new_agent_id" type="text" class="crea_set_input" name="crea_agent_id" placeholder="<?php echo __(ARETKCREA_SETTING_AGENT_PLACEHOLD_ID, ARETKCREA_PLUGIN_SLUG); ?>">
            <input id="aretk_crea_input_new_agent_email" type="text" class="crea_set_input" name="crea_agent_email" placeholder="<?php echo __(ARETKCREA_SETTING_AGENT_PLACEHOLD_EMAIL, ARETKCREA_PLUGIN_SLUG); ?>">
            <input id="aretk_crea_add_new_agent" type="button" name="crea-add-agent" value="<?php echo __(ARETKCREA_SETTING_AGENT_ADD_BTN, ARETKCREA_PLUGIN_SLUG); ?>" class="crea-agent-btn-primary button button-primary">
            <div id="record_delete_confirmation" style="display:none;"><?php echo ARETKCREA_AGENT_ID_DELETE_CONFIRMATION; ?></div>
        </div>
        <div class="crea-err-msg" id="aretk-crea-valid-msg" style="display:none">
            <div class="aretk-crea-agent-name-msg">
                <p class="aretk-agent-name-not-empty set-err-msg" style="display:none"><?php echo __(ARETKCREA_AGENT_NAME_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
            </div>
            <div class="aretk-crea-agent-id-msg">
                <p class="aretk-agent-id-not-empty set-err-msg" style="display:none"><?php echo __(ARETKCREA_AGENT_ID_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
                <p class="aretk-agent-id-exsits set-err-msg" style="display:none"><?php echo __(ARETKCREA_AGENT_ID_ALREADY_EXSITS, ARETKCREA_PLUGIN_SLUG); ?></p>
            </div>
            <div class="aretk-crea-agent-email-msg"> 
                <p class="aretk-agent-email-not-empty set-err-msg" style="display:none"><?php echo __(ARETKCREA_AGENT_EMAIL_NOT_NULL, ARETKCREA_PLUGIN_SLUG); ?></p>
                <p class="aretk-agent-email-valid set-err-msg" style="display:none"><?php echo __(ARETKCREA_AGENT_EMAIL_NOT_VALID, ARETKCREA_PLUGIN_SLUG); ?></p>
            </div>
        </div>
    </div>
        <div class="accordion">
        <div class="accordion-section">
            <a class="accordion-section-title crea-section-How-to-find-crea-id" href="#How_to_find_out_your_CREA_ID"><?php echo __(ARETKCREA_SETTING_HOW_TO_FIND_CREA_ID, ARETKCREA_PLUGIN_SLUG); ?></a>
            <div id="How_to_find_out_your_CREA_ID" class="accordion-section-content">
                <div class="crea_setting_inforn">
                    <p><?php echo __(ARETKCREA_SETTING_HOW_TO_FIND_CREA_ID, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <div class="crea_inform_contain">
                        <div class="set-crea-steps">                        
                            <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo10.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo10.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                            <p><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>       
                        </div>
                        <div class="set-crea-steps">                        
                            <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo11.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo11.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                            <p><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>       
                        </div>
                        <div class="set-crea-steps">                        
                            <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo12.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo12.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                            <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>                   
                        </div>
                    </div>               
                </div>
            </div>
        </div>
        </div>
        <div class="crea-disclaimer-settings"><?php 
            $disclaimer1_array = get_option('aretk_crea_disclaimer1', '');
            if ( empty($disclaimer1_array) ){
                $pluralize = 'I am';
                $salestype = 'an agent';
                $licensetype = 'residential and commercial';
                $province = '';
            } else {
                $pluralize = $disclaimer1_array['pluralize'];
                $salestype = $disclaimer1_array['salestype'];
                $licensetype = $disclaimer1_array['licensetype'];
                $province = $disclaimer1_array['province'];
            }
            ?>
            <h3 class="crea-disclaimer-title">DISCLAIMERS</h3>
            <p>The following disclaimer is required to be displayed either in your footer and/or on the property detail pages. This plugin will automatically place this disclaimer in the bottom of the property detail pages. If you would like to have this disclaimer in your theme footer, you will need to manually add it.</p>               
            <p>Use the inline dropdown boxes to modify the disclaimer to best fit your needs.</p>
            <div id="required-disclaminer1">
                <p>
                    <select name="disclaimer_pluralize" id="disclaimer_pluralize">
                      <option value="I am"<?php if($pluralize === 'I am' || empty($pluralize)){ echo ' selected="selected"'; } ?>>I am</option>
                      <option value="We are"<?php if($pluralize === 'We are'){ echo ' selected="selected"'; } ?>>We are</option>
                    </select> <select name="disclaimer_salestype" id="disclaimer_salestype">
                      <option value="an agent"<?php if($salestype === 'an agent' || empty($salestype)){ echo ' selected="selected"'; } ?>>an agent</option>
                      <option value="agents"<?php if($salestype === 'agents'){ echo ' selected="selected"'; } ?>>agents</option>
                      <option value="a broker"<?php if($salestype === 'a broker'){ echo ' selected="selected"'; } ?>>a broker</option>
                      <option value="brokers"<?php if($salestype === 'brokers'){ echo ' selected="selected"'; } ?>>brokers</option>
                      <option value="a brokerage"<?php if($salestype === 'a brokerage'){ echo ' selected="selected"'; } ?>>a brokerage</option>
                    </select>
                    licensed to trade in <select name="disclaimer_licensetype" id="disclaimer_licensetype">
                      <option value="residential and commercial"<?php if($licensetype === 'residential and commercial' || empty($licensetype)){ echo ' selected="selected"'; } ?>>residential and commercial</option>
                      <option value="residential"<?php if($licensetype === 'residential'){ echo ' selected="selected"'; } ?>>residential</option>
                      <option value="commercial"<?php if($licensetype === 'commercial'){ echo ' selected="selected"'; } ?>>commercial</option>
                    </select> real estate in <select name="disclaimer_province" id="disclaimer_province">
                      <option value=''<?php if(empty($province)){ echo ' selected="selected"'; } ?>></option>
                      <option value="Alberta"<?php if($province === 'Alberta'){ echo ' selected="selected"'; } ?>>Alberta</option>
                      <option value="British Columbia"<?php if($province === 'British Columbia'){ echo ' selected="selected"'; } ?>>British Columbia</option>
                      <option value="Manitoba"<?php if($province === 'Manitoba'){ echo ' selected="selected"'; } ?>>Manitoba</option>
                      <option value="New Brunswick"<?php if($province === 'New Brunswick'){ echo ' selected="selected"'; } ?>>New Brunswick</option>
                      <option value="Newfoundland and Labrador"<?php if($province === 'Newfoundland and Labrador'){ echo ' selected="selected"'; } ?>>Newfoundland and Labrador</option>
                      <option value="Northwest Territories"<?php if($province === 'Northwest Territories'){ echo ' selected="selected"'; } ?>>Northwest Territories</option>
                      <option value="Nova Scotia"<?php if($province === 'Nova Scoti'){ echo ' selected="selected"'; } ?>>Nova Scotia</option>
                      <option value="Nunavut"<?php if($province === 'Nunavut'){ echo ' selected="selected"'; } ?>>Nunavut</option>
                      <option value="Ontario"<?php if($province === 'Ontario'){ echo ' selected="selected"'; } ?>>Ontario</option>
                      <option value="Prince Edward Island"<?php if($province === 'Prince Edward Island'){ echo ' selected="selected"'; } ?>>Prince Edward Island</option>
                      <option value="Quebec"<?php if($province === 'Quebec'){ echo ' selected="selected"'; } ?>>Quebec</option>
                      <option value="Saskatchewan"<?php if($province === 'Saskatchewan'){ echo ' selected="selected"'; } ?>>Saskatchewan</option>
                      <option value="Yukon"<?php if($province === 'Yukon'){ echo ' selected="selected"'; } ?>>Yukon</option>
                    </select>. The out of province listing content on this website is not intended to solicit a trade in real estate.  Any consumers interested in out of province listings must contact a person who is licensed to trade in real estate in that province.
                </p>
            </div>              
            <input type="button" name="crea-disclaimer-update" id="crea-disclaimer-update" class="crea-btn-primary button button-primary" value="<?php echo __(ARETKCREA_SETTING_DDF_UPDATE_DISCLAIMER, ARETKCREA_PLUGIN_SLUG); ?>" />
            <span class="suceess_msg" style="color: green;">Disclaimer Updated</span>
        </div>
    </div>
    <?php
}

/**
 * create function for aretkcrea_custom_listings_settings_maplisting_html
 * 
 * @return return html for the CREA listings Settings.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_listings_settings_maplisting_html()
{
    global $wpdb;
    $getSubscriptionStatus = get_option('crea_subscription_status', '');
    $subscriptionKey = get_option('crea_subscription_key', ''); 
    $allListingArr = array();
    $filter_array = array();
    $crea_user_name_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
    $propertyListId = NULL;
    if ( isset($_GET['id']) && is_numeric($_GET['id']) ){
        $propertyListId = (int) $_GET['id'];
    }
    if( $getSubscriptionStatus === 'valid' && !empty($propertyListId) ){
        $sql_select = "SELECT `username` FROM `$crea_user_name_table_name`";    
        $sql_prep = $wpdb->prepare( $sql_select, NULL );
        $getAllUsername = $wpdb->get_results($sql_prep);            
        $userNameList = '';
        if (isset($getAllUsername) && !empty($getAllUsername)) {
            foreach ($getAllUsername as $singleUsername) {
                $userName = $singleUsername->username;
                if ( !empty( $userName ) ){ 
                    $userNameList.=$userName.',';
                }                               
                unset($singleUsername);
            }                           
            unset($getAllUsername);                         
            $userNameList = rtrim($userNameList,',');           
            $filter_array['property_ids'] = $propertyListId;                    
            $filter_array['result_type'] = 'full';
            $filter_array['crea_feed_id'] = $userNameList;
            $listings = Aretk_Crea_Public::aretk_get_listings_subsc($subscriptionKey, $filter_array);
            
            if ( isset($listings) && !empty($listings)) {
                $allListingArr = (array)$listings['listing_data'][$propertyListId];
            }           
            $prop_lat = NULL;
            $prop_lon = NULL;
            $prop_heading = '270';
            $prop_pitch = '0';
            $prop_zoom = '1';
            
            if (!empty($allListingArr['geocoded_latitude'])){
                $prop_lat = $allListingArr['geocoded_latitude'];
            } 
            if (!empty($allListingArr['geocoded_longitude'])){
                $prop_lon = $allListingArr['geocoded_longitude'];
            } 
            if ( !empty($prop_lat) && !empty($prop_lon) ){ 
                $prop_latlng = '('. $prop_lat .', '. $prop_lon .')';
            } else {
                $prop_latlng =  NULL;
            }
            if (is_numeric($allListingArr['geocoded_pov_heading'])){
                $prop_heading = $allListingArr['geocoded_pov_heading'];
            } 
            if (is_numeric($allListingArr['geocoded_pov_pitch'])){
                $prop_pitch = $allListingArr['geocoded_pov_pitch'];
            } 
            if (is_numeric($allListingArr['geocoded_pov_zoom'])){
                $prop_zoom = $allListingArr['geocoded_pov_zoom'];
            } 
        }
    } ?>
    <div class="crea-container crea-maplisting-settings">
        <div class="crea-plugin-title remove-border">
            <h2><?php echo __("Map Listing", ARETKCREA_PLUGIN_SLUG); ?></h2>
        </div><?php          
        $google_map_api_name = get_option('google-map-api-name');
        if (empty($google_map_api_name)){ $google_map_api_name = NULL; }
        if( $getSubscriptionStatus === 'valid' && is_numeric($propertyListId) && is_numeric($allListingArr['ID']) && !empty($google_map_api_name) ){ ?>         
            <img id="property_img" src="<?php echo $allListingArr['listing_photos'][0]->URL; ?>" alt="<?php echo $allListingArr['generated_address']; ?>"/>             
            <h3 id="property_loc"><?php echo $allListingArr['generated_address']; ?></h3>   
            <div id="property_param">
                <input id="property_id" type="hidden" value="<?php echo $propertyListId; ?>" />
                <input id="property_address" type="hidden" value="<?php echo $allListingArr['generated_address']; ?>" />
                <input id="property_latlng" type="hidden" value="<?php echo $prop_latlng; ?>" />
                <input id="property_latitude" type="hidden" value="<?php echo $prop_lat; ?>" />
                <input id="property_longitude" type="hidden" value="<?php echo $prop_lon; ?>" />
                <input id="property_pov_heading" type="hidden" value="<?php echo $prop_heading; ?>" />
                <input id="property_pov_pitch" type="hidden" value="<?php echo $prop_pitch; ?>" />
                <input id="property_pov_zoom" type="hidden" value="<?php echo $prop_zoom; ?>" />
            </div>
            <div id="map"><span class="map_status">Loading..</span></div>
            <div id="map_pano"><span class="map_status">Loading..</span></div>
            <a class="crea-save-map-location button button-primary" href="#"><?php echo __('Save Changes', ARETKCREA_PLUGIN_SLUG); ?></a><img class="ajax_loading" src="/wp-admin/images/loading.gif" alt="" /><img class="ajax_pass" src="/wp-admin/images/yes.png" alt="" /><img class="ajax_fail" src="/wp-admin/images/no.png" alt="" />
            <?php           
        } else {
            ?>
            <p><?php echo __("There was an error processing this listing", ARETKCREA_PLUGIN_SLUG); ?></p>
            <?php
        }
        ?>
    </div>
    <?php
}

/**
 * create function for aretkcrea_custom_listings_settings_html
 * 
 * @return return html for the CREA listings Settings.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_listings_settings_html() {
    global $wpdb;
    $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;  ?>
    <div class="crea-container crea-listing-settings">
        <div class="crea_plugin_listing_top_content">
            <p> Below will list all your exclusive listings that have been manually entered. To add listings, click the 'Add New Listing' button.</p>
            <p>If the CREA ADD-ON is active, in addition to any exclusive listings, the listings below will include any listings from the CREA data feeds that are associated with the CREA AGENT IDs entered under Step 2 in the CREA DDF<sup>&reg;</sup> SETTINGS page.</p>
        </div>  
        <div class="crea-plugin-title remove-border"><h2><?php echo __("LISTINGS", ARETKCREA_PLUGIN_SLUG); ?></h2></div>
        <div class="crea_add_new_list"><a class="crea-add-new-listing-btn button button-primary" href="<?php echo admin_url('admin.php?page=create_new_listings'); ?>"><?php echo __(ARETKCREA_ADD_LISTING_BTN, ARETKCREA_PLUGIN_SLUG); ?></a></div>
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>"><?php           
        $getSubscriptionStatus = get_option('crea_subscription_status', '');
        if( isset( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ){ ?>
            <div class="crea_listiing_serch_box_html"><?php
                $sql_select = "SELECT COUNT(crea_id) AS `count` FROM `$crea_agent_table_name`"; 
                $sql_prep = $wpdb->prepare( $sql_select, NULL );
                $get_total_recods = $wpdb->get_results($sql_prep);
                $record_count = $get_total_recods[0]->count;
                if ($record_count >= 1) { ?>
                    <select id="filter_by_agent_name" name="crea_listing_agent_name">
                        <option value="">Filter by Agent Name</option><?php
                        $sql_select = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$crea_agent_table_name`";    
                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                        $get_total_recods = $wpdb->get_results($sql_prep);
                        if (!empty($get_total_recods) && $get_total_recods != '') {
                            foreach ($get_total_recods as $get_total_value) {
                                ?>
                                <option value="<?php echo $get_total_value->crea_agent_id; ?>"><?php echo $get_total_value->crea_agent_name; ?></option><?php       
                            }
                        }
                        ?>
                    </select><?php 
                } ?>      
                <input id="filter_by_mlsid" type="text" name="crea_listting_mla_record_search" placeholder="Search Listings by MLS#" class="" id="">
                <input id="listing_admin_search" type="button" class="button button-primary" value="Search">
            </div><?php 
        } ?>
        <div class="crea_plugin_listing_content">
            <div id="crea_loder_display" class="crea_loading_screen" style="display:none;"><div id="loadingScreen"></div></div>
            <span id="hidden_message_of_delete_listing" style="display:none;"><?php echo __(ARETKCREA_LISTING_TABLE_TRASH_MESSAGE, ARETKCREA_PLUGIN_SLUG); ?></span>
            <table class="display" id="crea_setting_listting_content" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th width="20%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG); ?></th><?php
                        if( isset( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){  ?>
                            <th width="20%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG); ?></th><?php 
                        } ?>                        
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th width="10%"><?php echo __(ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG); ?></th>
                    </tr>
                </thead>
                <tbody><?php
                    if (isset($getAllListingData) && !empty($getAllListingData) && isset( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {                        
                        $allListingFinalArr = json_decode($getAllListingData);                      
                    } else if( isset( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){                    
                        //get all feeds from crea settings
                        $crea_user_name_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
                        $sql_select = "SELECT `username` FROM `$crea_user_name_table_name`";    
                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                        $getAllUsername = $wpdb->get_results($sql_prep);
                        $allListingArr = array();                       
                        //get all agent ids from database
                        $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
                        $sql_select = "SELECT `crea_agent_id` FROM `$crea_agent_table_name`";   
                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                        $getAllAgentIds = $wpdb->get_results($sql_prep);            
                        $getAllAgentIdArray = array();
                        if ( isset($getAllAgentIds) && !empty($getAllAgentIds) ){ 
                            foreach ($getAllAgentIds as $agent_key => $agent ) {
                                $getAllAgentIdArray[] = $agent->crea_agent_id;
                            }
                        }                       
                        if ( isset($getAllAgentIdArray) && !empty($getAllAgentIdArray)) { 
                            $agent_ids = implode(',',$getAllAgentIdArray);
                        } else { 
                            $agent_ids = null;
                        }                       
                        $allListingArr = array();
                        $userNameList = '';
                        if (isset($getAllUsername) && !empty($getAllUsername)) {
                            foreach ($getAllUsername as $singleUsername) {
                                $userName = $singleUsername->username;
                                if ( !empty( $userName ) ){ 
                                    $userNameList.=$userName.',';
                                }                               
                                unset($singleUsername);
                            }                           
                            unset($getAllUsername);                         
                            $userNameList = rtrim($userNameList,',');                       
                            $result_type = 'basic';
                            if ( $agent_ids != null ){ 
                                $listings = Aretk_Crea_Admin::aretkcrea_get_listing_records_based_on_agents($userNameList, $result_type,$agent_ids);
                                if ( isset($listings) && !empty($listings)) {
                                    foreach ($listings as $listing_key => $listing ){ 
                                        if ( !isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) { 
                                            $allListingArr[$listing->mlsID] = $listing;
                                        }
                                    }
                                }
                            }
                        }
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type' => 'aretk_listing',
                            'post_status' => 'publish'
                        );
                        $posts_array = (array) get_posts($args);
                        $exclusiveArr = array();
                        foreach ($posts_array as $singlePost) {
                            $singlePost1 = (array) $singlePost;
                            $singlePost2 = (object) $singlePost1;
                            $exclusiveArr[] = $singlePost2;
                        }                      
                        $allListingFinalArr = array_merge($allListingArr, $exclusiveArr);
                    } else { 
                        //For Exclusive listing
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type' => 'aretk_listing',
                            'post_status' => 'publish'
                        );
                        $posts_array = (array) get_posts($args);
                        $exclusiveArr = array();
                        foreach ($posts_array as $singlePost) {
                            $singlePost1 = (array) $singlePost;
                            $singlePost2 = (object) $singlePost1;
                            $exclusiveArr[] = $singlePost2;
                        }                      
                        $allListingFinalArr = $exclusiveArr;
                    }
                    $listings_counter = 1;
                    foreach ($allListingFinalArr as $singleListing) 
                    {
                        if (isset($singleListing->post_author) && !empty($singleListing->post_author)) 
                        {
                            $ListingAddress = get_post_meta($singleListing->ID, 'listingAddress', true);
                            $ListingCity = get_post_meta($singleListing->ID, 'listingcity', true);
                            $ListingMls = get_post_meta($singleListing->ID, 'listingMls', true);
                            $ListingPrice = get_post_meta($singleListing->ID, 'listingPrice', true);
                            $date = date('d-m-Y', strtotime($singleListing->post_date));
                            $crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
                            $sql_select = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `image_position`=1 AND `unique_id`=%d";   
                            $sql_prep = $wpdb->prepare( $sql_select, $singleListing->ID );
                            $resultSet = $wpdb->get_results($sql_prep);
                            if (isset($resultSet) && !empty($resultSet)) {
                                $path = $resultSet[0]->image_url;
                            } else {
                                $path = ARETK_CREA_PLUGIN_URL.'admin/images/dummy_image.png';
                            }                          
                            $listingpagecount = 0;
                            $getSubscriptionStatus = get_option('crea_subscription_status', '');
                            $listingpageval = get_post_meta($singleListing->ID,'crea_aretk_db_listing_page_count',true);         
                            if( !empty( $listingpageval ) && $listingpageval !='' ) {
                                $listingpagecount = $listingpageval;
                            } else {
                                $listingpagecount = 0;
                            }                           
                            $agentArrDecoded = get_post_meta($singleListing->ID,'listingAgentId',true);                         
                            $agentArr = json_decode($agentArrDecoded);
                            $htmlAgent = '';
                            $mls_number = isset($ListingMls) && !empty($ListingMls) ? $ListingMls : 'Exclusive';
                            if (isset($agentArr) && !empty($agentArr)) 
                            {
                                $htmlAgent = '';
                                foreach ($agentArr as $singleAgent) 
                                {
                                    $crea_agent_table_name = $wpdb->prefix.ARETKCREA_AGENT_TABLE;
                                    $sql_select = "SELECT `crea_agent_name` FROM `$crea_agent_table_name` WHERE `crea_agent_id`= %s";   
                                    $sql_prep = $wpdb->prepare( $sql_select, $singleAgent );
                                    $resultAgentArr = $wpdb->get_results($sql_prep);
                                    if ( isset($resultAgentArr) && !empty($resultAgentArr) ){ 
                                        $htmlAgent .= $resultAgentArr[0]->crea_agent_name . ', ';
                                    }
                                }
                            } ?>
                            <tr>
                                <td><img alt="<?php echo $listings_counter; ?>" style="height:100px;width:100px;" src="<?php echo $path; ?>"></td>
                                <td><?php echo $mls_number; ?><br /><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=create_new_listings&id=<?php echo $singleListing->ID; ?>">Edit</a> | <a id="<?php echo $singleListing->ID; ?>" class="trash_listing" href="javascript:void(0);">Trash</a></td>
                                <td><?php echo $ListingAddress; ?></td>
                                <td><?php echo $ListingCity; ?></td>
                                <td>$<?php echo $ListingPrice; ?></td>
                                <?php    // start subscription status condition
                                $getSubscriptionListingagents = get_option('crea_subscription_status', true);
                                if( isset( $getSubscriptionListingagents ) && !empty( $getSubscriptionListingagents ) && $getSubscriptionListingagents === 'valid'  ){  ?>
                                    <td><?php echo rtrim($htmlAgent, ', '); ?></td><?php  
                                } ?>   
                                <td><?php echo $listingpagecount;?></td>
                                <td><?php echo $date; ?></td> 
                            </tr><?php
                        } else {
                            $htmlAgent = '';
                            foreach ($singleListing->listing_agents as $singleAgent) {
                                $htmlAgent .= $singleAgent->Name . ', ';
                            }                           
                            if (is_object ($singleListing->listing_photos) ){
                                 if ($singleListing->listing_photos->URL == '' || $singleListing->listing_photos->URL == null) {
                                    $apiListingImageURL = ARETK_CREA_PLUGIN_URL.'admin/images/dummy_image.png';
                                } else  {
                                    $apiListingImageURL = $singleListing->listing_photos->URL;
                                }
                            } else if (is_object ($singleListing->listing_photos[0])) {
                                if ($singleListing->listing_photos[0]->URL == '' || $singleListing->listing_photos[0]->URL == null) {
                                    $apiListingImageURL = ARETK_CREA_PLUGIN_URL.'admin/images/dummy_image.png';
                                } else  {
                                    $apiListingImageURL = $singleListing->listing_photos[0]->URL;
                                }
                            } else {
                                $apiListingImageURL = ARETK_CREA_PLUGIN_URL.'admin/images/dummy_image.png';
                            }                            
                            $mlsId = isset($singleListing->mlsID) ? $singleListing->mlsID : '-';
                            $dates = isset($singleListing->ListingContractDate) ? $singleListing->ListingContractDate : '-';
                            $listingpagecount = isset( $singleListing->ViewCount ) ? $singleListing->ViewCount : 0; ?>
                            <tr>
                                <td><img alt="<?php echo $listings_counter; ?>" style="height:100px;width:100px;" src="<?php echo $apiListingImageURL; ?>"></td>
                                <td>
                                    <?php echo $mlsId; ?><br />
                                    <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listings_settings&id=<?php echo $singleListing->ID; ?>"><?php echo __("Map It", ARETKCREA_PLUGIN_SLUG); ?></a>
                                </td>
                                <td><?php echo $singleListing->StreetAddress ; ?></td>
                                <td><?php echo $singleListing->City; ?></td>
                                <td>$<?php echo $singleListing->Price; ?></td>
                                 <?php    // start subscription status condition
                                $getSubscriptionListingagents = get_option('crea_subscription_status', true);
                                if( isset( $getSubscriptionListingagents ) && !empty( $getSubscriptionListingagents ) &&        $getSubscriptionListingagents === 'valid'  ){  ?>
                                    <td><?php echo rtrim($htmlAgent, ', '); ?></td><?php 
                                } ?> 
                                <td><?php echo $listingpagecount; ?></td>
                                <td><?php echo $dates; ?></td>
                            </tr><?php
                        }
                        $listings_counter++;
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG); ?></th>
                       <?php    // start subscription status condition
                        $getSubscriptionListingagents = get_option('crea_subscription_status', true);
                        if( isset( $getSubscriptionListingagents ) && !empty( $getSubscriptionListingagents ) && $getSubscriptionListingagents === 'valid'  ){  ?> 
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG); ?></th>
                       <?php } //End subscription status condition  ?> 
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG); ?></th>
                        <th><?php echo __(ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div><?php
}

/**
 * @return return html for the CREA create new listings Settings
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_create_listings_settings_html() 
{
    global $wpdb;
    $upload_dir = wp_upload_dir();
    
    $crea_listing_ID = '';
    if ( isset($_GET['id']) && is_numeric($_GET['id']) ){
        $crea_listing_ID = (int) $_GET['id'];
    }
    
    $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE; ?>
    <form id="create_listing_form" action="<?php echo get_admin_url(); ?>admin-post.php" method='post' enctype="multipart/form-data" novalidate="novalidate">
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <input type='hidden' name='action' value='submit-form' />
        <input type='hidden' name='posttype' value='list' />
        <?php if (!empty($crea_listing_ID) && $crea_listing_ID != '') { ?>
            <input type='hidden' name='action-which' value='edit' />
            <input id="edit_page_upload_image_ajax_get_id" type="hidden" name="aretk-listing-id" value="<?php echo $crea_listing_ID; ?>"/>
        <?php } else { ?>
            <input type='hidden' name='action-which' value='add' />
            <input id="edit_page_upload_image_ajax_get_id" type="hidden" name="aretk-listing-id" value=""/>
        <?php } 
        
        /**
         * get aretk_listing postmeta value by post id
         * 
         */
        $post_name = 'aretk_listing';
        $crea_post_title = get_the_title($crea_listing_ID);
        $content_post = get_post($crea_listing_ID);
        if (!empty($content_post) && $content_post != '') {
            $crea_post_content = $content_post->post_content;
        }
        $crea_unique_id = get_post_meta($crea_listing_ID, 'uniqueId', true);
        $crea_listingAgentId = get_post_meta($crea_listing_ID, 'listingAgentId', true);
        $crea_listingAddress = get_post_meta($crea_listing_ID, 'listingAddress', true);
        $crea_listingcity = get_post_meta($crea_listing_ID, 'listingcity', true);
        $crea_listingProvince = get_post_meta($crea_listing_ID, 'listingProvince', true);
        $crea_listingMls = get_post_meta($crea_listing_ID, 'listingMls', true);
        $crea_listingAgentStatus = get_post_meta($crea_listing_ID, 'listingAgentStatus', true);
        $crea_listingPrice = get_post_meta($crea_listing_ID, 'listingPrice', true);
        $crea_listingPropertyType = get_post_meta($crea_listing_ID, 'listingPropertyType', true);
        $crea_listingStructureType = get_post_meta($crea_listing_ID, 'listingStructureType', true);
        $crea_listingBedRooms = get_post_meta($crea_listing_ID, 'listingBedRooms', true);
        $crea_listingBathrooms = get_post_meta($crea_listing_ID, 'listingBathrooms', true);
        $crea_listingBathroomsPartial = get_post_meta($crea_listing_ID, 'listingBathroomsPartial', true);
        $crea_listingFinishedBasement = get_post_meta($crea_listing_ID, 'listingFinishedBasement', true);
        $crea_listingParkingSlot = get_post_meta($crea_listing_ID, 'listingParkingSlot', true);
        $crea_listingParkinggarage = get_post_meta($crea_listing_ID, 'listingParkinggarage', true);
        $crea_listingTourUrl = get_post_meta($crea_listing_ID, 'listingTourUrl', true);
        $crea_listingUtilityArr = get_post_meta($crea_listing_ID, 'listingUtilityArr', true);
        $crea_listingFeatureArr = get_post_meta($crea_listing_ID, 'listingFeatureArr', true);
        $crea_listingopenhosedatetimeArr = get_post_meta($crea_listing_ID, 'listingopenhosedatetimeArr', true);
        $crea_listing_type = get_post_meta($crea_listing_ID, 'listing_type', true);
        $crea_listingAgentId_decode = json_decode($crea_listingAgentId);        
        ?>      
        <div class="crea-container crea-create-listing-settings">         
            <div class="crea-plugin-title remove-border"><h2><?php if (!empty($crea_listing_ID) && $crea_listing_ID != '') { echo __(ARETKCREA_EDIT_LISTING_TITLE, ARETKCREA_PLUGIN_SLUG); } else { echo __(ARETKCREA_ADD_NEW_LISTING_TITLE, ARETKCREA_PLUGIN_SLUG); } ?></h2></div>            
            <div class="crea-new-listing-form">
                <div class="main">
                    <div class="accordion">
                        <!-- accordiaon section for general tab-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-general-tab"><?php echo __(ARETKCREA_GENERAL_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-general-tab" class="accordion-section-content">
                                
                                <?php // start subscription status condition  
                                $getSubscriptionListing = get_option('crea_subscription_status', true);
                                if( isset( $getSubscriptionListing ) && !empty( $getSubscriptionListing ) && $getSubscriptionListing === 'valid'  )
                                { ?>
                                    <div class="listing-tab-detail-row">
                                        <div class="set-agent-title add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_AGENT_NAME, ARETKCREA_PLUGIN_SLUG); ?></label></div>                                    
                                        <div id="crea_select_option_reoder" class="crea_option_agent_value"><?php 
                                            if (!empty($crea_listing_ID) && $crea_listing_ID != '') { 
                                                if (!empty($crea_listingAgentId_decode) && $crea_listingAgentId_decode != '') {     
                                                    foreach ($crea_listingAgentId_decode as $crea_listingAgentId_key => $crea_listingAgentId_value) 
                                                    {                                               
                                                        $key_ids = '';
                                                        if ($crea_listingAgentId_key == 0) { $key_ids = ''; } else { $key_ids = $crea_listingAgentId_key; } ?>
                                                        <div id="crea_multiple_agent_add<?php echo $key_ids; ?>" class="multiple_agent_add_default">
                                                            <a class="crea_general_agnet_ids_sorting" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/sorting_icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                            <select class="crea_check_agent_option_value required" id="listing_view_agent_id<?php echo $key_ids; ?>" name="listing_view_agent_id[]">
                                                                <option value="">Select Agent Name</option><?php
                                                                $sql_select = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$crea_agent_table_name`";    
                                                                $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                                                $get_agent_ids = $wpdb->get_results($sql_prep);
                                                                if (!empty($get_agent_ids) && $get_agent_ids != '') {
                                                                    foreach ($get_agent_ids as $get_agent_ids_val) { ?>
                                                                    <option value="<?php echo $get_agent_ids_val->crea_agent_id; ?>" <?php if ($crea_listingAgentId_value == $get_agent_ids_val->crea_agent_id) { echo 'selected'; } ?> ><?php echo $get_agent_ids_val->crea_agent_name; ?></option>
                                                                    <?php }
                                                                }
                                                                ?>
                                                            </select>
                                                            <a id="crea_general_agnet_ids_delete<?php echo $key_ids; ?>" class="crea_general_agnet_ids_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                        </div><?php 
                                                    }
                                                } else { ?>
                                                    <div id="crea_multiple_agent_add" class="multiple_agent_add_default">
                                                        <a  class="crea_general_agnet_ids_sorting" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/sorting_icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                        <select class="crea_check_agent_option_value required" id="listing_view_agent_id" name="listing_view_agent_id[]">
                                                            <option value="">Select Agent Name</option><?php
                                                            $sql_select = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$crea_agent_table_name`";    
                                                            $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                                            $get_agent_ids = $wpdb->get_results($sql_prep);
                                                            if (!empty($get_agent_ids) && $get_agent_ids != '') {
                                                                foreach ($get_agent_ids as $get_agent_ids_val) { ?>
                                                                    <option value="<?php echo $get_agent_ids_val->crea_agent_id; ?>" <?php if ($crea_listingAgentId == $get_agent_ids_val->crea_agent_id) { ?> selected <?php } ?>><?php echo $get_agent_ids_val->crea_agent_name; ?></option><?php 
                                                                }
                                                            } ?>
                                                        </select>
                                                        <a  id="crea_general_agnet_ids_delete" class="crea_general_agnet_ids_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                    </div><?php 
                                                }                                           
                                            } else { ?>                                      
                                                <div id="crea_multiple_agent_add" class="multiple_agent_add_default">
                                                    <a  class="crea_general_agnet_ids_sorting" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/sorting_icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                    <select class="crea_check_agent_option_value required" id="listing_view_agent_id" name="listing_view_agent_id[]">
                                                        <option value="">Select Agent Name</option><?php
                                                        $sql_select = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$crea_agent_table_name`";    
                                                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                                        $get_agent_ids = $wpdb->get_results($sql_prep);
                                                        if (!empty($get_agent_ids) && $get_agent_ids != '') {
                                                            foreach ($get_agent_ids as $get_agent_ids_val) { ?>
                                                                <option value="<?php echo $get_agent_ids_val->crea_agent_id; ?>" <?php if ($crea_listingAgentId == $get_agent_ids_val->crea_agent_id) { ?> selected <?php } ?>><?php echo $get_agent_ids_val->crea_agent_name; ?></option><?php 
                                                            } 
                                                        } ?>
                                                    </select>
                                                    <a  id="crea_general_agnet_ids_delete" class="crea_general_agnet_ids_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a>
                                                </div><?php 
                                            } ?>                                       
                                        </div>
                                        <div style="display:none;">
                                            <select class="crea_check_agent_option_value required" id="get_select_option_values" name="listing_view_agent_id[]">
                                                <option value="">Select Agent Name</option><?php 
                                                $sql_select = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$crea_agent_table_name`";
                                                $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                                $get_agent_ids = $wpdb->get_results($sql_prep);
                                                if (!empty($get_agent_ids) && $get_agent_ids != '')
                                                {
                                                    foreach ($get_agent_ids as $get_agent_ids_val) 
                                                    { ?>
                                                        <option value="<?php echo $get_agent_ids_val->crea_agent_id; ?>" <?php if ($crea_listingAgentId == $get_agent_ids_val->crea_agent_id) { ?> selected <?php } ?>><?php echo $get_agent_ids_val->crea_agent_name; ?></option><?php 
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="display_alerdy_exist_agent_name" style="display:none"></div> 
                                        <div class="crea_agent_add_new" id="add_new_agent_ids"><input type="button" class="button button-primary" id="add_new_agent_btn" value="<?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_ADD_MORE_AGENT_ID, ARETKCREA_PLUGIN_SLUG); ?>"></div>
                                    </div>  <?php
                                } //subscription status condition ?>                          
                                
                                <!--//HTML for address, city and Province details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set_common_address  crea_listing_address_row">
                                        <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_STREET_ADDRESS, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                        <div class="input-box"><input id="agent_listing_tab_address" class="agent_listing_tab_address required" type="text" name="agent_listing_tab_address" value="<?php echo $crea_listingAddress; ?>"></div>
                                    </div>
                                    <div class="set_common_address crea_listing_city_row">
                                        <div class="set-agent-title set-text-center add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_CITY, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                        <div class="input-box"><input id="agent_listing_tab_city" class="agent_listing_tab_city required" type="text" name="agent_listing_tab_city" value="<?php echo $crea_listingcity; ?>"></div>
                                    </div>
                                    <div class="set_common_address crea_listing_province_row">
                                        <div class="set-agent-title set-text-center add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_PROVINCE, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                        <div class="input-box"><input id="agent_listing_tab_Province" class="agent_listing_tab_Province required" type="text" name="agent_listing_tab_Province" value="<?php echo $crea_listingProvince; ?>"></div>
                                    </div>
                                </div>

                                <div class="listing-tab-detail-row">
                                    <!-- <div class="set_mls  crea_listing_mls_row"> -->
                                        <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __('MLS Number', ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                        <div class="input-box"><input id="agent_listing_mls_number" class="agent_listing_tab_mls" type="text" name="agent_listing_tab_mls" value="<?php echo isset($crea_listingMls) ? $crea_listingMls : ''; ?>"></div>
                                    <!-- </div> -->
                                </div>
                                <!--//status details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_STATUS, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                   <div class="input-box"> <select class="listing_view_agent_status required" name="listing_view_agent_status" id="listing_view_agent_status">
                                        <option value="">Select Transaction Type</option>
                                        <option value="For Sale" <?php if ($crea_listingAgentStatus == 'For Sale') { ?> selected <?php } ?>>For Sale</option>
                                        <option value="For Lease" <?php if ($crea_listingAgentStatus == 'For Lease') { ?> selected <?php } ?>>For Lease</option>
                                        <option value="For Rent" <?php if ($crea_listingAgentStatus == 'For Rent') { ?> selected <?php } ?>>For Rent</option>
                                        <option value="Sold" <?php if ($crea_listingAgentStatus == 'Sold') { ?> selected <?php } ?>>Sold</option>
                                    </select></div>
                                </div>
                                <!--//price details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_PRICE, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <div class="input-box"><input id="agent_listing_tab_price" class="agent_listing_tab_price required" type="text" name="agent_listing_tab_price" value="<?php echo $crea_listingPrice; ?>"> <span class="price_restriction_text">"No Commas or Spaces"</span></div>
                                </div>
                                <!--//Property Type details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_PROPERTY_TYPE, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-property-type" name="listing-view-agent-property-type">
                                        <option value="">Property Type</option>
                                        <option value="Residential" <?php if ($crea_listingPropertyType == 'Residential') { ?> selected <?php } ?>>Residential</option>
                                        <option value="Commercial" <?php if ($crea_listingPropertyType == 'Commercial') { ?> selected <?php } ?>>Commercial</option>
                                        <option value="Single Family" <?php if ($crea_listingPropertyType == 'Single Family') { ?> selected <?php } ?>>Single Family</option>
                                        <option value="Multi Family" <?php if ($crea_listingPropertyType == 'Multi Family') { ?> selected <?php } ?>>Multi Family</option>
                                        <option value="Cottage" <?php if ($crea_listingPropertyType == 'Cottage') { ?> selected <?php } ?>>Cottage</option>
                                        <option value="Farm" <?php if ($crea_listingPropertyType == 'Farm') { ?> selected <?php } ?>>Farm</option>
                                        <option value="Vacant Land" <?php if ($crea_listingPropertyType == 'Vacant Land') { ?> selected <?php } ?>>Vacant Land</option>
                                    </select>
                                </div>
                                <!--//Structure Type details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_STRUTURE_TYPE, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-structure-type" name="listing-view-agent-structure-type">
                                        <option value="">Select Structure Type</option>
                                        <option value="Condo" <?php if ($crea_listingStructureType == 'Condo') { ?> selected <?php } ?> >Condo</option>
                                        <option value="Detached" <?php if ($crea_listingStructureType == 'Detached') { ?> selected <?php } ?> >Detached</option>
                                        <option value="Semi-Detached" <?php if ($crea_listingStructureType == 'Semi-Detached') { ?> selected <?php } ?> >Semi-Detached</option>
                                        <option value="Town House" <?php if ($crea_listingStructureType == 'Town House') { ?> selected <?php } ?> >Town House</option>
                                        <option value="Business" <?php if ($crea_listingStructureType == 'Business') { ?> selected <?php } ?> >Business</option>
                                        <option value="Bungalow" <?php if ($crea_listingStructureType == 'Bungalow') { ?> selected <?php } ?> >Bungalow</option>
                                        <option value="2 Storey" <?php if ($crea_listingStructureType == '2 Storey') { ?> selected <?php } ?> >2 Storey</option>
                                        <option value="2+ Storey" <?php if ($crea_listingStructureType == '2+ Storey') { ?> selected <?php } ?> >2+ Storey</option>
                                        <option value="Office Space" <?php if ($crea_listingStructureType == 'Office Space') { ?> selected <?php } ?> >Office Space</option>
                                    </select>
                                </div>
                                <!--//Bedrooms details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_BEDROOMS, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-bedrooms" name="listing-view-agent-bedrooms">
                                        <option value="">Select Bedrooms</option><?php 
                                        for ($badrooms = 1; $badrooms <= 10; $badrooms++) { ?>
                                            <option value="<?php echo $badrooms; ?>" <?php if ($crea_listingBedRooms == $badrooms) { ?> selected <?php } ?>><?php echo $badrooms; ?></option><?php 
                                        } ?>
                                    </select>
                                </div>
                                <!--//Bathrooms Full details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title  add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_BATHROOM, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-bathrooms-full" name="listing-view-agent-bathrooms-full">
                                        <option value="">Select Bathrooms Full</option><?php 
                                        for ($bathrooms_full = 1; $bathrooms_full <= 10; $bathrooms_full++) { ?>
                                            <option value="<?php echo $bathrooms_full; ?>" <?php if ($crea_listingBathrooms == $bathrooms_full) { ?> selected <?php } ?>><?php echo $bathrooms_full; ?></option><?php 
                                        } ?>
                                    </select>
                                </div>
                                <!--//Bathrooms Partial details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_PARTIAL_BATHROOM, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-bathrooms-partial" name="listing-view-agent-bathrooms-partial">
                                        <option value="">Select Bathrooms Partial</option><?php 
                                        for ($badrooms_partial = 1; $badrooms_partial <= 10; $badrooms_partial++) { ?>
                                            <option value="<?php echo $badrooms_partial; ?>" <?php if ($crea_listingBathroomsPartial == $badrooms_partial) { ?> selected <?php } ?>><?php echo $badrooms_partial; ?></option><?php
                                        } ?>
                                    </select>
                                </div>
                                <!--//basement details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title  add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_BASEMENT, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-agent-finished-basement" name="listing-view-agent-finished-basement">
                                        <option value="">Select Finished Basement</option>
                                        <option value="Yes" <?php if ($crea_listingFinishedBasement == 'Yes') { ?> selected <?php } ?>>Yes</option>
                                        <option value="No" <?php if ($crea_listingFinishedBasement == 'No') { ?> selected <?php } ?>>No</option>
                                        <option value="Partial" <?php if ($crea_listingFinishedBasement == 'Partial') { ?> selected <?php } ?>>Partial</option>
                                    </select>
                                </div>
                                <!--//tax Partial details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title set-desc-top add_new_listing_center_tax "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_GENERAL_TAB_DESCRIPTION, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <textarea id="crea_listing_agent_discription" name="listing-view-agent-descriptions" class="listing-view-agent-descriptions"><?php echo isset($crea_post_content) ? $crea_post_content : ''; ?></textarea>
                                </div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordiaon section for general tab-->

                        <!--// accordiaon section for PARKING/GARAGE tab-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-parking-garage-tab"><?php echo __(ARETKCREA_PARKING_GARAGE_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-parking-garage-tab" class="accordion-section-content">
                                <!--//HTML for Number of Parking Spots details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_PARKING_GARAGE_TAB_PARKING_SPOT, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-pa-ga-parking-slot" name="listing-view-pa-ga-parking-slot">
                                        <option value=""> Select Parking Spots</option><?php 
                                        for ($parking_spot = 1; $parking_spot <= 10; $parking_spot++) { ?>
                                            <option value="<?php echo $parking_spot; ?>" <?php if ($crea_listingParkingSlot == $parking_spot) { ?> selected <?php } ?>><?php echo $parking_spot; ?></option><?php 
                                        } ?>
                                    </select>
                                </div>
                                <!--//HTML for Garage details-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title add_new_listing_center_tax"><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_PARKING_GARAGE_TAB_GARAGE, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <select class="listing-view-pa-ga-garage" name="listing-view-pa-ga-garage">
                                    <?php                                     
                                    $crea_listingParkinggarageAttachedSelected = '';
                                    $crea_listingParkinggarageDetachedSelected = '';
                                    $crea_listingParkinggarageGarageSelected = '';
                                    $crea_listingParkinggarageNoneSelected = '';                                        
                                    if ( isset($crea_listingParkinggarage) && !empty($crea_listingParkinggarage)){    
                                        if ( $crea_listingParkinggarage == 'Attached garage'){
                                            $crea_listingParkinggarageAttachedSelected = 'selected';
                                        }                                       
                                        if ( $crea_listingParkinggarage == 'Detached garage' ){ 
                                            $crea_listingParkinggarageDetachedSelected = 'selected';
                                        }                                       
                                        if ( $crea_listingParkinggarage == 'Garage' ){ 
                                            $crea_listingParkinggarageGarageSelected = 'selected';
                                        }
                                    } else { 
                                        $crea_listingParkinggarageNoneSelected = 'selected';
                                    } ?>    
                                    <option <?php echo $crea_listingParkinggarageNoneSelected; ?> value=""> Select Garage</option>
                                    <option <?php echo $crea_listingParkinggarageAttachedSelected; ?> value="Attached garage">Attached</option>
                                    <option <?php echo $crea_listingParkinggarageDetachedSelected; ?> value="Detached garage">Detached</option>
                                    <option <?php echo $crea_listingParkinggarageGarageSelected; ?> value="Garage">None</option>
                                    </select>
                                </div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordiaon section for PARKING/GARAGE tab-->
                        
                        <!--// accordion section for VIRTUAL TOUR-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-virtual-tour-tab"><?php echo __(ARETKCREA_LISTING_SETTING_VIRTUAL_TOUR_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-virtual-tour-tab" class="accordion-section-content">
                                <!--//HTML for ADD URL-->
                                <div class="listing-tab-detail-row">
                                    <div class="set-agent-title "><label class="agent-listing-tab-title"><?php echo __(ARETKCREA_LISTING_SETTING_VIRTUAL_TOUR_TAB_URL, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                                    <input id="crea_listing_virtual_tour_url" class="listing_virtual_tor_add_url" type="text" name="listing_virtual_tor_add_url" value="<?php echo $crea_listingTourUrl; ?>">
                                </div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for VIRTUAL TOUR-->
                        
                        <!--// accordion section for UTILITIES-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-utilities-tab"><?php echo __(ARETKCREA_LISTING_SETTING_UTILITIES_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-utilities-tab" class="accordion-section-content"><?php                            
                                if ($crea_listing_ID != '' && !empty($crea_listing_ID)) 
                                { ?>
                                    <div id="utilities-new-tabe-add-section" class="add-new-utilities-content">
                                        <?php
                                        $crea_utility_array = json_decode($crea_listingUtilityArr);
                                        if ( isset($crea_utility_array) && !empty($crea_utility_array) )
                                        { 
                                            foreach ($crea_utility_array as $crea_utility_array_key => $crea_utility_array_val) 
                                            { ?>
                                                <div id="crea-utility-textbox<?php echo $crea_utility_array_key; ?>" class="crea-utilities-html"><input type="text" id="crea-utilities-box<?php echo $crea_utility_array_key; ?>" class="check_utitlity_values" value="<?php echo $crea_utility_array_val; ?>" name="crea-utilities-input[]"><a  id="crea_utility_delete<?php echo $crea_utility_array_key; ?>" class="crea_utilities_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div><?php 
                                            }
                                        } else { ?>
                                            <div id="utilities-new-tabe-add-section" class="add-new-utilities-content"><div id="crea-utility-textbox" class="crea-utilities-html"><input id="crea-utilities-box" class="check_utitlity_values" type="text" name="crea-utilities-input[]"><a  id="crea_utility_delete" class="crea_utilities_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div></div><?php
                                        } ?>
                                    </div><?php 
                                } else { ?>
                                    <div id="utilities-new-tabe-add-section" class="add-new-utilities-content"><div id="crea-utility-textbox" class="crea-utilities-html"><input id="crea-utilities-box" class="check_utitlity_values" type="text" name="crea-utilities-input[]"><a  id="crea_utility_delete" class="crea_utilities_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div></div><?php 
                                } ?>
                                <div class="set-utilities-btn"><input type="button" id="crea-utilitiy-add-more-input" name="crea_utilitiy_add_more" class="button button-primary" value="<?php echo __(ARETKCREA_LISTING_SETTING_UTILITIES_TAB_ADDMORE, ARETKCREA_PLUGIN_SLUG); ?>"><label><?php echo __(ARETKCREA_LISTING_SETTING_UTILITIES_TAB_TEXBOX_LIMIT_MSG, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for UTILITIES-->
                        
                        <!--// accordion section for FEATURES-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-features-tab"><?php echo __(ARETKCREA_LISTING_SETTING_FEATURES_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-features-tab" class="accordion-section-content"><?php 
                                if ($crea_listing_ID != '' && !empty($crea_listing_ID)) { ?>
                                    <div id="features-new-tabe-add-section" class="add-new-features-content"><?php
                                        $crea_feature_array = json_decode($crea_listingFeatureArr);
                                        if ( isset($crea_feature_array) && !empty($crea_feature_array) ) { 
                                            foreach ($crea_feature_array as $crea_feature_array_key => $crea_feature_array_val) { ?>
                                                <div id="crea-features-textbox<?php echo $crea_feature_array_key; ?>" class="crea-features-html"><input type="text" id="crea-features-box<?php echo $crea_feature_array_key; ?>" class="crea_listing_feature_input" name="crea-features-input[]" value="<?php echo $crea_feature_array_val; ?>"><a  id="crea_features_delete<?php echo $crea_feature_array_key; ?>" class="crea_features_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div><?php 
                                            }
                                        } else { ?>
                                            <div id="features-new-tabe-add-section" class="add-new-features-content"><div id="crea-features-textbox" class="crea-features-html"><input id="crea-features-box" type="text" class="crea_listing_feature_input" name="crea-features-input[]"><a  id="crea_features_delete" class="crea_features_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div></div><?php
                                        } ?>
                                    </div><?php
                                } else { ?>
                                    <div id="features-new-tabe-add-section" class="add-new-features-content"><div id="crea-features-textbox" class="crea-features-html"><input id="crea-features-box" class="crea_listing_feature_input" type="text" name="crea-features-input[]"><a  id="crea_features_delete" class="crea_features_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></div></div><?php
                                } ?>
                                <div class="set-features-btn"><input type="button" id="crea-features-add-more-input" name="crea_features_add_more" class="button button-primary" value="<?php echo __(ARETKCREA_LISTING_SETTING_FEATURES_TAB_ADDMORE, ARETKCREA_PLUGIN_SLUG); ?>"><label><?php echo __(ARETKCREA_LISTING_SETTING_FEATURES_TAB_TEXBOX_LIMIT_MSG, ARETKCREA_PLUGIN_SLUG); ?></label></div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for FEATURES-->
                        
                        <!--// accordion section for PHOTOS-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-photos-tab"><?php echo __(ARETKCREA_LISTING_SETTING_PHOTO_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-photos-tab" class="accordion-section-content"><?php
                                if ( !empty($crea_listing_ID) ) 
								{
                                    $crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
                                    $unique_id = (int) $crea_listing_ID;
                                    $sql_select = "SELECT * FROM `$crea_listing_images_detail_table_name` WHERE `unique_id` = %d ORDER BY `image_position` ASC";    
                                    $sql_prep = $wpdb->prepare( $sql_select, $unique_id );
                                    $imageSet = $wpdb->get_results($sql_prep); ?>
									<div class="test-images" id="<?php echo $crea_listing_ID; ?>"><?php
										if (isset($imageSet) && !empty($imageSet)) 
										{ ?>
                                            <a href="javascript:void(0);" class="btn outlined mleft_no reorder_link" id="save_reorder">reorder photos</a>
                                            <div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br />2. Click 'Save Reordering' when finished.</div>
                                            <div class="gallery">
                                                <ul class="reorder_ul reorder-photos-list"><?php 
                                                    foreach ($imageSet as $image) 
													{ ?>
                                                        <li class="ui-sortable-handle delete-icon" id="image_li_<?php echo $image->id; ?>"><div  id="image_li_<?php echo $image->id; ?>" class = "delete-showcase-photo-listing"></div>
                                                            <a href="javascript:void(0);" id="image_li_<?php echo $image->id; ?>" style="float:none;" class="image_link  delete_listing_photo delete-icon"></a>
                                                                <img style="height:100px;width:100px;" src="<?php echo $image->image_url; ?>" alt="">
                                                        </li><?php 
                                                    } ?>
                                                </ul>
                                            </div><?php
										} ?>
									</div><?php
								} ?>
                                <div id="maindiv">
                                    <div id="formdiv">
                                        <div id="filediv">
                                            <input class="filesinput" name="file[]" type="file" id="file" accept=".jpg,.jpeg,.png" multiple/><?php                           
                                            if ( !empty($crea_listing_ID) ) { ?>        
                                                <a href="javascript:void(0);" id="edit_page_upload_image_ajax" class="upload button button-primary" style="display:none">Upload</a> <?php 
                                            } ?>
                                            <img id="imageloading" src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/bx_loader.gif" alt="loading" height="32" width="32" />
                                        </div>
                                    </div>
                                </div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for PHOTOS-->
                        
                        <!--// accordion section for EXTERNAL DOCUMENTS-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-external-document-tab"><?php echo __(ARETKCREA_LISTING_SETTING_EXTERNAL_DOCUMENT_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-external-document-tab" class="accordion-section-content">         
                                <div id="crea_listing_multiplefile_display" class="crea_listing_display_multiple_files"><?php
                                if ( !empty($crea_listing_ID) ) 
                                {
                                    $crea_listing_document_detail_table_name = $wpdb->prefix.ARETKCREA_LISTING_DOCUMENT_HISTORY;
                                    $unique_id = (int) $crea_listing_ID;
                                    $sql_select = "SELECT * FROM `$crea_listing_document_detail_table_name` WHERE `unique_id` = %d ORDER BY `ID` ASC";  
                                    $sql_prep = $wpdb->prepare( $sql_select, $unique_id );
                                    $documentSet = $wpdb->get_results($sql_prep);
                                    foreach ($documentSet as $document) 
                                    { ?>
                                        <div class="crea_listing_display_select_files">
                                        <a id="<?php echo $document->id; ?>" href="javascript:void(0);" class="crea_delete_documents"><img id="crea_listing_dicument_delte_ids" width="20px" src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" alt="Delete Document"></a>
                                        <img class="crea_document_files_img" width="50px" src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/document_icon.png" alt="Document Icon">
                                        <p><?php echo $document->document_name; ?></p>
                                        <input type="hidden" name="crea_listing_multiplefile_document_array[]" value="<?php echo $document->document_name; ?>" >
                                        </div><?php  
                                    } 
                                } ?>                        
                                </div>
                                <div class="crea_external_documents" id="crea-listing-external-documents">
                                    <div class="crea_ext_document_html" id="crea_external_document_html">
                                        <div id="extdocmaindiv">
                                            <input class="filesinputextdoc" name="extdocfileinput[]" type="file" multiple/>
                                        </div>
                                        <input type="button" id="addMoreExtDocument" class="upload button button-primary general_browse_button" style="display:none" value="<?php echo __(ARETKCREA_LISTING_SETTING_EXTERNAL_DOCUMENT_ADDMORE_BTN, ARETKCREA_PLUGIN_SLUG); ?>"/>
                                    </div>
                                </div>
                                <div class="listing_not_allowed_documents_list_failed"></div>
                                <div class="set-crea-ext-document-btn"><p class="check_validation_for_multiple_files checkmaxTenfileallowed">A maximum of 10 files can be added at a time</p><p class="check_validation_for_multiple_files_format checkformatfileallowed" >Only allowed .png, .jpg, .jpeg, .doc, .docx, .csv, .pdf and .txt</p><p class="check_validation_for_multiple_files">File Size Limit 5MB Per Document.</p></div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for EXTERNAL DOCUMENTS-->
                        
                        <!--// accordion section for OPEN HOUSE-->
                        <div class="accordion-section">
                            <a class="accordion-section-title" href="#listting-open-house-tab"><?php echo __(ARETKCREA_LISTING_SETTING_OPEN_HOUSE_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-open-house-tab" class="accordion-section-content">
                                <div id="listing-open-house-date-time-html" class="display-date-time-html">
                                    <table cellpadding="10" cellspacing="10">
                                        <thead>
                                            <tr>
                                                <th><?php echo __(ARETKCREA_LISTING_SETTING_OPEN_HOUSE_DATE, ARETKCREA_PLUGIN_SLUG); ?></th>
                                                <th><?php echo __(ARETKCREA_LISTING_SETTING_OPEN_HOUSE_START_TIME, ARETKCREA_PLUGIN_SLUG); ?></th>
                                                <th><?php echo __(ARETKCREA_LISTING_SETTING_OPEN_HOUSE_END_TIME, ARETKCREA_PLUGIN_SLUG); ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="find_tbody"><?php
                                            $crea_listing_date_time_array = json_decode($crea_listingopenhosedatetimeArr);
                                            if (!empty($crea_listing_date_time_array) && $crea_listing_date_time_array != '') 
                                            {
                                                foreach ($crea_listing_date_time_array as $crea_listing_date_time_key => $crea_listing_date_time_value) 
                                                {
                                                    $crea_op_date = $crea_listing_date_time_value->date;
                                                    $crea_op_start_time = $crea_listing_date_time_value->start_time;
                                                    $crea_op_end_time = $crea_listing_date_time_value->end_time; ?>
                                                    <tr class="open-house-date-time-html" id="date-time-html<?php echo $crea_listing_date_time_key; ?>">
                                                        <td><div class="input-box"><input type="text" name="crea_home_date_picker[]" class="datepicker_popup" id="datepicker<?php echo $crea_listing_date_time_key; ?>" value="<?php echo $crea_op_date; ?>"/></div></td>
                                                        <td><?php
                                                            $start_time = "00:00"; 
                                                            $end_time = "23:30"; 
                                                            $view_Start = strtotime($start_time);
                                                            $view_End = strtotime($end_time);
                                                            $view_Now = $view_Start; ?>
                                                            <select name="crea-open-house-start-time[]" id="crea_open_house_start_time_id<?php echo $crea_listing_date_time_key; ?>">
                                                                <option value="">Select Time</option><?php
                                                                while ($view_Now <= $view_End)
                                                                {
                                                                    $select_option_val = date("H:i", $view_Now);
                                                                    $view_Now = strtotime('+30 minutes', $view_Now); ?>
                                                                    <option value="<?php echo $select_option_val; ?>"<?php if ($crea_op_start_time == $select_option_val) { ?> selected <?php } ?>><?php echo $select_option_val; ?></option><?php 
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td><?php
                                                            $start_time = "00:00";
                                                            $end_time = "23:30";
                                                            $view_Start = strtotime($start_time);
                                                            $view_End = strtotime($end_time);
                                                            $view_Now = $view_Start; ?>
                                                            <select name="crea-open-house-end-time[]" id="crea_open_house_end_time_id<?php echo $crea_listing_date_time_key; ?>">
                                                                <option value="">Select Time</option><?php
                                                                while ($view_Now <= $view_End) 
                                                                {
                                                                    $slect_option_val = date("H:i", $view_Now);
                                                                    $view_Now = strtotime('+30 minutes', $view_Now); ?>
                                                                    <option value="<?php echo $slect_option_val; ?>" <?php if ($crea_op_end_time == $slect_option_val) { ?> selected <?php } ?>><?php echo $slect_option_val; ?></option><?php 
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td><a  id="crea_date_time_delete" class="crea_date_time_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></td>
                                                    </tr><?php
                                                }
                                            } else { ?>
                                                <tr id="date-time-html" class="open-house-date-time-html">
                                                    <td><div class="input-box"><input type="text" name="crea_home_date_picker[]" class="datepicker_popup" id="datepicker"/></div></td>
                                                    <td><?php
                                                        $start_time = "00:00";
                                                        $end_time = "23:30";
                                                        $view_Start = strtotime($start_time);
                                                        $view_End = strtotime($end_time);
                                                        $view_Now = $view_Start;                        
                                                        if (empty($crea_op_start_time)){
                                                            $crea_op_start_time = "09:00";
                                                        }
                                                        if (empty($crea_op_end_time)){
                                                            $crea_op_end_time = "17:00";
                                                        } ?>
                                                        <select name="crea-open-house-start-time[]" id="crea_open_house_start_time_id">
                                                            <option value="">Select Time</option><?php
                                                            while ($view_Now <= $view_End) {
                                                                $select_option_val = date("H:i", $view_Now);
                                                                $view_Now = strtotime('+30 minutes', $view_Now); ?>
                                                                <option value="<?php echo $select_option_val; ?>"<?php if ($crea_op_start_time == $select_option_val) { ?> selected <?php } ?>><?php echo $select_option_val; ?></option><?php 
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td><?php
                                                        $start_time = "00:00";
                                                        $end_time = "23:30";
                                                        $view_Start = strtotime($start_time);
                                                        $view_End = strtotime($end_time);
                                                        $view_Now = $view_Start; ?>
                                                        <select name="crea-open-house-end-time[]" id="crea_open_house_end_time_id">
                                                            <option value="">Select Time</option><?php
                                                            while ($view_Now <= $view_End) {
                                                                $slect_option_val = date("H:i", $view_Now);
                                                                $view_Now = strtotime('+30 minutes', $view_Now); ?>
                                                                <option value="<?php echo $slect_option_val; ?>"<?php if ($crea_op_end_time == $slect_option_val) { ?> selected <?php } ?>><?php echo $slect_option_val; ?></option><?php 
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td><a  id="crea_date_time_delete" class="crea_date_time_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="delete" width="20" height="20"></a></td>
                                                </tr><?php 
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="open-house-date-time-btn"><input id="crea_add_new_date_time" type="button" class="button button-primary" value="<?php echo __(ARETKCREA_LISTING_SETTING_OPEN_HOUSE_ADD_MORE_DATE_BTN, ARETKCREA_PLUGIN_SLUG); ?>" ></div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end ccordion section for OPEN HOUSE-->
                        
                        <!--// accordion section for LOCATION-->
                        <div class="accordion-section">
                            <a class="accordion-section-title google-map-open" href="#listting-location-tab"><?php echo __(ARETKCREA_LISTING_SETTING_LOCATION_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                            <div id="listting-location-tab" class="accordion-section-content">
                                <div class="google-map-address">
                                    <?php 
                                    if (isset($crea_listing_ID) && !empty($crea_listing_ID)) {  
									
                                        $lat1 = get_post_meta($crea_listing_ID,'crea_google_map_latitude',true); 
                                        $long1 = get_post_meta($crea_listing_ID,'crea_google_map_longitude',true);
                                        $geoAddress1 = get_post_meta($crea_listing_ID,'crea_google_map_geo_address',true);
                                        $lat = isset($lat1) && !empty($lat1) ? $lat1 : 57.67807921815639;
                                        $long = isset($long1) && !empty($long1) ? $long1 : -101.80516868749999;
                                        $geoAddress = isset($geoAddress1) && !empty($geoAddress1) ? "$geoAddress1" : 'Canada';									
                                    } else {                                            
                                        $lat = 57.67807921815639;
                                        $long = -101.80516868749999;
                                        $geoAddress = 'Canada';
                                    } ?>
                                    <input type="text" name="crea_google_map_address_text" id="crea_listing_google_map_location_txt" value="<?php echo $geoAddress; ?>" />
                                    <input type="button" id="crea_listing_map_button" class="button button-primary crea_listing_address_location" value="<?php echo __(ARETKCREA_LISTING_SETTING_LOCATION_SEARCH_BTN, ARETKCREA_PLUGIN_SLUG); ?>" />
                                    <input name="crea_google_map_latitude" type="hidden" id="crea_google_map_latitude" value="<?php echo $lat; ?>" />
                                    <input name="crea_google_map_longitude" type="hidden" id="crea_google_map_longitude" value="<?php echo $long; ?>" />
                                    <input name="crea_google_map_geo_address" type="hidden" id="crea_google_map_geo_address" value="<?php echo $geoAddress; ?>" />
                                </div>
                                <div class="crea_google_maps" id="crea_location_google_maps" style="width:100%;height:400px;"></div>
                            </div><!--end .accordion-section-content-->
                        </div><!--end accordion section for LOCATION-->
                    </div><!--end accordion-->
                </div><!--end main-->                
                <div class="crea_validate_message_display" style="display:none;">
                    <p class="set_error_msg check_agent_name_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_AGENT_NAME, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_address_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_STREET_ADDRESS, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_city_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_CITY, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_province_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_PROVINCE, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_status_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_STATUS, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_price_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_PRICE, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <p class="set_error_msg check_agent_virtual_url_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_VIRTUAL_TOUR, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <?php if( $crea_listing_ID == '' ) { ?>
                    <p class="set_error_msg check_agent_photo_required" style="display:none;"><?php echo __(ARETKCREA_PARKING_ERROR_REQUIRED_PHOTO, ARETKCREA_PLUGIN_SLUG); ?></p>
                    <?php } ?>
                </div>                  
                <div class="crea_new_listing_btn_part">
                    <?php
                    if (!empty($crea_listing_ID) && $crea_listing_ID != '') {
                        $btn_value = __(ARETKCREA_ADD_NEW_LISTING_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG);
                    } else {
                        $btn_value = __(ARETKCREA_ADD_NEW_LISTING_SAVE_BTN, ARETKCREA_PLUGIN_SLUG);
                    } ?>
                    <input type="submit" name="crea-listing-save" class="crea-new-listing-save button button-primary" value="<?php echo $btn_value; ?>">
                     <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listings_settings"><input type="button" style="padding: 0 10px 1px;" name="crea-listing-cancel" id="crea-lead-cancel-btn" class="crea-new-listing-cancel button button-primary" value="<?php echo __(ARETKCREA_ADD_NEW_LISTING_CANCEL_BTN, ARETKCREA_PLUGIN_SLUG); ?>" /></a>
                </div>
            </div>
        </div>
    </form><?php
}

/**
 * create function for aretkcrea_custom_showcase_settings_html
 *  
 * @return return html for the CREA Showcase Settings fourth tab.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_showcase_settings_html() { ?>
    <div class="crea-container">
        <div class="crea-plugin-title remove-border"><h2>SHOWCASES</h2></div>
        <div class="crea_add_new_list"><a class="crea-add-new-listing-btn button button-primary" href="<?php echo admin_url('admin.php?page=create_new_showcase'); ?>"><?php echo __(ARETKCREA_ADD_NEW_SHOWCASE, ARETKCREA_PLUGIN_SLUG); ?></a></div>
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <div class="crea-showcase-setings-inner-wrap">
            <div class="crea-showcase-setings-inner-wrap-block1">
               <p><strong>Default 'Listings' Showcase</strong><br />
               This ARETK plugin automatically creates a Default Listing page which you can copy and paste the shortcode into any of your WordPress pages. This showcase by default shows the listings by 'List View' and pulls listings from your exclusive listings (as well as CREA listings from the first Datafeed entered if you have subscribed to the CREA Add-On). You can simply use this Default Listing Showcase as it is and/or you can create additional new listing showcases (by clicking ADD NEW SHOWCASE button above). You have the option of 5 different listing displays (List, Grid, Carousel, Single Slider or Map View), various setting options and colour options. Once the new showcase has been done, a new shortcode will be created and you can copy and paste this new shortcode into any of your WordPress web pages.</p>
                <p><strong>Default 'Listing Details' Showcase</strong><br />
                This 'listing details' showcase is a single listing page. A Page in your WordPress site will be automatically created when this plugin is installed. This page shows when you click on any particular listing from any of your listing showcases with the particulars of that listing. You can edit the Default 'Listing Details' showcase by clicking the edit button to the right of the shortcode, but you cannot delete the 'Listing Details' Showcase or create additional 'Listing Detail' Showcases.</p>               
                <p><strong>Default - listings Search Showcase</strong><br />                
                The Default 'Search' Showcase can be edited and you can copy and paste the shortcode for this Search Box into any of your WordPress pages. By default this Search Box searches the Default 'Listings' Showcase and will post to this page. You can edit which Showcase the search box searches and how the results are displayed by selecting a different showcase under the settings tab when editing the Default 'Search' Showcase. You need to create the alternate listing showcase prior to changing this setting.</p>              
            </div>
            <div class="crea-showcase-setings-inner-wrap-block2">
                <table class="crea-showcase-table" width="100%" border="0" cellspacing="0" cellpadding="10" id="crea-showcase-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>TITLE</th>
                            <th>DATE</th>
                            <th>SHOWCASE TYPE</th>
                            <th>SHORT CODE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Default - Listings Showcase</td>
                            <td>-</td>
                            <td>Listings Showcase</td>
                            <td>[ARTEK-DLS]</td>
                            <td>-</td>
                        </tr>
                        <tr></tr>                           
                            <td>2</td>
                            <td>Default - Listing Details Showcase</td>
                            <td>-</td>
                            <td>Listing Details Showcase</td>
                            <td>-</td>
                            <td>    
                                <a id="crea_showcase_edit_2" class="crea_showcase_action crea_showcase_edit_action" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listing_details_settings">
                                    <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/edit-icon.png'; ?>" alt="edit" width="20" height="20">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Default - Listings Search Showcase</td>
                            <td>-</td>
                            <td>Search Showcase</td>
                            <td>[ARETK-DSS]</td>
                            <td>    
                               <a id="crea_showcase_edit_3" class="crea_showcase_action crea_showcase_edit_action" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=search_listing_settings_showcase">
                                    <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/edit-icon.png'; ?>" alt="edit" width="20" height="20">
                                </a>
                            </td>
                        </tr><?php 
                        $get_aretk_showcase_args = array(
                            'post_type' => 'aretk_showcase',
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'posts_per_page'   => -1,
                            'order' => 'ASC'
                        );
                        $showcase_array = get_posts( $get_aretk_showcase_args ); 
                        $showcase_counter = 4;
                        foreach ( $showcase_array as $showcase_array_key=>$showcase_array_value ) {
                            $get_showcase_category = get_the_terms( $showcase_array_value->ID, 'listing-showcase' ); ?> 
                            <tr>
                                <td><?php echo $showcase_counter; ?></td>
                                <td><?php echo $showcase_array_value->post_title;  ?></td>
                                <td><?php echo $showcase_array_value->post_date; ?></td>
                                <td><?php
                                if( !empty($get_showcase_category) && $get_showcase_category!='') { 
                                    foreach ($get_showcase_category as $get_showcase_category_key=>$get_showcase_category_value ) { 
                                        echo "Listings Showcase"; 
                                    } 
                                } ?>
                               </td>
                                <td><?php echo maybe_unserialize( get_post_meta($showcase_array_value->ID,"showcse_crea_save_short_code",true)); ?></td>
                                <td>    
                                    <a id="crea_showcase_edit_<?php echo $showcase_array_value->ID;?>" class="set_showcase_action crea_showcase_action crea_showcase_edit_action" href="<?php echo home_url(); ?>/wp-admin/admin.php?page=create_new_showcase&action=edit&showcase_id=<?php echo $showcase_array_value->ID; ?>">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/edit-icon.png'; ?>" alt="edit" width="20" height="20">
                                    </a>
                                    <a href="javascript:void(0);" id="<?php echo $showcase_array_value->ID;?>" class="set_showcase_action crea_listing_showcase_delete_action">
                                      <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/delete-icon.png'; ?>" alt="edit" width="20" height="20">
                                    </a>
                                </td>
                            </tr><?php  
                            $showcase_counter = $showcase_counter + 1;  
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><?php
}

/**
 * create function for aretkcrea_custom_new_create_showcase_html
 *
 * @return return html for the crea showcase html.
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_new_create_showcase_html() 
{
    $showcaseid = '';
    if ( isset($_GET['showcase_id']) && is_numeric($_GET['showcase_id']) ){
        $showcaseid = (int) $_GET['showcase_id'];
    }   

    $crea_listing_view_color_array = array();
    $crea_grid_view_color_array = array();
    $crea_carousel_view_color_array = array();
    $crea_map_view_color_array = array();
    $crea_slider_view_color_array = array();
    $showcase_filter_property_types = array();
	$showcase_filter_ownership_types = array();
    $showcase_filter_property_status = array();
    $showcase_filter_listing_agent_ids = array();   
    $showcase_filter_listing_province = array();
    if( $showcaseid !='' && !empty($showcaseid)) {
        $showcase_title = get_the_title($showcaseid);
        $showcse_crea_feed_ddf_type = maybe_unserialize( get_post_meta($showcaseid,'showcse_crea_feed_ddf_type',true) );
        $showcse_crea_feed_include_exclude = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_feed_include_exclude',true) );
        $showcse_crea_display_theams_option = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_display_theams_option',true) );
        $showcse_crea_filter_brokerage = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_brokerage',true) );
        $showcse_crea_filter_office = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_office',true) );
        $showcse_crea_filter_agent_name = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_agent_name',true) );
        $showcse_crea_filter_brokerage_hidden_name = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_brokerage_hidden_name',true) );
        $showcse_crea_filter_office_hidden_name = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_office_hidden_name',true) );
        $showcse_crea_filter_agents_hidden_name = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_agents_hidden_name',true) );
        $showcse_crea_filter_listing = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_listing',true) );
        $showcse_crea_filter_by_map_km = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_by_map_km',true) );
        $showcse_crea_filter_by_other_day = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_by_other_day',true) );
        $showcse_crea_filter_inclue_open_house = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_inclue_open_house',true) );     
        $showcase_filter_price_min = maybe_unserialize( get_post_meta($showcaseid,'showcse_filter_price_min',true) ); 
        $showcase_filter_price_max = maybe_unserialize( get_post_meta($showcaseid,'showcse_filter_price_max',true) );           
        $showcase_filter_property_types = maybe_unserialize( get_post_meta($showcaseid,'showcase_filter_property_types',true) );
        $showcase_filter_property_types = explode(',', $showcase_filter_property_types); 
		$showcase_filter_ownership_types = maybe_unserialize( get_post_meta($showcaseid,'showcase_filter_ownership_types',true) );
        $showcase_filter_ownership_types = explode(',', $showcase_filter_ownership_types); 
        $showcase_filter_property_status = maybe_unserialize( get_post_meta($showcaseid,'showcase_filter_property_status',true) );
        $showcase_filter_property_status = explode(',', $showcase_filter_property_status);      
        $showcase_filter_listing_agent_ids = maybe_unserialize( get_post_meta($showcaseid,'showcase_filter_listing_agent_ids',true) );
        $showcase_filter_listing_agent_ids = explode(',', $showcase_filter_listing_agent_ids);      
        $showcase_filter_listing_province = maybe_unserialize( get_post_meta($showcaseid,'showcase_filter_listing_province',true) );
        $showcase_filter_listing_province = explode(',', $showcase_filter_listing_province);        
        $showcse_crea_filter_google_map_latitude     = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_google_map_latitude',true) );
        $showcse_crea_filter_google_map_longitude   = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_google_map_longitude',true) );     
        $showcse_crea_filter_google_map_zoom = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_filter_google_map_zoom',true) );     
        $showcse_crea_serializable_listing_array = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_serializable_listing_array',true) );
        $showcse_crea_serializable_grid_array = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_serializable_grid_array',true) );
        $showcse_crea_serializable_carousel_array = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_serializable_carousel_array',true) );
        $showcse_crea_serializable_map_array = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_serializable_map_array',true) );
        $showcse_crea_serializable_slider_array = maybe_unserialize(  get_post_meta($showcaseid,'showcse_crea_serializable_slider_array',true) );
        $crea_listing_view_color_array = maybe_unserialize(  get_post_meta($showcaseid,'Showcase_crea_listing_view_color_array',true) );
        $crea_grid_view_color_array = maybe_unserialize(  get_post_meta($showcaseid,'Showcase_crea_grid_view_color_array',true) );
        $crea_carousel_view_color_array = maybe_unserialize(  get_post_meta($showcaseid,'Showcase_crea_carousel_view_color_array',true) );
        $crea_map_view_color_array = maybe_unserialize(  get_post_meta($showcaseid,'Showcase_crea_map_view_color_array',true) );
        $crea_slider_view_color_array = maybe_unserialize(  get_post_meta($showcaseid,'Showcase_crea_slider_view_color_array',true) );
    } ?>
    <div class="se-pre-con"></div>
    <div class="crea-container">   
        <form id="crea_showcase_form_validate" method="post" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data" novalidate="novalidate">
            <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
            <div class="crea-plugin-title remove-border">
                <h2><?php
                    if( $showcaseid != '' && !empty($showcaseid) ) {
                        echo __(ARETKCREA_EDIT_SHOWCASE_TITLE, ARETKCREA_PLUGIN_SLUG); 
                    } else{ 
                        echo __(ARETKCREA_NEW_SHOWCASE_TITLE, ARETKCREA_PLUGIN_SLUG); 
                    }?>
                </h2>
            </div>       
            <div class="crea-plugin-showcase-content"><?php                 
                $getSubscriptionStatus = get_option('crea_subscription_status', '');                
                if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ 
                    $getSubscriptionKey = get_option('crea_subscription_key', '');
                    $key = !empty($getSubscriptionKey) ? $getSubscriptionKey : ''; ?>
                    <input type="hidden" id="get_api_key_by_subscription" value="<?php echo  $key; ?>"><?php 
                } ?>            
                <div class="crea-showcase-tab-title-input">
                    <label><?php echo __(ARETKCREA_NEW_SHOWCASE_INPUT_TITLE, ARETKCREA_PLUGIN_SLUG); ?> </label>
                    <input type="text" id="crea_showcase_post_title" value="<?php echo isset($showcase_title) ? $showcase_title : ''; ?>" name="crea_showcase_title" placeholder="Title here">
                </div>           
                <div class="crea_showcase_listing_menu">
                    <div id="crea_showcase_menu_tab">
                        <ul>
                            <li><a href="#crea_showcase_feed_tab"><?php echo __(ARETKCREA_NEW_SHOWCASE_FEED_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                            <li><a href="#crea_showcase_display_tab" ><?php echo __(ARETKCREA_NEW_SHOWCASE_DISPLAY_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                            <?php                           
                            if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ ?>
                                <li><a href="#crea_showcase_filter_tab" id="crea_showcase_filter_button_tab"><?php echo __(ARETKCREA_NEW_SHOWCASE_FILTER_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li><?php 
                            }  ?>   
                            <li><a class="crea_showcase_setting_tabs" href="#crea_showcase_setting_tab"><?php echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                            <?php  
                            if( !empty( $showcaseid ) && isset( $showcaseid )){ 
                                $map_theme_view = get_post_meta($showcaseid ,'showcse_crea_display_theams_option',true);    
                                 if( isset( $map_theme_view ) && !empty( $map_theme_view ) && $map_theme_view != 'Map') {  
                                    $map_theme_view_results = "block";
                                 }else { 
                                    $map_theme_view_results = "none";
                                 }
                            } else { 
                                $map_theme_view_results = "block";
                            }
                            ?>
                            <li><a href="#crea_showcase_colour_tab" style="display:<?php echo $map_theme_view_results; ?>"  id="crea_showcase_color_tab_hidden_for_map_view"><?php echo __(ARETKCREA_NEW_SHOWCASE_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li><?php                                
                            if( !empty( $showcaseid ) && isset( $showcaseid )){ 
                                $previewnsaveview = 'block'; ?>
                                <li><a href="#crea_showcase_save_tab"><?php echo __(ARETKCREA_NEW_SHOWCASE_SAVE_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li><?php
                            } else {
                                $previewnsaveview = 'none'; 
                            } ?>
                            <li>    
                                <div class="set_crea_showcase_save_btn">
                                    <input type="submit" id="crea_showcase_preview_save_btn" name="showcase_save_btn" class="button button-primary" value="<?php 
                                    if( !empty($showcaseid) && $showcaseid != '' ) { 
                                        echo __(ARETKCREA_LISTING_SHOWCASE_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG); 
                                    } else { 
                                        echo __(ARETKCREA_LISTING_SHOWCASE_SAVE_BTN, ARETKCREA_PLUGIN_SLUG);
                                    } ?>">
                                </div>
                            </li>                           
                        </ul>                    
                        <input type='hidden' name='action' value='showcase-form' />
                        <input type='hidden' name='posttype' value='showcase' /><?php                       
                        if( !empty($showcaseid) && $showcaseid !='' ) { ?>
                            <input type="hidden" name='action-which'  value="edit" />
                            <input type="hidden" name='showcase_ids' id="showcase_ids" value="<?php echo $showcaseid; ?>" ><?php 
                        } else { ?>
                            <input type="hidden" name='action-which' value="add" /><?php 
                        } 
                        /*
                        // Commented this out for now as there is currently only one showcase type.  Will uncomment this once we start adding other showcase types
                        <div id="crea_showcase_type_tab">
                            <div class="crea_showcase_title_tab"><h4><?php echo __(ARETKCREA_NEW_SHOWCASE_TYPE_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4></div>
                            <div class="crea_showcase_tab_content">
                                <div class="crea_showcase_content_html"><a name="crea_showcase_listing_ype" href="javascript:void(0);"><input type="button" class="button button-primary" value="LISTINGS SHOWCASE"></a></div>
                            </div>
                        </div>
                        */ ?>
                        <div id="crea_showcase_feed_tab">
                            <div class="crea_showcase_title_tab">
                                <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_FEED_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            </div>
                            <div class="crea_showcase_content_html"><?php                                                   
                                if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ ?>
                                    <p>
                                        <select class="set_feed_option" id="set_feed_option"name="crea_showcase_feed_ddf_option">
                                            <option value="">Select A CREA DDF&reg; Feed</option><?php
                                                global $wpdb;
                                                $crea_user_listing_detail = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
                                                $sql_select = "SELECT `username`,`ddf_type` FROM `$crea_user_listing_detail`";
                                                $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                                $get_user_listing_results = $wpdb->get_results($sql_prep);                      
                                                if ($get_user_listing_results != '' && !empty($get_user_listing_results)) {
                                                    foreach ($get_user_listing_results as $get_user_listing_values) { 
                                                        $showcse_crea_feed_ddf_type_selected = "";
                                                        if( isset( $showcse_crea_feed_ddf_type ) && !empty( $showcse_crea_feed_ddf_type ) && $get_user_listing_values->username == $showcse_crea_feed_ddf_type ) { 
                                                            $showcse_crea_feed_ddf_type_selected = "selected";
                                                        } ?>
                                                        <option <?php echo $showcse_crea_feed_ddf_type_selected; ?> value="<?php echo $get_user_listing_values->username; ?>"><?php echo $get_user_listing_values->ddf_type . ' (' . $get_user_listing_values->username . ')'; ?></option>
                                                        <?php
                                                    }
                                                } ?>
                                        </select>
                                    </p><?php 
                                } else { ?> 
                                    <p>
                                        <select class="set_feed_option" name="crea_showcase_feed_ddf_option">
                                            <option value="Exclusive Listing">Exclusive Listing</option>
                                        </select>
                                    </p><?php 
                                } //End subscription status condition                   
                                if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ ?>    
                                    <p><?php
                                        $showcse_crea_feed_include_exclude_checked = '';
                                        $showcse_crea_feed_include_exclude = isset($showcse_crea_feed_include_exclude) ? $showcse_crea_feed_include_exclude : '';
                                        if( $showcaseid != ''  && $showcse_crea_feed_include_exclude != '' ) {                          
                                            if ( $showcse_crea_feed_include_exclude == 'no' ) { 
                                                $showcse_crea_feed_include_exclude_checked = '';
                                            } else {
                                                $showcse_crea_feed_include_exclude_checked = 'checked';
                                            }                                       
                                        } ?>
                                        <input type="checkbox" id="showcase_inc_exc_listing_feed" name="crea_showcase_inc_exc_listing_feed" <?php echo $showcse_crea_feed_include_exclude_checked; ?> value="yes">Include the Exclusive Listing
                                    </p><?php 
                                } ?>        
                            </div>
                        </div>                      
                        <div id="crea_showcase_display_tab">
                            <div class="crea_showcase_title_tab">
                                <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_DISPLAY_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            </div>
                            <div class="crea_showcase_theam_content_html"><?php 
                                if( $showcaseid !='' && !empty($showcaseid)) { ?>
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/list-view-img.png'; ?>" width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" <?php if($showcse_crea_display_theams_option == "Listing View") { ?> checked <?php } ?> value="Listing View" class ='search_crea_display_theme_option'>List
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/gried-view-img.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" <?php if($showcse_crea_display_theams_option == "Grid View") { ?> checked <?php } ?>  value="Grid View" class ='search_crea_display_theme_option'>Grid
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/carousel.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" <?php if($showcse_crea_display_theams_option == "Carousel") { ?> checked <?php } ?>  value="Carousel" class ='search_crea_display_theme_option' >Carousel
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/map.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" <?php if($showcse_crea_display_theams_option == "Map") { ?> checked <?php } ?>  value="Map" class ='search_crea_display_theme_option' >Map
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/slider.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" <?php if($showcse_crea_display_theams_option == "Slider") { ?> checked <?php } ?>  value="Slider" class ='search_crea_display_theme_option' >Slider
                                    </div><?php  
                                } else { ?>
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/list-view-img.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" checked value="Listing View" class ='search_crea_display_theme_option'>List
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/gried-view-img.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" value="Grid View" class ='search_crea_display_theme_option'>Grid
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/carousel.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" value="Carousel" class ='search_crea_display_theme_option' >Carousel
                                    </div>  
                                    <div class="crea_display_theams">
                                        <p><strong><?php echo __(ARETKCREA_NEW_SHOWCASE_DISPLAY_MAP_OPTION_CONTENT, ARETKCREA_PLUGIN_SLUG); ?></strong></p>
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/map.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" value="Map" class ='search_crea_display_theme_option' >Map
                                    </div>  
                                    <div class="crea_display_theams">
                                        <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/slider.png'; ?>"  width="298px" height="298px" alt="photo" class="crea-set-images">
                                        <input type="radio" name="crea_showcase_theams_option" value="Slider" class ='search_crea_display_theme_option' >Slider
                                    </div><?php 
                                } ?>
                            </div>
                        </div><?php                     
                        if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  )
                        { ?>
                            <div id="crea_showcase_filter_tab">
                                <input type="hidden" name="crea_default_choosen_option_showcse_filter_hidden_name" value="<?php if($showcaseid != '' && !empty($showcaseid)){ echo '1'; } else { echo '0'; } ?>" class="crea_default_choosen_option_showcse_filter_hidden_name">    
                                <div class="crea_showcase_title_tab">
                                    <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_FILTER_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                </div>                              
                                <div class="crea_showcase_filter_content">
                                    <div class="model"></div>
                                    <div class="crea_showcase_filter_by_other">
                                        <p class="set_filter_by_other">Only show listings added in the last </p><input type="number" min="0" value="<?php echo isset($showcse_crea_filter_by_other_day) ? $showcse_crea_filter_by_other_day : ''; ?>" name="crea_showcase_filter_by_other_days" id="crea_showcase_filter_days"><p class="set_filter_by_other">Days</p>                          
                                        <p class="set_filter_checkbox_include">Only show listings with an upcoming open house <?php 
                                        if( !empty($showcaseid) && $showcaseid !='') { ?>
                                            <input id="crea_checkbox_filter_open_house_id" <?php if( $showcse_crea_filter_inclue_open_house == 'yes') {?> checked value="yes" <?php } else{ ?> value="no" <?php } ?> name="crea_checkbox_open_house_filter" type="checkbox"><?php 
                                        } else { ?>
                                            <input id="crea_checkbox_filter_open_house_id" value="no" name="crea_checkbox_open_house_filter" type="checkbox"><?php 
                                        } ?></p>                                        
                                        <p><label>Filter by Price Range:</label><br />
                                        <label for="showcase_filter_price_min">Min Price:</label> <input type="number" min="0" value="<?php echo $showcase_filter_price_min; ?>" name="showcase_filter_price_min" id="showcase_filter_price_min">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label for="showcase_filter_price_max">Max Price:</label> <input type="number" value="<?php echo $showcase_filter_price_max; ?>" name="showcase_filter_price_max" id="showcase_filter_price_max">
                                        </p>                                        
                                        <p><label for="showcase_filter_property_types">Filter by Property Type:</label><br />
                                        <select name="showcase_filter_property_types[]" id="showcase_filter_property_types" class="showcase_filter_property_types" multiple="true" style="width:400px;" data-placeholder="Select Property Type">                                            
                                            <option value="commercial"<?php if (in_array('commercial', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Commercial</option>
                                            <option value="residential"<?php if (in_array('residential', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Residential</option>
                                            <option value="agriculture"<?php if (in_array('agriculture', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Agriculture</option>
                                            <option value="business"<?php if (in_array('business', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Business</option>
                                            <option value="hospitality"<?php if (in_array('hospitality', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Hospitality</option>
                                            <option value="industrial"<?php if (in_array('industrial', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Industrial</option>
                                            <option value="institutional"<?php if (in_array('institutional', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Institutional</option>
                                            <option value="multi-family"<?php if (in_array('multi-family', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Multi-family</option>
                                            <option value="office"<?php if (in_array('office', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Office</option>
                                            <option value="other"<?php if (in_array('other', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Other</option>
                                            <option value="parking"<?php if (in_array('parking', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Parking</option>
                                            <option value="recreational"<?php if (in_array('recreational', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Recreational</option>
                                            <option value="retail"<?php if (in_array('retail', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Retail</option>
                                            <option value="single family"<?php if (in_array('single family', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Single Family</option>
                                            <option value="vacant land"<?php if (in_array('vacant land', $showcase_filter_property_types)){ echo ' selected="selected"'; } ?>>Vacant Land</option>
                                        </select>
                                        </p>
										<p><label for="showcase_filter_ownership_types">Filter by Ownership Type:</label><br />
                                        <select name="showcase_filter_ownership_types[]" id="showcase_filter_ownership_types" class="showcase_filter_ownership_types" multiple="true" style="width:400px;" data-placeholder="Select Ownership Type">
											<option value="condo"<?php if (in_array('condo', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Condo</option>
											<option value="cooperative"<?php if (in_array('cooperative', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Cooperative</option>
											<option value="freehold"<?php if (in_array('freehold', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Freehold</option>
											<option value="lease"<?php if (in_array('lease', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Lease</option>
											<option value="strata"<?php if (in_array('strata', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Strata</option>
											<option value="timeshare"<?php if (in_array('timeshare', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Timeshare</option>
											<option value="other"<?php if (in_array('other', $showcase_filter_ownership_types)){ echo ' selected="selected"'; } ?>>Other</option>											
                                        </select>
                                        </p>
                                        <p>                                     
                                        <label for="showcase_filter_property_status">Filter by status:</label><br />
                                        <select name="showcase_filter_property_status[]" id="showcase_filter_listing_status" class="showcase_filter_listing_status" multiple="true" style="width:400px;" data-placeholder="Select Status">
                                            <option value="for sale"<?php if (in_array('for sale', $showcase_filter_property_status)){ echo ' selected="selected"'; } ?>>For Sale</option>
                                            <option value="for rent/lease"<?php if (in_array('for rent/lease', $showcase_filter_property_status)){ echo ' selected="selected"'; } ?>>For Rent/Lease</option>
                                            <option value="sold"<?php if (in_array('sold', $showcase_filter_property_status)){ echo ' selected="selected"'; } ?>>Sold</option>
                                        </select>
                                        </p>                                        
                                        <p>
                                        <label for="showcase_filter_listing_agent_ids">Filter by Listing Agent:</label><br /><?php                                  
                                        $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
                                        $sql_select = "SELECT `crea_id`, `crea_agent_name`, `crea_agent_id`, `crea_agent_email`, `crea_agent_modified_date` FROM `$crea_agent_table_name` ORDER BY `crea_agent_name` ASC";
                                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                        $get_agents_results = $wpdb->get_results($sql_prep);    
                                        if (!empty($get_agents_results) && $get_agents_results != '') { ?>
                                            <select name="showcase_filter_listing_agent_ids[]" id="showcase_filter_listing_agent_ids" class="showcase_filter_listing_agent_ids" multiple="true" style="width:400px;" data-placeholder="Select Agent"><?php                                      
                                                $counter = 0;
                                                foreach ($get_agents_results as $get_agents_key => $get_agents_value) {         
                                                    echo '<option value="'. $get_agents_value->crea_agent_id.'"';
                                                    if (in_array($get_agents_value->crea_agent_id, $showcase_filter_listing_agent_ids)){ 
                                                        echo ' selected="selected"'; 
                                                    }
                                                    echo '>'.$get_agents_value->crea_agent_name.'</option>';
                                                } ?>
                                            </select><?php
                                        } ?>
                                        </p>        
                                        <p>
                                        <label for="showcase_filter_listing_province">Filter by Province:</label><br />
                                            <select name="showcase_filter_listing_province[]" id="showcase_filter_listing_province" class="showcase_filter_listing_province" multiple="true" style="width:400px;" data-placeholder="Select Province"><?php
                                                echo '<option value="AB"';
                                                if (in_array('AB', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Alberta</option>';                                       
                                                echo '<option value="BC"';
                                                if (in_array('BC', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>British Columbia</option>';                              
                                                echo '<option value="MB"';
                                                if (in_array('MB', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Manitoba</option>';
                                                echo '<option value="NB"';
                                                if (in_array('NB', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>New Brunswick</option>';
                                                echo '<option value="NL"';
                                                if (in_array('NL', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Newfoundland</option>';
                                                echo '<option value="NS"';
                                                if (in_array('NS', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Nova Scotia</option>';
                                                echo '<option value="NT"';
                                                if (in_array('NT', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Northwest Territories</option>';
                                                echo '<option value="NU"';
                                                if (in_array('NU', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Nunavut</option>';
                                                echo '<option value="ON"';
                                                if (in_array('ON', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Ontario</option>';
                                                echo '<option value="PE"';
                                                if (in_array('PE', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Prince Edward Island</option>';
                                                echo '<option value="QC"';
                                                if (in_array('QC', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Quebec</option>';
                                                echo '<option value="SK"';
                                                if (in_array('SK', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Saskatchewan</option>';  
                                                echo '<option value="YT"';
                                                if (in_array('YT', $showcase_filter_listing_province)){ echo ' selected="selected"'; }
                                                echo '>Yukon</option>'; ?>
                                            </select>
                                        </p>        
                                    </div><?php 
                                    if ($showcse_crea_display_theams_option !== 'Map' ){ ?>
                                        
                                        <div class="showcase_filter_by_map_listing">
                                            <div class="crea_showcase_filter_title"><p>Filter by Map Radius</p></div>
                                            <p>Use this section to filter out listings within a specific radius.</p>
                                            <p>Drag the red marker on the map below to the desired location and select a radius.  If no radius is set, then all listings will be returned from the selected feed.</p>
                                            <div class="crea_google_maps_showcase" id="crea_location_google_maps_feed" style="width:100%;height:350px;"></div><?php
                                            if( empty($showcse_crea_filter_google_map_latitude) ) {
                                                $showcse_crea_filter_google_map_latitude = "57.67807921815639";
                                            }
                                            if( empty($showcse_crea_filter_google_map_longitude) ) {
                                                $showcse_crea_filter_google_map_longitude = "-101.80516868749999";
                                            }
                                            if( !is_numeric($showcse_crea_filter_google_map_zoom) || empty($showcse_crea_filter_google_map_zoom) ) {
                                                $showcse_crea_filter_google_map_zoom = "5";
                                            }
                                            if( !is_numeric($showcse_crea_filter_by_map_km) || $showcse_crea_filter_by_map_km == '0' ){ 
                                                $showcse_crea_filter_by_map_km = 0;
                                            }                                       
                                            ?>                                                      
                                            <p>
                                                Filter Radius
                                                <input type="number" min="0" step="0.5" value="<?php echo $showcse_crea_filter_by_map_km; ?>" name="crea_filter_by_map_km" id="crea_filter_by_map_km"> KM
                                            </p>
                                            <input type="hidden" name="crea_filter_google_map_latitude"  value="<?php echo  $showcse_crea_filter_google_map_latitude ?>" id="crea_googlemap_filter_latitude">
                                            <input type="hidden" name="crea_filter_google_map_longitude"  value="<?php echo $showcse_crea_filter_google_map_longitude; ?>" id="crea_googlemap_filter_longitude">
                                            <input type="hidden" name="showcase_filter_google_map_zoom"  value="<?php echo $showcse_crea_filter_google_map_zoom; ?>" id="showcase_filter_google_map_zoom">
                                        </div><?php // END filter by map 
                                    } 
                                    /* Listing filters..*/ ?>
                                    <div class="showcase_filter_by_listing">
                                        <div class="set_showcase_option_filter">
                                            <div class="crea_showcase_filter_title"><p>Filter by Board</p></div>
                                            <p class="select_board_option">[ <?php
                                            if ( $showcaseid != '' && !empty($showcaseid) && isset( $showcse_crea_filter_brokerage_hidden_name ) && !empty( $showcse_crea_filter_brokerage_hidden_name ) ){ 
                                                echo 'Click here to re-load available data';
                                            } else {
                                                echo 'Click here to load available Boards';
                                            } ?> ]</p>
                                            <select name="crea_filter_brokerage[]" id="crea_showcse_brokerage_filter" class="crea_showcse_brokerage_filter" multiple="true" data-placeholder="Select Broard" style="width:400px;<?php
                                                if ( $showcaseid != '' && !empty($showcaseid) && isset( $showcse_crea_filter_brokerage_hidden_name ) && !empty( $showcse_crea_filter_brokerage_hidden_name ) ){ 
                                                    echo '">';
                                                    $results_board = explode(';', $showcse_crea_filter_brokerage_hidden_name); 
                                                    foreach ( $results_board as $results ){ 
                                                        if( !empty( $results) ){ 
                                                            $results = explode(':', $results); ?>
                                                            <option value="<?php echo $results[0]; ?>" selected="selected"><?php  echo $results[1]; ?></option><?php 
                                                        } 
                                                    }  
                                                } else {
                                                    echo ' visibility:hidden;">';
                                                }?>
                                            </select>
                                            <p>Leave blank to display all listings belonging to the selected feed(s).</p>
                                            <input type="hidden" name="crea_filter_brokerage_hidden_name" class="crea_filter_brokerage_hidden_name" value="<?php echo $showcse_crea_filter_brokerage_hidden_name; ?>">
                                        </div>                                      
                                        <?php // Office Filters 
                                        /* new feature, commenting out for now until further testing complete, 
                                        ?>
                                        <div id="crea_filter_office_wrap" class="set_showcase_option_filter" style="visibility:hidden">
                                            <div class="crea_showcase_filter_title"><p>Filter by Office</p></div>
                                            <select name="crea_filter_office[]" id="crea_showcse_office_filter" multiple="true" style="width:400px; margin-top: 16px;" data-placeholder="Select Office"><?php
                                            if( $showcaseid != '' && !empty($showcaseid) && isset( $showcse_crea_filter_office_hidden_name ) && !empty( $showcse_crea_filter_office_hidden_name )){ 
                                                $results_Office = explode(';', $showcse_crea_filter_office_hidden_name); 
                                                foreach ( $results_Office as $result_office ){ 
                                                    $result_office = explode(':', $result_office);?>
                                                    <option value="<?php echo $result_office[0]; ?>" selected="selected"><?php  echo $result_office[1]?></option><?php 
                                                } 
                                            } ?>
                                            </select>
                                            <p>Leave blank to display all listings belonging to the selected board(s).</p>
                                            <input type="hidden" name="crea_showcse_office_filter_hidden_name" class="crea_showcse_office_filter_hidden_name" value="<?php echo $showcse_crea_filter_office_hidden_name; ?>">
                                        </div>                                      
                                        <?php # Agent Filters   ?>                                  
                                        <div id="crea_filter_agent_wrap" class="set_showcase_option_filter" style="visibility:hidden">
                                            <div class="crea_showcase_filter_title"><p>Filter by Agent</p></div>
                                            <select name="crea_filter_agent_name[]" id="crea_showcse_agent_name_filter" class="crea_showcse_agent_name_filter" multiple="true" style="width:400px; margin-top: 16px;" data-placeholder="Select Agent">
                                                <?php
                                                if ( $showcaseid != '' && !empty($showcaseid) && isset( $showcse_crea_filter_agents_hidden_name ) && !empty( $showcse_crea_filter_agents_hidden_name ) ){
                                                    $results_agent = explode(';', $showcse_crea_filter_agents_hidden_name); 
                                                    foreach ( $results_agent as $result_agent ){ 
                                                        if( !empty( $result_agent) ){ 
                                                            $result_agent = explode(':', $result_agent); ?>
                                                            <option value="<?php echo $result_agent[0]; ?>" selected="selected"><?php  echo $result_agent[1]; ?></option><?php 
                                                        } 
                                                    }  
                                                } ?>
                                            </select>
                                            <p>Leave blank to display all listings belonging to the selected office(s).</p>
                                            <input type="hidden" name="crea_showcse_agent_name_filter_hidden_name" class="crea_showcse_agent_name_filter_hidden_name" value="<?php echo $showcse_crea_filter_agents_hidden_name; ?>">
                                        </div>
                                        */ ?>
                                    </div><?php // END filter by listing                            
                                    ?>
                                </div><?php // END crea_showcase_filter_content ?>
                            </div><?php 
                        }                       
                        # END Showcase Filters
                        #==============================
                        
                        
                        #==============================
                        # Showcase Settings Tab Start ?>                    
                        <div id="crea_showcase_setting_tab">
                            <div class="crea_showcase_title_tab">
                                <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            </div>
                            <div class="crea_showcase_settings_content">
                                <!-- div for listing view setting !-->
                                <?php 
                                $listingshowcasename = '';
                                $listingviewsearchbar = '';
                                $listingsearchposition = '';
                                $listingviewtop = '';
                                $listingviewright = '';
                                $maxlistingonpage = '';
                                $listingopenhouse_yes_or_not ='';
                                $listingstatus_yes_or_not ='';
                                $Listing_search_simple_enable_or_disable ='';                               
                                if( !empty($showcse_crea_serializable_listing_array) && $showcse_crea_serializable_listing_array !='' ) { 
                                    $listingshowcasename = !empty($showcse_crea_serializable_listing_array['listingshowcasename']) ? $showcse_crea_serializable_listing_array['listingshowcasename'] :'';
                                    $listingviewsearchbar = !empty($showcse_crea_serializable_listing_array['listingviewsearchbar']) ?  $showcse_crea_serializable_listing_array['listingviewsearchbar'] :'yes';
                                    $listingsearchposition = !empty($showcse_crea_serializable_listing_array['listingsearchposition']) ?  $showcse_crea_serializable_listing_array['listingsearchposition'] :'';
                                    $listingviewtop = !empty($showcse_crea_serializable_listing_array['listingviewtop']) ?  $showcse_crea_serializable_listing_array['listingviewtop'] :'';
                                    $listingviewright = !empty($showcse_crea_serializable_listing_array['listingviewright']) ?  $showcse_crea_serializable_listing_array['listingviewright'] :'';
                                    $maxlistingonpage = !empty($showcse_crea_serializable_listing_array['maxlistingonpage']) ?  $showcse_crea_serializable_listing_array['maxlistingonpage'] :'';                                   
                                    $listingopenhouse_yes_or_not = !empty($showcse_crea_serializable_listing_array['listingopenhouse']) ?  $showcse_crea_serializable_listing_array['listingopenhouse'] :'yes';
                                    $listingstatus_yes_or_not    = !empty($showcse_crea_serializable_listing_array['listingstatus']) ?  $showcse_crea_serializable_listing_array['listingstatus'] :'yes';
                                    $Listing_search_simple_enable_or_disable = !empty($showcse_crea_serializable_listing_array['Listing_search_simple_enable_or_disable']) ?  $showcse_crea_serializable_listing_array['Listing_search_simple_enable_or_disable'] :'no';                                    
                                } else { 
                                    $listingshowcasename = '';
                                    $listingviewsearchbar = 'yes';
                                    $listingsearchposition = '';
                                    $listingviewtop = '';
                                    $listingviewright = '';
                                    $maxlistingonpage = '';
                                    $listingopenhouse_yes_or_not ='yes';
                                    $listingstatus_yes_or_not ='yes';
                                    $Listing_search_simple_enable_or_disable ='no';                  
                                }               
                                
                                // Listings Showcase settings ?>                            
                                <div id="crea_showcase_setting_listing_view_display" class="showcase_setting_listing_view_display" style="display:none">
                                    <div class="crea_showcase_setting_sorting_title"><p>Default Sorting</p></div>
                                    <div class="crea_showcase_sorting_content">                            
                                        <select name="crea_sorting_listing_showcase_name" id="crea_showcase_option_listing_id">
                                            <option value="">Select Default Sorting</option>
                                            <option <?php if($listingshowcasename == "Price descending" || $listingshowcasename == "" ) { echo "selected";} ?> value="Price descending">Price descending</option>
                                            <option <?php if($listingshowcasename == "Price ascending") { echo "selected";} ?> value="Price ascending">Price ascending</option>
                                            <option <?php if($listingshowcasename == "Listing date - newest to oldest") { echo "selected";} ?> value="Listing date - newest to oldest">Listing date - newest to oldest</option>
                                            <option <?php if($listingshowcasename == "Listing date - oldest to newest") { echo "selected";} ?> value="Listing date - oldest to newest">Listing date - oldest to newest</option>
                                            <option <?php if($listingshowcasename == "Random") { echo "selected";} ?> value="Random">Random</option>
                                        </select>
                                    </div>
                                    <div class="crea_showcase_setting_sorting_title">
                                        <p>Search Bar <label>
                                        <input type="radio" name="listing_view_setiing" class="listing_view_search_option_selection" id="listing_view_showcase_yes"  <?php if( $listingviewsearchbar == "yes" ) { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" name="listing_view_setiing" class="listing_view_search_option_selection" id="listing_view_showcase_no" <?php if( $listingviewsearchbar == "no") { echo"checked"; } ?> value="no">No
                                        </label></p>
                                    </div>                                  
                                    <table class="enable_search_position" >
                                        <p class="enable_search_position"></p>                                  
                                        <tr>
                                            <td>
                                                <p><input type="radio"  name="listing_view_setiing_status_of_search" <?php if( $Listing_search_simple_enable_or_disable === "yes" ) { echo"checked"; } ?> value="yes"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_SIMPLE, ARETKCREA_PLUGIN_SLUG); ?></p>
                                                    
                                                <p><input type="radio" name="listing_view_setiing_status_of_search" <?php if( $Listing_search_simple_enable_or_disable === "no" ) { echo"checked"; } ?> value="no"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_DETAIL, ARETKCREA_PLUGIN_SLUG); ?></p>
                                            </td>
                                        </tr>                                       
                                    </table>
                                    <p>
                                        <span class="open_house_name">Open House</span><label>
                                        <input type="radio" class="is_enable_open_house" name="listing_view_setiing_open_house" <?php if( $listingopenhouse_yes_or_not == "yes" ) { echo"checked"; } ?> id="listing_view_showcase_open_house_yes"  value="yes">Yes
                                        <input type="radio" class="is_enable_open_house" name="listing_view_setiing_open_house"  <?php if( $listingopenhouse_yes_or_not == "no") { echo"checked"; } ?> id="listing_view_showcase_open_house_no"  value="no">No</label>
                                    </p>
                                    <p>
                                        <span class="status_setting_name">Status</span><label>
                                        <input type="radio" class="is_enable_status" name="listing_view_setiing_status"  id="listing_view_showcase_status_yes" <?php if( $listingstatus_yes_or_not == "yes") { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" class="is_enable_status" name="listing_view_setiing_status" id="listing_view_showcase_status_no"  <?php if( $listingstatus_yes_or_not == "no") { echo"checked"; } ?>  value="no">No</label>
                                    </p>                                    
                                    <table>
                                        <tr>
                                            <td>Max listings on a page</td>
                                            <td>
                                                <input min="1" max="20" maxlength="2" type="number" value="<?php if(!empty( $maxlistingonpage )) { echo $maxlistingonpage; } else { echo "20"; } ?>" name="Max_listings_on_a_page" id="listing_view_showcase_max_listing">Default 20
                                            </td>
                                        </tr>
                                    </table>                            
                                </div><?php 
                                # End Listings view showcase settings
                                
                                # Grid view showcase setings
                                $gridviewshowcasename ='';
                                $gridviewsearchbar ='';
                                $gridviewsearchpostion ='';
                                $gridviewtop ='';
                                $gridviewright ='';
                                $maxgridviewselectedrow ='';
                                $maxgridviewselectedcolumn ='';
                                $gridviewopenhouse_yew_or_not ='';
                                $gridviewstatus_yes_or_not ='';
                                $grid_view_setiing_status_search_simple_or_datail ='';
								
								$showcase_Grid_listings_results_total = 0;
								$showcase_Grid_listings_batch_size = 20;
									
                                if( !empty($showcse_crea_serializable_grid_array) && $showcse_crea_serializable_grid_array !='' ) {     
                                    $gridviewshowcasename = !empty($showcse_crea_serializable_grid_array['gridviewshowcasename']) ? $showcse_crea_serializable_grid_array['gridviewshowcasename'] :'';
                                    $gridviewsearchbar = !empty($showcse_crea_serializable_grid_array['gridviewsearchbar']) ? $showcse_crea_serializable_grid_array['gridviewsearchbar'] :'yes';
                                    $gridviewsearchpostion = !empty($showcse_crea_serializable_grid_array['gridviewsearchpostion']) ? $showcse_crea_serializable_grid_array['gridviewsearchpostion'] :'';
                                    $gridviewtop = !empty($showcse_crea_serializable_grid_array['gridviewtop']) ? $showcse_crea_serializable_grid_array['gridviewtop'] :'';
                                    $gridviewright = !empty($showcse_crea_serializable_grid_array['gridviewright']) ? $showcse_crea_serializable_grid_array['gridviewright'] :'';
                                    $maxgridviewselectedrow = !empty($showcse_crea_serializable_grid_array['maxgridviewselectedrow']) ? $showcse_crea_serializable_grid_array['maxgridviewselectedrow'] :'yes';
                                    $maxgridviewselectedcolumn = !empty($showcse_crea_serializable_grid_array['maxgridviewselectedcolumn']) ? $showcse_crea_serializable_grid_array['maxgridviewselectedcolumn'] :'yes';                            
                                    $gridviewopenhouse_yew_or_not = !empty($showcse_crea_serializable_grid_array['gridviewopenhouse']) ? $showcse_crea_serializable_grid_array['gridviewopenhouse'] :'';
                                    $gridviewstatus_yes_or_not = !empty($showcse_crea_serializable_grid_array['gridviewstatus']) ? $showcse_crea_serializable_grid_array['gridviewstatus'] :'';
                                    $grid_view_setiing_status_search_simple_or_datail = !empty($showcse_crea_serializable_grid_array['grid_view_setiing_status_search_simple_or_datail']) ? $showcse_crea_serializable_grid_array['grid_view_setiing_status_search_simple_or_datail'] :'';  
									
									$showcase_Grid_listings_results_total = !empty($showcse_crea_serializable_grid_array['Grid_listings_results_total']) ? $showcse_crea_serializable_grid_array['Grid_listings_results_total'] : 0;
									
									$showcase_Grid_listings_batch_size = !empty($showcse_crea_serializable_grid_array['Grid_listings_batch_size']) ? $showcse_crea_serializable_grid_array['Grid_listings_batch_size'] : 20;
									
                                } else {                                    
                                    $gridviewshowcasename ='';
                                    $gridviewsearchbar ='yes';
                                    $gridviewsearchpostion ='';
                                    $gridviewtop ='';
                                    $gridviewright ='';
                                    $maxgridviewselectedrow ='';
                                    $maxgridviewselectedcolumn ='';
                                    $gridviewopenhouse_yew_or_not ='yes';
                                    $gridviewstatus_yes_or_not ='yes';
                                    $grid_view_setiing_status_search_simple_or_datail ='no'; 
									
									$showcase_Grid_listings_results_total = 0;
									$showcase_Grid_listings_batch_size = 20;
                                } ?> 
                                <div id="crea_showcase_setting_grid_view_display" class="showcase_setting_grid_view_display" style="display:none">
                                    <p>
                                        <label for="max_grid_selectd_column">Max number of columns to use:</label><br />
                                        <select name="crea_max_grid_selected_column" id="max_grid_selectd_column">
                                            <option selected="" value="Select Column">Select Column</option><?php 
                                            for ($maxgridcol = 1 ; $maxgridcol <=4 ; $maxgridcol++  ) { ?>
                                                <option <?php if( $maxgridviewselectedcolumn == $maxgridcol || ( empty( $maxgridviewselectedcolumn ) && $maxgridcol == '4'  )) { echo "selected"; } ?> value="<?php echo $maxgridcol; ?>"><?php echo $maxgridcol; ?></option><?php 
                                            } ?>
                                        </select>
                                    </p>
									 <p>
                                        <label for="Grid_listings_results_total">Total number of listings to return:</label><br />
                                        <input type="number" min="0" maxlength="2" name="Grid_listings_results_total"value="<?php echo $showcase_Grid_listings_results_total; ?>" id="Grid_listings_results_total" > <span>Set to zero to return all listings</span><br />
                                    </p>
									<p>
                                        <label for="Grid_listings_batch_size">Number of listings to show per page</label><br />
                                        <input type="number" min="1" maxlength="2" name="Grid_listings_batch_size"value="<?php echo $showcase_Grid_listings_batch_size; ?>" id="Grid_listings_batch_size"> <span>Default = 20</span><br />
                                    </p>
									<p><span>Note: If the 'Total number of listings to return' is greater than the 'Number of listings to show per page' then a pagination section will appear below the listings.</span></p>
                                    <p>
                                        <span class="open_house_name">Display Open House Information:</span><br />
                                        <label>
                                        <input type="radio" name="grid_view_setiing_open_house" <?php if( $gridviewopenhouse_yew_or_not === "yes" ) { echo"checked"; } ?> id="listing_view_showcase_open_house_yes"  value="yes">Yes
                                        <input type="radio" name="grid_view_setiing_open_house"  <?php if( $gridviewopenhouse_yew_or_not === "no") { echo"checked"; } ?> id="listing_view_showcase_open_house_no"  value="no">No
                                        </label>
                                    </p>
                                    <p>
                                        <span class="status_setting_name">Display Listing Status:</span><br />
                                        <label>
                                        <input type="radio" name="grid_view_setiing_status"  id="grid_view_showcase_status_yes" <?php if( $gridviewstatus_yes_or_not === "yes" ) { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" name="grid_view_setiing_status" id="grid_view_showcase_status_no"  <?php if( $gridviewstatus_yes_or_not === "no") { echo"checked"; } ?>  value="no">No
                                        </label>
                                    </p>                                    
                                    <p>
                                        <label for="crea_showcase_option_grid_id">Default Sorting:</label><br />
                                        <select name="crea_sorting_showcase_grid_name" id="crea_showcase_option_grid_id">
                                            <option value="">Select Default Sorting</option>
                                            <option <?php if( $gridviewshowcasename == "Price descending" || $gridviewshowcasename == "" ) { echo"selected";} ?> value="Price descending">Price descending</option>
                                            <option <?php if( $gridviewshowcasename == "Price ascending" ) { echo"selected";} ?> value="Price ascending">Price ascending</option>
                                            <option <?php if( $gridviewshowcasename == "Listing date - newest to oldest" ) { echo"selected";} ?> value="Listing date - newest to oldest">Listing date - newest to oldest</option>
                                            <option <?php if( $gridviewshowcasename == "Listing date - oldest to newest" ) { echo"selected";} ?> value="Listing date - oldest to newest">Listing date - oldest to newest</option>
                                            <option <?php if( $gridviewshowcasename == "Random" ) { echo"selected";} ?> value="Random">Random</option>
                                        </select>
                                    </p>
                                    <p>
                                        <span class="status_setting_name">Display listings search form:</span><br />
                                        <label>                                     
                                        <input type="radio" name="grid_search_view_setiing" class="grid_view_search_option_selection" id="grid_view_search_option_selection_yes" <?php if( $gridviewsearchbar === 'yes' ){ echo "checked";} ?>  value="yes">Yes
                                        <input type="radio" name="grid_search_view_setiing" class="grid_view_search_option_selection" id="grid_view_search_option_selection_no" <?php if( $gridviewsearchbar === 'no'){  echo "checked";} ?> value="no">No
                                        </label>
                                    </p>
                                    <table class="enable_grid_search_position">
                                        <tr>
                                            <td>
                                                <input type="radio" name="grid_view_setiing_status_search" <?php if( $grid_view_setiing_status_search_simple_or_datail == "yes" ) { echo"checked"; } ?>  value="yes"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_SIMPLE, ARETKCREA_PLUGIN_SLUG); ?><br />
                                                <input type="radio" name="grid_view_setiing_status_search" <?php if( $grid_view_setiing_status_search_simple_or_datail == "no") { echo"checked"; } ?>   value="no"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_DETAIL, ARETKCREA_PLUGIN_SLUG); ?>
                                            </td>
                                        </tr>                                           
                                    </table>                                    
                                </div><?php 
                                # END Grid view showcase setings
                          
                                #-------------------------------------------------
                                # carousel view showcase settings ?>
                                <div id="crea_showcase_setting_carousel_view_display" class="showcase_setting_carousel_view_display" style="display:none"><?php 
                                    $showcase_carousel_sorting_name ='';
                                    $showcase_carousel_price ='';
                                    $showcase_carousel_status ='';
                                    $showcase_carousel_open_house_info ='';
                                    $showcase_carousel_min_listing ='';
                                    $showcase_carousel_max_listing ='';                                              
                                    if(!empty($showcse_crea_serializable_carousel_array) && $showcse_crea_serializable_carousel_array !='' ) {  
                                        $showcase_carousel_sorting_name = !empty($showcse_crea_serializable_carousel_array['carouselshowcasename']) ? $showcse_crea_serializable_carousel_array['carouselshowcasename'] :'';
                                        $showcase_carousel_price = !empty($showcse_crea_serializable_carousel_array['carouselshowcasenameprice']) ? $showcse_crea_serializable_carousel_array['carouselshowcasenameprice'] :'yes';
                                        $showcase_carousel_status = !empty($showcse_crea_serializable_carousel_array['carouselshowcasenamestatus']) ? $showcse_crea_serializable_carousel_array['carouselshowcasenamestatus']:'yes';
                                        $showcase_carousel_open_house_info = !empty($showcse_crea_serializable_carousel_array['carouselshowcasenameopenhouseinfo']) ?  $showcse_crea_serializable_carousel_array['carouselshowcasenameopenhouseinfo']:'yes';
                                        $showcase_carousel_min_listing = !empty($showcse_crea_serializable_carousel_array['minlistingcarouselshowcasename']) ?  $showcse_crea_serializable_carousel_array['minlistingcarouselshowcasename']:'4';
                                        $showcase_carousel_max_listing = !empty($showcse_crea_serializable_carousel_array['maxlistingcarouselshowcasename']) ?  $showcse_crea_serializable_carousel_array['maxlistingcarouselshowcasename']:'20';                                       
                                        $showcase_carousel_pagination_dots = !empty($showcse_crea_serializable_carousel_array['listing_carousel_pagination_dots']) ?  $showcse_crea_serializable_carousel_array['listing_carousel_pagination_dots']:'true';                                     
                                        $showcase_carousel_display_prevnext = !empty($showcse_crea_serializable_carousel_array['listing_carousel_display_prevnext']) ?  $showcse_crea_serializable_carousel_array['listing_carousel_display_prevnext']:'false';                                     
                                        $showcase_carousel_scroll_speed = !empty($showcse_crea_serializable_carousel_array['listing_carousel_scroll_speed']) ?  $showcse_crea_serializable_carousel_array['listing_carousel_scroll_speed']:'3000';                                      
                                    } else {                                    
                                        $showcase_carousel_sorting_name ='';
                                        $showcase_carousel_price ='yes';
                                        $showcase_carousel_status ='yes';
                                        $showcase_carousel_open_house_info ='yes';
                                        $showcase_carousel_min_listing ='4';
                                        $showcase_carousel_max_listing ='20';
                                        $showcase_carousel_pagination_dots = 'true';
                                        $showcase_carousel_display_prevnext = 'false';
                                        $showcase_carousel_scroll_speed = '3000';
                                    } ?>                                
                                    <p>             
                                        <label for="crea_showcase_carousel_option_carousel_id">Default Sorting:</label><br /> 
                                        <select name="crea_sorting_showcase_carousel_name" id="crea_showcase_carousel_option_carousel_id">                                      
                                            <option value="">Select Default Sorting</option>
                                            <option <?php if( $showcase_carousel_sorting_name == "Price descending" || $showcase_carousel_sorting_name == "" ) { echo "selected"; } ?> value="Price descending">Price descending</option>
                                            <option <?php if( $showcase_carousel_sorting_name == "Price ascending") { echo "selected"; } ?> value="Price ascending">Price ascending</option>
                                            <option <?php if( $showcase_carousel_sorting_name == "Listing date - newest to oldest") { echo "selected"; } ?> value="Listing date - newest to oldest">Listing date - newest to oldest</option>
                                            <option <?php if( $showcase_carousel_sorting_name == "Listing date - oldest to newest") { echo "selected"; } ?> value="Listing date - oldest to newest">Listing date - oldest to newest</option>
                                            <option <?php if( $showcase_carousel_sorting_name == "Random") { echo "selected"; } ?> value="Random">Random</option>          
                                        </select>
                                    </p>                                    
                                    <p>
                                        <span>Display Listing Price:</span><br />
                                        <label>
                                        <input type="radio" name="listing_carousel_show_price" <?php if( $showcase_carousel_price === "yes" ) { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" name="listing_carousel_show_price"  <?php if( $showcase_carousel_price === "no") { echo"checked"; } ?> value="no">No
                                        </label>
                                    </p>                                    
                                    <p>
                                        <span>Display Listing Status:</span><br />
                                        <label>
                                        <input type="radio" name="listing_carousel_show_status" <?php if( $showcase_carousel_status === "yes" ) { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" name="listing_carousel_show_status"  <?php if( $showcase_carousel_status === "no") { echo"checked"; } ?> value="no">No
                                        </label>
                                    </p>                                    
                                    <p>
                                        <span>Display Open House Information:</span><br />
                                        <label>
                                        <input type="radio" name="listing_carousel_show_open_house_info" <?php if( $showcase_carousel_open_house_info === "yes" ) { echo"checked"; } ?> value="yes">Yes
                                        <input type="radio" name="listing_carousel_show_open_house_info"  <?php if( $showcase_carousel_open_house_info === "no") { echo"checked"; } ?> value="no">No
                                        </label>
                                    </p>
                                    <hr />
                                    <p>
                                        <label for="min_of_listing_carousel">Mimimum number of listings for Carousel to appear:</label><br />
                                        <select id="min_of_listing_carousel" name="crea_min_of_listing_carousel">
                                            <option value="4">Select Min Value</option><?php
                                                for ( $i=1 ; $i<=4; $i++ ) 
                                                { 
                                                    if( !empty($showcaseid) && $showcaseid!='' ) 
                                                    {  ?>
                                                        <option <?php if( $i == $showcase_carousel_min_listing ) { echo 'selected="selected" '; } ?>value="<?php echo $i; ?>"><?php echo $i; ?></option><?php  
                                                    } else { ?>
                                                        <option <?php if( $i == 4 ) { echo 'selected="selected" '; } ?>value="<?php echo $i; ?>"><?php echo $i; ?></option><?php 
                                                    } 
                                                } ?>
                                        </select> <span>Default = 4</span>
                                    </p>
                                    <p>
                                        <label for="Max_of_listings_for_Carousel">Maxmimum number of listings to show in carousel:</label><br />
                                        <input type="number" min="1" maxlength="2" name="Max_of_listings_for_Carousel"value="<?php echo $showcase_carousel_max_listing; ?>" id="Max_of_listings_for_Carousel" > <span>Default = 20</span><br />
                                        <span>Note, displaying a large number of listings in the carousel may cause page load delays and browser crashes.</span>
                                    </p>
                                    <p>
                                        <span>Display Carousel Pagination Dots:</span><br />
                                        <label>
                                        <input type="radio" name="listing_carousel_pagination_dots" <?php if( $showcase_carousel_pagination_dots === "true" ) { echo"checked"; } ?> value="true">Yes
                                        <input type="radio" name="listing_carousel_pagination_dots"  <?php if( $showcase_carousel_pagination_dots === "false") { echo"checked"; } ?> value="false">No
                                        </label>
                                    </p>                                    
                                    <p>
                                        <span>Display Carousel next/prev buttons:</span><br />
                                        <label>
                                        <input type="radio" name="listing_carousel_display_prevnext" <?php if( $showcase_carousel_display_prevnext === "true" ) { echo"checked"; } ?> value="true">Yes
                                        <input type="radio" name="listing_carousel_display_prevnext"  <?php if( $showcase_carousel_display_prevnext === "false") { echo"checked"; } ?> value="false">No
                                        </label>
                                    </p>
                                    <p>
                                        <label for="listing_carousel_scroll_speed">Carousel Scroll Speed in milliseconds:</label><br />
                                        <input type="number" min="1" maxlength="5" name="listing_carousel_scroll_speed"value="<?php echo $showcase_carousel_scroll_speed;  ?>" id="listing_carousel_scroll_speed" > <span>Default = 3000 (3 seconds)</span>
                                    </p>
                                </div><?php
                                # END carousel view showcase settings
                                #-----------------------------------------------------------

                                #-----------------------------------------------------------
                                # Slider view showcase settings
                                $sildersortingshowcasename ='';
                                $slidershowcaseshowprice ='';
                                $slidershowcaseshowstatus ='';
                                $slidershowcaseopenhouseinfo ='';
                                $minslidershowcaselisting ='';
                                $maxslidershowcaselisting ='';                              
                                if( $showcse_crea_serializable_slider_array !='' && !empty($showcse_crea_serializable_slider_array) ){
                                    $sildersortingshowcasename = !empty($showcse_crea_serializable_slider_array['sildersortingshowcasename']) ? $showcse_crea_serializable_slider_array['sildersortingshowcasename'] :'';
                                    $slidershowcaseshowprice = !empty($showcse_crea_serializable_slider_array['slidershowcaseshowprice']) ? $showcse_crea_serializable_slider_array['slidershowcaseshowprice'] :'yes';
                                    $slidershowcaseshowstatus = !empty($showcse_crea_serializable_slider_array['slidershowcaseshowstatus']) ? $showcse_crea_serializable_slider_array['slidershowcaseshowstatus'] :'yes';
                                    $slidershowcaseopenhouseinfo = !empty($showcse_crea_serializable_slider_array['slidershowcaseopenhouseinfo']) ? $showcse_crea_serializable_slider_array['slidershowcaseopenhouseinfo'] : 'yes';
                                    $minslidershowcaselisting = !empty($showcse_crea_serializable_slider_array['minslidershowcaselisting']) ? $showcse_crea_serializable_slider_array['minslidershowcaselisting'] :'';
                                    $maxslidershowcaselisting = !empty($showcse_crea_serializable_slider_array['maxslidershowcaselisting']) ? $showcse_crea_serializable_slider_array['maxslidershowcaselisting'] :'';                          
                                } else {  
                                    $sildersortingshowcasename ='';
                                    $slidershowcaseshowprice ='yes';
                                    $slidershowcaseshowstatus ='yes';
                                    $slidershowcaseopenhouseinfo ='yes';
                                    $minslidershowcaselisting ='';
                                    $maxslidershowcaselisting ='';

                                } ?>                            
                                <!-- start div for slider view display !-->
                                <div id="crea_showcase_setting_slider_view_display" class="showcase_setting_slider_view_display" style="display:none"> 
                                    <div class="crea_showcase_setting_sorting_title"><p>Default Sorting</p></div>
                                    <div class="crea_showcase_sorting_content">
                                        <select name="crea_sorting_showcase_slider_display_name" id="crea_sorting_showcase_slider_display_name">
                                            <option value="">Select Default Sorting</option>
                                            <option <?php if( $sildersortingshowcasename == "Price descending" || $sildersortingshowcasename == "") { echo"selected";} ?> value="Price descending">Price descending</option>
                                            <option <?php if( $sildersortingshowcasename == "Price ascending" ) { echo"selected";} ?> value="Price ascending">Price ascending</option>
                                            <option <?php if( $sildersortingshowcasename == "Listing date - newest to oldest" ) { echo"selected";} ?> value="Listing date - newest to oldest">Listing date - newest to oldest</option>
                                            <option <?php if( $sildersortingshowcasename == "Listing date - oldest to newest" ) { echo"selected";} ?> value="Listing date - oldest to newest">Listing date - oldest to newest</option>
                                            <option <?php if( $sildersortingshowcasename == "Random" ) { echo"selected";} ?> value="Random">Random</option>
                                        </select>                                       
                                        <table width="100%" class="slider_setting_display">
                                            <tr> 
                                                <td class="slider_left">Show Price</td>
                                                <td class="slider_right"><input type="checkbox" name="listing_slider_show_price" id="listing_slider_show_price" <?php if( $slidershowcaseshowprice == 'yes' ) { ?> checked value="yes" <?php } else { ?> value="no" <?php } ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="slider_left">Show Status </td>
                                                <td class="slider_right"><input type="checkbox" name="listing_slider_show_status" id="listing_slider_show_status"<?php if( $slidershowcaseshowstatus == 'yes'  ) { ?> checked value="yes" <?php } else { ?> value="no" <?php } ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="slider_left">Show Open House Info</td>
                                                <td class="slider_right"><input type="checkbox" name="listing_slider_show_open_house_info" id="listing_slider_show_open_house_info" <?php if( $slidershowcaseopenhouseinfo == 'yes'  ) { ?> checked value="yes" <?php } else { ?> value="no" <?php } ?>></td>
                                            </tr>
                                            <tr>
                                                <td class="slider_left">Max # of listings loaded for Slider</td><?php                   
                                                if( !empty( $maxslidershowcaselisting ) && $maxslidershowcaselisting == ''){ 
                                                    $maxslidershowcaselisting_results = $maxslidershowcaselisting;
                                                }else { 
                                                    $maxslidershowcaselisting_results = "20";
                                                }?>
                                                <td class="slider_right"><input type="number" min="1" maxlength="2" value="<?php echo $maxslidershowcaselisting_results; ?>" name="max_of_listings_for_slider" id="max_of_listings_for_slider" ><p class="set_slider_default_value">Default =20</p></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div><?php
                                
                                # END carousel view showcase settings
                                #------------------------------------                               
                                
                                #------------------------------------
                                # Map view showcase settings                                    
                                
                                $mapfilterlatitude = '57.67807921815639';
                                $mapfilterlongitude = '-101.80516868749999';
                                $showcasemapimagezoom = '6';
                                $mapviewdisplayhight ='600';
                                $mapviewdisplaysearch_bar_status ='yes';
                                $mapviewdisplayadvancesearch_bar_status ='yes';                         
                                if( !empty($showcse_crea_serializable_map_array ) && $showcse_crea_serializable_map_array !='' ) 
                                {       
                                    $mapfilterlatitude = !empty($showcse_crea_serializable_map_array['mapfilterlatitude']) ? $showcse_crea_serializable_map_array['mapfilterlatitude'] :'57.67807921815639';
                                    $mapfilterlongitude = !empty($showcse_crea_serializable_map_array['mapfilterlongitude']) ? $showcse_crea_serializable_map_array['mapfilterlongitude'] :'-101.80516868749999';
                                    $showcasemapimagezoom = !empty($showcse_crea_serializable_map_array['showcasemapimagezoom']) ? $showcse_crea_serializable_map_array['showcasemapimagezoom'] :'6';
                                    $mapviewdisplayhight = !empty($showcse_crea_serializable_map_array['mapviewdisplayhight']) ? $showcse_crea_serializable_map_array['mapviewdisplayhight'] :'600';
                                    $mapviewdisplaysearch_bar_status = !empty($showcse_crea_serializable_map_array['mapviewdisplaysearch']) ? $showcse_crea_serializable_map_array['mapviewdisplaysearch'] :'yes';
                                    $mapviewdisplayadvancesearch_bar_status = !empty($showcse_crea_serializable_map_array['mapviewdisplaysearch_simple_or_detail']) ? $showcse_crea_serializable_map_array['mapviewdisplaysearch_simple_or_detail'] :'yes';                             
                                } ?>                              
                                <div id="crea_showcase_setting_map_view_display" class="showcase_setting_map_view_display" style="display:none">            
                                    <p>
                                        <label for="crea_showcase_setting_map_height">Map Height: </label>
                                        <input id="crea_showcase_setting_map_height" type="text" name="only_map_view_display_hight" <?php if( !empty($mapviewdisplayhight) && $mapviewdisplayhight !='' ) { ?> value="<?php echo $mapviewdisplayhight; ?>" <?php } else{ ?> value="600" <?php }?>>
                                    </p>
                                    <p>
                                        <label>Display listings search form:</label><br />
                                        <input type="radio" name="map_search_view_setiing" class="map_view_search_option_selection" id="map_view_search_option_selection_yes" <?php  if( $mapviewdisplaysearch_bar_status === 'yes' ){ echo "checked";} ?>  value="yes">Yes
                                        <input type="radio" name="map_search_view_setiing" class="map_view_search_option_selection" id="map_view_search_option_selection_no" <?php if( $mapviewdisplaysearch_bar_status === 'no'){  echo "checked";} ?> value="no">No
                                    </p>                                    
                                    <p class="enable_map_search_position">
                                        <input type="radio" name="map_view_setiing_status_search" <?php if( $mapviewdisplayadvancesearch_bar_status == "yes" ) { echo"checked"; } ?> value="yes"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_SIMPLE, ARETKCREA_PLUGIN_SLUG); ?><br />
                                        <input type="radio" name="map_view_setiing_status_search" <?php if( $mapviewdisplayadvancesearch_bar_status == "no") { echo"checked"; } ?> value="no"><?php  echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_DETAIL, ARETKCREA_PLUGIN_SLUG); ?>
                                    </p>                                
                                    <div class="crea_showcase_setting_sorting_title"><p>Default Map Position</p></div>
                                    <p>Drag the map marker to the desired center position and set the default zoom level.</p>           
                                    <div class="crea_google_maps_showcase" id="crea_location_google_maps_showcase" style="width:100%;height:350px;"></div>                                  
                                    <div class="drag_marker_google">                                
                                        <P>
                                            <label for="crea_googlemap_showcase_zoom_results">Zoom: </label>
                                            <input id="crea_googlemap_showcase_zoom_results" type="text" readonly="readonly" name="google_image_zoom" <?php if( !empty($showcasemapimagezoom) && $showcasemapimagezoom !='' ) { ?> value="<?php echo $showcasemapimagezoom; ?>" <?php } else{ ?> value="6" <?php }?>>
                                        </p>
                                        <p> 
                                            <label for="crea_googlemap_showcase_latitude_results">Latitude: </label>
                                            <input type="text"  readonly="readonly" name="crea_showcase_google_map_latitude" <?php if( !empty($mapfilterlatitude) && $mapfilterlatitude !='' ) { ?> value="<?php echo $mapfilterlatitude; ?>" <?php } else{ ?> value="57.67807921815639" <?php }?> id="crea_googlemap_showcase_latitude_results">
                                        </p>
                                        <p> 
                                            <label for="crea_googlemap_showcase_longitude_results">Longitude: </label> 
                                            <input type="text" readonly="readonly" name="crea_showcase_google_map_longitude" <?php if( !empty($mapfilterlongitude) && $mapfilterlongitude !='' ) { ?> value="<?php echo $mapfilterlongitude; ?>" <?php } else{ ?> value="-101.80516868749999" <?php }?> id="crea_googlemap_showcase_longitude_results">
                                        </p>
                                    </div>                                  
                                </div><?php # END Map view showcase settings ?>                                                     
                            </div>
                        </div><?php # END showcase settings tab         
                        
                        # Showcase Colour Settings ?>                       
                        <div id="crea_showcase_colour_tab">
                            <div class="crea_showcase_title_tab">
                                <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                            </div>                          
                            <div class="crea_showcase_color_content"><?php 
                                #--------------------------------------------------------
                                # COLOUR settings for List view Showcase                                
                                $listingShowcaseTextColor = '';
                                $listingShowcaseAddressBarColor = '';
                                $listingShowcasePriceColor ='';
                                $listingShowcaseStatusTextColor ='';
                                $listingShowcaseOpenHouseColor ='';                             
                                $listingShowcaseOpenHouseTextColor ='';                         
                                if( !empty( $crea_listing_view_color_array ) && $crea_listing_view_color_array !='') { 
                                    $listingShowcaseTextColor = isset( $crea_listing_view_color_array['listingShowcaseTextColor'] ) ? $crea_listing_view_color_array['listingShowcaseTextColor'] :'000000';
                                    $listingShowcaseAddressBarColor = isset( $crea_listing_view_color_array['listingShowcaseAddressBarColor'] ) ? $crea_listing_view_color_array['listingShowcaseAddressBarColor'] :'000000';
                                    $listingShowcasePriceColor = isset( $crea_listing_view_color_array['listingShowcasePriceColor'] ) ? $crea_listing_view_color_array['listingShowcasePriceColor'] :'000000';
                                    $listingShowcaseStatusTextColor = isset( $crea_listing_view_color_array['listingShowcaseStatusTextColor'] ) ? $crea_listing_view_color_array['listingShowcaseStatusTextColor'] :'000000';
                                    $listingShowcaseOpenHouseColor = isset( $crea_listing_view_color_array['listingShowcaseOpenHouseColor'] ) ? $crea_listing_view_color_array['listingShowcaseOpenHouseColor'] :'000000';                                  
                                    $listingShowcaseOpenHouseTextColor = isset( $crea_listing_view_color_array['listingShowcaseOpenHouseTextColor'] ) ? $crea_listing_view_color_array['listingShowcaseOpenHouseTextColor'] :'ffffff';                                  
                                } ?>                                    
                                <div class="crea_showcase_listing_color_view" style="display:block;">
                                    <div class="set_showcae_color"><h5>Text color</h5><input name="crea_listing_showcase_text_color" type="text" class="jscolor" value="<?php  if($listingShowcaseTextColor != '') { echo $listingShowcaseTextColor; } else { echo '000000'; }; ?>"></div>
                                    <div class="set_showcae_color"><h5>Address Text Colours</h5><input name="crea_listing_showcase_address_bar_color" type="text" class="jscolor" value="<?php if($listingShowcaseAddressBarColor != '') { echo $listingShowcaseAddressBarColor; } else { echo '000000'; }; ?>"></div>
                                    <div class="set_showcae_color"><h5>Price Text Colours</h5><input name="crea_listing_showcase_price_color" type="text" class="jscolor" value="<?php if($listingShowcasePriceColor != '') { echo $listingShowcasePriceColor; } else { echo '000000'; }; ?>"></div>
                                    <div class="set_showcae_color"><h5>Status Text Color </h5><input name="crea_listing_showcase_status_text_color" type="text" class="jscolor" value="<?php if($listingShowcaseStatusTextColor != '') { echo $listingShowcaseStatusTextColor; } else { echo '000000'; }; ?>"></div>
                                    <div class="set_showcae_color"><h5>Open House Background Colour</h5><input name="crea_listing_showcase_open_house_color" type="text" class="jscolor" value="<?php if($listingShowcaseOpenHouseColor != '') { echo $listingShowcaseOpenHouseColor; } else { echo '000000'; }; ?>"></div>
                                    <div class="set_showcae_color"><h5>Open House Text Colour</h5><input name="crea_listing_showcase_open_house_text_color" type="text" class="jscolor" value="<?php if($listingShowcaseOpenHouseTextColor != '') { echo $listingShowcaseOpenHouseTextColor; } else { echo 'ffffff'; }; ?>"></div>
                                </div><?
                                # END COLOUR settings for Listings Showcase 
                                #--------------------------------------------------------
                                
                                #--------------------------------------------------------
                                # COLOUR settings for Grid view Showcase
                                
                                # Set defaults if not set.
                                if( !empty( $crea_grid_view_color_array )  && $crea_grid_view_color_array !='' ) {
                                    
                                    $gridShowcaseTextColor = isset( $crea_grid_view_color_array['gridShowcaseTextColor'] ) ? $crea_grid_view_color_array['gridShowcaseTextColor'] :'000000';                                    
                                    $gridShowcase_TextBgColor = isset( $crea_grid_view_color_array['gridShowcase_TextBgColor'] ) ? $crea_grid_view_color_array['gridShowcase_TextBgColor'] :'ffffff';                               
                                    $gridShowcase_oh_color_txt = isset( $crea_grid_view_color_array['gridShowcase_oh_color_txt'] ) ? $crea_grid_view_color_array['gridShowcase_oh_color_txt'] :'ffffff';                                
                                    $gridShowcase_oh_color_bg = isset( $crea_grid_view_color_array['gridShowcase_oh_color_bg'] ) ? $crea_grid_view_color_array['gridShowcase_oh_color_bg'] :'ffffff';                               
                                    $gridShowcaseStatusBoxTextColor = isset( $crea_grid_view_color_array['gridShowcaseStatusBoxTextColor'] ) ? $crea_grid_view_color_array['gridShowcaseStatusBoxTextColor'] :'FFFFFF';                             
                                    $gridShowcaseStatusBoxColor = isset( $crea_grid_view_color_array['gridShowcaseStatusBoxColor'] ) ? $crea_grid_view_color_array['gridShowcaseStatusBoxColor'] :'a5a5a5';                             
                                    $gridShowcasePaginationColor = isset( $crea_grid_view_color_array['gridShowcasePaginationColor'] ) ? $crea_grid_view_color_array['gridShowcasePaginationColor'] :'C2C9D0';                          
                                    $gridShowcasePaginationTextColor = isset( $crea_grid_view_color_array['gridShowcasePaginationTextColor'] ) ? $crea_grid_view_color_array['gridShowcasePaginationTextColor'] :'000000';
                                } ?>
                                <div class="crea_showcase_grid_color_view" style="display:none;">
                                    <div class="set_showcae_color">
                                        <h5>Text color:</h5><input name="crea_grid_showcase_text_color" type="text" class="jscolor" value="<?php if( $gridShowcaseTextColor !='' ) { echo $gridShowcaseTextColor; } else { echo '000000';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Text background color:</h5>
                                        <input name="crea_grid_showcase_text_bgcolor" id="crea_grid_showcase_text_bgcolor" type="text" class="jscolor" value="<?php if( $gridShowcase_TextBgColor != '' ) { echo $gridShowcase_TextBgColor; } else { echo 'ffffff';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Open house text color:</h5>
                                        <input name="crea_grid_showcase_oh_color_txt" id="crea_grid_showcase_oh_color_txt" type="text" class="jscolor" value="<?php if( $gridShowcase_oh_color_txt != '' ) { echo $gridShowcase_oh_color_txt; } else { echo 'ffffff';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Open house background color:</h5>
                                        <input name="crea_grid_showcase_oh_color_bg" id="crea_grid_showcase_oh_color_bg" type="text" class="jscolor" value="<?php if( $gridShowcase_oh_color_bg != '' ) { echo $gridShowcase_oh_color_bg; } else { echo '444444';} ?>">
                                    </div>                                  
                                    <div class="set_showcae_color"><h5>Bottom box text color</h5><input name="crea_showcase_status_box_text_color" type="text" class="jscolor" value="<?php if( $gridShowcaseStatusBoxTextColor !='' ) { echo $gridShowcaseStatusBoxTextColor; } else { echo 'FFFFFF';} ?>"></div>                                      
                                    <div class="set_showcae_color"><h5>Bottom box background color</h5><input name="crea_showcase_status_box_color" type="text" class="jscolor" value="<?php if( $gridShowcaseStatusBoxColor !='' ) { echo $gridShowcaseStatusBoxColor; } else { echo '290891';} ?>"></div>                                 
                                </div><?php
                                # END COLOUR settings for Listings Showcase 

                                
                                #------------------------------------------------------
                                # COLOUR settings for Carousel view Showcase
                                $carouselShowcaseTextColor ='';
                                $carouselShowcaseBackgroundColor ='';
                                    
                                if( !empty($crea_carousel_view_color_array ) && $crea_carousel_view_color_array  !='' ) {               
                                    $carouselShowcaseTextColor = isset($crea_carousel_view_color_array['carouselShowcaseTextColor']) ? $crea_carousel_view_color_array['carouselShowcaseTextColor'] :'000000';                          
                                    $carouselShowcaseBackgroundColor = isset($crea_carousel_view_color_array['carouselShowcaseBackgroundColor']) ? $crea_carousel_view_color_array['carouselShowcaseBackgroundColor'] :'FFFFFF';                                    
                                    $crea_carousel_showcase_oh_color_txt = isset($crea_carousel_view_color_array['crea_carousel_showcase_oh_color_txt']) ? $crea_carousel_view_color_array['crea_carousel_showcase_oh_color_txt'] :'FFFFFF';                                    
                                    $crea_carousel_showcase_oh_color_bg = isset($crea_carousel_view_color_array['crea_carousel_showcase_oh_color_bg']) ? $crea_carousel_view_color_array['crea_carousel_showcase_oh_color_bg'] :'000000';                               
                                } ?>
                                <div class="crea_showcase_carousel_color_view" style="display:none;">
                                    <div class="set_showcae_color">
                                        <h5>Listing Status/Price text color</h5>
                                        <input name="crea_carousel_showcase_text_color" type="text" class="jscolor" value="<?php if( $carouselShowcaseTextColor != '' ) { echo $carouselShowcaseTextColor; } else { echo '000000';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Listing Status/Price background color</h5>
                                        <input name="crea_carousel_showcase_background_color" type="text" class="jscolor" value="<?php if( $carouselShowcaseBackgroundColor != '' ) { echo $carouselShowcaseBackgroundColor; } else { echo 'DADADA';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Open House Text Color</h5>
                                        <input name="crea_carousel_showcase_oh_color_txt" type="text" class="jscolor" value="<?php if( $crea_carousel_showcase_oh_color_txt != '' ) { echo $crea_carousel_showcase_oh_color_txt; } else { echo 'ffffff';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Open House Background Color</h5>
                                        <input name="crea_carousel_showcase_oh_color_bg" type="text" class="jscolor" value="<?php if( $crea_carousel_showcase_oh_color_bg != '' ) { echo $crea_carousel_showcase_oh_color_bg; } else { echo '000000';} ?>">
                                    </div>
                                    <div class="set_showcae_color">
                                        <h5>Progress Bar Golor</h5>
                                        <input name="crea_carousel_showcase_progressbar_color_bg" type="text" class="jscolor" value="<?php if( $crea_carousel_showcase_progressbar_color_bg != '' ) { echo $crea_carousel_showcase_progressbar_color_bg; } else { echo '000000';} ?>">
                                    </div>
                                </div><?php
                                # END COLOUR settings for Carousel Showcase 
                                #-------------------------------------------------
                                
                                # COLOUR settings for Map view Showcase         
                                $mapShowcaseTextColor ='';
                                $mapShowcaseButtonColor ='';
                                $mapShowcaseResetButtonColor ='';
                                $mapShowcaseListingHoverColor ='';
                                $mapShowcaseTopPictureColor ='';
                                $mapShowcasepriceColor ='';
                                $mapShowcasepriceTextColor ='';
                                $mapShowcaseTopPictureTextColor ='';                                    
                                if( !empty($crea_map_view_color_array ) && $crea_map_view_color_array  !='' ) 
                                {
                                    $mapShowcaseTextColor = isset($crea_map_view_color_array['mapShowcaseTextColor']) ?$crea_map_view_color_array['mapShowcaseTextColor'] :'000000';
                                    $mapShowcaseButtonColor = isset($crea_map_view_color_array['mapShowcaseButtonColor']) ? $crea_map_view_color_array['mapShowcaseButtonColor'] :'000000';
                                    $mapShowcaseResetButtonColor = isset($crea_map_view_color_array['mapShowcaseResetButtonColor']) ? $crea_map_view_color_array['mapShowcaseResetButtonColor'] :'001AFF';
                                    $mapShowcaseListingHoverColor = isset($crea_map_view_color_array['mapShowcaseListingHoverColor']) ?$crea_map_view_color_array['mapShowcaseListingHoverColor'] :'001AFF';
                                    $mapShowcaseTopPictureColor = isset($crea_map_view_color_array['mapShowcaseTopPictureColor']) ? $crea_map_view_color_array['mapShowcaseTopPictureColor'] :'000000';
                                    $mapShowcaseTopPictureTextColor = isset($crea_map_view_color_array['mapShowcaseTopPictureTextColor']) ? $crea_map_view_color_array['mapShowcaseTopPictureTextColor'] :'FFFFFF';                     
                                    $mapShowcasepriceColor = isset($crea_map_view_color_array['mapShowcasePriceColor']) ? $crea_map_view_color_array['mapShowcasePriceColor'] :'001AFF';                        
                                    $mapShowcasepriceTextColor = isset($crea_map_view_color_array['mapShowcasePriceTextColor']) ? $crea_map_view_color_array['mapShowcasePriceTextColor'] :'000000';
                                } ?>
                                <div class="crea_showcase_map_color_view" style="display:none;">
                                    <div class="set_showcae_color"><h5>Map View Text color</h5><input name="crea_map_showcase_text_color" type="text" class="jscolor" value="<?php if( $mapShowcaseTextColor!='') { echo $mapShowcaseTextColor; } else { echo '000000';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Colour of Buttons</h5><input name="crea_map_showcase_button_color" type="text" class="jscolor" value="<?php if( $mapShowcaseButtonColor!='') { echo $mapShowcaseButtonColor; } else { echo '000000';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Colour of Reset Button</h5><input name="crea_map_showcase_reset_button_color" type="text" class="jscolor" value="<?php if( $mapShowcaseResetButtonColor!='') { echo $mapShowcaseResetButtonColor; } else { echo '001AFF';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Listing Hover Color</h5><input name="crea_map_showcase_listing_hover_color" type="text" class="jscolor" value="<?php if( $mapShowcaseListingHoverColor!='') { echo $mapShowcaseListingHoverColor; } else { echo '001AFF';} ?>"></div>                    
                                    <div class="set_showcae_color"><h5>Price Text Color</h5><input name="crea_map_showcase_price_text_color" type="text" class="jscolor" value="<?php if( $mapShowcasepriceTextColor!='') { echo $mapShowcasepriceTextColor; } else { echo '000000';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Status (Top Left of Pictures)</h5><input name="crea_map_showcase_top_picture_color" type="text" class="jscolor" value="<?php if( $mapShowcaseTopPictureColor!='') { echo $mapShowcaseTopPictureColor; } else { echo '000000';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Status Text Color (Top Left of Pictures)</h5><input name="crea_map_showcase_top_picture_text_color" type="text" class="jscolor" value="<?php if( $mapShowcaseTopPictureTextColor!='') { echo $mapShowcaseTopPictureTextColor; } else { echo 'FFFFFF';} ?>"></div>
                                </div><?php
                                # END COLOUR settings for Map Showcase 
                                
                                # COLOUR settings for Slider view Showcase   
                                $sliderShowcaseTextColor = '';
                                $sliderShowcaseTabBtnColor = '';
                                $sliderShowcaseMoreInfoBtnColor = '';                           
                                if( !empty( $crea_slider_view_color_array ) && $crea_slider_view_color_array  !='' ) {                  
                                    $sliderShowcaseTextColor = isset( $crea_slider_view_color_array['sliderShowcaseTextColor'] ) ? $crea_slider_view_color_array['sliderShowcaseTextColor'] :'ffffff';                              
                                    $sliderShowcaseTabBtnColor = isset( $crea_slider_view_color_array['sliderShowcaseTabBtnColor'] ) ? $crea_slider_view_color_array['sliderShowcaseTabBtnColor'] :'000000';                            
                                    $sliderShowcaseMoreInfoBtnColor = isset( $crea_slider_view_color_array['sliderShowcaseMoreInfoBtnColor'] ) ? $crea_slider_view_color_array['sliderShowcaseMoreInfoBtnColor'] :'777777';                                 
                                    $crea_slider_showcase_oh_color_txt = isset( $crea_slider_view_color_array['crea_slider_showcase_oh_color_txt'] ) ? $crea_slider_view_color_array['crea_slider_showcase_oh_color_txt'] :'ffffff';                                    
                                    $crea_slider_showcase_oh_color_bg = isset( $crea_slider_view_color_array['crea_slider_showcase_oh_color_bg'] ) ? $crea_slider_view_color_array['crea_slider_showcase_oh_color_bg'] :'ff0000';
                                } ?>
                                <div class="crea_showcase_slider_color_view" style="display:none;">
                                    <div class="set_showcae_color"><h5>Property Details Text color</h5><input name="crea_slider_showcase_text_color" type="text" class="jscolor" value="<?php if( $sliderShowcaseTextColor!='') { echo $sliderShowcaseTextColor; } else { echo 'FFFFFF';} ?>"></div>
                                    <div class="set_showcae_color"><h5>Property Details Background Color</h5><input name="crea_slider_showcase_tab_button_color" type="text" class="jscolor" value="<?php if( $sliderShowcaseTabBtnColor!='') { echo $sliderShowcaseTabBtnColor; } else { echo '000000';} ?>"></div><br />
                                    <div class="set_showcae_color"><h5>Open House Text Color</h5><input name="crea_slider_showcase_oh_color_txt" type="text" class="jscolor" value="<?php if( $crea_slider_showcase_oh_color_txt!='') { echo $crea_slider_showcase_oh_color_txt; } else { echo 'ffffff';} ?>"></div><br />
                                    <div class="set_showcae_color"><h5>Open House Background Color</h5><input name="crea_slider_showcase_oh_color_bg" type="text" class="jscolor" value="<?php if( $crea_slider_showcase_oh_color_bg!='') { echo $crea_slider_showcase_oh_color_bg; } else { echo 'ff0000';} ?>"></div><br/>
                                    <div class="set_showcae_color"><h5>Progress Barr</h5><input name="crea_slider_showcase_more_info_button_color" type="text" class="jscolor" value="<?php if( $sliderShowcaseMoreInfoBtnColor!='') { echo $sliderShowcaseMoreInfoBtnColor; } else { echo '777';} ?>"></div>
                                </div><?php
                                # End COLOUR settings for Slider view Showcase  
                                ?>
                            </div>
                        </div><?php                         
                        #END crea_showcase_colour_tab 
                        #============================
                                        
                        # Start Showcase shortcode tab ?>
                        <div id="crea_showcase_save_tab" style="display:<?php echo $previewnsaveview; ?>;">
                            <div class="crea_showcase_title_tab"><h4><?php echo __(ARETKCREA_NEW_SHOWCASE_SAVE_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4></div>
                            <div class="crea_showcase_save_content">
                                <p>Below is your short code for this showcase, copy and paste it into any page on this website</p>
                                <p class="crea_showcase_save_shortcode"><?php 
                                    $genrated_shortcode = '';
                                    if( !empty($showcaseid)  && $showcaseid !='' ) {
                                        $genrated_shortcode = maybe_unserialize( get_post_meta($showcaseid,"showcse_crea_save_short_code",true));
                                        $genrated_shortcode = !empty($genrated_shortcode) ? $genrated_shortcode : '';                                           
                                    } ?>
                                </p>                                
                                <input  id="clipboard" readonly value="<?php echo $genrated_shortcode; ?>">     
                                <input  data-clipboard-target="#clipboard" class="button button-primary zclip js-textareacopybtn btn" id="crea_showcase_copy_clipboard" data-zclip-text="<?php echo $genrated_shortcode; ?>" type="button" value="COPY TO CLIPBOARD">   
                            </div>
                        </div>
                    </div><?php # END crea_showcase_menu_tab ?>
                </div><?php # END crea_showcase_listing_menu ?>
            </div><?php # END crea-plugin-showcase-content ?>
        </form> 
    </div><?php # END crea-container
}

function aretkcrea_custom_showcase_listing_detail_settings() 
{ ?>
    <div class="se-pre-con"></div>
    <div class="crea-container">
        <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <div class="crea-plugin-title remove-border"><h2><?php echo __(ARETKCREA_LISTING_DETAIL_SETTINGS_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2></div>
        <div class="crea-plugin-showcase-content">           
            <div  class="crea_showcase_listing_menu">
                <div id="crea_showcase_menu_tab">
                    <ul>
                        <li><a href="#crea_showcase_setting_tab"><?php echo __(ARETKCREA_LISTING_DETAIL_SETTINGS_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                        <li><a href="#crea_listing_detail_setting_colour_tab"><?php echo __(ARETKCREA_LISTING_DETAIL_SETTINGS_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                        <li>
                            <div class="custom_list_settings_save_div">
                                <input type="button" id="crea_showcase_listting_save_btn" class="btn_save_customlist button button-primary" name="btn_save_customlist" value="Update"/>
                            </div>
                        </li>                       
                    </ul>
                    <div id="crea_showcase_setting_tab">
                        <div class="crea_showcase_title_tab"><h4><?php echo __(ARETKCREA_NEW_SHOWCASE_LISTING_DETAIL_SETTING_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4></div>
                        <div class="crea_showcase_tab_content"><?php                        
                            $include_info_val = get_option('crea_listing_include_information');
                            $include_contact_val = get_option('crea_listing_include_contact_form');
                            $include_map_val = get_option('crea_listing_include_map');
                            $include_walk_score_val = get_option('crea_listing_include_walk_score');
                            $include_print_btn_val = get_option('crea_listing_include_print_btn');
                            $include_price_color_val = get_option('crea_listing_include_price_color');
                            $include_send_btn_val = get_option('crea_listing_include_send_btn_color');
                            $include_email_address_of_agent = get_option('crea_listing_include_email_address_of_agent');                
                            $include_info_val =  !empty($include_info_val) ? $include_info_val : 'Yes';
                            $include_contact_val =  !empty($include_contact_val) ? $include_contact_val : 'Yes';
                            $include_map_val =  !empty($include_map_val) ? $include_map_val : 'Yes';
                            $include_walk_score_val =  !empty($include_walk_score_val) ? $include_walk_score_val : 'Yes';
                            $include_print_btn_val =  !empty($include_print_btn_val) ? $include_print_btn_val : 'Yes';
                            $include_email_address_of_agent =  !empty($include_email_address_of_agent) ? $include_email_address_of_agent : 'Yes'; ?>
                            <table class="setting_tab_listing">
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Agent Information</td>
                                    <td class="showcase_listing_detail_setting_redio_buttong">
                                        <input <?php if ($include_info_val == 'Yes') { ?> checked="checked" <?php } ?> type="radio" name="include_information" value="Yes">Yes
                                    </td>
                                    <td>
                                        <input type="radio" name="include_information" <?php if ($include_info_val == 'No') { ?> checked="checked"<?php } ?>  value="No">No
                                    </td>
                                </tr>
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Contact Form</td>
                                    <td><input type="radio" name="include_contact_form" <?php if ($include_contact_val == 'Yes') { ?> checked <?php } ?>  value="Yes">Yes</td>
                                    <td><input type="radio" name="include_contact_form" <?php if ($include_contact_val == 'No') { ?> checked <?php } ?>  value="No">No</td>
                                </tr>
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Map</td>
                                    <td><input type="radio" name="include_map" <?php if ($include_map_val == 'Yes') { ?> checked <?php } ?>  value="Yes">Yes</td>
                                    <td><input type="radio" name="include_map" <?php if ($include_map_val == 'No') { ?> checked <?php } ?> value="No">No<span class="google_map_information_content">["Include  Google map key in plugin settings"]</span></td>
                                </tr>
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Walk Score</td>
                                    <td ><input type="radio" name="include_walk_score" <?php if ($include_walk_score_val == 'Yes') { ?> checked <?php } ?>  value="Yes">Yes</td>
                                    <td><input type="radio" name="include_walk_score"  <?php if ($include_walk_score_val == 'No') { ?> checked <?php } ?> value="No">No<span class="walkscore_information_content">["Include walkscore  key in plugin settings"]</span></td>
                                </tr>
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Print Button</td>
                                    <td><input type="radio" name="include_print_button"  <?php if ($include_print_btn_val == 'Yes') { ?> checked <?php } ?> value="Yes">Yes</td>
                                    <td><input type="radio" name="include_print_button"  <?php if ($include_print_btn_val == 'No') { ?> checked <?php } ?> value="No">No</td>
                                </tr>
                                <tr>
                                    <td class="listing_setting_tab_listing">Include Email Address of Agent</td>
                                    <td class="showcase_listing_detail_setting_redio_buttong"><input  <?php if ($include_email_address_of_agent == 'Yes') { ?> checked <?php } ?>  type="radio" name="include_email_address_of_agent" value="Yes">Yes</td>
                                    <td><input type="radio" name="include_email_address_of_agent"  <?php if ($include_email_address_of_agent == 'No') { ?> checked <?php } ?> value="No">No</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="crea_listing_detail_setting_colour_tab">
                        <div class="crea_showcase_listing_detail_setting_title_tab"><h4><?php echo __(ARETKCREA_NEW_SHOWCASE_LISTING_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4></div>
                        <div class="crea_showcase_listing_detail_setting_content">
                            <table class="crea_listing_detail_setting_colour_tab">
                                <tr> 
                                    <td class="colour_tab_content_listing">Send Button Background Colour</td>
                                    <?php if ($include_send_btn_val != '') { ?>
                                        <td><input class="jscolor" id="crea_listing_send_btn_color_id"  name="custom_list_send_button" value="<?php echo $include_send_btn_val; ?>"></td>
                                    <?php } else { ?>
                                        <td><input class="jscolor" id="crea_listing_send_btn_color_id"  name="custom_list_send_button" value="0001C8"></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td class="colour_tab_content_listing">Send Button Text Colour</td>
                                    <?php if ($include_price_color_val != '') { ?>
                                        <td><input class="jscolor" id="crea_listing_price_color_id" name="custom_list_price_color" value="<?php echo $include_price_color_val; ?>"></td>
                                    <?php } else { ?>
                                        <td><input class="jscolor" id="crea_listing_price_color_id" name="custom_list_price_color" value="ffffff"></td>
                                    <?php } ?>
                                </tr>
                            </table>
                        </div>
                    </div>          
                </div>
            </div>
        </div>
    </div><?php
}

/**
 * Function is responsible for create default listing showcase.
 * 
 * @return return html for the CREA search listing showcase Html.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_default_listing_settings_html() { 
    global  $wpdb;  
    $crea_default_listing_text                  = get_option('crea_default_listing_text');
    $crea_default_listing_address               = get_option('crea_default_listing_address_bar_listing_include');
    $crea_default_listing_price_color           = get_option('crea_default_listing_price_color');
    $crea_default_listing_status_color          = get_option('crea_default_listing_status_color');
    $crea_default_listing_openhouse_color       = get_option('crea_default_listing_openhouse_color');
    $crea_default_listing_status_text_color     = get_option('crea_default_listing_status_text_color');
    $crea_default_listing_openhouse_text_color  = get_option('crea_default_listing_openhouse_text_color');
    $crea_default_listing_pagination_color      = get_option('crea_default_listing_pagination_color_id_yes_or_not');
    $crea_default_listing_pagination_text_color = get_option('crea_default_listing_pagination_text_color_id_yes_or_not'); ?>
    <div class="se-pre-con"></div>
    <div class="crea-container">
        <div class="crea-plugin-title remove-border"><h2><?php echo __(ARETKCREA_DEFAULT_LISTING_DETAIL_SETTINGS_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2></div>
        <div class="crea-plugin-showcase-content">
          <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>"> 
            <div  class="crea_showcase_listing_menu">
                <div id="crea_showcase_search_menu_tab">
                    <ul>
                        <li><a href="#crea_default_listing_colour_tab"><?php echo __("Color", ARETKCREA_PLUGIN_SLUG); ?></a></li>
                        <li><a href="#crea_default_listing_setting_tab"><?php echo __("Setting", ARETKCREA_PLUGIN_SLUG); ?></a></li>            
                        <li>
                            <div class="search_listing_settings_save_data">
                                <input type="button" class="btn_save_searchlist button button-primary" id="btn_save_default_listing_button" name="btn_save_default_listing" value="Update"/>
                            </div>
                        </li>                       
                    </ul>
                    <!-- start tab content !-->
                    <div id="crea_default_listing_colour_tab">
                        <div class="crea_showcase_search_title_tab">
                            <h4><?php echo __(CREA_SEARCH_NEW_SHOWCASE_LISTING_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                        </div>
                        <div class="layout_color_div">
                            <table class="search_listing_setting_color_tab_table">
                                <tr>
                                    <td><?php echo __('Listing View Text color', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id ='crea_default_listing_title_color_id' name="crea_default_listing_title_color" value="<?php if(isset( $crea_default_listing_text) && !empty( $crea_default_listing_text )){ echo $crea_default_listing_text ;} else {  echo "000000"; }  ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Colour of Address', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_address_color_id" name="crea_default_listing_address_address" value=" <?php if(isset( $crea_default_listing_address) && !empty( $crea_default_listing_address )){ echo $crea_default_listing_address ;} else {  echo "0001C8"; }  ?>" ></td>
                                </tr> 
                                 <tr>
                                    <td><?php echo __('Colour of Price', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_prise_color_id" name="crea_default_listing_prise" value=" <?php if(isset( $crea_default_listing_price_color) && !empty( $crea_default_listing_price_color )){ echo $crea_default_listing_price_color ;} else {  echo "0001C8"; }  ?>"></td>
                                </tr>   
                                <tr>
                                    <td><?php echo __('Colour of Status', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_status_color_id" name="crea_default_listing_status" value=" <?php if(isset( $crea_default_listing_status_color) && !empty( $crea_default_listing_status_color )){ echo $crea_default_listing_status_color ;} else {  echo "F2F2F2"; }  ?>"></td>
                                </tr>                               
                                <tr>
                                    <td><?php echo __('Status Text Color', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="default_listing_status_text_color_id" name="default_listing_status_text_button_color" value=" <?php if(isset( $crea_default_listing_status_text_color) && !empty( $crea_default_listing_status_text_color )){ echo $crea_default_listing_status_text_color ;} else {  echo "0001C8"; }  ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Open house box (top left hand corner of picture)', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_search_detail_button_color_id" name="default_listing_openhouse_button_color" value=" <?php if(isset( $crea_default_listing_openhouse_color) && !empty( $crea_default_listing_openhouse_color )){ echo $crea_default_listing_openhouse_color ;} else {  echo "F2F2F2"; }  ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Open house Text box (top left hand corner of picture)', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_openhouse_text_color_id" name="crea_default_listing_openhouse_text_color" value=" <?php if(isset( $crea_default_listing_openhouse_text_color) && !empty( $crea_default_listing_openhouse_text_color )){ echo $crea_default_listing_openhouse_text_color ;} else {  echo "0001C8"; }  ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Pagination Color', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_pagination_color_id" name="crea_default_listing_pagination_color_id" value=" <?php if(isset( $crea_default_listing_pagination_color) && !empty( $crea_default_listing_pagination_color )){ echo $crea_default_listing_pagination_color ;} else {  echo "0001C8"; }  ?>"></td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Pagination TextColor', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input class="jscolor" id="crea_default_listing_pagination_text_color_id" name="crea_default_listing_pagination_text_color_id" value=" <?php if(isset( $crea_default_listing_pagination_text_color) && !empty( $crea_default_listing_pagination_text_color )){ echo $crea_default_listing_pagination_text_color ;} else {  echo "FFFFFF"; }  ?>"></td>
                                </tr>
                            </table>                            
                        </div>
                    </div>                      
                    <!-- start setting view  !-->
                    <?php 
                    $default_listing_openhouse_yes_or_not = 'yes';
                    $default_listing_status_yes_or_not = 'yes';
                    $default_listing_openhouse_yes_or_not = get_option('crea_default_listing_openhouse_yes_or_not');
                    $default_listing_status_yes_or_not = get_option('crea_default_listing_status_yes_or_not'); ?>
                    <div id="crea_default_listing_setting_tab">
                        <div class="crea_showcase_search_title_tab">
                            <h4><?php echo __("Setting", ARETKCREA_PLUGIN_SLUG); ?></h4>
                        </div>                          
                        <div class="layout_color_div">
                            <table class="search_listing_setting_color_tab_table">
                                <tr>
                                    <td><?php echo __('Openhouse', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input type="radio" name="default_listing_view_setiing_open_house" id="default_listing_view_showcase_yes" <?php if($default_listing_openhouse_yes_or_not == "yes") { ?> checked <?php } ?> value="yes">Yes
                                    <input type="radio" name="default_listing_view_setiing_open_house" id="default_listing_view_showcase_no" <?php if($default_listing_openhouse_yes_or_not == "no") { ?> checked <?php } ?> value="no">No</td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Status', ARETKCREA_PLUGIN_SLUG); ?></td>
                                    <td><input type="radio" name="default_listing_view_setiing_status" id="default_listing_view_showcase_yes" <?php if($default_listing_status_yes_or_not == "yes") { ?> checked <?php } ?> value="yes">Yes
                                    <input type="radio" name="default_listing_view_setiing_status" id="default_listing_view_showcase_no" <?php if($default_listing_status_yes_or_not == "no") { ?> checked <?php } ?> value="no">No</td>
                                </tr> 
                            </table>
                        </div>
                    </div>                                      
                    <!-- end setting view  !-->  
                </div>
            </div>
        </div>  
    </div><?php 
}


/**
 * Function create search listing showcase.
 * 
 * @return return html for the CREA search listing showcase Html.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_search_listing_settings_html() 
{
    global $wpdb; 
    $getSubscriptionStatus = get_option('crea_subscription_status', '');
    $crea_search_feed_id = get_option('crea_search_feed_id');
    $showcase_inc_exc_listing_feed = get_option('crea_search_inc_exc_listing_feed');    
    $crea_select_result_layout = get_option('crea_select_result_layout');   
    $crea_search_exclude_field_all = get_option('crea_search_exclude_field_all');  
    $crea_search_exclude_field_property_type = get_option('crea_search_exclude_field_property_type');
	$crea_search_exclude_field_ownership_type = get_option('crea_search_exclude_field_ownership_type');
    $crea_search_exclude_field_Price = get_option('crea_search_exclude_field_Price');
    $crea_search_exclude_field_status = get_option('crea_search_exclude_field_status');    
    $crea_search_exclude_field_bedrooms = get_option('crea_search_exclude_field_bedrooms');
    $crea_search_exclude_field_bathrooms_full = get_option('crea_search_exclude_field_bathrooms_full');
    $crea_search_exclude_field_bathrooms_partial = get_option('crea_search_exclude_field_bathrooms_partial');
    $crea_search_exclude_field_finished_basement = get_option('crea_search_exclude_field_finished_basement');    
    $crea_search_detail_button_color_id = get_option('crea_search_detail_button_color_id');
    $crea_search_detail_title_color_id = get_option('crea_search_detail_title_color_id');
    $crea_search_max_price_slider_range = get_option('crea_search_max_price_slider_range'); 
    $aretkcrea_showcase_search_advancefilterclosed = get_option('aretkcrea_showcase_search_advancefilterclosed'); 
	
	if ('' === $crea_search_exclude_field_ownership_type && 'false' !== $crea_search_exclude_field_ownership_type){
		$crea_search_exclude_field_ownership_type = 'Ownership Type';
	}	
    ?>
    <div class="crea-container">
    <form id="crea_showcase_form_validate" method="post" action="<?php echo get_admin_url(); ?>admin-post.php" enctype="multipart/form-data" novalidate="novalidate">
        <div class="crea-plugin-showcase-content">
            <div class="crea-plugin-title remove-border">
                <h2><?php echo __(ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2>
            </div>        
            <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
            <div class="se-pre-con"></div>
            <div id="crea_showcase_search_menu_tab">
                <ul>
                    <li><a href="#crea_showcase_feed_tab"><?php echo __(ARETKCREA_NEW_SHOWCASE_FEED_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                    <li><a href="#crea_search_listing_setting_tab"><?php echo __(ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                    <li><a href="#crea_search_listing_colour_tab"><?php echo __(ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a></li>
                    <li><input type="button" class="btn_save_searchlist button button-primary" id="btn_save_search_listing_showcase_button" name="btn_save_searchlist" value="Update"/></li>
                </ul>
                <div id="crea_showcase_feed_tab">
                    <div class="crea_showcase_title_tab">
                        <h4><?php echo __(ARETKCREA_NEW_SHOWCASE_FEED_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                    </div>
                    <div class="crea_showcase_content_html"><?php               
                        if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ ?>
                            <p>
                                <select class="set_feed_option" id="set_feed_option" name="crea_showcase_feed_ddf_option">
                                    <option value="">Select A DDF</option><?php                                 
                                        $crea_user_listing_detail = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
                                        $sql_select = "SELECT `username`,`ddf_type` FROM `$crea_user_listing_detail`";
                                        $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                        $get_user_listing_results = $wpdb->get_results($sql_prep);
                                
                                        if ($get_user_listing_results != '' && !empty($get_user_listing_results)) {
                                            if ($crea_search_feed_id === false){
                                                $userName = $get_user_listing_results[0]->username;
                                                $crea_search_feed_id = !empty( $userName ) ? $userName : '';    
                                            }
                                            foreach ($get_user_listing_results as $get_user_listing_values) { 
                                                $showcse_crea_feed_ddf_type_selected = "";
                                                if( isset( $crea_search_feed_id ) && !empty( $crea_search_feed_id ) && $get_user_listing_values->username == $crea_search_feed_id ) { 
                                                    $showcse_crea_feed_ddf_type_selected = "selected";
                                                } ?>
                                                <option <?php echo $showcse_crea_feed_ddf_type_selected; ?> value="<?php echo $get_user_listing_values->username; ?>"><?php echo $get_user_listing_values->ddf_type . ' (' . $get_user_listing_values->username . ')'; ?></option><?php
                                            }
                                        } ?>
                                </select>
                            </p><?php
                        } else { ?> 
                            <p>
                                <select class="set_feed_option" name="crea_showcase_feed_ddf_option">
                                    <option value="Exclusive Listing" selected="selected">Exclusive Listing</option>
                                </select>
                            </p><?php 
                        }         
                        if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){ ?>    
                            <p><?php
                                $include_excl_checked = '';                             
                                if ($showcase_inc_exc_listing_feed === false){
                                    $include_excl_checked = 'checked="checked"';
                                } else {                                            
                                    $showcase_inc_exc_listing_feed = isset($showcase_inc_exc_listing_feed) ? $showcase_inc_exc_listing_feed : '';   
                                    
                                    if ( $showcase_inc_exc_listing_feed == 'no' ) { 
                                        $include_excl_checked = '';
                                    } else {
                                        $include_excl_checked = 'checked="checked"';
                                    }
                                } ?>
                                <input type="checkbox" id="showcase_inc_exc_listing_feed" name="crea_showcase_inc_exc_listing_feed" <?php echo $include_excl_checked; ?> value="yes" />Include the Exclusive Listing
                            </p><?php 
                        } ?>        
                    </div>
                </div>
                <div id="crea_search_listing_setting_tab">
                    <div class="crea_showcase_search_title_tab">
                        <h4><?php echo __(ARETKCREA_SEARCH_NEW_SHOWCASE_LISTING_DETAIL_SETTING_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                    </div>  
                    <div class="search_list_settings_wrap"><?php 
                        $max_price_for_ranger_slider = "10000000";
                        if(isset( $crea_search_max_price_slider_range ) && !empty( $crea_search_max_price_slider_range )){ 
                            $max_price_for_ranger_slider = $crea_search_max_price_slider_range;                                 
                        } ?>                        
                        <div class="search_list_checkbox_div">
                            <div class="div_checkbox">
                                
                                <strong><?php echo __(ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_DETAIL, ARETKCREA_PLUGIN_SLUG); ?></strong>
                                <p>
                                    <span class="aretk_shocasesettings_radio">
                                        <input <?php
                                        if ( $aretkcrea_showcase_search_advancefilterclosed === 'yes'){
                                            echo 'checked="checked" ';
                                        } ?>type="radio" name="aretkcrea_showcase_search_advancefilterclosed" value="yes" />Yes
                                    </span>&nbsp;&nbsp;&nbsp;
                                    <span class="aretk_shocasesettings_radio">
                                        <input <?php
                                        if ( empty($aretkcrea_showcase_search_advancefilterclosed) ||$aretkcrea_showcase_search_advancefilterclosed === 'no' ){
                                            echo 'checked="checked" ';
                                        } ?>type="radio" name="aretkcrea_showcase_search_advancefilterclosed" value="no" />No
                                    </span>
                                </p>
                            </div>
                            <div class="div_checkbox">  
                                <strong><?php echo __('Exclude Specific Fields from  Advance Search', ARETKCREA_PLUGIN_SLUG); ?></strong>
                                <p><input type="checkbox" class="case" name="search_exclude_field_status" value="Status" <?php if($crea_search_exclude_field_all == 'All' || $crea_search_exclude_field_status =='Status') { echo 'checked="checked"';} ?>  />Status</p>                                
                                <p><input type="checkbox" class="case" name="search_exclude_field_bedrooms" value="Bedrooms" <?php if($crea_search_exclude_field_all == 'All' || $crea_search_exclude_field_bedrooms =='Bedrooms') { echo 'checked="checked"';} ?>  />Bedrooms</p>
                                <p><input type="checkbox" class="case" name="search_exclude_field_bathrooms_full" value="Bathrooms Full" <?php if($crea_search_exclude_field_all == 'All' || $crea_search_exclude_field_bathrooms_full =='Bathrooms Full' ) { echo 'checked="checked"';} ?> />Bathrooms</p>                             
                                <p><input type="checkbox" class="case" name="search_exclude_field_property_type" value="Property Type" <?php if($crea_search_exclude_field_all == 'All' || $crea_search_exclude_field_property_type == 'Property Type' ) { echo 'checked="checked"';} ?>  />Property Type</p>
								<p><input type="checkbox" class="case" name="search_exclude_field_ownership_type" value="Ownership Type" <?php if($crea_search_exclude_field_all == 'All' || $crea_search_exclude_field_ownership_type == 'Ownership Type' ) { echo 'checked="checked"';} ?>  />Ownership Type</p>
                            </div>
                        </div>
                    </div>
                    <div class="search_list_price_ranger_maxPrice">
                         <strong><?php echo __(ARETKCREA_SEARCH_NEW_SHOWCASE_MAX_PRICERANGER, ARETKCREA_PLUGIN_SLUG);  ?></strong>
                         <p>
                         <input type="number" name="search_max_price_slider_range" id="search_max_price_slider_range" value="<?php echo $max_price_for_ranger_slider; ?>"><span class="Default_search_price_ranger_title">Default 10,000,000</span>
                         </p>
                    </div>
                </div>
                <div id="crea_search_listing_colour_tab">
                    <div class="crea_showcase_search_title_tab">
                        <h4><?php echo __(ARETKCREA_SEARCH_NEW_SHOWCASE_LISTING_COLOUR_TAB_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                    </div>
                    <div class="layout_color_div">
                        <table class="search_listing_setting_color_tab_table">
                            <tr>
                                <td><?php echo __('Search Button Text Colour', ARETKCREA_PLUGIN_SLUG); ?></td>
                                <td><input class="jscolor" id ='crea_search_detail_title_color_id' name="search_title_color" value="<?php if($crea_search_detail_title_color_id !=''){ echo $crea_search_detail_title_color_id ;} else { echo 'ffffff';} ?>"></td>
                            </tr>
                            <tr>
                                <td><?php echo __('Search Button Background Colour', ARETKCREA_PLUGIN_SLUG); ?></td>
                                <td><input class="jscolor" id="crea_search_detail_button_color_id" name="search_button_color" value="<?php if($crea_search_detail_button_color_id !=''){ echo $crea_search_detail_button_color_id ;} else { echo '2012A6';} ?>"></td>
                            </tr> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div><?php
}

/**
 * @return redirect to main aretk leads page.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_leads_settings_html() { 
    $url = admin_url() . 'edit.php?post_type=aretk_lead';
    echo '<h2>'. __('LEADS', ARETKCREA_PLUGIN_SLUG) .'</h2>';
    echo '<script>window.location.href="'. $url .'"</script>';
    exit;
}

/**
 * @return redirect to aretk Lead Categories.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_create_new_lead_category_html(){ 
    $url = admin_url() . 'edit-tags.php?taxonomy=lead-category';
    echo '<h2>'. __('LEAD CATEGORIES', ARETKCREA_PLUGIN_SLUG) .'</h2>';
    echo '<script>window.location.href="'. $url .'"</script>';
    exit;
}

/**
 * create function for aretkcrea_custom_lead_form_listing
 * 
 * @return return html for the CREA Lead Form listing
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_lead_form_listing() {?>
    <div class="custom_lead_form_listing_lead_button">
        <ul class="leadsbuttons subsubsub">
            <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=aretk_lead" id="leads" class="button button-primary aretk-leads"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="add-new-lead"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=create_new_leads" id="add-new-lead" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="add-end-email"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=send_email_leads" id="send-email" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="lead-category"><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=lead-category"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
            <li class="leadforms activeleadpage"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=leads_form" id="lead-forms" class="button button-primary aretk-leadforms"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="import-lead"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=import_leads" id="import-lead-csv" class="button button-primary aretk-import-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="export-lead"><a href="#"><input type="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
            <li class="download-lead"><div class="download-export-csv"></div></li>
        </ul>
    </div>
    <div class="crea_lead_new_btns">    
        <p><h2 class="set_lead_form_title"><?php echo __("LEAD FORMS", ARETKCREA_PLUGIN_SLUG); ?></h2></p>     
    </div>
   <div id="crea_inport_lead_form_main" style="display:none;">
        <div class="set_import_files">
            <input accept=".csv" type="file" name="crea_import_lead" id="crea_add_new_import_lead">
            <input type="button" class="button button-primary" name="import_new_lead" id="crea_import_lead_btn" value="Import Lead">
            <p id="import_csv_error_msg" style="display:none;">Please choose only CSV format</p>
        </div>
    </div>  
    <table class="crea_table_lead_form_listing" width="84%" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th></th>
                <th><?php echo __(ARETKCREA_LEAD_FORM_HEAD_ONE, ARETKCREA_PLUGIN_SLUG); ?></th>
                <th><?php echo __(ARETKCREA_LEAD_FORM_HEAD_TWO, ARETKCREA_PLUGIN_SLUG); ?></th>
                <th><?php echo __(ARETKCREA_LEAD_FORM_HEAD_THREE, ARETKCREA_PLUGIN_SLUG); ?></th>
                <th><?php echo __(ARETKCREA_LEAD_FORM_HEAD_FOUR, ARETKCREA_PLUGIN_SLUG); ?></th>
                <th><?php echo __(ARETKCREA_LEAD_FORM_HEAD_FIVE, ARETKCREA_PLUGIN_SLUG); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>01</td>
                <td><?php echo __('BUYER FORM', ARETKCREA_PLUGIN_SLUG); ?></td> 
                <td>01-01-2016</td>
                <td><?php echo __('BUYER FORM', ARETKCREA_PLUGIN_SLUG); ?></td>
                <td>[ARTEK-BF]</td>
            </tr>
            <tr>
                <td></td>
                <td>02</td>
                <td><?php echo __('SELLER FORM', ARETKCREA_PLUGIN_SLUG); ?></td>
                <td>01-01-2016</td>
                <td><?php echo __('SELLER FORM', ARETKCREA_PLUGIN_SLUG); ?></td>
                <td>[ARTEK-SF]</td>
            </tr>
            <tr>
                <td></td>
                <td>03</td>
                <td><?php echo __('CONTACT FORM', ARETKCREA_PLUGIN_SLUG); ?></td>
                <td>01-01-2016</td>
                <td><?php echo __('CONTACT FORM', ARETKCREA_PLUGIN_SLUG); ?></td>
                <td>[ARTEK-CF]</td>
            </tr>
        </tbody>
    </table><?php
}

/**
 * create function for create new leads form
 * 
 * @return return html for the CREA Custom Create New Leads Form
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_create_new_leads_form() 
{
    global $wpdb;   

    $crea_lead_ID = '';
    if ( isset($_GET['ID']) && is_numeric($_GET['ID']) ){
        $crea_lead_ID = (int) $_GET['ID'];
    }
    $crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
    ?>
    <!-- lead button !-->
    <div class="create_new_lead_button_top">
        <ul class="leadsbuttons subsubsub">
            <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=aretk_lead" id="leads" class="button button-primary aretk-leads"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="add-new-lead activeleadpage"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=create_new_leads" id="add-new-lead" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="add-end-email"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=send_email_leads" id="send-email" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="lead-category"><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=lead-category"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
            <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=leads_form" id="lead-forms" class="button button-primary aretk-leadforms"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="import-lead"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=import_leads" id="import-lead-csv" class="button button-primary aretk-import-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
            <li class="export-lead"><a href="#"><input type="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
            <li class="download-lead"><div class="download-export-csv"></div></li>
        </ul>
    </div>          
    <!-- lead button end !-->   
    <form action="<?php echo get_admin_url(); ?>admin-post.php" method='post' class="newleadForm"  enctype="multipart/form-data">
    <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <input type='hidden' name='action' value='lead-form' />
        <input type='hidden' name='posttype' value='lead' />
        <?php 
        if (!empty($crea_lead_ID) && $crea_lead_ID != '') 
        { ?>
            <input type='hidden' name='action-which' value='edit' />
            <input id="aretk_lead_id" type="hidden" name="aretk-lead-id" value="<?php echo $crea_lead_ID; ?>"/><?php 
        } else { ?>
            <input type='hidden' name='action-which' value='add' /><?php 
        } 
        $crea_lead_post_title = get_the_title($crea_lead_ID);
        $crea_agent_id = get_post_meta($crea_lead_ID, 'crea_agent_id', true);
        $crea_lead_phone_no = get_post_meta($crea_lead_ID, 'lead_phone_no', true);
        $crea_lead_phone_email = get_post_meta($crea_lead_ID, 'lead_phone_email', true);
        $crea_create_lead_company = get_post_meta($crea_lead_ID, 'create_lead_company', true);
        $crea_lead_address_line = get_post_meta($crea_lead_ID, 'lead_address_line', true);
        $crea_lead_province = get_post_meta($crea_lead_ID, 'lead_province', true);
        $crea_lead_city = get_post_meta($crea_lead_ID, 'lead_city', true);
        $crea_create_lead_country = get_post_meta($crea_lead_ID, 'create_lead_country', true);
        $crea_agent_social_type = get_post_meta($crea_lead_ID, 'agent_social_type', true);
        $crea_lead_form_type = get_post_meta($crea_lead_ID, 'lead_form_type', true);
        $crea_lead_primary_email = get_post_meta($crea_lead_ID, 'lead_primary_email', true);
        if( $crea_lead_ID !='') {
            $content_post = get_post($crea_lead_ID);
            $crea_lead_comment = $content_post->post_content;
        }
        /**
        * record unserialize
        */
        $crea_lead_phone_no_unserialize = maybe_unserialize($crea_lead_phone_no);
        $crea_lead_phone_email_unserialize = maybe_unserialize($crea_lead_phone_email);
        $crea_agent_social_type_unserialize = maybe_unserialize($crea_agent_social_type); ?>
        <div class="crea-container">        
            <div class="crea-plugin-title remove-border"><h2><?php if (!empty($crea_lead_ID) && $crea_lead_ID != '') { echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_TITLE_EDIT, ARETKCREA_PLUGIN_SLUG); } else { echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_TITLE_ADD, ARETKCREA_PLUGIN_SLUG); } ?></h2></div><?php 
            if( !empty($crea_lead_ID) && $crea_lead_ID != '') { ?>
                <div class="crea_send_lead_emails">
                    <a href="<?php echo home_url("/wp-admin/admin.php?page=send_email_leads&lead_id=".$crea_lead_ID." "); ?>"><input type="button" class="button button-primary" value="<?php echo __(ARETKCREA_LEAD_SEND_EMAIL_BTN,ARETKCREA_PLUGIN_SLUG); ?>"></a>
                </div><?php 
            } ?>            
            <div class="crea-create-new-leads">
                <div class="crea_leads_left_part_html">
                    <input type = "hidden" id ="all_validation_check">
                    <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
                    <table width="100%" class = "create-new-lead-table">    
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_NAME, ARETKCREA_PLUGIN_SLUG); ?><span class ="">*</span></td>
                            <td><input class="crea_lead_agent_name"  id="create_lead_name" type ="text" name = "create_lead_name" value="<?php 
                            if ( isset($crea_lead_post_title) && $crea_lead_post_title !== 'Default title' ){
                                echo $crea_lead_post_title;
                            } ?>">
                            <label for="create_lead_name" class="new_lead_agents_name_validation_error" generated="true" class="crea_new_lead_suuces_msg error"></label>
                            </td>
                        </tr>
                        <tr>
                            <?php  
                            $taxonomy = 'lead-category';
                            $term_args=array(
                              'hide_empty' => false,
                              'orderby' => 'name',
                              'order' => 'ASC',
                              'post_per_page' => -1
                            );
                            $tax_terms = get_terms($taxonomy,$term_args);
                            ?>
                            <td style="padding-bottom:20px;">Category</td>
                            <td style="vertical-align:text-top;"><select class="chzn-select" multiple="true" name="new_lead_category[]" style="width:200px;margin-bottom:10px;"><?php 
                            foreach ( $tax_terms as $lead_category_name) { 
                                $term_list = wp_get_post_terms($crea_lead_ID, 'lead-category', array("fields" => "all"));
                                $get_all_selectd_array =array();
                                foreach ( $term_list as $get_category_tearm ) { 
                                    $get_all_selectd_array[] = $get_category_tearm->name;
                                } ?> 
                                <option <?php if( in_array( $lead_category_name->name , $get_all_selectd_array ) ) { echo 'selected'; } ?>  value="<?php  echo $lead_category_name->name; ?>"><?php echo  $lead_category_name->name ;?></option><?php  
                            } ?>
                            </select></td>
                        </tr>
                        <?php  //Start subscription status condition
                        $getSubscriptionStatus = get_option('crea_subscription_status', '');
                        if( isset( $getSubscriptionStatus ) && !empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid'  ){  ?>   
                            <tr>
                                <td class ="create-new-leads-table-title"><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_AGENTS_NAME, ARETKCREA_PLUGIN_SLUG) ?> </td>
                                <td class ="create-new-leads-table-feild">
                                <select id="aretk_crea_agent_id" class="set_width" name="crea_agent_id">
                                    <option value="">Agent Name</option><?php
                                    $sql_select = "SELECT * FROM `$crea_agent_table_name`";
                                    $sql_prep = $wpdb->prepare( $sql_select, NULL );
                                    $get_lead_agent_name = $wpdb->get_results($sql_prep);
                                    if (!empty($get_lead_agent_name) && $get_lead_agent_name != '') 
                                    {
                                        foreach ($get_lead_agent_name as $get_lead_agent_name_valu) { ?>
                                            <option <?php if ($crea_agent_id == $get_lead_agent_name_valu->crea_agent_id ) { echo 'selected';} ?> value="<?php echo $get_lead_agent_name_valu->crea_agent_id; ?>"><?php echo $get_lead_agent_name_valu->crea_agent_name; ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                                <label for="aretk_crea_agent_id" generated="true" class="crea_new_lead_suuces_msg error"></label>
                                </td>
                            </tr><?php 
                        } //END  subscription status condition ?>
                        <tr>
                            <td class="phone_number_vertical_aling"><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_PHONE, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td>
                                <div id="add_more_phone_type"><?php
                                    if (!empty($crea_lead_ID) && $crea_lead_ID != '') {
                                        if (!empty($crea_lead_phone_no_unserialize) && $crea_lead_phone_no_unserialize != '') {
                                            if( is_array( $crea_lead_phone_no_unserialize ) ) {
                                                foreach ($crea_lead_phone_no_unserialize as $crea_lead_phone_key => $crea_lead_phone_value) {
                                                    $phone_counter = '';
                                                    if ($crea_lead_phone_key == 0) { $phone_counter = ''; } else { $phone_counter = $crea_lead_phone_key; } ?>
                                                    <p id="crea_phone_p_id<?php echo $phone_counter; ?>" class="crea_main_phone_p_class">
                                                    <select id="aretk_crea_agent_phone_type<?php echo $phone_counter; ?>" class="phone_type" name="crea_agent_phone_type[]">
                                                        <option value="" <?php if ($crea_lead_phone_value['PhoneType'] == '') { echo'selected'; } ?>  >Select Type</option>
                                                        <option value="Home" <?php if ($crea_lead_phone_value['PhoneType'] == 'Home') { echo'selected'; } ?>>Home</option>
                                                        <option value="Mobile" <?php if ($crea_lead_phone_value['PhoneType'] == 'Mobile') { echo'selected'; } ?>>Mobile</option>
                                                        <option value="Fax" <?php if ($crea_lead_phone_value['PhoneType'] == 'Fax') { echo'selected'; } ?>>Fax</option>
                                                    </select>
                                                    <input type ="text" maxlength="10" value="<?php echo isset($crea_lead_phone_value['PhoneNo']) ? $crea_lead_phone_value['PhoneNo'] : ''; ?>" class ="create-new-lead-phone-no-list" id="add_more_phone_number_id<?php echo $phone_counter; ?>" name ="create_lead_phone_no[]">
                                                    <a id="crea_add_more_phone_delete<?php echo $phone_counter; ?>" class="crea_add_more_phone_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class ="add_more_phone_delete_icon" alt="delete" width="20" height="20"></a>
                                                    </p><?php 
                                                }
                                            } else { ?>
                                                <p id="crea_phone_p_id" class="crea_main_phone_p_class">
                                                <select id="aretk_crea_agent_phone_type" class="phone_type" name="crea_agent_phone_type[]">
                                                    <option value="" >Select Type</option>
                                                    <option value="Home" selected>Home</option>
                                                    <option value="Mobile" >Mobile</option>
                                                    <option value="Fax" >Fax</option>
                                                </select>
                                                <input type ="text" value="<?php echo $crea_lead_phone_no_unserialize;  ?>" maxlength="10" class ="create-new-lead-phone-no-list" id="add_more_phone_number_id" name ="create_lead_phone_no[]">
                                                <a id="crea_add_more_phone_delete" class="crea_add_more_phone_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class ="add_more_phone_delete_icon" alt="delete" width="20" height="20"></a>
                                                </p><?php 
                                            }                                               
                                        } else { ?>
                                            <p id="crea_phone_p_id" class="crea_main_phone_p_class">
                                                <select id="aretk_crea_agent_phone_type" class="phone_type" name="crea_agent_phone_type[]">
                                                    <option value="">Select Type</option>
                                                    <option value="Home">Home</option>
                                                    <option value="Mobile" >Mobile</option>
                                                    <option value="Fax" >Fax</option>
                                                </select>
                                                <input type ="text" value="" maxlength="10" class ="create-new-lead-phone-no-list" id="add_more_phone_number_id" name ="create_lead_phone_no[]">
                                                <a id="crea_add_more_phone_delete" class="crea_add_more_phone_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class ="add_more_phone_delete_icon" alt="delete" width="20" height="20"></a>
                                            </p><?php 
                                        }
                                    } else { ?>
                                        <p id="crea_phone_p_id" class="crea_main_phone_p_class">
                                            <select id="aretk_crea_agent_phone_type" class="phone_type" name="crea_agent_phone_type[]">
                                                <option value="" >Select Type</option>
                                                <option value="Home" >Home</option>
                                                <option value="Mobile" >Mobile</option>
                                                <option value="Fax" >Fax</option>
                                            </select>
                                            <input type ="text" maxlength="10" value="" class ="create-new-lead-phone-no-list" id="add_more_phone_number_id" name ="create_lead_phone_no[]">
                                            <a id="crea_add_more_phone_delete" class="crea_add_more_phone_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class ="add_more_phone_delete_icon" alt="delete" width="20" height="20"></a>
                                        </p><?php 
                                    } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="select-all-type-validation-error set_message" style="display:none"></td>
                            <td class="select-phone-type-error set_message" style="display:none"></td>
                            <td class ="crea-phone-error-message set_message" style="display:none"></td>    
                            <td><label for="aretk_crea_agent_phone_type" generated="true" class="error" style="display:none"></label></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type ="button" id ="new-lead-phone" class = "new-lead-another-phone-button button button-primary" value ="Add Another Phone"><p class="crea_phone_or_type_not_null" style="display:none;"><?php echo __('Please Fill Above Phone Option',ARETKCREA_PLUGIN_SLUG); ?></p></td> 
                        </tr>
                        <tr class ="add_more_email_tr">
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_EMAIL, ARETKCREA_PLUGIN_SLUG); ?><span class ="">*</span></td>
                            <td class ="add_more_email_td"><?php
                                if (!empty($crea_lead_ID) && $crea_lead_ID != '') {
                                    if (!empty($crea_lead_phone_email_unserialize) && $crea_lead_phone_email_unserialize != '') { 
                                        if( is_array($crea_lead_phone_email_unserialize)) { ?>
                                            <div id ="add_more_email" class="crea_multiple_email_add_feature"><?php 
                                            foreach ($crea_lead_phone_email_unserialize as $crea_lead_phone_email_key => $crea_lead_phone_email_value) { ?>
                                                <p class="crea_multiple_email_p_tag" id="crea_more_email_add_p_tag<?php if( $crea_lead_phone_email_key == 0 ) { echo ''; } else{ echo $crea_lead_phone_email_key; } ?>">
                                                    <input class="email_add" type ="text" id="email_name<?php if( $crea_lead_phone_email_key == 0 ) { echo ''; } else{ echo $crea_lead_phone_email_key; } ?>" value="<?php echo isset($crea_lead_phone_email_value) ? $crea_lead_phone_email_value : ''; ?>" name ="create_lead_phone_email[]">
                                                    <input type="radio" name="PrimaryEmail" <?php if ($crea_lead_primary_email == $crea_lead_phone_email_value) { echo 'checked'; } ?>  class ="create-new-lead-phone-no-list primery_mail_check" id="create_new_lead_primart_mail<?php if( $crea_lead_phone_email_key == 0 ) { echo ''; } else{ echo $crea_lead_phone_email_key; } ?>" value="<?php echo $crea_lead_primary_email; ?>">Make Primary Email
                                                    <a id="crea_add_more_email_delete<?php if( $crea_lead_phone_email_key == 0 ) { echo ''; } else{ echo $crea_lead_phone_email_key; } ?>" class="crea_add_more_email_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
                                                    <label class="validation_email_mess"></label>
                                                </p><?php 
                                            } ?>
                                            </div><?php 
                                        } else { ?>
                                            <div id ="add_more_email" class="crea_multiple_email_add_feature">
                                            <p class="crea_multiple_email_p_tag" id="crea_more_email_add_p_tag"><input class="email_add" value="<?php echo $crea_lead_phone_email_unserialize; ?>" type ="text" id="email_name" name ="create_lead_phone_email[]">
                                                <input type="radio" name="PrimaryEmail" <?php if ($crea_lead_primary_email == $crea_lead_phone_email_unserialize) { ?> checked value="<?php echo $crea_lead_phone_email_unserialize; ?>" <?php } ?> class ="create-new-lead-phone-no-list primery_mail_check" id="create_new_lead_primart_mail">Make Primary Email
                                                <a id="crea_add_more_email_delete" class="crea_add_more_email_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
                                                <label class="validation_email_mess"></label>
                                            </p>
                                            </div><?php 
                                        }
                                    } else { ?>
                                        <div id ="add_more_email" class="crea_multiple_email_add_feature">
                                            <p class="crea_multiple_email_p_tag" id="crea_more_email_add_p_tag"><input class="email_add" type ="text" value="" id="email_name" name ="create_lead_phone_email[]">
                                                <input type="radio" name="PrimaryEmail" class ="create-new-lead-phone-no-list primery_mail_check" id="create_new_lead_primart_mail" value="">Make Primary Email
                                                <a id="crea_add_more_email_delete" class="crea_add_more_email_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
                                                <label class="validation_email_mess"></label>
                                            </p>
                                         </div>
                              <?php }
                                } else {
                                    ?>
                                    <div id ="add_more_email" class="crea_multiple_email_add_feature">
                                        <p class="crea_multiple_email_p_tag" id="crea_more_email_add_p_tag">
                                            <input class="email_add" type ="text" value="" id="email_name" name ="create_lead_phone_email[]">
                                            <input type="radio" name="PrimaryEmail" class ="create-new-lead-phone-no-list primery_mail_check" id="create_new_lead_primart_mail" value="">Make Primary Email
                                            <a id="crea_add_more_email_delete" class="crea_add_more_email_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
                                            <label class="validation_email_mess"></label>
                                        </p>
                                     </div>
                          <?php } ?>
                            </td>    
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type ="button" id ="create-new-lead-email"class = "new-lead-another-phone-button  button button-primary" value ="Add Another Email"><p class ="crea-email-error-message set_email_error_msg" style="display:none"></p></td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_COMPANY, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><input type ="text" class="crea_lead_company" id="crea_lead_company_id"  value="<?php echo isset($crea_create_lead_company) ? $crea_create_lead_company : ''; ?>" name ="create_lead_company"></td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_ADDRESS_ONE, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><input type ="text" class="crea_lead_address" id="crea_lead_address_id" value="<?php echo isset($crea_lead_address_line) ? $crea_lead_address_line : ''; ?>" name ="create_lead_address_line1"></td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_PROVINCE, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><input type ="text" class="crea_lead_province" id="crea_lead_province_id" value="<?php echo isset($crea_lead_province) ? $crea_lead_province : ''; ?>"  name ="create_lead_province"></td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_CITY, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><input type ="text" class="crea_lead_city" id="crea_lead_city_id" value="<?php echo isset($crea_lead_city) ? $crea_lead_city : ''; ?>"  name ="create_lead_city"></td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_COUNTRY, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><input type ="text" class="crea_lead_country" id="crea_lead_country_id" value="<?php echo isset($crea_create_lead_country) ? $crea_create_lead_country : ''; ?>"  name ="create_lead_country"></td>
                        </tr>
                        <tr>
                            <td class="social_url_vertical_aling"><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_SOCIAL, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td>
                                <div id="add_more_social_type"><?php
                                    if (!empty($crea_lead_ID) && $crea_lead_ID != '') {
                                        if (!empty($crea_agent_social_type_unserialize) && $crea_agent_social_type_unserialize != '') {
                                            foreach ($crea_agent_social_type_unserialize as $crea_agent_social_type_key => $crea_agent_social_type_value) { ?>
                                                <p class="crea_add_more_social_link_main" id="add_more_crea_social_link_id<?php if( $crea_agent_social_type_key == 0 ) { echo'';} else{ echo $crea_agent_social_type_key;}?>">
                                                    <input type ="text" id="social_url_new<?php if( $crea_agent_social_type_key == 0 ) { echo'';} else{ echo $crea_agent_social_type_key;}?>" value="<?php echo isset($crea_agent_social_type_value['SocialLink']) ? $crea_agent_social_type_value['SocialLink'] : ''; ?>" class ="create-new-lead-social-url" name ="create_lead_social_url[]">
                                                    <select id="aretk_crea_new_lead_social_link<?php if( $crea_agent_social_type_key == 0 ) { echo'';} else{ echo $crea_agent_social_type_key;}?>" class="social_type" name="crea_agent_social_type[]">
                                                        <option value="" <?php if ($crea_agent_social_type_value['SocialType'] == '') { echo' selected'; } ?>>Select Type</option>
                                                        <option value="Facebook" <?php if ($crea_agent_social_type_value['SocialType'] == 'Facebook') { echo' selected'; } ?>>Facebook</option>
                                                        <option value="Twitter" <?php if ($crea_agent_social_type_value['SocialType'] == 'Twitter') { echo' selected'; } ?>>Twitter</option>
                                                        <option value="LinkedIn" <?php if ($crea_agent_social_type_value['SocialType'] == 'LinkedIn') { echo' selected'; } ?>>LinkedIn</option>
                                                        <option value="Pinterest" <?php if ($crea_agent_social_type_value['SocialType'] == 'Pinterest') { echo' selected'; } ?>>Pinterest</option>
                                                    </select>
                                                    <a id="crea_add_more_social_delete<?php if( $crea_agent_social_type_key == 0 ) { echo'';} else{ echo $crea_agent_social_type_key;}?>" class="crea_add_more_social_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" alt="delete" class="social-link-delete-icon" width="20" height="20"></a>
                                                </p><?php
                                            }
                                        } else { ?>
                                            <p class="crea_add_more_social_link_main" id="add_more_crea_social_link_id">
                                                <input type ="text" id="social_url_new" class ="create-new-lead-social-url" name ="create_lead_social_url[]">
                                                <select id="aretk_crea_new_lead_social_link" class="social_type" name="crea_agent_social_type[]">
                                                    <option value="">Select Type</option>
                                                    <option value="Facebook">Facebook</option>
                                                    <option value="Twitter">Twitter</option>
                                                    <option value="LinkedIn">LinkedIn</option>
                                                    <option value="Pinterest">Pinterest</option>
                                                </select>
                                            </p><?php 
                                        }
                                    } else { ?>
                                        <p class="crea_add_more_social_link_main" id="add_more_crea_social_link_id">
                                            <input type ="text" id="social_url_new" class ="create-new-lead-social-url" name ="create_lead_social_url[]">
                                            <select id="aretk_crea_new_lead_social_link" class="social_type" name="crea_agent_social_type[]">
                                                <option value="">Select Type</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="LinkedIn">LinkedIn</option>
                                                <option value="Pinterest">Pinterest</option>        
                                                <option value="Twitter">Twitter</option>
                                                <option value="YouTube">YouTube</option>
                                            </select>
                                        </p>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type ="button" id ="new-lead-social-link" class = "new-lead-another-phone-button button button-primary" value ="Add Another Social Url">
                                <p class ="crea-all-social-validation-error-message" style="display:none"></p>
                                <p class ="crea-social-error-message" style="display:none"></p>
                                <p class="select-social-type-error" style="display:none"></p>
                                </td>
                        </tr>
                        <tr>
                            <td><?php echo __(ARETKCREA_CREATE_NEW_LEAD_FORM_COMMENT, ARETKCREA_PLUGIN_SLUG); ?></td>
                            <td><textarea class="crea_lead_comments" id="crea_lead_textare_comment" name ="comment"  cols="60" rows="6" placeholder = "Enter comments here"><?php echo isset($crea_lead_comment) ? $crea_lead_comment : ''; ?></textarea></td>
                        </tr>                       
                        <tr>
                            <td><p><input type="hidden" name="hidden"></p></td>
                        </tr>
                        <tr><?php
                            $btn_value = '';
                            if (!empty($crea_lead_ID) && $crea_lead_ID != '') {$btn_value = 'Update';} else {$btn_value = 'Save';} ?>
                            <td></td>
                            <td>
                            <input type="submit" class="new-lead-form-save-button create-new-lead-phone-no-list button button-primary" value="<?php echo $btn_value; ?>" />
                            <input type ="reset" id="crea-lead-cancel-btn" class="new-lead-form-save-button create-new-lead-phone-no-list button button-primary" value="Cancel" /></td>
                        </tr>
                    </table>
                </div>
                <div class="crea_lead_right_part_html">
                <?php if( !empty($crea_lead_ID) && $crea_lead_ID!='') { ?>              
                    <div class="crea_all_corresponding_listing_contnent"><?php 
                    global $wpdb;
                    $post_meta_table = $wpdb->prefix.'postmeta';
                    $sql_select = "SELECT `meta_id`, `meta_key`, `meta_value` FROM `$post_meta_table` WHERE `meta_key` LIKE 'crea_lead_corrsponding_text%' AND `post_id` = %d ORDER BY `meta_id` DESC"; 
                    $sql_prep = $wpdb->prepare( $sql_select, $crea_lead_ID, NULL );
                    $get_lead_corrsponding_ids_count = $wpdb->get_results($sql_prep);   
                    if( !empty($get_lead_corrsponding_ids_count) && $get_lead_corrsponding_ids_count != '' ) { ?>
                        <div class="set_all_corrsponding_result">
                            <div class="admin_sub_heading">Correspondence History</div><?php 
                            foreach ( $get_lead_corrsponding_ids_count as $get_lead_corrsponding_ids_key=>$get_lead_corrsponding_ids_value ) {                              
                                $corrsponding_messages = $get_lead_corrsponding_ids_value->meta_value;
                                $corrsponding_messages = json_decode($corrsponding_messages);
                                if( is_array($corrsponding_messages) ) {
                                    $corrsponding_message = $corrsponding_messages[1];
                                    $correspondace_date = $corrsponding_messages[2];
                                    $corrsponding_subject = $corrsponding_messages[3];
                                    $corrsponding_type = $corrsponding_messages[4];                             
                                } else {
                                    $corrsponding_message = $corrsponding_messages;
                                    $correspondace_date = '';
                                    $corrsponding_subject = '';
                                    $corrsponding_type = '';
                                } ?>
                                <div class="crea_corrsponding_row">
                                    <div class="correspondence_genwrap">
                                        <a id="crea_add_corrsponding_delete<?php echo $get_lead_corrsponding_ids_value->meta_id;?>" class="crea_add_corrsponding_delete_action" href="javascript:void(0);"><img src="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
                                        <div class="display_correpond_current_date"><span><?php echo $correspondace_date; ?></span></div>
                                        <div class="correspondence_type test00"><?php echo $corrsponding_type; ?></div><?php
                                        if ($corrsponding_type !== 'note'){ ?>
                                            <div class="correspondence_subject"><span>Subject:</span><?php echo $corrsponding_subject;?></div>
                                            <div class="correspondence_toggle">[ <a class="correspondence_toggle" href="#">view</a> ]</div><?php
                                        } ?>            
                                    </div>
                                    <div class="correspondence_detwrap correspondence_<?php echo str_replace(' ', '-', $corrsponding_type); ?>"><?php
                                        echo html_entity_decode($corrsponding_message); ?>                      
                                    </div>                                  
                                </div><?php 
                            } ?>
                        </div><?php 
                    } ?>
                    </div>
                    <div class="succesful_msg_lead_csv_correspond" style="display:none"></div>                  
                    <div id="add_correspondence_note">
                        <input type="button" id="crea_add_new_correspondence_body_content" class="button button-primary" value="<?php echo __(ARETKCREA_LEAD_ADD_CORRESPONDENCE_BTN,ARETKCREA_PLUGIN_SLUG); ?>" >                   
                        <div class="crea_add_corrsponding_body_content" style="display:none;">
                            <textarea name="crea_add_corrsponding_content_area" id="crea_new_corrsponding_area_content_box"></textarea>
                            <input type="button" id="crea_save_lead_correspondence_btn" class="button button-primary" value="Update" >
                            <input type="reset" id="crea_cancel_lead_correspondence_btn" class="button button-primary" value="Cancel" >
                            <p class="crea_corrsponding_erro_msg" style="display:none;"></p>
                        </div>
                    </div><?php 
                } ?>
                <div class="lead_reminder" style="float: left;width: 100%;margin: 20px;"><?php  
                    if ( $crea_lead_ID !='' && !empty($crea_lead_ID) ) { ?>
                        <a href="javascript:void(0);" id="add_lead_reminder" class="button button-primary">Add Reminder</a><?php 
                    } 
                    if( $crea_lead_ID !='' && !empty($crea_lead_ID) ) { ?>
                        <div class="reminder_list" id="reminder_list"><?php
                        $lead_reminder_id = $crea_lead_ID ;
                        $reminderTableName =  $wpdb->prefix.ARETKCREA_LEAD_REMINDER_HISTORY;
                        $sql_select = "SELECT * FROM `$reminderTableName` WHERE `reminder_lead_id` = %d ORDER BY `id` ASC"; 
                        $sql_prep = $wpdb->prepare( $sql_select, $crea_lead_ID );
                        $getReminderResults = $wpdb->get_results($sql_prep);
                        if ( !empty( $getReminderResults) &&  $getReminderResults !='' ) {
                            $reminderCounter = 1;
                            foreach ( $getReminderResults as $getReminderResultsValue ) { ?>
                            <div id="addNewReminerMain<?php echo $reminderCounter; ?>" class="crea_reminder_display">
                                <table width="100%" class="create-new-lead-table">
                                    <tbody>
                                        <tr>
                                            <td><p style="margin-bottom:0;">Email - The address of the person receiving the reminder</p><p class="set_reminder_text reminder_text_email">Email<span class="required_fields">*</span></p></td>   
                                            <td><input class="set_text_fields crea_lead_reminder_email_text" type="text" value="<?php echo isset( $getReminderResultsValue->reminder_email ) ? $getReminderResultsValue->reminder_email :''; ?>" name="crea_lead_reminder_email" id="crea_lead_reminder_text_email<?php echo $reminderCounter; ?>"><p id="crea_reminder_email_error<?php echo $reminderCounter; ?>" style="display:none;" class="setErrmsg reminderemailError"></p><p id="crea_reminder_valid_email_error<?php echo $reminderCounter; ?>" style="display:none;" class="setErrmsg reminderemailErrorvalid"></p></td> 
                                        </tr>
                                        <tr>
                                            <td><p class="set_reminder_text reminder_text_subject">Subject<span class="required_fields">*</span></p></td>   
                                            <td><input class="set_text_fields crea_lead_reminder_subject_text" type="text" value="<?php echo isset( $getReminderResultsValue->reminder_subject ) ? stripslashes($getReminderResultsValue->reminder_subject) :''; ?>" name="crea_lead_reminder_subject" id="crea_lead_reminder_text_subject<?php echo $reminderCounter; ?>"><p id="crea_reminder_subject_error<?php echo $reminderCounter; ?>" style="display:none;" class="setErrmsg reminderSubjectsError"></p></td>   
                                        </tr>                                       
                                        <tr>
                                            <td><p class="set_reminder_text reminder_text_comment">Comment</p></td>
                                            <td><textarea class="set_text_fields crea_lead_reminder_comment_text" name="crea_lead_reminder_comment" id="crea_lead_reminder_text_comment<?php  echo $reminderCounter; ?>"><?php echo isset( $getReminderResultsValue->reminder_comment ) ? stripslashes($getReminderResultsValue->reminder_comment) :''; ?></textarea></td>                  
                                        </tr>
                                        <tr>
                                            <td><p class="set_reminder_text reminder_text_datetime">Date and Time<span class="required_fields">*</span></p></td>    
                                            <td><input class="set_text_fields crea_lead_reminder_datetime_text" type="text" value="<?php echo isset( $getReminderResultsValue->reminder_time ) ? $getReminderResultsValue->reminder_time :''; ?>" name="crea_lead_reminder_datetime" id="crea_lead_reminder_text_datetime<?php echo $reminderCounter; ?>"><p id="crea_reminder_datetime_error<?php echo $reminderCounter; ?>" style="display:none;" class="setErrmsg reminderdatetimeError"></p>
                                            </td>   
                                        </tr>
                                        <tr>
                                            <td><p class="set_reminder_text reminder_text_repeat">Repeat</p></td>   
                                            <td>
                                                <input id="crea_lead_no_repeat_remider_id<?php  echo $reminderCounter; ?>" type="radio" class="repeat_reminder_value crea_lead_reminder_No_repeat_text" name="crea_lead_reminder_repeat<?php  echo $reminderCounter; ?>" <?php if (isset($getReminderResultsValue->reminder_repeat) && $getReminderResultsValue->reminder_repeat=="no-repeat") echo "checked";?> value="no-repeat">No Repeat<br/>
                                                <input id="crea_lead_daily_repeat_remider_id<?php  echo $reminderCounter; ?>" type="radio" class="repeat_reminder_value crea_lead_reminder_daily_repeat_text" name="crea_lead_reminder_repeat<?php  echo $reminderCounter; ?>" <?php if (isset($getReminderResultsValue->reminder_repeat) && $getReminderResultsValue->reminder_repeat=="daily") echo "checked";?> value="daily">Daily<br/>
                                                <input id="crea_lead_weekly_repeat_remider_id<?php  echo  $reminderCounter; ?>" type="radio" class="repeat_reminder_value crea_lead_reminder_weekly_repeat_text" name="crea_lead_reminder_repeat<?php  echo $reminderCounter; ?>" <?php if (isset($getReminderResultsValue->reminder_repeat) && $getReminderResultsValue->reminder_repeat=="weekly") echo "checked";?> value="weekly">Weekly<br/>
                                                <input id="crea_lead_monthly_repeat_remider_id<?php  echo  $reminderCounter; ?>" type="radio" class="repeat_reminder_value crea_lead_reminder_monthly_repeat_text" name="crea_lead_reminder_repeat<?php  echo $reminderCounter; ?>" <?php if (isset($getReminderResultsValue->reminder_repeat) && $getReminderResultsValue->reminder_repeat=="monthly") echo "checked";?> value="monthly">Monthly<br/>
                                                <input id="crea_lead_yearly_repeat_remider_id<?php   echo $reminderCounter; ?>" type="radio" class="repeat_reminder_value crea_lead_reminder_yearly_repeat_text" name="crea_lead_reminder_repeat<?php  echo $reminderCounter; ?>" <?php if (isset($getReminderResultsValue->reminder_repeat) && $getReminderResultsValue->reminder_repeat=="yearly") echo "checked";?> value="yearly">Yearly<br/>
                                            </td>   
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit_block">
                                    <a href="javascript:void(0);" id="update_lead_reminder_ajax<?php echo $reminderCounter; ?>" class="button button-primary crea_lead_update_reminder">Update</a>
                                    <a href="javascript:void(0);" id="remove_lead_reminder<?php echo $reminderCounter; ?>" class="button button-primary crea_lead_remove_reminder">Remove Reminder</a>
                                    <?php if( !empty($lead_reminder_id) && $lead_reminder_id !='' ) { ?>
                                        <input type="hidden" name="crea_lead_reminder_hiiden_id" class="crea_lead_reminder_unique_id" value="<?php echo $lead_reminder_id; ?>" id="lead_reminder_hidden_id<?php echo $reminderCounter; ?>">
                                        <input type="hidden" name="crea_lead_reminder_Table_hiiden_id" class="crea_lead_reminder_table_id" value="<?php echo $getReminderResultsValue->id; ?>" id="lead_reminder_table_hidden_id<?php echo $reminderCounter; ?>">
                                    <?php } ?>
                                    <div id="aretk_update_lead_reminder_cron_disclaimer">Note: email delivery times are approximate</div>
                                </div>                              
                            </div><?php
                            $reminderCounter = $reminderCounter +1;
                            }
                        } ?>
                        </div><?php 
                    } else { ?>
                        <div class="reminder_list" id="reminder_list"></div><?php 
                    } ?>
                </div>
                </div>
            </div>
        </div>  
    </form><?php
}

/**
 * create function for aretkcrea_custom_send_email_leads_form
 * 
 * @return return html for the CREA send email leads tab
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author Aretk Inc
 * @param null
 */
function aretkcrea_custom_send_email_leads_form() { 
    global $wpdb;

    $lead_ids = isset($_REQUEST['lead_id']) ? (INT) $_REQUEST['lead_id'] : '';
    $lead_email_bcc = (isset($_REQUEST['email']) && $_REQUEST['email'] === 'bcc') ? 'bcc' : '';
    $all_aretk_bcc_email = array();
    $all_lead_primary_email = array();
    $all_lead_primary_email = array();
    $all_bcc_email = array();
    $all_selected_mail_bcc =''; 
    $lead_name = '';
    $crea_bulk_bcc_mail_custom = get_option('selected_lead_post_email_to_bcc');
    if( $lead_ids == '' && $lead_email_bcc === 'bcc' && !empty($crea_bulk_bcc_mail_custom) ) 
    {
        foreach ( $crea_bulk_bcc_mail_custom as $crea_bulk_bcc_mail_value ) 
        {
            $all_lead_primary_email[$crea_bulk_bcc_mail_value] = get_post_meta($crea_bulk_bcc_mail_value,'lead_primary_email',true);
            $all_lead_first_email[$crea_bulk_bcc_mail_value] = get_post_meta($crea_bulk_bcc_mail_value,'lead_phone_email',true);
        }
        foreach ( $all_lead_primary_email as $all_lead_primary_email_key => $all_lead_primary_email_value )
        {
            if( $all_lead_primary_email_value != "" && !empty($all_lead_primary_email_value)) {
                $all_bcc_email[$all_lead_primary_email_key] = $all_lead_primary_email_value;
            }
        }
        $lead_unserialize_array = array();
        $lead_unserialize_array_value = array();
        
        foreach ( $all_lead_first_email as $all_lead_first_email_key=>$all_lead_first_email_value ){
             $lead_unserialize_array = maybe_unserialize($all_lead_first_email_value);
             if( is_array($lead_unserialize_array)) {
                 $lead_unserialize_array_value[$all_lead_first_email_key] = $lead_unserialize_array[0];
             } else{ 
                $lead_unserialize_array_value[$all_lead_first_email_key] = $lead_unserialize_array;
             }
        }
        $merge_email_array_duplicate = $all_bcc_email + $lead_unserialize_array_value;
        $store_email_lead_ids = array();
        foreach ( $merge_email_array_duplicate as $merge_email_array_duplicate_key =>$merge_email_array_duplicate_value ) {
            $store_email_lead_ids[$merge_email_array_duplicate_key] = $merge_email_array_duplicate_value;
            $lead_ids .= $merge_email_array_duplicate_key .',';
        }
        $lead_ids = rtrim($lead_ids, ',');
        $add_multiple_email_lead_ids = $store_email_lead_ids;
        if( $add_multiple_email_lead_ids != '' && !empty($add_multiple_email_lead_ids) ) {
            update_option('crea_bulk_email_lead_ids', json_encode( $add_multiple_email_lead_ids ));
        }
        $all_selected_mail_bcc = implode(",", $merge_email_array_duplicate);
    } elseif (!empty($lead_ids) && is_numeric($lead_ids)){
        $lead_name = get_the_title($lead_ids);
        $all_lead_primay_email = get_post_meta($lead_ids, 'lead_primary_email', true);
        if(  $all_lead_primay_email == "" ) {
            $all_lead_first_email = maybe_unserialize( get_post_meta($lead_ids,'lead_phone_email',true) );
            $selected_mail_to = $all_lead_first_email[0];
        } else {
            $selected_mail_to = $all_lead_primay_email;
        }
        $add_single_array[$lead_ids] = $selected_mail_to;
        update_option('crea_bulk_email_lead_ids', json_encode( $add_single_array) );
    } else {
        update_option('crea_bulk_email_lead_ids', '' );
    }   
    $admin_email_address = get_option('admin_email'); 
    ?>
    <div class="crea-container"><?php
        if( !empty($lead_ids) && is_numeric($lead_ids) ) { ?>
            <div class="crea_send_lead_emails">
                <a href="<?php echo home_url("/wp-admin/admin.php?page=create_new_leads&ID=$lead_ids&action=edit"); ?>"><input type="button" class="button button-primary" value="<?php echo __(ARETKCREA_LEAD_BACK_TO_EDIT_LEAD_BTN,ARETKCREA_PLUGIN_SLUG); ?>"></a>
            </div><?php             
        } else { ?>
            <div class="crea_send_lead_emails">
                <ul class="leadsbuttons subsubsub">
                    <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=aretk_lead" id="leads" class="button button-primary aretk-leads"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                    <li class="add-new-lead"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=create_new_leads" id="add-new-lead" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                    <li class="add-end-email"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=send_email_leads" id="send-email" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                    <li class="lead-category"><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=lead-category"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
                    <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=leads_form" id="lead-forms" class="button button-primary aretk-leadforms"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                    <li class="import-lead activeleadpage"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=import_leads" id="import-lead-csv" class="button button-primary aretk-import-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                    <li class="export-lead"><a href="#"><input type="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
                    <li class="download-lead"><div class="download-export-csv"></div></li>
                </ul>
            </div><?php
        } ?>
        <div class="crea-lead-send-email-title"><h2><?php 
        if ( is_numeric($lead_ids) && !empty($lead_name) ){
            echo 'Email '. $lead_name;
        } else if (!empty($lead_ids) && preg_match('/^[0-9,]+$/', $lead_ids)){
            echo 'Bulk Email Leads';
        } else {
            echo __(ARETKCREA_PLUGIN_LEAD_SEND_EMAIL_TITLE, ARETKCREA_PLUGIN_SLUG); 
        } ?></h2></div>
        <form method="POST" class="lead_send_email" enctype="multipart/form-data">
            <div class="crea-lead-send-email-content">
                <div class="succes_message" style="display:none"></div>
                <div class="crea-lead-send-mail-subject set-content"><p>Subject<sup>*</sup></p><input type="text" value="" id='crea_send_email_lead_subject' name="send_email_subject"> 
                    <label for="crea_send_email_lead_subject" generated="true" class="error"></label>
                </div>
                <div class="crea-lead-send-mail-to-email set-content"><p>To</p><input id="crea_send_email_lead_to_email" type="text" name ="send_email_to" value="<?php
                if (!empty($selected_mail_to)){ echo $selected_mail_to; } ?>" />
                    <label class="lead_send_email_to_error_msg"></label>    
                </div>
                <div class="crea-lead-send-mail-cc-email set-content"><p>CC</p><input id="crea_send_email_lead_cc_email" value="" type="text" name="send_email_cc" /></div>
                <div class="crea-lead-send-mail-bcc-email set-content"><p>BCC</p><input id="crea_send_email_lead_bcc_email" type="text" name="send_email_bcc" value="<?php 
                if(isset( $all_selected_mail_bcc)&& !empty($all_selected_mail_bcc)) {
                    echo ','. $all_selected_mail_bcc;
                } ?>"<?php
                if (!empty($lead_ids) && preg_match('/^[0-9,]+$/', $lead_ids) && strpos($lead_ids, ',') !== false){
                    echo ' disabled="disabled"';
                }                
                ?> /><br /><p>&nbsp;</p><span id="crea-lead-send-mail_multiple_email_note"><?php
                if (!empty($lead_ids) && preg_match('/^[0-9,]+$/', $lead_ids) && strpos($lead_ids, ',') !== false){
                    echo 'Note: The BCC field cannot be updated when sending a bulk email to your leads.';
                } else {
                    echo 'Note: You can send multiple emails at the same time, just separate each email with a comma.';
                }?></span>          
                </div>
                <div class="crea-lead-send-mail-body-content set-content"><p>Body<sup>*</sup></p><?php
                    $editor_id = 'crea_send_email_body';
                    wp_editor('', $editor_id, $settings = array('editor_height' => 200, 'textarea_name' => 'crea_email_body_content', 'quicktags' => false, 'media_buttons' => false, 'wpautop' => false)); ?>         
                </div>
                <label class="lead_send_email_editor_error_msg"></label>
                <div class="crea-lead-send-mail-attachment set-content"><p></p>
                    <input type="file" id="crea_leads_send_email_browse" name="crea_lead_send_email_file_upload"/>
                </div>            
                <div class="crea-lead-send-email-button">
                    <div class="succes_message" style="display:none; margin-bottom:10px;"></div>
                    <input type="submit" class="button button-primary" name="crea_send" id="crea_lead_send_email_btn" value="SEND">
                    <input type="reset" class="button button-primary" id="crea-lead-cancel-btn" value="CANCEL">
                </div>
            </div><?php
            if( !empty($lead_ids) && is_numeric($lead_ids) ) {
                echo '<input type="hidden" id="send_email_lead_id" value="'.$lead_ids.'">';
            } ?>
        </form>
    </div><?php
}

/**
 * create function for aretkcrea_custom_support_settings_html
 * 
 * @return return html for the CREA support Settings sixth tab.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc
 * @param null
 */
function aretkcrea_custom_support_settings_html() 
{ 
    $getSubscriptionStatus = get_option('crea_subscription_status', '');
    ?>
    <div class="support-settings-container">
    <input type = "hidden" id ="areatk_plugin_url" value="<?php echo ARETK_CREA_PLUGIN_URL; ?>">
        <div class="crea-plugin-title remove-border">
            <h2><?php echo __(ARETKCREA_SUPPORT_QA_HEADING, ARETKCREA_PLUGIN_SLUG); ?></h2>
        </div>
        <div class="support-ticket-btn-main">
            <label class="support-tic-label"><?php echo __(ARETKCREA_SUPPORT_QA_TITLE, ARETKCREA_PLUGIN_SLUG); ?></label>
            <?php
            if ($getSubscriptionStatus === 'not-valid' || empty($getSubscriptionStatus) || $getSubscriptionStatus == "" ) {
                ?>
                <a href="https://aretk.com/contact/" target="_blank"><input type="button"  name="support-submit-ticket" value="<?php echo __(ARETKCREA_SUPPORT_SUBMIT_TICKET_BTN, ARETKCREA_PLUGIN_SLUG) ?>" class="button button-primary ticket-btn"></a>
                <?php
            } else {
                ?>
                <a href="https://www.aretk.com/my-account/#forum" target="_blank"><input type="button" name="support-view-forum" value="<?php echo __(ARETKCREA_SUPPORT_VIEW_FORUM_BTN, ARETKCREA_PLUGIN_SLUG); ?>" class="button button-primary view-forum-btn"></a>
                <a href="https://www.aretk.com/my-account/#support-ticket" target="_blank"><input type="button"  name="support-submit-ticket" value="<?php echo __(ARETKCREA_SUPPORT_SUBMIT_TICKET_BTN, ARETKCREA_PLUGIN_SLUG) ?>" class="button button-primary ticket-btn"></a>                
                <?php
            } ?>            
        </div>
        <div class="main">
            <div class="accordion">
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#subscription-crea-listing"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_USE_PLUGIN_WITHOUT_SUBSCRIPTION, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="subscription-crea-listing" class="accordion-section-content">
                        <p>Yes, absolutly. The CREA DDF<sup>&reg;</sup> add-on is needed if you are a Canadaian REALTOR® and would to automatically sync the CREA DDF<sup>&reg;</sup> listings within your website.  Without this add-on you will need to manually add your listings under the 'Listings' tab and click on ADD NEW LISTING.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-sign-up-aretk-subscription"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_I_SIGN_UP_SUBSCRIPTION, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-sign-up-aretk-subscription" class="accordion-section-content">
                        <p>Under the Subscription Tab, click on GET API KEY. This will bring you to <a target="_blank" href="https://aretk.com/" class="crea_set_subscription_links">www.aretk.com</a> where you will sign up for a subscription for your website. Each API Key is only valid for 1 primary domain name. If you have multiple websites, then you will need to purchase an API key for each website. Once you receive your API key, copy and paste it into your Aretk Plugin under the Subscription tab.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-much-aretk-subscription"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_MUCH_IS_SUBSCRIPTION, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-much-aretk-subscription" class="accordion-section-content">
                        <p>An Aretk Subscription is $25/month. This is purchased on a month to month basis and can be cancelled anytime.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#can-i-cancel-my-aretk-subscription"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_CAN_I_CANCEL_SUBSCRIPTION, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="can-i-cancel-my-aretk-subscription" class="accordion-section-content">
                        <p>Yes, once you cancel the Aretk Subscription, the CREA listings will no longer be viewed on your website. But you can continue to use your Aretk Plugin and all the listings showcases with your own manually entered Listings as well as continue to use the Contact Management System and all the Contact Forms.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-filter-feed-showcase"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_FILTER_LISTING_SHOWCASE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-filter-feed-showcase" class="accordion-section-content">
                        <p>Go to the Showcase Tab and click on the Edit button (pencil) beside the default listing showcase. This will bring you to the Feeds page where you can select which feed you want to use as well if you want to include your Exclusive listings along with the feed. Then click on the Display button and you can select a List View, Grid View, Carousel View, Map View or Slider. You can filter based the location or show only new listings or open houses. Under the Settings Tab you can choose to add a Search Bar or not as well as other setting options. Lastly is the Colours tab where you can make some changes to the colours displayed. Save the changes, they will automatically be reflected in the listings default wordpress page created by your plugin.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-create-additional-listing-showcase"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_CREATE_ADDITIONAL_SHOWCASE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-create-additional-listing-showcase" class="accordion-section-content">
                        <p>To create additional show cases, click on "Add New Showcase" on the top left hand corner of the Showcases tab. Select the DDF<sup>&reg;</sup> feed you would like to use for this showcase and select whether you want to include your manually entered exclusive listings within this showcase. Select one of the 5 different showcases available, LIST, GRID,, CAROUSEL, MAP and SLIDERS. You can also filter by area, newest listings and/or only to display Open Houses. Once the settings and colours have been selected, Save the new Showcase. This new showcase will now be listed in your Showcase Tab. You can then copy and paste the shortcode into any Wordpress page.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-i-change-default-listing-details-page"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_MAKE_CHANGE_DEFAULT_PAGE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-i-change-default-listing-details-page" class="accordion-section-content">
                        <p>Go to the Showcase page and Edit button (pencil) beside the Listings Details Showcase. There you can update the colours and settings such as including agent information, contact form, Google map, Walk Score, print button, and email address and Save. This will automatically be reflected in the Listing Details Page created by the plugin within your WordPress website.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-to-put-search-box-into-another-page"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_PUT_ANOTHER_WORDPRESS_PAGE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-to-put-search-box-into-another-page" class="accordion-section-content">
                        <p>In the Showcase tab, there is a Default Search Showcase, you can copy and paste this short-code into any Wordpress Website.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-i-change-feed-search-box"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_CHANGE_SERCH_BOX_FEED, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-i-change-feed-search-box" class="accordion-section-content">
                        <p>Create a NEW Listing Showcase and choose the feed you want to use as well as any of the filters/settings. Then edit the Default Search Showcase tab and under the settings tab you can select from all the showcase you have created and you can pick from them which you would like to search and post the results to. By default, the Search Showcase will search the first data feed in your plugin and your Exclusive Listings and will display in the default Listing Showcase (Grid Layout). If you do not have an active Aretk CREA DDF<sup>&reg;</sup> Subscription, this search box will only search your exclusive listings.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-i-add-exclusive-listing-showcase"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_ADD_LISTING_SHOWCASE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-i-add-exclusive-listing-showcase" class="accordion-section-content">
                        <p>Go to the Listings tab in your Aretk Plugin. If you have activated your Aretk CREA DDF<sup>&reg;</sup> subscription then all the listings displayed will be associated to the Agent ID that you have entered in the CREA DDF<sup>&reg;</sup> SETTINGS Tab. You can click on "Add new Listing" on the top right corner to add Exclusive Listings. If you have not activated your Aretk CREA DDF<sup>&reg;</sup> subscription, there will be no listings displayed until you manually enter your own listings by clicking "Add New Listing".</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#can-i-edit-my-listing-page"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_CAN_I_EDIT_LISTING_PAGE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="can-i-edit-my-listing-page" class="accordion-section-content">
                        <p>You can only edit the manually entered exclusive listings. You can not edit any of the listings have been downloaded from CREA. If any of these listings are displaying incorrect information, it could be because it hasn`t been updated yet (can take up to 24 hours to update changes made with your board) or you need to make those updates with your board so your website and www.realtor.ca receive the updates.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-i-add-aretk-contact-management-system"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_ADD_CONTENT_MANAGEMENT_SYSTEM, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-i-add-aretk-contact-management-system" class="accordion-section-content">
                        <p>Under the Leads tab there is a subtab called "Leads Form". This shows 3 pre-created forms for you to use in your website, CONTACT FORM, BUYER FORM and SELLER FORM. These three forms have short-codes that you can copy and paste into any WordPress page. All leads received from these forms will be captured in your LEADS section of your Aretk Plugin. An email will also be sent to is the email address listed in the WordPress settings page of your WordPress Dashboard.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#how-do-i-upload-aretk-contact-management-system"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_UPLOAD_CONTENT_MANAGEMENT_SYSTEM, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="how-do-i-upload-aretk-contact-management-system" class="accordion-section-content">
                        <p>In your Outlook, you can export your contacts into either a "comma separated values" or csv format. Save this file onto your computer and then in your plugin in the LEADS tab, click on "Import Leads" and upload this CVS file you have saved.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#what-is-the-walk-score-api"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_WHAT_IS_THE_WALK_SCORE_API, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="what-is-the-walk-score-api" class="accordion-section-content">
                        <p>Walk Score gives each property a rating as to how walkable it is to the nearest ammentities. If you want your real estate listings to display a walk score, you need to get a Walk Score widget API key and enter it into your ARETK plugin settings. To get your Walk Score API key go to this website - <a target="_blank" href="https://www.walkscore.com/professional/sign-up.php">https://www.walkscore.com/professional/sign-up.php</a> and sign up - it's free! Once you have signed up with Walk Score they will email you an API key which you will need to copy and paste it into your ARETK plugin under settings.</p>
                    </div>
                </div>
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#what-is-the-google-map-api"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_WHAT_IS_THE_GOOGLE_MAP_API, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="what-is-the-google-map-api" class="accordion-section-content">
                        <p>The Google Maps API key allows you to display Google Maps within your website. To get a Google Maps API key, go to this site  - <a target="_blank" href="https://developers.google.com/maps/web/">https://developers.google.com/maps/web/</a> and click on "GET A KEY". If you are not already logged into Google you will be required to at this point.  Once logged in select "create a new project" and give your project a name, like "Website Gmap API" and then click the button labeled ""CREATE AND ENABLE API" to continue. You will get an API Key, copy and paste this API key into your ARETK plugin settings.  Note: to improve your API key security, restrict this key's usage in the API Console - click the link to access the API console.  Under key restrictions select "HTTP referrers" and then add your websites domain name in the feild provided and then save your changes.  This extra step will prevent others from using you API key.</p>
                    </div>
                </div>                
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#plugin-support-how-to-find-crea-user-name"><?php echo __(ARETKCREA_SETTING_INFORMATION_TITLE, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="plugin-support-how-to-find-crea-user-name" class="accordion-section-content">
                        <div class="crea_setting_inforn">
                            <p><?php echo __(ARETKCREA_SETTING_INFORMATION_TITLE, ARETKCREA_PLUGIN_SLUG); ?></p>
                            <div class="crea_inform_contain">
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_ONE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo1.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo1.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_ONE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_TWO, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo2.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo2.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_TWO_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_THREE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo3.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo3.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_THREE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                     <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-1.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-1.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-2.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo4-2.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_ONE, ARETKCREA_PLUGIN_SLUG); ?></p>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_TWO, ARETKCREA_PLUGIN_SLUG); ?></p>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_THREE, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FIVE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo5.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo5.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_FIVE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SIX, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo6.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo6.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SIX_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SEVEN, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo7.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo7.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p><?php echo __(ARETKCREA_SETTING_INFO_TITLE_SEVEN_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                     <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_EIGHT, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8-2.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo8-2.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_EIGHT_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                                <div class="set-crea-steps">
                                    <h4><?php echo __(ARETKCREA_SETTING_INFO_TITLE_NINE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                    <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo9.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo9.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                    <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_TITLE_NINE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>                
                <div class="accordion-section">
                    <a class="accordion-section-title" href="#crea-section-how-to-find-crea-support-id"><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_TO_FIND_CREA_ID, ARETKCREA_PLUGIN_SLUG); ?></a>
                    <div id="crea-section-how-to-find-crea-support-id" class="accordion-section-content">
                    <div class="crea_setting_inforn">
                        <p><?php echo __(ARETKCREA_SUPPORT_OA_TITLE_HOW_TO_FIND_CREA_ID, ARETKCREA_PLUGIN_SLUG); ?></p>                                             
                        <div class="crea_inform_contain">
                            <div class="set-crea-steps">
                                <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo10.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo10.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                <p><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </div>
                            <div class="set-crea-steps">
                                <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO, ARETKCREA_PLUGIN_SLUG); ?></h4>
                                <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo11.jpg'; ?>"><img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo11.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                <p><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </div>
                            <div class="set-crea-steps">
                                <h4><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE, ARETKCREA_PLUGIN_SLUG); ?></h4>
                               <a data-rel="lightcase" href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo12.jpg'; ?>"> <img src="<?php echo ARETK_CREA_PLUGIN_URL.'admin/images/photo12.jpg'; ?>" alt="photo" class="crea-set-images"></a>
                                <p class="text-justify"><?php echo __(ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE_CONT, ARETKCREA_PLUGIN_SLUG); ?></p>
                            </div>          
                        </div>                
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
}

/**
 * create function for custom import lead  * 
 * @return return html for the import lead page.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_import_leads_html() { ?>
    <div class="crea-container">
        <div class="import_lead_page">
            <ul class="leadsbuttons subsubsub">
                <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=aretk_lead" id="leads" class="button button-primary aretk-leads"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                <li class="add-new-lead"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=create_new_leads" id="add-new-lead" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                <li class="add-end-email"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=send_email_leads" id="send-email" class="button button-primary aretk-add-new-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                <li class="lead-category"><a href="<?php echo site_url(); ?>/wp-admin/edit-tags.php?taxonomy=lead-category"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
                <li class="leadforms"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=leads_form" id="lead-forms" class="button button-primary aretk-leadforms"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                <li class="import-lead activeleadpage"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=import_leads" id="import-lead-csv" class="button button-primary aretk-import-lead"><?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?></a></li>
                <li class="export-lead"><a href="#"><input type="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="<?php echo strtoupper(__(ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG)); ?>"></a></li>
                <li class="download-lead"><div class="download-export-csv"></div></li>
            </ul>           
            <div class="crea-plugin-title remove-border"><h2><?php echo __(ARETKCREA_LEAD_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG); ?></h2></div>
            <div class="succesful_msg_lead_csv" style="display:none"></div> 
            <div class="set_import_files">
                <input accept=".csv" type="file" name="crea_import_lead" id="crea_add_new_import_lead">
                <input type="button" class="button button-primary" name="import_new_lead" id="crea_import_lead_btn" value="<?php echo __(ARETKCREA_LEAD_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG); ?>">
                <p id="import_csv_error_msg" style="display:none;">Please choose only CSV format</p>
            </div>
            <div class="sample-csv-download-main">
                <p>Sample CSV file for Add Leads</p>
                <a href="<?php echo ARETK_CREA_PLUGIN_URL ?>admin/assets/import-lead.csv"><input type="button" name="csv-leads-download" value="Click to download" class="button button-primary"></a>           
            </div>
        </div>
    </div>
<?php 
}

/**
 * create function for aretkcrea_custom_plugin_settings_html
 * 
 * @return return html for the CREA custom Settings seventh tab.
 * @package Phase 1
 * @since Phase 1
 * @version 
 * @author Aretk Inc.
 * @param null
 */
function aretkcrea_custom_plugin_settings_html() {
    global  $wpdb;
    $get_google_map_script_load = 'yes';
    $get_google_map_script_load = get_option('crea_google_map_script_load_or_not');
    $script_selected = "checked";
    if(isset( $get_google_map_script_load ) && !empty( $get_google_map_script_load ) ) {
        if( $get_google_map_script_load == 'Yes' ) { 
            $script_selected = "checked";
        }  else { 
            $script_selected = "";
        }
    } ?>
    <div class="plugin-setting-container">
        <div class="crea-plugin-title remove-border"><h2><?php echo __(ARETKCREA_PLUGIN_SETTINGS_PAGE_TITLE, ARETKCREA_PLUGIN_SLUG); ?></h2></div>
        <div class="plugin-setting-contain">
            <div class="rows">
                <div class="left-rows"><label><?php echo __(ARETKCREA_WALK_SCORE_API_KEY, ARETKCREA_PLUGIN_SLUG); ?></label><br /><br /></div>
                <div class="right-rows">
                    <input type="text" name="walk-score-api-name"  id="walk-score-api-name" value="<?php echo esc_attr(get_option('walk-score-api-name')); ?>" class="api-key-set"/><br />
                    <a target="_blank" href="https://www.walkscore.com/professional/sign-up.php">Click here to sign up for a Walk Score API Key</a>
                </div>
            </div>
            <div class="rows">
                <div class="left-rows"><label><?php echo __(ARETKCREA_GOOGLE_MAP_API_KEY, ARETKCREA_PLUGIN_SLUG); ?></label><br /><br /></div><div class="right-rows"><input type="text" name="google-map-api-name"  id="google-map-api-name" value="<?php echo esc_attr(get_option('google-map-api-name')); ?>" class="api-key-set"/><br />
                <a target="_blank" href="https://developers.google.com/maps/web/">Click here to sign up for a Google Map API Key</a>                
                </div>
            </div> 
            <div class="rows">
                <div class="left-rows"><label><?php echo __(ARETKCREA_GOOGLE_MAP_SCRIPT_LOAD_OR_NOT, ARETKCREA_PLUGIN_SLUG); ?></label></div><div class="right-rows"><input class="crea_checkbox_set" type="checkbox" name="crea_google_map_api_allowed" id="crea_google_api_enable_disable"<?php echo $script_selected; ?> /></div>
            </div>
             <div class="rows">
                <div class="left-rows">
                    <label><?php echo __('Google reCAPTCHA Public Key', ARETKCREA_PLUGIN_SLUG); ?></label><br />
                </div>
                <div class="right-rows">
                    <input type="text" name="google-recaptcha-api-public" id="google-recaptcha-api-public" value="<?php echo esc_attr(get_option('aretk_googleCaptchaKey_public')); ?>" class="api-key-set"/><br />             
                </div>
            </div>
             <div class="rows">
                <div class="left-rows">
                    <label><?php echo __('Google reCAPTCHA Private Key', ARETKCREA_PLUGIN_SLUG); ?></label><br /><br />
                </div>
                <div class="right-rows">
                    <input type="text" name="google-recaptcha-api-private" id="google-recaptcha-api-private" value="<?php echo esc_attr(get_option('aretk_googleCaptchaKey_private')); ?>" class="api-key-set"/><br />
                    <a target="_blank" href="https://www.google.com/recaptcha/">Click here to sign up for Google's reCAPTCHA</a>                
                </div>
            </div>
            <div class="csv-download-main">
                <p><?php echo __(ARETKCREA_SAMPLE_CSV_FILE_FOR_ADD_LEADS, ARETKCREA_PLUGIN_SLUG); ?></p>
                <a href="<?php echo ARETK_CREA_PLUGIN_URL.'admin/assets/import-lead.csv'; ?>"><input type="button" name="csv-leads-download" value="<?php echo __(ARETKCREA_PLUGIN_SETTINGS_PAGE_DOWNLOAD, ARETKCREA_PLUGIN_SLUG); ?>" class="button"/></a>
            </div>
            <div class="rows">
                <input type="submit" name="crea_plugin_setting_save_keys" id="crea_plugin_setting_save_keys" class="crea-save-btn button button-primary crea_plugin_setting_save_keys" value="<?php echo __(ARETKCREA_PLUGIN_SETTINGS_PAGE_SAVE, ARETKCREA_PLUGIN_SLUG); ?>" />
                <span class="suceess_msg" style="display:none;color:green;padding:5px;"><?php echo __(ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_SUCESS, ARETKCREA_PLUGIN_SLUG); ?></span>
                <span class="error_msg" style="display:none;color:red;padding:5px;"><?php echo __(ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_ERROR, ARETKCREA_PLUGIN_SLUG); ?></span>
            </div>
        </div>       
    </div><?php
}