<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Language codes with their names
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default language name
    | Here, Numbers are considered as keywords.
    |
     */
    /*
     *
    | odd number for fail message
    | even number for success message
    | code range from -01 to -10. example: 101-110, 201-210
     */

    101                                     => '{name} record/s not found.',
    102                                     => '{name} record/s found.',
    103                                     => '{name} not created successfully.',
    104                                     => '{name} created successfully.',
    105                                     => '{name} not updated successfully.',
    106                                     => '{name} updated successfully.',
    107                                     => 'Entities are not related to user.',
    108                                     => 'Entities are related to user successfully.',
    109                                     => 'The entered details are does not exists.',
    110                                     => 'The entered details are validated successfully.',
    111                                     => 'The entered details are incorrect. Please enter valid details.',
    113                                     => 'Token not found',
    115                                     => 'Provided token is expired.',
    117                                     => 'An error while decoding token.',
    118                                     => '{name} deleted successfully.',
    119                                     => '{name} not deleted successfully.',
    121                                     => 'Failed to delete record. As have a relation with {name}.',
    123                                     => 'The {name} is required.',
    124                                     => 'Valid Password.',
    125                                     => 'Invalid Password.',
    126                                     => 'Your request to reset password has been send successfully. Please Check your Mail box for Reset link. Link is {name}',
    127                                     => 'The entered user does not exists.',
    129                                     => 'Email not sent. Please try again.',
    130                                     => 'Password reset successfully. Please login.',
    131                                     => 'Link Expire.',
    132                                     => 'Link is Valid.',
    133                                     => 'At least one IP should be there in Whitelisted IPs.',
    134                                     => '{name} Whitelisted successfully.',
    135                                     => '{name} has been already whitelisted.',
    137                                     => 'You are trying to access Account from unauthorized Public IP address. We have sent link to your registered email address to authorize this new Public IP address. Please click on that link to whitelist this public IP address. {name}',
    138                                     => '{name} Approve successfully.',
    139                                     => '{name} assigned to user successfully.',
    140                                     => '{name} not assigned to user successfully.',
    142                                     => '{name}  successfully.',
    143                                     => 'Invalid Token.',
    144                                     => 'Token Verified Successfully',
    145                                     => 'Acknowledged Successfully',
    146                                     => 'Failed to Acknowledged',
    147                                     => 'Failed to set session',
    161                                     => 'Unable to process request.',
    162                                     => "Invalid File type.",

    100                                     => 'Access denied. You are trying to access invalid user.',
    200                                     => 'Access denied. You are trying to access invalid entity.',
    300                                     => 'Pasword field should contain at least one small case letter, uppercase letter, digit',
    500                                     => 'IP Changed',
    600                                     => '{name} User is logged in successfully',
    700                                     => '{name} User is logged out successfully',
    800                                     => '{name}',
    900                                     => 'Unauthorized Access.',
    1000                                    => 'Failed to login due to network issue.',

    /* ======Validation Messages Goes Here ========*/
    //For required
    '000'                                   => 'The {name} field should be required.',

    //For Descriptions: html_tags_not_allowed
    '001'                                   => 'The {name} HTML Tags not Allowed.',

    //For Key: key_value
    '002'                                   => '{name} Only Uppercase letters and Underscores are Allowed.',

    // For Name: allow_alpha_numeric_space_dash_underscore_only
    '003'                                   => 'The {name} only Alphanumeric, Space, Dash & Underscore are allowed.',

    // For unique
    '004'                                   => 'The {name} is already exists.',

    // For external table id : foreign_key_exists
    '005'                                   => 'The {name}  does not exists.',

    //For name already taken : composite_unique
    '006'                                   => 'The {name} has already been taken.',

    // for Template name : allow_alphal_numeric_dash_underscore_only
    '007'                                   => 'The {name}  only Alphanumeric, Dash & Underscore are allowed.',

    // for Template name : allow_alphal_numeric_dash_underscore_only
    '008'                                   => '{name} To generate JSON click on Generate JSON.',

    // for Template name :allow_alpha_space_only
    '009'                                   => 'The {name}  only Alphabets & Space are allowed.',

    // for regx:  IP used in Form data Config For AD Server /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/
    '010'                                   => 'The {name} have Invalid IP.',

    // for regx: Used in Form data Config For User suffix /^@([\w-]+\.)+[\w-]{2,4}$/
    //For eg.:  @example.com
    '011'                                   => 'The {name} have Invalid Format.',

    // for regx: Used in Form data Config For User suffix /^[a-zA-Z0-9]+((,\s|=)[a-zA-Z0-9]+)*[a-zA-Z0-9]+$/
    //For eg.:  eg. CN=Users, DC=example, DC=com
    '012'                                   => 'The {name} have Invalid Format.',

    // for Template name : ip_subnet
    '013'                                   => 'The {name} Invalid Ip / Subnet.',

    // for Email : email(lumen default validation)
    '014'                                   => 'The {name} have Invalid email format.',

    // for Password : password
    '015'                                   => 'The {name} Confirmation should match the Password.',

    //custom messages
    'msg_buss_thanks'                       => 'Thank you for your business.',
    'msg_clicktodelete'                     => 'Click To Delete Record',
    'msg_nofilesattached'                   => 'There are no files attached',
    'msg_vendor_select'                     => 'Please select vendor',
    'msg_norecordfound'                     => 'No Record Found.',
    'msg_delattachmentconfirm'              => 'Are you sure you want to Delete this Attachment ?',
    'msg_delrecordconfirm'                  => 'Are you sure you want to Delete this record ?',
    'msg_invalidtemplate'                   => 'Invalid Template.',
    'msg_templaterendering'                 => 'Template Rendering',
    'msg_actionconfirmation_pr'             => 'Are you sure you want to {name} this PR ?',
    'msg_actionconfirmation_qc'             => 'Are you sure you want to {name} this Quotation Comparison ?',
    'msg_actionconfirmation_po'             => 'Are you sure you want to {name} this PO ?',
    'msg_ondatecomment'                     => ' On {name} <div class="media"><p>Comment: {comment}</p></div>',
    'msg_clicktoedit'                       => 'Click To Edit Record',
    'msg_max_allowed_size'                  => 'File size could not exceed {name}',
    'msg_only_csv_allowed'                  => 'Uploaded file must be a CSV file',
    'msg_std_value_bvlocven'                => 'Only standard values are allowed for BV, Location and Vendor',

    "msg_contract_loading"                  => "Loading Contract",
    "msg_contract_add"                      => "Add Contract",
    "msg_session_open"                      => "Session is already open",
    "msg_contract_edit"                     => "Edit Contract",
    "msg_updating_contract"                 => "Updating Contract",
    "msg_contract_delete"                   => "Are you sure you want to delete Contract record?",
    "msg_deleting_contract"                 => "Deleting Contract",
    "msg_contract_asset_delete"             => "Are you sure you want to delete associated assets record?",
    "msg_contract_renew"                    => " Renew Contract",
    "msg_vendor_loading"                    => "Loading Vendor",
    "msg_contracttype_loading"              => "Loading Contract Types",
    "msg_vendor_add"                        => "Add Vendor",
    "msg_view_vendor"                       => "View Vendor",
    "msg_vendor_edit"                       => "Edit Vendor",
    "msg_updating_vendor"                   => "Updating Vendor",
    "msg_vendor_delete"                     => "Are you sure you want to delete vendor record?",
    "msg_deleting_vendor"                   => "Deleting Vendor",
    "msg_contracttype_add"                  => "Add Contract Type",
    "msg_contracttype_edit"                 => "Edit Contract Type",
    "msg_updating_contractype"              => "Updating Contract Type",
    "msg_contracttype_delete"               => "Are you sure you want to delete contract type record?",
    "msg_deleting_contracttype"             => "Deleting  Contract Type",

    "msg_asset_delete"                      => "When you delete the asset all related financial information,attached asset relationship and history will be deleted. Are you sure you want to delete the asset?",

    "msg_recivingqty_cnt_morethan_orderqty" => "Receiving quantity cannot be more than the Maximum Limit.",
    "msg_maxt_limit_to_receive_item_qty"    => "[ Maximum Limit : 500]",

    "msg_discount_cnt_greaterthan_subtotal" => "Discount cannot be greater than Sub Total.",

    //email templates
    "msg_emailtemplate_loading"             => "Loading Email Templates",
    "msg_emailtemplate_add"                 => "Add Email Template",
    "msg_emailtemplate_edit"                => "Edit Email Template",
    "msg_updating_emailtemplate"            => "Updating Email template",
    "msg_emailtemplate_delete"              => "Are you sure you want to delete template record?",
    "msg_config_email_ids"                  => "[Note: Please enter valid email address with comma separated.]",

    //Softwares
    "msg_softwaretype_loading"              => "Loading Software Type",
    "msg_softwaretype_add"                  => "Add Software Type",
    "msg_softwaretype_edit"                 => "Edit Software Type",
    "msg_updating_softwaretype"             => "Updating Software Type",
    "msg_softwaretype_delete"               => "Are you sure you want to delete software type record?",

    "msg_softwarecategory_loading"          => "Loading Software Category",
    "msg_softwarecategory_add"              => "Add Software Category",
    "msg_softwarecategory_edit"             => "Edit Software Category",
    "msg_updating_softwarecategory"         => "Updating Software Category",
    "msg_softwarecategory_delete"           => "Are you sure you want to delete software category record?",
    "msg_softwaremanufacturer_delete"       => "Are you sure you want to  delete software manufacturer record?",
    "msg_updating_softwaremanufacturer"     => "Updating Software Manufacturer",
    "msg_softwaremanufacturer_add"          => "Add Software Manufacturer",
    "msg_softwaremanufacturer_edit"         => "Edit Software Manufacturer",
    "msg_softwaremanufacturer_loading"      => "Loading Software Manufacturer",
    "msg_licensetype_loading"               => "Loading License Type",
    "msg_licensetype_add"                   => "Add License Type",
    "msg_licensetype_edit"                  => "Edit License Type",
    "msg_updating_licensetype"              => "Updating License Type",
    "msg_licensetype_delete"                => "Are you sure you want to delete license type record",
    "msg_free_asset"                        => "Are you sure you want to detach this asset?",
    "msg_software_edit"                     => "Edit Software",
    "msg_software_add"                      => "Add Software",
    "msg_updating_software"                 => "Updating Software",
    "msg_software_delete"                   => "Are you sure you want to delete software record?",

    "msg_sw_asset_delete"                   => "Are you sure you want to remove asset record?",
    'msg_actionconfirmation_contract'       => 'Are you sure you want to {name} this Contract?',
    'msg_costcenter_delete'                 => "Are you sure you want to delete cost centers record",
    "msg_sw_uninstall"                      => "Are you sure you want to remove record",
    "msg_software_license_edit"             => "Software License Edit",
    "msg_license_reach"                     => "Maximum license allocation limit has been already reached.",
    "msg_license_as_installation"           => "Select as per max installation",
    "msg_sw_asset_saving"                   => "Software Asset saving",
    "msg_report_notification_remove"        => "This notification further will not reflect.",
    "msg_aqui_expiry"                       => "Aqusition date should not be greater than expiry date",
    "msg_rep_field_info"                    => "&nbsp;&nbsp;Drag &amp; Drop fields from the left (Available Fields) over to the right side in the desired location on your report.",
    "msg_confirmation_remove_commonly"      => "Are you sure you want to remove {name}?",
    "msg_cannot_delete_default_item"        => "You can not delete first Item",

    //contract
    "msg_invalid_file_ext"                  => "Invalid file extension.",
    "msg_allowed_ext"                       => "(Allowed File Extensions: .doc, .docx, .odt, .jpeg, .png, .svg, .gif, .xlsx, .pdf, .csv, .txt)",

    "msg_confirmclone"                      => 'Are you sure you want to make Clone of this Template ?',
    'msg_confirmdelete'                     => 'Are you sure you want to delete?',

    // Payment Terms
    "msg_paymentterm_loading"               => "Loading Payment Terms",
    "msg_paymentterm_add"                   => "Add Payment Terms",
    "msg_paymentterm_edit"                  => "Edit Payment Terms",
    "msg_updating_paymentterm"              => "Updating Payment Terms",
    "msg_paymentterm_delete"                => "Are you sure you want to delete Payment Terms record?",
    "msg_deleting_paymentterm"              => "Deleting Payment Terms",

    // Bill To
    "msg_billto_loading"                    => "Loading Bill To",
    "msg_billto_add"                        => "Add Bill To",
    "msg_billto_edit"                       => "Edit Bill To",
    "msg_updating_billto"                   => "Updating Bill To",
    "msg_billto_delete"                     => "Are you sure you want to delete Bill To record?",
    "msg_deleting_billto"                   => "Deleting Bill To",

    // Ship To
    "msg_shipto_loading"                    => "Loading Ship To",
    "msg_shipto_add"                        => "Add Ship To",
    "msg_shipto_edit"                       => "Edit Ship To",
    "msg_updating_shipto"                   => "Updating Ship To",
    "msg_shipto_delete"                     => "Are you sure you want to delete Ship To record?",
    "msg_deleting_shipto"                   => "Deleting Ship To",

    // Contact
    "msg_contact_loading"                   => "Loading Contact",
    "msg_contact_add"                       => "Add Contact",
    "msg_contact_edit"                      => "Edit Contact",
    "msg_updating_contact"                  => "Updating Contact",
    "msg_contact_delete"                    => "Are you sure you want to delete Contact record?",
    "msg_deleting_contact"                  => "Deleting Contact",

    // Requestername
    "msg_requestername_loading"                   => "Loading Requester Name",
    "msg_requestername_add"                       => "Add Requester Name",
    "msg_requestername_edit"                      => "Edit Requester Name",
    "msg_updating_requestername"                  => "Updating Requester Name",
    "msg_requestername_delete"                    => "Are you sure you want to delete Requester Name record?",
    "msg_deleting_requestername"                  => "Deleting Requester Name",

    // Delivery Details
    "msg_delivery_loading"                  => "Loading Delivery Details",
    "msg_delivery_add"                      => "Add Delivery Details",
    "msg_delivery_edit"                     => "Edit Delivery Details",
    "msg_updating_delivery"                 => "Updating Delivery Details",
    "msg_delivery_delete"                   => "Are you sure you want to delete Delivery Details record?",
    "msg_deleting_delivery"                 => "Deleting Delivery Details",

     "msg_added"                 => "Added",
     "msg_convert_to_pr"                 => "Convert To PR",
     
    
];
