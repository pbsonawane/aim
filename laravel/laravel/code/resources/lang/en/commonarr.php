<?php
return [
    'template_type'       => ['po' => 'PO', 'warrenty' => 'Warrenty', 'service' => 'Service', 'cr' => 'CR', 'incident' => 'Incident', 'problem' => 'Problem', 'config' => 'Config', 'credentials' => 'Credentials'],

    'yes_no'              => ['y' => 'Yes', 'n' => 'No'],

    'role_type'           => ['client' => 'Client', 'staff' => 'Staff'],

    'selected_criteria'   => ['equal' => 'Equals', 'notequal' => 'Not Equals'],

    'installation_allow'  => ['unlimited' => 'Unlimited', 'single' => 'Single', 'volume' => 'Volume', 'oem' => 'OEM'],

    'ci_types'            => ['software' => 'Software'],

    'devices'             => ['server' => 'Server', 'desktop' => 'Desktop', 'laptop' => 'Laptop'],

    'match'               => ['AND' => 'AND', 'OR' => 'OR'],

    'contract_status'     => ['active' => 'Active', 'expired' => 'Expired'],

    'pr_priority'         => ['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'],

    'po_status'           => ['pending approval' => 'Pending Approval', 'open' => 'Open', 'partially approved' => 'Partially Approved', 'approved' => 'Approved', 'partially received' => 'Partially Received', 'item received' => 'Item Received', 'closed' => 'Closed', 'cancelled' => 'Cancelled', 'deleted' => 'Deleted'],
    
    'pr_status'           => ['pending approval' => 'Pending Approval', 'open' => 'Open', 'approved' => 'Approved', 'closed' => 'Closed', 'cancelled' => 'Cancelled', 'deleted' => 'Deleted'],

    'asset_status'        => ['in_store' => 'In Store', 'in_use' => 'In Use', 'in_repair' => 'In Repair', 'expired' => 'Expired', 'disposed' => 'Disposed'],

    'prefix'              => ['' => '-Select-', 'Ms' => 'Ms', 'Miss' => 'Miss', 'Mrs' => 'Mrs', 'Mr' => 'Mr', 'Dr' => 'Dr'],

    'associated_with'     => ['' => '-Select-', 'Bill To' => 'Bill To', 'Ship To' => 'Ship To', 'Other' => 'Other'],

    'pr_taxes'            => ['NA' => 'NA', 'Including All' => 'Including All', 'Extra At Actual' => 'Extra At Actual'],

    'pr_project_catergoy' => ['External' => 'External', 'Internal' => 'Internal'],

    'pr_category'         => ['Hardware' => 'Hardware', 'Software' => 'Software', 'Services' => 'Services'],

    'pr_requirement_for'  => ['IT' => 'IT', 'Non IT' => 'Non IT', 'Advertisement' => 'Advertisement', 'Connectivity' => 'Connectivity'],

    'pr_poject_name_dd'   => ['MDC' => 'MDC', 'BDC' => 'BDC', 'NDC' => 'NDC'],

    //'selected_criteria'=> array('equal' => 'Equals', 'notequal' => 'Not Equals', 'contains'=>'Contains', 'notcontains'=>'Not Contains', 'start_with'=>'Start With','end_with'=>'End With'),
];
