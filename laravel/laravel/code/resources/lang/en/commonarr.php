<?php
return [
    'template_type'       => array('po' => 'PO', 'warrenty' => 'Warrenty', 'service' => 'Service', 'cr' => 'CR', 'incident' => 'Incident', 'problem' => 'Problem', 'config' => 'Config', 'credentials' => 'Credentials'),

    'yes_no'              => array('y' => 'Yes', 'n' => 'No'),

    'role_type'           => array('client' => 'Client', 'staff' => 'Staff'),

    'selected_criteria'   => array('equal' => 'Equals', 'notequal' => 'Not Equals'),

    'installation_allow'  => array('unlimited' => 'Unlimited', 'single' => 'Single', 'volume' => 'Volume', 'oem' => 'OEM'),

    'ci_types'            => array('software' => 'Software'),

    'devices'             => array('server' => 'Server', 'desktop' => 'Desktop', 'laptop' => 'Laptop'),

    'match'               => array('AND' => 'AND', 'OR' => 'OR'),

    'contract_status'     => array('active' => 'Active', 'expired' => 'Expired'),

    'pr_priority'         => array('high' => 'High', 'medium' => 'Medium', 'low' => 'Low'),

    'po_status'           => array('pending approval' => 'Pending Approval', 'open' => 'Open', 'partially approved' => 'Partially Approved', 'approved' => 'Approved', 'partially received' => 'Partially Received', 'item received' => 'Item Received', 'closed' => 'Closed', 'cancelled' => 'Cancelled', 'deleted' => 'Deleted'),
    
    'pr_status'           => array('pending approval' => 'Pending Approval', 'open' => 'Open', 'approved' => 'Approved', 'closed' => 'Closed', 'cancelled' => 'Cancelled', 'deleted' => 'Deleted'),

    'asset_status'        => array('in_store' => 'In Store', 'in_use' => 'In Use', 'in_repair' => 'In Repair', 'expired' => 'Expired', 'disposed' => 'Disposed'),

    'prefix'              => array('' => '-Select-', 'Ms' => 'Ms', 'Miss' => 'Miss', 'Mrs' => 'Mrs', 'Mr' => 'Mr', 'Dr' => 'Dr'),

    'associated_with'     => array('' => '-Select-', 'Bill To' => 'Bill To', 'Ship To' => 'Ship To', 'Other' => 'Other'),

    'pr_taxes'            => array('NA' => 'NA', 'Including All' => 'Including All', 'Extra At Actual' => 'Extra At Actual'),

    'pr_project_catergoy' => array('External' => 'External', 'Internal' => 'Internal'),

    'pr_category'         => array('Hardware' => 'Hardware', 'Software' => 'Software', 'Services' => 'Services'),

    'pr_requirement_for'  => array('IT' => 'IT', 'Non IT' => 'Non IT', 'Advertisement' => 'Advertisement', 'Connectivity' => 'Connectivity'),

    'pr_poject_name_dd'   => array('MDC' => 'MDC', 'BDC' => 'BDC', 'NDC' => 'NDC'),

    //'selected_criteria'=> array('equal' => 'Equals', 'notequal' => 'Not Equals', 'contains'=>'Contains', 'notcontains'=>'Not Contains', 'start_with'=>'Start With','end_with'=>'End With'),
];
