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

        101 => '{name} रेकॉर्ड / एस आढळली नाहीत.',
        102 => '{name} रेकॉर्ड / एस आढळले.',
        103 => '{name} not created successfully.',
        104 => '{name} created successfully.',
        105 => '{name} not updated successfully.',
        106 => '{name} यशस्वीरित्या अद्यतनित केले.',
        107 => 'Entities are not related to user.',
        108 => 'Entities are related to user successfully.',
        109 => 'प्रविष्ट केलेला तपशील विद्यमान नाही.',
        110 => 'The entered details are validated successfully.',
        111 => 'प्रविष्ट केलेला तपशील चुकीचा आहे. कृपया वैध तपशील प्रविष्ट करा.',
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
        127 => 'प्रविष्ट केलेला वापरकर्ता विद्यमान नाही.',
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
        144 => '{name} Uploaded successfully.',
        145 => '{name} Failed To Upload.',
        147 => 'Credential Not assigned to this host.',
        148 => 'Your request to one time OTP has been send successfully. Please Check your Mail box for OTP', 
        151 => 'Invalid Param.',
        153 => 'Unable to create system directory.',
        155 => 'Directory or file is not writable.',
        157 => 'Failed to write enlight system config file.',
        159 => 'Configuration is empty.',
        161 => 'Unable to process request',
        163 => 'Something went wrong...',
        165 => 'Invalid from date',
        167 => 'Invalid to date',
        168=> 'Invalid OTP',
        169=> 'OTP is Expire.',
        171 => "Connection to AD fail.",
        173 => "{name} is failed to add in group.",
        175 => '{name} is failed to disable in Active Directory',
        177 => 'Failed to assign group to user.',
        179 => 'Failed to get {name} Record.',
       
               

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
        '000' => '{name} आवश्यक आहे.',

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
        '008' => '{name} जेएसओएन व्युत्पन्न करण्यासाठी जनरेट जेएसओएन वर क्लिक करा.',

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
        '023' => 'Password must contain at least {name} non-aplhanumeric characters',
        /* END : Password Policy */
        //numeric
        '024' => 'The {name} must be a number.',

		//custom messages
		'msg_recordnotfound'			=> 'रेकॉर्ड सापडला नाही.',
		'msg_recordfound'				=> 'रेकॉर्ड सापडला.',
		'msg_userdeleted'				=> 'वापरकर्त्याने यशस्वीरित्या हटविला.',
		'msg_cantdeleteregions'			=> 'प्रदेश हटविला जाऊ शकत नाही.',
		'msg_relbetweenregionanddc'		=> 'प्रांत आणि डेटासेंटर यांच्यात संबंध अस्तित्त्वात आहेत.',
		'msg_norelbetweenregionanddc'	=> 'प्रदेश आणि डेटासेंटरमध्ये काही संबंध नाही.',
        'msg_regionidrequired'			=> 'प्रदेश आयडी आवश्यक आहे.',
        'msg_contactnodigits'=>'Contact number should be 10 digits.',
		'msg_nowritepermissiondir'=>'Directory has no write permission.',
		'msg_nowritepermissionfile'=>'File has no write permission.',

        /*Datacenter Section*/
        'msg_relation_with_dc_regions' => 'रेकॉर्ड हटविण्यात अयशस्वी. जसे डीसी_प्रादेश / पॉड  संबंध आहे',
        'msg_record_deleted_successfully' => 'रेकॉर्ड यशस्वीरित्या हटविला',
        'msg_id_required' => 'आयडी फील्ड आवश्यक आहे',

];