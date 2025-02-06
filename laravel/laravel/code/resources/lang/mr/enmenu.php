<?php

$menu_array = [
	'menu' => [
		'iam' => [
			'title' => 'IAM',
			'links' => [ 
					'usermanagement' => [
						'title' => 'User Management',
						'sublinks' => 
							[
								'users' => [
									'title' => 'Users',
									'icon' => 'fa fa-user',
									'link' => '/users',
									'key' => 'USERS'
									
								],
								'roles' => [
									'title' => 'Roles',
									'icon' => 'fa fa-lock',
									'link' => '/roles',
									'key' => 'ROLES'
								],
								'permissions' => [
									'title' => 'Permissions',
									'icon' => 'fa fa-lock',
									'link' => '/permissions',
									'key' => 'PERMISSIONS'
								],
								'departments' => [
									'title' => 'Departments',
									'icon' => 'fa fa-building',
									'link' => '/departments',
									'key' => 'DEPARTMENTS'
								],
								'designations' => [
									'title' => 'Designations',
									'icon' => 'fa fa-user',
									'link' => '/designations',
									'key' => 'DESIGNATIONS'
								],
								'modules' => [
									'title' => 'Modules',
									'icon' => 'fa fa-tasks',
									'link' => '/modules',
									'key' => 'MODULES'
                                ],
                                'whitelistip' => [
									'title' => 'Whitelist IP',
									'icon' => 'fa fa-unlock-alt',
									'link' => '/whitelistip',
									'key' => 'WHITELISTIP'
								]
							]					
					],
					'inventory' => [
						'title' => 'Inventory',
						'sublinks' => 
							[
								'bus' => [
									'title' => 'Business Units',
									'icon' => 'fa fa-building',
									'link' => '/businessunits',
									'key' => 'BUSINESSUNITS'
								],
								'bvs' => [
									'title' => 'Business Verticals',
									'icon' => 'fa fa-building-o',
									'link' => '/businessverticals',
									'key' => 'BUSINESSVERTICALS'
								],
								'regions' => [
									'title' => 'Regions',
									'icon' => 'fa fa-map-marker',
									'link' => '/regions',
									'key' => 'REGIONS'
								],
								'locations' => [
									'title' => 'Locations',
									'icon' => 'fa fa-map-marker',
									'link' => '/locations',
									'key' => 'LOCATIONS'
								],
								'datacenters' => [
									'title' => 'Datacenters',
									'icon' => 'fa fa-building',
									'link' => '/datacenters',
									'key' => 'DATACENTERS'
								],
								'pods' => [
									'title' => 'Pods',
									'icon' => 'fa fa-cloud',
									'link' => '/pods',
									'key' => 'PODS'
								]
							]
					],
					'settings' => [
						'title' => 'Settings',
						'sublinks' => 
							[
								'settingstemplate' => [
									'title' => 'Setting Templates',
									'icon' => 'fa fa-cog',
									'link' => '/settingstemplates',
									'key' => 'SETTINGSTEMPLATES'
								],
								'credential' => [
									'title' => 'Credentials',
									'icon' => 'fa fa-key',
									'link' => '/credentials',
									'key' => 'CREDENTIALS'
								],
								'rebranding' => [
									'title' => 'Re-branding',
									'icon' => 'fa fa-legal',
									'link' => '/rebranding',
									'key' => 'REBRANDING'
								],
								'ensysconfig_setting' => [
									'title' => 'Configuration Setting',
									'icon' => 'fa fa-wrench',
									'link' => '/config/ensysconfig_setting',
									'key' => 'CONFIG'
								]
							]
					],
					'logs' => [
						'title' => 'Logs',
						'sublinks' => 
							[
								'error' => [
									'title' => 'Error Logs',
									'icon' => 'fa fa-exclamation-triangle',
									'link' => '/logs/error',
									'key' => 'LOGS'
								],
								'analytics' => [
									'title' => 'Analytics Logs',
									'icon' => 'fa fa-clock-o',
									'link' => '/logs/analytics',
									'key' => 'LOGS'
								],
								'response' => [
									'title' => 'Response Logs',
									'icon' => 'fa fa-history',
									'link' => '/logs/response',
									'key' => 'LOGS'
								],
								'debug' => [
									'title' => 'Debug Logs',
									'icon' => 'fa fa-bug',
									'link' => '/logs/debug',
									'key' => 'LOGS'
								]
							]
					]	
				]				
		],
		'itam' => [
			
			'title' => 'ITAM',
			'links' => [ 
				'usermanagement' => [
						'title' => 'CMDB',
						'sublinks' => 
							[
								'assets' => [
									'title' => 'Assets',
									'icon' => 'fa fa-user',
									'link' => '/users',
									'key' => 'ASSETS'
									
								],
								'purchaserequest' => [
									'title' => 'Purchase Request',
									'icon' => 'fa fa-lock',
									'link' => '/roles',
									'key' => 'PR'
								],
								'purchaseorder' => [
									'title' => 'Purchase Order',
									'icon' => 'fa fa-lock',
									'link' => '/permissions',
									'key' => 'PO'
								],
								'contracts' => [
									'title' => 'Contracts',
									'icon' => 'fa fa-building',
									'link' => '/departments',
									'key' => 'CONTRACTS'
								]
							]					
					],
					'admin' => [
						'title' => 'Admin',
						'sublinks' => 
							[
								'vendors' => [
									'title' => 'Vendors',
									'icon' => 'fa fa-cog',
									'link' => '/settingstemplate',
									'key' => 'VENDORS'
								],
								'citypes' => [
									'title' => 'CI Types',
									'icon' => 'fa fa-envelope',
									'link' => '/config/mailserversetting',
									'key' => 'CITYPE'
								],
								'citemplates' => [
									'title' => 'CI Template',
									'icon' => 'fa fa-key',
									'link' => '/credential',
									'key' => 'CITEMPLATE'
								],
								'costcenter' => [
									'title' => 'Cost Center',
									'icon' => 'fa fa-cogs',
									'link' => '/config/adconfig',
									'key' => 'COSTCENTER'
								],
								'contracttypes' => [
									'title' => 'Contract Types',
									'icon' => 'fa fa-legal',
									'link' => '/rebranding',
									'key' => 'CONTRACTTYPES'
								]
								
							]
					]	
				]						
		],
		'cloud' => [
			'title' => 'Cloud',
			#'links' => []							
		],
		'monitoring' => [
			'title' => 'Monitoring',
			'links' => []							
		],
		'siem' => [
			'title' => 'SIEM',
			'links' => []							
		],
		'netflow' => [
			'title' => 'NetFlow',
			'links' => []							
		],
		
		'application' => [
			'title' => 'App Monitoring',
			'links' => []							
		],
		
	]
];

$menu_array = setconfigsettingmenu($menu_array);
return $menu_array;