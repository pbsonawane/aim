<?php


return [	 
	'upload_purchase_path'	 	=> public_path('uploads/purchase/'),
	'api_log_enable' 			=> true,
	'api_log_path' 				=> storage_path().'/logs/api.log',
	/*'en_sysconfig_api_url' => 'http://172.16.7.41:30156',
	'en_sysconfig_config' => 'local',
	'en_sysconfig_config_path' => '/var/www/html/ensystemconfig/'*/
	//history action
	'action_create' => 'Create',
	'action_update' => 'Update',
	'action_delete' => 'Delete',
	'action_attach' => 'Attach',

	'current_env' => 'production',
	//'current_env' => 'dev', 

	'page' => 1,
    'report_limit' => 1000,
    'auth_service_url' => 'http://172.16.7.33:30141/',
    'api_crm_url' => 'http://10.60.90.69:31199/production/',
];




