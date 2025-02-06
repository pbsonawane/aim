<?php
return [
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
									'key' => 'BUS'
								],
								'bvs' => [
									'title' => 'Business Verticals',
									'icon' => 'fa fa-building-o',
									'link' => '/businessverticals',
									'key' => 'BVS'
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
									'key' => 'DCS'
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
									'link' => '/settingstemplate',
									'key' => 'SETTEMPLATE'
								],
								'mailserversetting' => [
									'title' => 'Mail Server Settings',
									'icon' => 'fa fa-envelope',
									'link' => '/config/mailserversetting',
									'key' => 'MAILSET'
								],
								'credential' => [
									'title' => 'Credentials',
									'icon' => 'fa fa-key',
									'link' => '/credential',
									'key' => 'CREDENTIAL'
								],
								'adconfig' => [
									'title' => 'AD Configuration',
									'icon' => 'fa fa-cogs',
									'link' => '/config/adconfig',
									'key' => 'ADCONFIG'
								],
								'rebranding' => [
									'title' => 'Re-branding',
									'icon' => 'fa fa-legal',
									'link' => '/config/rebranding',
									'key' => 'REBRANDING'
								],
								'generalsetting' => [
									'title' => 'General Settings',
									'icon' => 'fa fa-cogs',
									'link' => '/config/generalsetting'
								],
								'loggingsetting' => [
									'title' => 'Logging Settings',
									'icon' => 'fa fa-cogs',
									'link' => '/config/loggingsetting',
									'key' => 'GENSET'
								],
							]
					]	
				]				
		],
		'itam' => [
			
			'title' => 'ITAM',
			'links' => [ 
				'usermanagement' => [
						'title' => 'Asset Management',
						'sublinks' => 
							[
								'citemplates' => [
									'title' => 'CI Templates',
									'icon' => 'fa fa-user',
									'link' => '/citemplates',
									'key' => 'CITEMPLATES'									
								],
								'assets' => [
									'title' => 'Asset',
									'icon' => 'fa fa-user',
									'link' => '/assets',
									'key' => 'ASSETS'									
								]
							]					
                ],
                'purchasemanagement' => [
                    'title' => 'Purchase',
                    'sublinks' => 
                        [                            
                            'purchaserequest' => [
                                'title' => 'Purchase Request',
                                'icon' => 'glyphicons glyphicons-cart_in',
                                'link' => '/purchaserequest',
                                'key' => 'PURCHASEREQUEST'
                            ],
                            'purchaseorders' => [
                                'title' => 'Purchase Order',
                                'icon' => 'glyphicons glyphicons-cart_out',
                                'link' => '/purchaseorders',
                                'key' => 'PURCHASEORDERS'
                            ]
                        ]					
                ],
                'contractmanagement' => [
                    'title' => 'Contract',
                    'sublinks' => 
                        [
                            'contracttype' => [
                                'title' => 'Contract Types',
                                'icon' => 'glyphicons glyphicons-book',
                                'link' => '/contracttype',
                                'key' => 'CONTRACTTYPE'
                            ],
                            'contract' => [
                                'title' => 'Contracts',
                                'icon' => 'glyphicons glyphicons-log_book',
                                'link' => '/contract',
                                'key' => 'CONTRACT'
                            ]


                        ]					
                ]
                
                
                /*,
					'inventory' => [
						'title' => 'Inventory',
						'sublinks' => 
							[
								'bus' => [
									'title' => 'Business Units',
									'icon' => 'fa fa-building',
									'link' => '/businessunits',
									'key' => 'BUS'
								],
								'bvs' => [
									'title' => 'Business Verticals',
									'icon' => 'fa fa-building-o',
									'link' => '/businessverticals',
									'key' => 'BVS'
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
									'key' => 'DCS'
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
									'link' => '/settingstemplate',
									'key' => 'SETTEMPLATE'
								],
								'mailserversetting' => [
									'title' => 'Mail Server Settings',
									'icon' => 'fa fa-envelope',
									'link' => '/config/mailserversetting',
									'key' => 'MAILSET'
								],
								'credential' => [
									'title' => 'Credentials',
									'icon' => 'fa fa-key',
									'link' => '/credential',
									'key' => 'CREDENTIAL'
								],
								'adconfig' => [
									'title' => 'AD Configuration',
									'icon' => 'fa fa-cogs',
									'link' => '/config/adconfig',
									'key' => 'ADCONFIG'
								],
								'rebranding' => [
									'title' => 'Re-branding',
									'icon' => 'fa fa-legal',
									'link' => '/config/rebranding',
									'key' => 'REBRANDING'
								],
								'generalsetting' => [
									'title' => 'General Settings',
									'icon' => 'fa fa-cogs',
									'link' => '/config/generalsetting'
								],
								'loggingsetting' => [
									'title' => 'Logging Settings',
									'icon' => 'fa fa-cogs',
									'link' => '/config/loggingsetting',
									'key' => 'GENSET'
								]
							]
                    ]	*/
				]						
		]
	]
];
