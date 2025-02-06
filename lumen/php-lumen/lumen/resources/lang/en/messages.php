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
       
        101 => '{name} record/s found.',
        102 => '{name} record/s not found.',
        103 => '{name} not created successfully.',
        104 => '{name} created successfully.',
        105 => '{name} not updated successfully.',
        106 => '{name} updated successfully',
        107 => 'Entities are not related to user.',
        108 => 'Entities are related to user successfully.',
        109 => 'The entered details are does not exists.',
        110 => 'The entered details are validated successfully.',
        111 => 'The entered details are incorrect. Please enter valid details.',
        113 => 'Token not found',
        115 => 'Provided token is expired.',
        117 => 'An error while decoding token.',
        118 => '{name} deleted successfully.',
        119 => '{name} not deleted successfully.',
        121 => 'Failed to delete record. As have a relation with {name}.',
        123 => 'The {name} is required.',
        124 => 'Valid Password.',
        125 => 'Invalid Password.',
        126 => 'Your request to reset password has been send successfully. Please Check your Mail box for Reset link. Link is {name}', 
        127 => 'Invalid Email. Please try again.',
        129 => 'Email not sent. Please try again.',
        130 => 'Password reset successfully. Please login.',
        131 => 'Link Expire.',
        132 => 'Link is Valid.',
        133 => 'At least one IP should be there in Whitelisted IPs.',
        134 => '{name} Whitelisted successfully.', 
        135 => '{name} has been already whitelisted.',
        137 => 'You are trying to access Account from unauthorized Public IP address. We have sent link to your registered email address to authorize this new Public IP address. Please click on that link to whitelist this public IP address. {name}',
        138 => '{name} Approve successfully.',
        139 => 'Failed to {name}.', //For PR
        140 => '{name}  successfully.',  //For PR    
        144 => '{name} Uploaded successfully.',
        145 => '{name} Failed To Upload.',
        146 => "{name} Attached successfully.",
        147 => "{name} not Attached successfully.",
        161 => 'Unable to process request',
        163 => 'Something went wrong...',
        
        100 => 'Access denied. You are trying to access invalid user.',
        200 => 'Access denied. You are trying to access invalid entity.',
        300 => 'Pasword field should contain at least one small case letter, uppercase letter, digit',
        500 => 'IP Changed',
        600 => '{name} User is logged in successfully',
        700 => '{name} User is logged out successfully',
        800 => '{name}',
        900 => 'Unauthorized Access.',
        

        /* ======Validation Messages Goes Here ========*/
        //For required
        '000' => 'The {name} field should be required.',

        //For Descriptions: html_tags_not_allowed
        '001' => 'The {name} HTML Tags not Allowed.',
        
        //For Key: key_value
        '002' => '{name} Only Uppercase letters and Underscores are Allowed.',

        // For Name: allow_alpha_numeric_space_dash_underscore_only
        '003' => 'The {name} only Alphanumeric, Space, Dash & Underscore are allowed.',

        // For unique
        '004' => 'The {name} is already exists.',

        // For external table id : foreign_key_exists
        '005' => 'The {name}  does not exists.',

        //For name already taken : composite_unique
        '006' => 'The {name} has already been taken.',

        // for Template name : allow_alphal_numeric_dash_underscore_only
        '007' => 'The {name}  only Alphanumeric, Dash & Underscore are allowed.',
        
        // for Template name : allow_alphal_numeric_dash_underscore_only
        '008' => '{name} To generate JSON click on Generate JSON.',

        // for Template name :allow_alpha_space_only
        '009' => 'The {name}  only Alphabets & Space are allowed.',

        // for regx:  IP used in Form data Config For AD Server /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/ 
        '010' => 'The {name} have Invalid IP.', 
 
        // for regx: Used in Form data Config For User suffix /^@([\w-]+\.)+[\w-]{2,4}$/ 
        //For eg.:  @example.com 
        '011' => 'The {name} have Invalid Format.', 
 
        // for regx: Used in Form data Config For User suffix /^[a-zA-Z0-9]+((,\s|=)[a-zA-Z0-9]+)*[a-zA-Z0-9]+$/ 
        //For eg.:  eg. CN=Users, DC=example, DC=com 
        '012' => 'The {name} have Invalid Format.', 

        // for Template name : ip_subnet
        '013' => 'The {name} Invalid Ip / Subnet.',

         // for Email : email(lumen default validation)
        '014' => 'The {name} have Invalid email format.',

         // for Password : password
        '015' => 'The {name} Confirmation should match the Password.',

        /* START : Password Policy */
        //min_length : 8
        '016' => 'Password must be more than {name} characters long.',

        //max_length : 16
        '017' => 'Password must be less than {name} characters long',

        //min_lowercase_chars : 1
        '018' => 'Password must contain at least {name} lowercase characters.',

        //min_uppercase_chars : 1
        '019' => 'Password must contain at least {name} uppercase characters.',

        //disallow_numeric_chars :
        '020' => 'Password may not contain numbers.', 

        //disallow_numeric_first
        '021' => 'First character cannot be numeric.',

        //min_numeric_chars: 1
        '022' => 'Password must contain at least {name} numbers.',

        //min_nonalphanumeric_chars: 1
        '023' => 'Password must contain at least {name} non-aplhanumeric characters.',
        /* END : Password Policy */
        '024' => 'The {name} field in row {id} should be required.',
        //numaric field
        "025" => 'The {name} field should be numeric.',
        //Alpha_numaric
        "026" => 'The {name} may only contain letters and numbers.',

        "027" => 'The {name} may only contain letters.',
        "028" => 'The {name} field must be a valid email address.',
        "029" => "Asset name {name} Attached to {name1} Component.",
        "030" => "Asset Added.",
        "031" => "Asset Deleted.",
        "032" => "Asset updated.",
        "033" => "Asset status changed in store to in use.",
        "034" => "Asset name {name} Detached to {name1} Component.",
        "035" => "Asset Detached.",
        "036" => "Asset status changed In use to In store.",
        "037" => "Asset name {name} Detached.",
        "038" => "The {name} field should be positive number.",

        "039" => "Software Added",
        "040" => "Software Updated",
        "041" => "Software Deleted",
        "042" => "Software Asset Installation Added",
        "043" => "Software Asset Deleted",
        "044" => "Software License Added",
        "045" => "Software License Updated",
        "046" => "Software License Deleted",
        "047" => "Software License Allocated",
        // for Template name : allow_alphal_numeric_dash_only
        '048' => 'The {name}  only Alphanumeric and Dash are allowed.',







    


        //purchase history messages
        "msg_approved"              => "{name} approved.",
        "msg_cancelled"             => "{name} cancelled.",
        "msg_closed"                => "{name} closed.",
        "msg_deleted"               => "{name} deleted.",
        "msg_item_received"         => "{name} item received.",
        "msg_notifyagain"           => "{name} notify again.",
        "msg_notifyowner"           => "{name} notify owner.",
        "msg_notifyvendor"          => "{name} notify vendor.",
        "msg_open"                  => "{name} open.",
        "msg_partiallyapproved"     => "{name} partially approved.",
        "msg_partiallyreceived"     => "{name} partially received.",
        "msg_pendingapproval"       => "{name} pending approval.",  //created
        "msg_rejected"              => "{name} rejected.",
        "msg_ordered"               => "{name} ordered.",
        "msg_received"              => "{name} received.",
        "msg_convert_to_pr"         => "{name} convert to PR.",

        "msg_created"               => "{name} created.",
        "msg_updated"               => "{name} updated.",
        "msg_renewed"               => "{name} renewed.",
        "msg_associatedchild"       => "{name} associated to child.",

        "msg_importsuccess"       => "{name} imported successfully.",
        "msg_importfail"          => "{name} import failed.",
        "msg_not_required"        => "{name} can not be edited.",
        


        //custom messages
        "msg_chkoneitem"        => "Please check at least 1 item.",
        "msg_assetrel_created"  => "Asset relationship ({reltype}) created between {parent} and {child}.",
        "msg_assetrel_deleted"  => "Asset relationship ({reltype}) deleted between {parent} and {child}.",
        "msg_change_stat"       => "Asset status {status} changed to {status1} status.",
        "msg_contactno_10digit" => "The Contact No. must be 10 digits.",
        'msg_norecordfound'     => 'No Record Found.',
        "msg_added_queue"       => "Request added successfully check your notifications.",
        'before_date'           => 'The {date1} must be a date before {date2}.',
        "msg_validate_posno"    => 'The {name} field must be a valid positive number.',


        //PR PO
        'msg_pr_po_same_user_can_not_for_approval' => 'Approval Details : Same User can not be in Confirmed & Optional Approval selection.',

        "msg_choose_field"        => "Please Choose at least 1 Field.",
        "msg_added"                 => "{name} Added",

];
