<?php
return [
	'menu' => [
		'iam' => [
			'title' => 'IAM',
			'links' => [ 
				 [
						'title' => 'User Management',
						'sublinks' => 
							[
								'users' => [
									'title' => 'Users',
									'link' => '/users'
								],
								'roles' => [
									'title' => 'Roles',
									'link' => '/roles'
								],
								'permissions' => [
									'title' => 'Permissions',
									'link' => '/permissions'
								],
								'departments' => [
									'title' => 'Departments',
									'link' => '/departments'
								],
								'designations' => [
									'title' => 'Designations',
									'link' => '/designations'
								],
								'modules' => [
									'title' => 'Modules',
									'link' => '/modules'
								]
							]					
					],
					[
						'title' => 'Inventory',
						'sublinks' => 
							[
								'bus' => [
									'title' => 'Business Verticals',
									'link' => '/businessunits'
								],
								'bvs' => [
									'title' => 'Business Verticals',
									'link' => '/businessverticals'
								],
								'regions' => [
									'title' => 'Regions',
									'link' => '/regions'
								],
								'locations' => [
									'title' => 'Locations',
									'link' => '/locations'
								],
								'datacenters' => [
									'title' => 'Datacenters',
									'link' => '/datacenters'
								],
								'pods' => [
									'title' => 'Pods',
									'link' => '/pods'
								]
							]
					],
					[
						'title' => 'Settings',
						'sublinks' => 
							[
								'settingstemplate' => [
									'title' => 'Setting Templates',
									'link' => '/settingstemplate'
								],
								'mailserversetting' => [
									'title' => 'Mail Server Settings',
									'link' => '/config/mailserversetting'
								],
								'credential' => [
									'title' => 'Credentials',
									'link' => '/credential'
								],
								'adconfig' => [
									'title' => 'AD Configuration',
									'link' => '/config/adconfig'
								],
								'rebranding' => [
									'title' => 'Re-branding',
									'link' => '/config/rebranding'
								],
								'generalsetting' => [
									'title' => 'General Settings',
									'link' => '/config/generalsetting'
								],
								'loggingsetting' => [
									'title' => 'Logging Settings',
									'link' => '/config/loggingsetting'
								]
							]
					]	
				]				
		],
		'itam' => [
			'title' => 'ITAM'
		]
	]
];
