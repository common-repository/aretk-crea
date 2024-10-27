<?php
/**
 * define constant variabes
 * definr admin side constant
 * @package Phase 1
 * @since Phase 1
 * @version 1.0.0
 * @author ARETK
 *
 * @param null
 */

// define constant for table names
define( 'ARETKCREA_AGENT_TABLE', 'crea_agent' );
define( 'ARETKCREA_API_LOG', 'crea_api_log' );
define( 'ARETKCREA_API_LOG_EXCLUSIVE', 'crea_api_log_exclusive' );
define( 'ARETKCREA_USER_LISTING_DETAILS', 'crea_user_listing_detail' );
define( 'ARETKCREA_PLUGIN_SLUG', 'aretk-crea' );
define( 'ARETKCREA_LISTING_IMAGES_HISTORY', 'crea_listing_images_detail' );
define( 'ARETKCREA_LISTING_DOCUMENT_HISTORY', 'crea_listing_document_detail' );
define( 'ARETKCREA_LISTING_DETAIL_COUNT', 'crea_listing_detail_count' );
define( 'ARETKCREA_LEAD_REMINDER_HISTORY', 'crea_lead_reminder_detail' );

// define constant for SUBSCRIPTION Settings
define( "ARETKCREA_SUBSCRIPTION_SETTING_TITLE", "ARETK CREA DDF<sup>&reg</sup> SUBSCRIPTION SETTINGS" );
define( "ARETKCREA_SUBSCRIPTION_API_TITLE", "To display your CREA DDF<sup>&reg</sup> feeds, purchase a CREA Subscription at <a href=\"https://aretk.com/\" target=\"_blank\">www.aretk.com</a> and enter the unique domain specific ARETK API Key below." );
define( "ARETKCREA_SUBSCRIPTION_API_KEY", "ARETK API key:" );
define( "ARETKCREA_SUBSCRIPTION_API_ACCOUNT_STATUS", "Your account is: " );

// SUBSCRIPTION Settings Title
define( "ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN", "UPDATE" );
define( "ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_EROOR_MSG_BLANK", "Please enter the subscription key." );
define( "ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_EROOR_MSG_NOT_VALID", "Please enter the correct subscription key." );
define( "ARETKCREA_SUBSCRIPTION_API_UPDATE_BTN_SUCESS", "Congratulations you key is valid" );
define( "ARETKCREA_SUBSCRIPTION_API_STATUS_ACTIVE", "Active" );
define( "ARETKCREA_SUBSCRIPTION_API_STATUS_INACTIVE", "In Active" );
define( "ARETKCREA_SUBSCRIPTION_API_KEY_BTN", "GET API KEY" );
define( "ARETKCREA_SUBSCRIPTION_MANAGE_ACCOUNT_BTN", "MANAGE YOUR ACCOUNT" );

// define constant for CREA Settings
define( "ARETKCREA_SETTING_TITLE", "CREA DDF<sup>&reg</sup> SETTINGS" );
define( "ARETKCREA_SETTING_USER_ID", "#" );
define( "ARETKCREA_SETTING_USER_NAME", "CREA USER NAME" );
define( "ARETKCREA_SETTING_DDF_TYPE", "DDF TYPE" );
define( "ARETKCREA_SETTING_DDF_LISTING", "# OF LISTINGS" );
define( "ARETKCREA_SETTING_LAST_UPDATED", "LAST UPDATED" );
define( "ARETKCREA_SETTING_STATUS", "FEED STATUS" );

define( "ARETKCREA_SETTING_DDF_UPDATE", "UPDATE" );
define( "ARETKCREA_SETTING_DDF_UPDATE_DISCLAIMER", "UPDATE DISCLAIMER" );
define( "ARETKCREA_SETTING_HOW_TO_FIND_CREA_ID", "How to find out your CREA AGENT ID?" );

// DEFINE information content
define( "ARETKCREA_SETTING_INFORMATION_TITLE", "How do you get your CREA USER NAME?" );
define( "ARETKCREA_SETTING_INFO_TITLE_ONE", "Step 1" );
define( "ARETKCREA_SETTING_INFO_TITLE_ONE_CONT", 'Log into <a target="_blank" href="http://www.realtorlink.ca">www.realtorlink.ca</a> with your user name and password and indicate the Real Estate Board which you are a member.' );
define( "ARETKCREA_SETTING_INFO_TITLE_TWO", "Step 2" );
define( "ARETKCREA_SETTING_INFO_TITLE_TWO_CONT", 'Click on the "<b>DDF Dashboard</b>" on the right hand side menu.' );
define( "ARETKCREA_SETTING_INFO_TITLE_THREE", "Step 3" );
define( "ARETKCREA_SETTING_INFO_TITLE_THREE_CONT", 'Click on "<b>Create/Edit Data Feeds</b>"' );
define( "ARETKCREA_SETTING_INFO_TITLE_FOUR", "Step 4" );
define( "ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT", 'Click on "<b>Add a Feed</b>" and select which feed you would like to select' );
define( "ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_ONE", '1. National Shared Pool - All Listings in Canada from Brokerages that have opted into the National Shared Pool.' );
define( "ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_TWO", '2. Member Website Feed - My Listings - Just your personal Listings' );
define( "ARETKCREA_SETTING_INFO_TITLE_FOUR_CONT_RULE_THREE", '3. Member Website Feed - One or More Offices - Your Office(s) listings if your Brokerage has given you permission' );
define( "ARETKCREA_SETTING_INFO_TITLE_FIVE", "Step 5" );
define( "ARETKCREA_SETTING_INFO_TITLE_FIVE_CONT", 'Select the option "I will be engaging a 3rd party technology provider to operate my data feeds"' );
define( "ARETKCREA_SETTING_INFO_TITLE_SIX", "Step 6" );
define( "ARETKCREA_SETTING_INFO_TITLE_SIX_CONT", 'Select the 3rd party provider : ARETK Inc.' );
define( "ARETKCREA_SETTING_INFO_TITLE_SEVEN", "Step 7" );
define( "ARETKCREA_SETTING_INFO_TITLE_SEVEN_CONT", 'Enter your web site URL - Use your primary domain name you will be using for your website (eg. <a href="http://www.domainname.com/" target="_blank">www.domainname.com</a>)' );
define( "ARETKCREA_SETTING_INFO_TITLE_EIGHT", "Step 8" );
define( "ARETKCREA_SETTING_INFO_TITLE_EIGHT_CONT", 'If you want to filter your listings (for example: just show a select board listings) then click on "I want to use filters to limit the listings provided by the data feed". Go down to the filter of our choice (for example: Uploading board) and select "Show Filter and select your criteria". Then select your choice and click ADD. If you don`t want to filter, just continue to the next step.' );
define( "ARETKCREA_SETTING_INFO_TITLE_NINE", "Step 9" );
define( "ARETKCREA_SETTING_INFO_TITLE_NINE_CONT", 'Click on the button "Click to review your Data Feed before Saving it", review the information and if everything is correct scroll down to the bottom of the page, click on "Click here to agree to the terms of use and save your data feed". Scroll down to your list of data feeds and click EDIT to the data feed you just created and copy and paste the USER NAME listed beside the "Destination Credentials" into your ARETK Plugin and pick the DDF Type that most describes the data feed you created such as National Shared Pool, Board Listings, Personal Listings or Office listings to help with future reference.' );

// DEFINE CREA AGENT 
define( "ARETKCREA_SETTING_AGENT_TITLE", "AGENTS" );
define( "ARETKCREA_SETTING_AGENT_ADD", "Add Agent" );
define( "ARETKCREA_SETTING_AGENT_AUTO_ID", "#" );
define( "ARETKCREA_SETTING_AGENT_ID", "AGENT CREA ID" );
define( "ARETKCREA_SETTING_AGENT_NAME", "AGENT NAME" );
define( "ARETKCREA_SETTING_AGENT_EMAIL", "AGENT EMAIL" );
define( "ARETKCREA_SETTING_AGENT_ADD_DATE", "ADDED DATE" );
define( "ARETKCREA_SETTING_AGENT_ACTION", "ACTIONS" );
define( "ARETKCREA_SETTING_AGENT_PLACEHOLD_NAME", "Agent Name" );
define( "ARETKCREA_SETTING_AGENT_PLACEHOLD_ID", "Agent CREA ID" );
define( "ARETKCREA_SETTING_AGENT_PLACEHOLD_EMAIL", "Agent Email" );
define( "ARETKCREA_SETTING_AGENT_ADD_BTN", "ADD" );
define( "ARETKCREA_SETTING_POPUP_AGENT_DETAILS_UPDATE_BTN", "UPDATE" );

//DEFINE agent information content
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE", "Step 1" );
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_ONE_CONT", 'Go to <a href="https://www.realtor.ca" target="_blank">www.realtor.ca</a>' );
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO", "Step 2" );
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_TWO_CONT", 'Click on "Find a Realtor" and select "Realtor Search" and look up your name.' );
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE", "Step 3" );
define( "ARETKCREA_SETTING_INFO_AGENT_TITLE_THREE_CONT", 'Once you have selected your profile, the URL in the browser will show: <a href="https://www.realtor.ca/RealtorSearch.aspx?MessageId=NoRealtors" target="_blank" >https://www.realtor.ca/Residential/RealtorDetails.aspx?individualId=*******</a>  Instead of the astericks will be your CREA ID, take this CREA ID and enter it into your ' );

//DEFINE agent validation messages
define( "ARETKCREA_AGENT_NAME_NOT_NULL", "Please add CREA agent NAME" );
define( "ARETKCREA_AGENT_ID_NOT_NULL", "Please add CREA agent ID" );
define( "ARETKCREA_AGENT_EMAIL_NOT_NULL", "Please add CREA agent email" );
define( "ARETKCREA_AGENT_EMAIL_NOT_VALID", "CREA agent email not valid" );
define( "ARETKCREA_AGENT_ADD_SUCESSFULLY", "CREA agent Sucessfully added" );
define( "ARETKCREA_AGENT_ID_ALREADY_EXSITS", "Agent ID alreay exsits" );
define( "ARETKCREA_AGENT_ID_DELETE_CONFIRMATION", "Are you sure you want to remove this agent?" );
define( "ARETKCREA_AGENT_RECORD_UPDATE", "Record updated sucessfully!" ); #not used??

//DEFINE Buyer Form Content
define( "ARETKCREA_BUYER_FORM_NAME", "Name" );
define( "ARETKCREA_BUYER_FORM_EMAIL", "Email" );
define( "ARETKCREA_BUYER_FORM_PHONE", "Phone" );
define( "ARETKCREA_BUYER_FORM_ADDRESS", "Address" );
define( "ARETKCREA_BUYER_FORM_PREFERRED_METHOD", "Preferred method of contact" );
define( "ARETKCREA_BUYER_FORM_DESCRIPTION", "Description of the home you`re looking for" );
define( "ARETKCREA_BUYER_FORM_DESIRABLE_COMMUNITIES", "Desirable communities" );
define( "ARETKCREA_BUYER_FORM_PRICE_RANGE", "Price range" );
define( "ARETKCREA_BUYER_FORM_MINIMUM_BEDROOM", "Minimum number of bedrooms" );
define( "ARETKCREA_BUYER_FORM_MINIMUM_BATHROOM", "Minimum number of bathrooms" );
define( "ARETKCREA_BUYER_FORM_PLANNING", "How soon are you planning to buy?" );
define( "ARETKCREA_BUYER_FORM_REALTOR", "Are you currently working with a real estate agent?" );
define( "ARETKCREA_BUYER_FORM_COMMENT", "Comments" );
define( "ARETKCREA_BUYER_FORM_COMMENT_PLACEHOLDER", "Enter your comments here" );
define( "ARETKCREA_BUYER_FORM_SUCESS", "Your Form Submitted Successfully" );

//DEFINE Seller Form Content
define( "ARETKCREA_SELLER_FORM_TITLE", "Discover the true value of your home today for FREE" );
define( "ARETKCREA_SELLER_FORM_TITLE_ONE", "Please provide us with your details and we`ll get started right away" );
define( "ARETKCREA_SELLER_FORM_NAME", "Name" );
define( "ARETKCREA_SELLER_FORM_EMAIL", "Email" );
define( "ARETKCREA_SELLER_FORM_PHONE", "Phone" );
define( "ARETKCREA_SELLER_FORM_ADDRESS", "Property address" );
define( "ARETKCREA_SELLER_FORM_PREFERRED_METHOD", "Preferred method of contact" );
define( "ARETKCREA_SELLER_FORM_DESCRIPTION", "Description of your property" );
define( "ARETKCREA_SELLER_FORM_APPROXIMATE_SQUARE_FEET", "Approximate square feet" );
define( "ARETKCREA_SELLER_FORM_BEDROOM", "Number of bedrooms" );
define( "ARETKCREA_SELLER_FORM_BATHROOM", "Number of bathrooms" );
define( "ARETKCREA_SELLER_FORM_PLANNING", "How soon are you planning to sell?" );
define( "ARETKCREA_SELLER_FORM_REALTOR", "Are you currently working with a real estate agent? " );
define( "ARETKCREA_SELLER_FORM_COMMENT", "Addition information regarding your property" );
define( "ARETKCREA_SELLER_FORM_COMMENT_PLACEHOLDER", "Selling features, recent renovations, etc." );
define( "ARETKCREA_SELLER_FORM_CAPTCHA", "Captcha" );

//DEFINE CONTACT FORM FIELD CONTENT
define( "ARETKCREA_CONTACT_FORM_NAME", "Name" );
define( "ARETKCREA_CONTACT_FORM_EMAIL", "Email" );
define( "ARETKCREA_CONTACT_FORM_PHONE", "Phone" );
define( "ARETKCREA_CONTACT_FORM_MESSAGE", "Message" );
define( "ARETKCREA_CONTACT_FORM_MESSAGE_PLACEHOLDER", "Enter your message here" );
define( "ARETKCREA_CONTACT_FORM_CAPTCHA", "Captcha" );

//DEFINE LEAD FORM LISTING CONTENT
define( "ARETKCREA_LEAD_FORM_HEAD_ONE", "NO" );
define( "ARETKCREA_LEAD_FORM_HEAD_TWO", "TITLE" );
define( "ARETKCREA_LEAD_FORM_HEAD_THREE", "DATE" );
define( "ARETKCREA_LEAD_FORM_HEAD_FOUR", "FORM TYPE" );
define( "ARETKCREA_LEAD_FORM_HEAD_FIVE", "SHORT CODE" );

//DEFINE CREATE NEW LEAD FORM CONTENT
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_TITLE_ADD", "Add New Lead" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_TITLE_EDIT", "Edit Lead" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_AGENTS_NAME", "Assign to Agent" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_NAME", "Name" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_PHONE", "Phone Number" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_EMAIL", "Email" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_COMPANY", "Company" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_ADDRESS_ONE", "Address" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_PROVINCE", "Province" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_CITY", "City" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_COUNTRY", "Country" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_SOCIAL", "Social URL" );
define( "ARETKCREA_CREATE_NEW_LEAD_FORM_COMMENT", "Comments" );
define( "ARETKCREA_LEAD_SEND_EMAIL_BTN", "Email this Lead" );
define( "ARETKCREA_LEAD_BACK_TO_EDIT_LEAD_BTN", "Return to Lead" );
define( "ARETKCREA_LEAD_ADD_CORRESPONDENCE_BTN", "Add Correspondence Note" );
define( "ARETKCREA_LEAD_IMPORT_LEADS", "Import Leads" );

//DEFINE LEADS BUTTON TEXT CONSTANT
define( "ARETKCREA_LEADS_BTN_TXT_LEADS", "Leads" );
define( "ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD", "Add New Lead" );
define( "ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL", "Send Email" );
define( "ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES", "Lead Categories" );
define( "ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS", "Lead Forms" );
define( "ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS", "Import Leads" );
define( "ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS", "Export Leads" );

//DEFINE LISTINGS SETTINGS CONSTANT
define( "ARETKCREA_ADD_LISTING_BTN", "ADD NEW LISTING" );
define( "ARETKCREA_ADD_NEW_SHOWCASE", "ADD NEW SHOWCASE" );
define( "ARETKCREA_ADD_LISTING_TABLE_PHOTO", "Photo" );
define( "ARETKCREA_ADD_LISTING_TABLE_MLS", "MLS#" );
define( "ARETKCREA_ADD_LISTING_TABLE_ADDRESS", "Address" );
define( "ARETKCREA_ADD_LISTING_TABLE_CITY", "City" );
define( "ARETKCREA_ADD_LISTING_TABLE_PRICE", "Price" );
define( "ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME", "Agent Name" );
define( "ARETKCREA_ADD_LISTING_TABLE_VIEWS", "Views" );
define( "ARETKCREA_ADD_LISTING_TABLE_DATE", "Dates" );
define( "ARETKCREA_LISTING_TABLE_TRASH_MESSAGE", "Are you sure you want to delete this listing?" );
define( "ARETKCREA_LISTING_SHOWCASE_SAVE_BTN", "Save" );
define( "ARETKCREA_LISTING_SHOWCASE_UPDATE_BTN", "Update" );

//DEFINE ADD NEW LISTING SETTINGS CONSTANT 
define( "ARETKCREA_ADD_NEW_LISTING_TITLE", "Add New Listing" );
define( "ARETKCREA_EDIT_LISTING_TITLE", "Edit Listing" );
define( "ARETKCREA_GENERAL_TAB_TITLE", "GENERAL" );
define( "ARETKCREA_PARKING_GARAGE_TAB_TITLE", "PARKING/GARAGE" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_AGENT_NAME", "Agent name is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_STREET_ADDRESS", "Street addres is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_CITY", "City is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_PROVINCE", "Province is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_STATUS", "Status is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_PRICE", "Price is required" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_VIRTUAL_TOUR", "Please enter a valid virtual tour URL" );
define( "ARETKCREA_PARKING_ERROR_REQUIRED_PHOTO", "Photo is required" );
define( "ARETKCREA_ADD_NEW_LISTING_SAVE_BTN", "SAVE" );
define( "ARETKCREA_ADD_NEW_LISTING_UPDATE_BTN", "UPDATE" );
define( "ARETKCREA_ADD_NEW_LISTING_CANCEL_BTN", "CANCEL" );

//DEFINE listing setting general tab Constant
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_AGENT_NAME", "Agent Name*" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_ADD_MORE_AGENT_ID", "Add More Agent" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_STREET_ADDRESS", "Street Address*" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_CITY", "City" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_PROVINCE", "Province" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_STATUS", "Status*" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_PRICE", "Price($)*" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_PROPERTY_TYPE", "Select Property Type" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_STRUTURE_TYPE", "Structure Type" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_BEDROOMS", "Bedrooms" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_BATHROOM", "Bathrooms Full" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_PARTIAL_BATHROOM", "Bathrooms Partial" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_BASEMENT", "Finished Basement" );
define( "ARETKCREA_LISTING_SETTING_GENERAL_TAB_DESCRIPTION", "Description" );

//DEFINE listing setting PARKING/GARAGE tab Constant
define( "ARETKCREA_LISTING_SETTING_PARKING_GARAGE_TAB_PARKING_SPOT", "Number of Parking Spots" );
define( "ARETKCREA_LISTING_SETTING_PARKING_GARAGE_TAB_GARAGE", "Garage" );

//DEFINE listing setting VIRTUAL TOUR tab Constant
define( "ARETKCREA_LISTING_SETTING_VIRTUAL_TOUR_TITLE", "VIRTUAL TOUR" );
define( "ARETKCREA_LISTING_SETTING_VIRTUAL_TOUR_TAB_URL", "ADD URL" );

//DEFINE listing setting UTILITIES tab Constant
define( "ARETKCREA_LISTING_SETTING_UTILITIES_TITLE", "UTILITIES" );
define( "ARETKCREA_LISTING_SETTING_UTILITIES_TAB_TEXBOX_LIMIT_MSG", "Maximum 10 Utilities" );
define( "ARETKCREA_LISTING_SETTING_UTILITIES_TAB_ADDMORE", "Add More" );

//DEFINE listing setting FEATURES tab Constant
define( "ARETKCREA_LISTING_SETTING_FEATURES_TITLE", "FEATURES" );
define( "ARETKCREA_LISTING_SETTING_FEATURES_TAB_TEXBOX_LIMIT_MSG", "Maximum 10 Features" );
define( "ARETKCREA_LISTING_SETTING_FEATURES_TAB_ADDMORE", "Add More" );

//DEFINE listing setting PHOTO tab Constant
define( "ARETKCREA_LISTING_SETTING_PHOTO_TITLE", "PHOTOS" );

//DEFINE listing setting EXTERNAL DOCUMENTS tab Constant
define( "ARETKCREA_LISTING_SETTING_EXTERNAL_DOCUMENT_TITLE", "EXTERNAL DOCUMENTS" );
define( "ARETKCREA_LISTING_SETTING_EXTERNAL_DOCUMENT_ADDMORE_BTN", "Add More Files" );

//DEFINE listing setting OPEN HOUSE tab Constant
define( "ARETKCREA_LISTING_SETTING_OPEN_HOUSE_TITLE", "OPEN HOUSE" );
define( "ARETKCREA_LISTING_SETTING_OPEN_HOUSE_DATE", "Date" );
define( "ARETKCREA_LISTING_SETTING_OPEN_HOUSE_START_TIME", "Start Time" );
define( "ARETKCREA_LISTING_SETTING_OPEN_HOUSE_END_TIME", "End Time" );
define( "ARETKCREA_LISTING_SETTING_OPEN_HOUSE_ADD_MORE_DATE_BTN", "Add Another Open House" );

//DEFINE plugin setting tab Constant
define( "ARETKCREA_PLUGIN_SETTINGS_PAGE_TITLE", "Plugin Settings" );
define( "ARETKCREA_WALK_SCORE_API_KEY", "Walk Score Widget API key" );
define( "ARETKCREA_GOOGLE_MAP_API_KEY", "Google Map API key" );
define( "ARETKCREA_GOOGLE_MAP_SCRIPT_LOAD_OR_NOT", "Do you want to load Google map Script?" );
define( "ARETKCREA_SAMPLE_CSV_FILE_FOR_ADD_LEADS", "Sample CSV File for Adding Leads" );
define( "ARETKCREA_PLUGIN_SETTINGS_PAGE_SAVE", "Save Settings" );
define( "ARETKCREA_PLUGIN_SETTINGS_PAGE_DOWNLOAD", "Click to download" );
define( "ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_SUCESS", "Settings Saved" );
define( "ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_ERROR", "There was an error saving the plugin settings, please try again." );

//DEFINE SEND EMAIL LEADS TAB CONSTANT
define( "ARETKCREA_PLUGIN_LEAD_SEND_EMAIL_TITLE", "Send Email" );

//DEFINE SUPPORT QUESTION ANSWER CONSTANT
define( "ARETKCREA_SUPPORT_QA_HEADING", "Support" );
define( "ARETKCREA_SUPPORT_QA_TITLE", "Questions & Answers" );
define( "ARETKCREA_SUPPORT_SUBMIT_TICKET_BTN", "submit a ticket" );
define( "ARETKCREA_SUPPORT_VIEW_FORUM_BTN", "view forum" );
define( "ARETKCREA_SUPPORT_OA_TITLE_USE_PLUGIN_WITHOUT_SUBSCRIPTION", "Can I use this plugin without an ARETK Subscription?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_I_SIGN_UP_SUBSCRIPTION", "How do I sign up for an ARETK subscription to showcase CREA DDF<sup>&reg;</sup> Listings?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_MUCH_IS_SUBSCRIPTION", "How much is an ARETK Subscription?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_CAN_I_CANCEL_SUBSCRIPTION", "Can I cancel my ARETK Subscription and keep using the plugin?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_FILTER_LISTING_SHOWCASE", "How do I filter the data feed in my default listing showcase?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_CREATE_ADDITIONAL_SHOWCASE", "How do I create additional listing showcases?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_MAKE_CHANGE_DEFAULT_PAGE", "How do I make changes to the default listing details page?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_PUT_ANOTHER_WORDPRESS_PAGE", "How to I put a Search Box into another WordPress page?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_CHANGE_SERCH_BOX_FEED", "How do I change which feed the Search box is searching from?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_ADD_LISTING_SHOWCASE", "How do I add exclusive listings to be included in my listing showcase?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_CAN_I_EDIT_LISTING_PAGE", "Can I edit the listings listed in my listings page?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_ADD_CONTENT_MANAGEMENT_SYSTEM", "How do I add Contact Forms in my website to be captured by my ARETK Leads Management System?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_DO_UPLOAD_CONTENT_MANAGEMENT_SYSTEM", "How do I upload my contacts from Outlook or Excel into my ARETK Leads Management System?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_WHAT_IS_THE_WALK_SCORE_API", "What is the Walk Score API and how do I get it?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_WHAT_IS_THE_GOOGLE_MAP_API", "What is the Google Maps API and how do I get it?" );
define( "ARETKCREA_SUPPORT_OA_TITLE_HOW_TO_FIND_CREA_ID", "How to find out your CREA ID?" );

// DEFINE NEW CREATE SHOWCASE CONSTANT
define( "ARETKCREA_NEW_SHOWCASE_TITLE", "Add New Showcase" );
define( "ARETKCREA_EDIT_SHOWCASE_TITLE", "Edit Showcase" );
define( "ARETKCREA_NEW_SHOWCASE_INPUT_TITLE", "Title" );
define( "ARETKCREA_NEW_SHOWCASE_TYPE_TAB_TITLE", "Type" );
define( "ARETKCREA_NEW_SHOWCASE_FEED_TAB_TITLE", "Feed" );
define( "ARETKCREA_NEW_SHOWCASE_DISPLAY_TAB_TITLE", "Display" );
define( "ARETKCREA_NEW_SHOWCASE_FILTER_TAB_TITLE", "Filters" );
define( "ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_TITLE", "Settings" );
define( "ARETKCREA_NEW_SHOWCASE_COLOUR_TAB_TITLE", "Colours" );
define( "ARETKCREA_NEW_SHOWCASE_SAVE_TAB_TITLE", "Shortcode" );
define( "ARETKCREA_NEW_SHOWCASE_DISPLAY_MAP_OPTION_CONTENT", "Google API Key must be entered in the Plugin Settings page for the Map showcase to work." );

//DEFINE LISTING DETAIL SETTINGS CONSTANT 
define( "ARETKCREA_LISTING_DETAIL_SETTINGS_TITLE", "DEFAULT LISTING DETAILS SHOWCASE" );
define( "ARETKCREA_LISTING_DETAIL_SETTINGS_TAB_TITLE", "Settings" );
define( "ARETKCREA_LISTING_DETAIL_SETTINGS_COLOUR_TAB_TITLE", "Colours" );
define( "ARETKCREA_NEW_SHOWCASE_LISTING_DETAIL_SETTING_TAB_TITLE", "Settings" );
define( "ARETKCREA_NEW_SHOWCASE_LISTING_COLOUR_TAB_TITLE", "Colours" );

// DEFINE SEARCH LISTING SETTING CONSTANT 
define( "ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_TITLE", "DEFAULT LISTINGS SEARCH SHOWCASE" );
define( "ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_TAB_TITLE", "Settings" );
define( "ARETKCREA_SEARCH_LISTING_DETAIL_SETTINGS_COLOUR_TAB_TITLE", "Colours" );
define( "ARETKCREA_SEARCH_NEW_SHOWCASE_LISTING_DETAIL_SETTING_TAB_TITLE", "Settings" );
define( "ARETKCREA_SEARCH_NEW_SHOWCASE_MAX_PRICERANGER", "Max Price for Price Range Slider" );
define( "ARETKCREA_SEARCH_NEW_SHOWCASE_LISTING_COLOUR_TAB_TITLE", "Colours" );
define( "ARETKCREA_SEARCH_NEW_ADVANCE_SEARCH_TITLE", "Advance Filters" );

// DEFINE Default LISTING SETTING CONSTANT 
define( "ARETKCREA_DEFAULT_LISTING_DETAIL_SETTINGS_TITLE", "DEFAULT LISTINGS SHOWCASE" );
define( "ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_SIMPLE", "Have the Advanced filter closed by default" );
define( "ARETKCREA_NEW_SHOWCASE_SETTINGS_TAB_SEARCH_DETAIL", "Have the Advanced filter open by default" );

//mail sent template header footer color constant
define( 'ARETKCREA_MAIL_HEADER_COLOR', '#55A185' );
define( 'ARETKCREA_MAIL_CONTENT_COLOR', '#FFFFFF' );

//mail header constant
define( 'ARETKCREA_BUYER_FORM_MAIL_HEADER', 'Buyer Form Mail' );
define( 'ARETKCREA_SELLER_FORM_MAIL_HEADER', 'Seller Form Mail' );
define( 'ARETKCREA_CONTACT_FORM_MAIL_HEADER', 'Contact Form Mail' );
define( 'ARETKCREA_REMINDER_MAIL_HEADER', 'Reminder Mail' );

//DEFINE LISTING LOCATION 
define( "ARETKCREA_LISTING_SETTING_LOCATION_TITLE", "MAP LOCATION" );
define( "ARETKCREA_LISTING_SETTING_LOCATION_SEARCH_BTN", "SEARCH" );
define( "ARETKCREA_SUBSCRIPTIONENDPOINT", "https://aretk.com/wp-json/api/v1/aretk" );
define( "ARETKCREA_LISTING_BASEDONSERVER_API", "https://api.aretk.com" );