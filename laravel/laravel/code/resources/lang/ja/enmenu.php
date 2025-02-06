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
						'title' => '資産運用管理',
						'sublinks' => 
							[
								
								'assets' => [
									'title' => '資産',
									'icon' => 'fa fa-user',
									'link' => '/assets/',
									'key' => 'ASSETS'									
                                ],
                                'software' => [
                                    'title' => 'ソフトウェア',
                                    'icon' => 'fa fa-desktop',
                                    //'link' => '/software',
                                    'link' => '/softwaredashboard',

                                    'key' => 'SOFTWARE'
                                ],
                                'asset_import' => [
                                    'title' => 'アセットをインポート',
                                    'icon' => 'fa fa-arrow-circle-o-down',
                                    'link' => '/asset_import',

                                    'key' => 'ASSET_IMPORT'
                                ],
							]					
                ],
                'purchasemanagement' => [
                    'title' => '購入',
                    'sublinks' => 
                        [                            
                            'purchaserequest' => [
                                'title' => '購入依頼',
                                'icon' => 'glyphicons glyphicons-cart_in',
                                'link' => '/purchaserequest',
                                'key' => 'PURCHASEREQUEST'
                            ],
                            'purchaseorders' => [
                                'title' => '注文書',
                                'icon' => 'glyphicons glyphicons-cart_out',
                                'link' => '/purchaseorders',
                                'key' => 'PURCHASEORDERS'
                            ],
                           
                        ]					
                ],
                'contractmanagement' => [
                    'title' => '契約する',
                    'sublinks' => 
                        [
                            'contracttype' => [
                                'title' => '契約タイプ',
                                'icon' => 'fa fa-file-text',
                                'link' => '/contracttype',
                                'key' => 'CONTRACTTYPE'
                            ],
                            'contract' => [
                                'title' => '契約',
                                'icon' => 'fa fa-file',
                                'link' => '/contract',
                                'key' => 'CONTRACT'
                            ],
                        ]					
                ],
                'settings' => [
                    'title' => '設定',
                    'sublinks' => 
                        [	
							'citemplates' => [
									'title' => 'CIテンプレート',
									'icon' => 'fa fa-puzzle-piece',
									'link' => '/citemplates',
									'key' => 'CITEMPLATES'									
								],
                            'vendor' => [
                                'title' => 'ベンダー',
                                'icon'  => 'fa fa-truck',
                                'link'  => '/vendor',
                                'key'   => 'VENDOR'
                            ],
                            'relationshiptype' => [
                                'title' => '関係タイプ',
                                'icon'  => 'fa fa-random',
                                'link'  => '/relationshiptype',
                                'key'   => 'RELATIONSHIPTYPE'
                            ],
                            'softwaretype' => [
                                'title' => 'ソフトウェアの種類',
                                'icon' => 'fa fa-list-alt',
                                'link' => '/softwaretype',
                                'key' => 'SOFTWARETYPE'
                            ],
                            'softwarecategory' => [
                                'title' => 'ソフトウェアカテゴリ',
                                'icon' => 'fa fa-laptop',
                                'link' => '/softwarecategory',
                                'key' => 'SOFTWARECATEGORY'
                            ],
                            'softwaremanufacturer' => [
                                'title' => 'ソフトウェアメーカー',
                                'icon' => 'fa fa-desktop',
                                'link' => '/softwaremanufacturer',
                                'key' => 'SOFTWAREMANUFACTURER'
                            ],
                            'licensetype' => [
                                'title' => 'ライセンスの種類',
                                'icon' => 'fa fa-certificate',
                                'link' => '/licensetype',
                                'key' => 'LICENSETYPE'
                            ],
                            'emailtemplate' => [
                                'title' => 'メールテンプレート',
                                'icon' => 'fa fa-book',
                                'link' => '/emailtemplate',
                                'key' => 'EMAILTEMPLATE'
                            ],
                            'costcenter' => [
                                'title' => 'コストセンター',
                                'icon' => 'fa fa-money',
                                'link' => '/costcenter',
                                'key' => 'COSTCENTER'
                            ],
							'settingstemplate' => [
									'title' => 'テンプレートの設定',
									'icon' => 'fa fa-cog',
									'link' => '/settingstemplate',
									'key' => 'SETTINGSTEMPLATE'
							],
                            
                        ]					
                ],
                'report' => [
                    'title' 	=> '報告書',
                    'sublinks'  => 
                        [
                            'reportcategory'=> [
                                'title' 	=> 'レポートのカテゴリ',
                                'icon' 		=> 'fa fa-tags',
                                'link' 		=> '/reportcategory',
                                'key' 		=> 'REPORTCATEGORY'
                            ],
                            'reports' => [
                                'title' => '報告書',
                                'icon' 	=> 'fa fa-bar-chart',
                                'link' 	=> '/reports',
                                'key' 	=> 'REPORTS'
                            ],
                        ]					
                ],
			]						
		]
		
	]
];

$menu_array = setconfigsettingmenu($menu_array);
return $menu_array;