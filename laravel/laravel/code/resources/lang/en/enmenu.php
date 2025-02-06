<?php

$menu_array = [
    'menu' => [
        'iam'  => [
            'title' => 'IAM',
            'links' => [
                'usermanagement' => [
                    'title'    => 'User Management',
                    'sublinks' =>
                    [
                        'users'        => [
                            'title' => 'Users',
                            'icon'  => 'fa fa-user',
                            'link'  => '/users',
                            'key'   => 'USERS',

                        ],
                        'roles'        => [
                            'title' => 'Roles',
                            'icon'  => 'fa fa-lock',
                            'link'  => '/roles',
                            'key'   => 'ROLES',
                        ],
                        'permissions'  => [
                            'title' => 'Permissions',
                            'icon'  => 'fa fa-lock',
                            'link'  => '/permissions',
                            'key'   => 'PERMISSIONS',
                        ],
                        'departments'  => [
                            'title' => 'Departments',
                            'icon'  => 'fa fa-building',
                            'link'  => '/departments',
                            'key'   => 'DEPARTMENTS',
                        ],
                        'designations' => [
                            'title' => 'Designations',
                            'icon'  => 'fa fa-user',
                            'link'  => '/designations',
                            'key'   => 'DESIGNATIONS',
                        ],
                        'modules'      => [
                            'title' => 'Modules',
                            'icon'  => 'fa fa-tasks',
                            'link'  => '/modules',
                            'key'   => 'MODULES',
                        ],
                        'whitelistip'  => [
                            'title' => 'Whitelist IP',
                            'icon'  => 'fa fa-unlock-alt',
                            'link'  => '/whitelistip',
                            'key'   => 'WHITELISTIP',
                        ],
                    ],
                ],
                'inventory'      => [
                    'title'    => 'Inventory',
                    'sublinks' =>
                    [
                        'bus'         => [
                            'title' => 'Business Units',
                            'icon'  => 'fa fa-building',
                            'link'  => '/businessunits',
                            'key'   => 'BUSINESSUNITS',
                        ],
                        'bvs'         => [
                            'title' => 'Business Verticals',
                            'icon'  => 'fa fa-building-o',
                            'link'  => '/businessverticals',
                            'key'   => 'BUSINESSVERTICALS',
                        ],
                        'regions'     => [
                            'title' => 'Regions',
                            'icon'  => 'fa fa-map-marker',
                            'link'  => '/regions',
                            'key'   => 'REGIONS',
                        ],
                        'locations'   => [
                            'title' => 'Locations',
                            'icon'  => 'fa fa-map-marker',
                            'link'  => '/locations',
                            'key'   => 'LOCATIONS',
                        ],
                        'datacenters' => [
                            'title' => 'Datacenters',
                            'icon'  => 'fa fa-building',
                            'link'  => '/datacenters',
                            'key'   => 'DATACENTERS',
                        ],
                        'pods'        => [
                            'title' => 'Pods',
                            'icon'  => 'fa fa-cloud',
                            'link'  => '/pods',
                            'key'   => 'PODS',
                        ],
                    ],
                ],
                'settings'       => [
                    'title'    => 'Settings',
                    'sublinks' =>
                    [
                        'settingstemplate'    => [
                            'title' => 'Setting Templates',
                            'icon'  => 'fa fa-cog',
                            'link'  => '/settingstemplates',
                            'key'   => 'SETTINGSTEMPLATES',
                        ],
                        'credential'          => [
                            'title' => 'Credentials',
                            'icon'  => 'fa fa-key',
                            'link'  => '/credentials',
                            'key'   => 'CREDENTIALS',
                        ],
                        'rebranding'          => [
                            'title' => 'Re-branding',
                            'icon'  => 'fa fa-legal',
                            'link'  => '/rebranding',
                            'key'   => 'REBRANDING',
                        ],
                        'ensysconfig_setting' => [
                            'title' => 'Configuration Setting',
                            'icon'  => 'fa fa-wrench',
                            'link'  => '/config/ensysconfig_setting',
                            'key'   => 'CONFIG',
                        ],
                    ],
                ],
                'logs'           => [
                    'title'    => 'Logs',
                    'sublinks' =>
                    [
                        'error'     => [
                            'title' => 'Error Logs',
                            'icon'  => 'fa fa-exclamation-triangle',
                            'link'  => '/logs/error',
                            'key'   => 'LOGS',
                        ],
                        'analytics' => [
                            'title' => 'Analytics Logs',
                            'icon'  => 'fa fa-clock-o',
                            'link'  => '/logs/analytics',
                            'key'   => 'LOGS',
                        ],
                        'response'  => [
                            'title' => 'Response Logs',
                            'icon'  => 'fa fa-history',
                            'link'  => '/logs/response',
                            'key'   => 'LOGS',
                        ],
                        'debug'     => [
                            'title' => 'Debug Logs',
                            'icon'  => 'fa fa-bug',
                            'link'  => '/logs/debug',
                            'key'   => 'LOGS',
                        ],
                    ],
                ],
            ],
        ],
        'itam' => [

            'title' => 'ITAM',
            'links' => [
                'usermanagement'     => [
                    'title'    => 'Asset Management',
                    'sublinks' =>
                    [

                        'assets'       => [
                            'title' => 'Assets',
                            'icon'  => 'fa fa-user',
                            'link'  => '/assets/',
                            'key'   => 'ASSETS',
                        ],
                        'software'     => [
                            'title' => 'Softwares',
                            'icon'  => 'fa fa-desktop',
                            //'link' => '/software',
                            'link'  => '/softwaredashboard',

                            'key'   => 'SOFTWARE',
                        ],
                        'asset_import' => [
                            'title' => 'Import Asset',
                            'icon'  => 'fa fa-arrow-circle-o-down',
                            'link'  => '/asset_import',

                            'key'   => 'ASSET_IMPORT',
                        ],
                        'complained_raised' => [
                            'title' => 'Complaint Raised',
                            'icon'  => 'fa fa-solid fa-trash',
                            'link'  => '/complaintraised',
                            'key'   => 'COMPLAINT_RAISED',
                        ],
                        'complained_raised_report' => [
                            'title' => 'Complaint Raised Report',
                            'icon'  => 'fa fa-solid fa-trash',
                            'link'  => '/complaintraisedreport',
                            'key'   => 'COMPLAINT_RAISED_REPORT',
                        ],
                    'softwaredashboard' => [
                    'title' => 'Software Dashboard',
                    'icon' => 'fa fa-desktop',
                    'link' => '/softwaredashboard',
                    'key' => 'SOFTWAREDASHBOARD'
                    ],

                    'licensedashboad' => [
                    'title' => ' License Dashboard',
                    'icon' => 'fa fa-desktop',
                    'link' => '/licensedashboard/view',
                    'key' => 'LICENSEDASHBOARD'
                    ],

                    'storedashboad' => [
                    'title' => ' Store Dashboard',
                    'icon' => 'fa fa-desktop',
                    'link' => '/storedashboard',
                    'key' => 'STOREDASHBOARD'
                    ],


                    ],
                ],
                'purchasemanagement' => [
                    'title'    => 'Purchase',
                    'sublinks' =>
                    [
                        'dashboard'       => [
                            'title' => 'Purchase User Dashboard',
                            'icon'  => 'glyphicons glyphicons-dashboard',
                            'link'  => '/purchaseuserdashboard',
                            'key'   => 'PURCHASEUSERDASHBOARD',
                        ],
                        'purchaserequest' => [
                            'title' => 'Purchase Request',
                            'icon'  => 'glyphicons glyphicons-cart_in',
                            'link'  => '/purchaserequest',
                            'key'   => 'PURCHASEREQUEST',
                        ],
                        'purchaseorders'  => [
                            'title' => 'Purchase Order',
                            'icon'  => 'glyphicons glyphicons-cart_out',
                            'link'  => '/purchaseorders',
                            'key'   => 'PURCHASEORDERS',
                        ],
                        'opportunity'     => [
                            'title' => 'Upcoming Opportunity',
                            'icon'  => 'glyphicons glyphicons-refresh',
                            'link'  => '/opportunity',
                            'key'   => 'OPPORTUNITY',
                        ],
                       'trackpurchaserequest'     => [
                            'title' => 'Track Purchase Request',
                            'icon'  => 'glyphicons glyphicons-cart_out',
                            'link'  => '/trackpurchaserequest',
                            'key'   => 'TRACKPURCHASEREQUEST',
                        ],
                        'trackpurchaseorder'     => [
                            'title' => 'Track Purchase Order',
                            'icon'  => 'glyphicons glyphicons-cart_out',
                            'link'  => '/trackpurchaseorder',
                            'key'   => 'TRACKPURCHASEORDER',
                        ],
                       /*  'trackpurchasereport'     => [
                            'title' => 'Track Purchase Report',
                            'icon'  => 'glyphicons glyphicons-cart_out',
                            'link'  => '/trackpurchasereport',
                            'key'   => 'TRACKPURCHASEREPORT',
                        ],*/
                    ],
                ],
                /*'contractmanagement' => [
                'title'    => 'Contract',
                'sublinks' =>
                [
                'contracttype' => [
                'title' => 'Contract Types',
                'icon'  => 'fa fa-file-text',
                'link'  => '/contracttype',
                'key'   => 'CONTRACTTYPE',
                ],
                'contract'     => [
                'title' => 'Contracts',
                'icon'  => 'fa fa-file',
                'link'  => '/contract',
                'key'   => 'CONTRACT',
                ],
                'costcenters' => [
                'title' => 'Costcenters',
                'icon' => 'glyphicons glyphicons-log_book',
                'link' => '/costcenters',
                'key' => 'COSTCENTERS'
                ],

                ],
                ],*/
                'settings'           => [
                    'title'    => 'Settings',
                    'sublinks' =>
                    [
                        'contact'          => [
                            'title' => 'Contacts',
                            'icon'  => 'fa fa-phone-square',
                            'link'  => '/contact',
                            'key'   => 'CONTACT',
                        ],
                        'requestername'    => [
                            'title' => 'Requester Name',
                            'icon'  => 'fa fa-user',
                            'link'  => '/requestername',
                            'key'   => 'REQUESTERNAME',
                        ],
                        'vendor'           => [
                            'title' => 'Vendors',
                            'icon'  => 'fa fa-truck',
                            'link'  => '/vendor',
                            'key'   => 'VENDOR',
                        ],
                        'billto'           => [
                            'title' => 'Bill To',
                            'icon'  => 'fa fa-map-marker',
                            'link'  => '/billto',
                            'key'   => 'BILLTO',
                        ],
                        'shipto'           => [
                            'title' => 'Ship To',
                            'icon'  => 'fa fa-location-arrow',
                            'link'  => '/shipto',
                            'key'   => 'SHIPTO',
                        ],
                        'paymentterm'      => [
                            'title' => 'Payment Terms',
                            'icon'  => 'fa fa-money',
                            'link'  => '/paymentterm',
                            'key'   => 'PAYMENTTERM',
                        ],
                        'delivery'         => [
                            'title' => 'Delivery Details',
                            'icon'  => 'fa fa-sign-out',
                            'link'  => '/delivery',
                            'key'   => 'DELIVERY',
                        ],
                        'citemplates'      => [
                            'title' => 'CI Templates',
                            'icon'  => 'fa fa-puzzle-piece',
                            'link'  => '/citemplates',
                            'key'   => 'CITEMPLATES',
                        ],
                        /*
                        'relationshiptype' => [
                            'title' => 'Relationship Type',
                            'icon'  => 'fa fa-random',
                            'link'  => '/relationshiptype',
                            'key'   => 'RELATIONSHIPTYPE',
                        ],
                        */
                        'softwaretype'         => [
                        'title' => 'Software Types',
                        'icon'  => 'fa fa-list-alt',
                        'link'  => '/softwaretype',
                        'key'   => 'SOFTWARETYPE',
                        ],
                        'softwarecategory'     => [
                        'title' => 'Software Category',
                        'icon'  => 'fa fa-laptop',
                        'link'  => '/softwarecategory',
                        'key'   => 'SOFTWARECATEGORY',
                        ],
                        'softwaremanufacturer' => [
                        'title' => 'Software Manufacturer',
                        'icon'  => 'fa fa-desktop',
                        'link'  => '/softwaremanufacturer',
                        'key'   => 'SOFTWAREMANUFACTURER',
                        ],
                        // 'licensetype'          => [
                        // 'title' => 'License Type',
                        // 'icon'  => 'fa fa-certificate',
                        // 'link'  => '/licensetype',
                        // 'key'   => 'LICENSETYPE',
                        // ],
                        // 'emailtemplate'    => [
                        //     'title' => 'Email Templates',
                        //     'icon'  => 'fa fa-book',
                        //     'link'  => '/emailtemplate',
                        //     'key'   => 'EMAILTEMPLATE',
                        // ],
                     //   'costcenter'       => [
                          //  'title' => 'Cost Centers',
                          //  'icon'  => 'fa fa-money',
                          //  'link'  => '/costcenter',
                         //   'key'   => 'COSTCENTER',
                     //   ],
                        // 'settingstemplate' => [
                        //     'title' => 'Setting Templates',
                        //     'icon'  => 'fa fa-cog',
                        //     'link'  => '/settingstemplate',
                        //     'key'   => 'SETTINGSTEMPLATE',
                        // ],

                    ],
                ],
                'report'             => [
                    'title'    => 'Reports',
                    'sublinks' =>
                    [
                       'reportcategory' => [
                            'title' => 'Report Category',
                            'icon'  => 'fa fa-tags',
                            'link'  => '/reportcategory',
                            'key'   => 'REPORTCATEGORY',
                        ],
                        'reports'        => [
                            'title' => 'Reports',
                            'icon'  => 'fa fa-bar-chart',
                            'link'  => '/reports',
                            'key'   => 'REPORTS',
                        ],
                        /* 'pbireports'        => [
                            'title' => 'Export PBI Reports',
                            'icon'  => 'fa fa-download',
                            'link'  => '/pbireports',
                            'key'   => 'PBIREPORTS',
                        ],*/

			/*  'trackpurchaserequest'     => [
                            'title' => 'Purchase Request Report',
                            'icon'  => 'fa fa-pie-chart',
                            'link'  => '/trackpurchaserequest',
                            'key'   => 'TRACKPURCHASEREQUEST',
                        ],
			
			 'trackpurchaseorder'     => [
                            'title' => 'Purchase Order Report',
                            'icon'  => 'fa fa-line-chart',
                            'link'  => '/trackpurchaseorder',
                            'key'   => 'TRACKPURCHASEORDER',
                        ],*/
			
				
			
                         
                    ],
                ],

                
         /* 'inventory' => [
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
            ],*/
            // 'settings' => [
            // 'title' => 'Settings',
            // 'sublinks' =>
            // [
            // 'settingstemplate' => [
            // 'title' => 'Setting Templates',
            // 'icon' => 'fa fa-cog',
            // 'link' => '/settingstemplate',
            // 'key' => 'SETTEMPLATE'
            // ],
            // 'mailserversetting' => [
            // 'title' => 'Mail Server Settings',
            // 'icon' => 'fa fa-envelope',
            // 'link' => '/config/mailserversetting',
            // 'key' => 'MAILSET'
            // ],
            // 'credential' => [
            // 'title' => 'Credentials',
            // 'icon' => 'fa fa-key',
            // 'link' => '/credential',
            // 'key' => 'CREDENTIAL'
            // ],
            // 'adconfig' => [
            // 'title' => 'AD Configuration',
            // 'icon' => 'fa fa-cogs',
            // 'link' => '/config/adconfig',
            // 'key' => 'ADCONFIG'
            // ],
            // 'rebranding' => [
            // 'title' => 'Re-branding',
            // 'icon' => 'fa fa-legal',
            // 'link' => '/config/rebranding',
            // 'key' => 'REBRANDING'
            // ],
            // 'generalsetting' => [
            // 'title' => 'General Settings',
            // 'icon' => 'fa fa-cogs',
            // 'link' => '/config/generalsetting'
            // ],
            // 'loggingsetting' => [
            // 'title' => 'Logging Settings',
            // 'icon' => 'fa fa-cogs',
            // 'link' => '/config/loggingsetting',
            // 'key' => 'GENSET'
            // ]
            // ]
            // ]   
            ],
        ],

    ],
];

$menu_array = setconfigsettingmenu($menu_array);
return $menu_array;
