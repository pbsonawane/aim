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
    
     101 => '{name} record/s not found.',
        102 => '{name} record/s found.',
        103 => '{name} not created successfully.',
        104 => '{name} created successfully.',
        105 => '{name} not updated successfully.',
        106 => '{name} updated successfully.',
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
        127 => 'The entered user does not exists.',
        129 => 'Email not sent. Please try again.',
        130 => 'Password reset successfully. Please login.',
        131 => 'Link Expire.',
        132 => 'Link is Valid.',
        133 => 'At least one IP should be there in Whitelisted IPs.',
        134 => '{name} Whitelisted successfully.', 
        135 => '{name} has been already whitelisted.',
        137 => 'You are trying to access Account from unauthorized Public IP address. We have sent link to your registered email address to authorize this new Public IP address. Please click on that link to whitelist this public IP address. {name}',
        138 => '{name} Approve successfully.',
        139 => '{name} assigned to user successfully.',
        140 => '{name} not assigned to user successfully.',
        142 => '{name}  successfully.',
        143 => 'Invalid Token.',
        144 => 'Token Verified Successfully',
        145 => 'Acknowledged Successfully',
        146 => 'Failed to Acknowledged',
        147 => 'Failed to set session',
       
        

        100 => 'Access denied. You are trying to access invalid user.',
        200 => 'Access denied. You are trying to access invalid entity.',
        300 => 'Pasword field should contain at least one small case letter, uppercase letter, digit',
        500 => 'IP Changed',
        600 => '{name} User is logged in successfully',
        700 => '{name} User is logged out successfully',
        800 => '{name}',
        900 => 'Unauthorized Access.',
        1000 => 'Failed to login due to network issue.',
        

        /* ======Validation Messages Goes Here ========*/
        //For required
        '000' => 'The {name} field should be required',

        //For Descriptions: html_tags_not_allowed
        '001' => 'The {name} HTML Tags not Allowed',
        
        //For Key: key_value
        '002' => '{name} Only Uppercase letters and Underscores are Allowed',

        // For Name: allow_alpha_numeric_space_dash_underscore_only
        '003' => 'The {name} only Alphanumeric, Space, Dash & Underscore are allowed',

        // For unique
        '004' => 'The {name} is already exists',

        // For external table id : foreign_key_exists
        '005' => 'The {name}  does not exists',

        //For name already taken : composite_unique
        '006' => 'The {name} has already been taken',

        // for Template name : allow_alphal_numeric_dash_underscore_only
        '007' => 'The {name}  only Alphanumeric, Dash & Underscore are allowed',
        
        // for Template name : allow_alphal_numeric_dash_underscore_only
        '008' => '{name} To generate JSON click on Generate JSON',

        // for Template name :allow_alpha_space_only
        '009' => 'The {name}  only Alphabets & Space are allowed',

        // for regx:  IP used in Form data Config For AD Server /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/ 
        '010' => 'The {name} have Invalid IP', 
 
        // for regx: Used in Form data Config For User suffix /^@([\w-]+\.)+[\w-]{2,4}$/ 
        //For eg.:  @example.com 
        '011' => 'The {name} have Invalid Format', 
 
        // for regx: Used in Form data Config For User suffix /^[a-zA-Z0-9]+((,\s|=)[a-zA-Z0-9]+)*[a-zA-Z0-9]+$/ 
        //For eg.:  eg. CN=Users, DC=example, DC=com 
        '012' => 'The {name} have Invalid Format', 

        // for Template name : ip_subnet
        '013' => 'The {name} Invalid Ip / Subnet',

         // for Email : email(lumen default validation)
         '014' => 'The {name} have Invalid email format',

         // for Password : password
         '015' => 'The {name} Confirmation should match the Password',
];
