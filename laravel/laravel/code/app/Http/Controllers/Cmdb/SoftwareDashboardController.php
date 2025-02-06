<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *SoftwareDashboardController class is implemented to do SoftwareDashboard operations.
 * @author Kavita Daware
 * @package SoftwareDashboard
 */
class SoftwareDashboardController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package SoftwareDashboard
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }
    /**
     * SoftwareDashboardController function is implemented to initiate a page to get list of SoftwareDashboard.
     * @author Kavita Daware
     * @access public
     * @package SoftwareDashboard
     * @return string
     */

    public function softwaredashboard(Request $request)
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'softwareDashboard()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["softwaredashboard"]);
        $data['pageTitle'] = trans('title.softwaredashboard');
        //$data['software_id'] = $software_id;
        $respsw = $this->itam->getswdashboard([]);
        //dd($respsw );
        if ($respsw['is_error'])
        {
            $dashboard = [];
        }
        else
        {
            $dashboard = _isset($respsw, 'content');
        }
        $data['dashboard'] = $dashboard;
        
        $resplicense = $this->itam->getswdashboardlicense([]);
       
        if ($resplicense['is_error'])
        {
            $dashboardlicense = [];
        }
        else
        {
            $dashboardlicense = _isset($resplicense, 'content');
        }
        $data['dashboardlicense'] = $dashboardlicense;
  

        $resp = $this->itam->getswallocationallsw([]);
          //dd($resp);
        if ($resp['is_error'])
        {
            $swallocationsallsw = [];
        }
        else
        {
                //$swallocations = _isset(_isset($resp, 'content'), 'records');
            $swallocationsallsw = _isset($resp, 'content');

        }

        $data['swallocationsallsw'] = $swallocationsallsw;

        $respcount = $this->itam->getswpurchasecountallsw([]);
            //dd($respcount);

        if ($respcount['is_error'])
        {
            $purchasecountallsw = [];
        }
        else
        {
            $purchasecountallsw = _isset($respcount, 'content');

        }

        $data['purchasecountallsw'] = $purchasecountallsw;   
            
        $respmanufacturer = $this->itam->getswdashboardmanufacturer([]);

        if ($respmanufacturer['is_error'])
        {
            $dashboardmanufacturer = [];
        }
        else
        {
            $dashboardmanufacturer= _isset($respmanufacturer, 'content');
        }
        $data['dashboardmanufacturer'] = $dashboardmanufacturer;
            
        $data['includeView'] = view("Cmdb/softwaredashboard", $data);
        return view('template', $data);
    }

     public function license_dashboard_c(Request $request)
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'softwareDashboard()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["softwaredashboard"]);
        $data['pageTitle'] = trans('title.softwaredashboard');
        //$data['software_id'] = $software_id;
        $respsw = $this->itam->getlicensedashboard([]);
      
        if ($respsw['is_error'])
        {
            $dashboard = [];
        }
        else
        {
            $dashboard = _isset($respsw, 'content');
        }
        $data['dashboard'] = $dashboard;
        // echo "<pre>";
        // print_r($dashboard);
        // die();
        
        $resplicense = $this->itam->getdatabaseboard([]);
       
        if ($resplicense['is_error'])
        {
            $dashboardlicense = [];
        }
        else
        {
            $dashboardlicense = _isset($resplicense, 'content');
        }
        $data['dashboardlicense'] = $dashboardlicense;
       
  

        $resp = $this->itam->getcpaneldashboard([]);
          //dd($resp);
        if ($resp['is_error'])
        {
            $cpaneldashboard = [];
        }
        else
        {
        
            $cpaneldashboard = _isset($resp, 'content');

        }

         $data['cpaneldashboard'] = $cpaneldashboard;
        // echo "<pre>";
        // print_r($cpaneldashboard);
        // die();

        // $respcount = $this->itam->getswpurchasecountallsw(array());
        //     //dd($respcount);

        // if ($respcount['is_error'])
        // {
        //     $purchasecountallsw = array();
        // }
        // else
        // {
        //     $purchasecountallsw = _isset($respcount, 'content');

        // }

        // $data['purchasecountallsw'] = $purchasecountallsw;   
            
        // $respmanufacturer = $this->itam->getswdashboardmanufacturer(array());

        // if ($respmanufacturer['is_error'])
        // {
        //     $dashboardmanufacturer = array();
        // }
        // else
        // {
        //     $dashboardmanufacturer= _isset($respmanufacturer, 'content');
        // }
        // $data['dashboardmanufacturer'] = $dashboardmanufacturer;
            
        $data['includeView'] = view("Cmdb/license_dashboard", $data);
        return view('template', $data,compact($dashboard,$dashboardlicense,$cpaneldashboard));
    }



     public function storedashboard_view(Request $request)
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'softwareDashboard()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["softwaredashboard"]);
        $data['pageTitle'] = trans('title.softwaredashboard');
        //$data['software_id'] = $software_id;
        $respsw = $this->itam->getstoredashboard([]);
       // print_r($respsw); die();
      
        if ($respsw['is_error'])
        {
            $dashboard = [];
        }
        else
        {
            $dashboard = _isset($respsw, 'content');
        }
        $data['dashboard'] = $dashboard;
        /* echo "<pre>";
         print_r($dashboard);
        die();*/
        
        // echo "<pre>";
        // print_r($cpaneldashboard);
        // die();

        // $respcount = $this->itam->getswpurchasecountallsw(array());
        //     //dd($respcount);

        // if ($respcount['is_error'])
        // {
        //     $purchasecountallsw = array();
        // }
        // else
        // {
        //     $purchasecountallsw = _isset($respcount, 'content');

        // }

        // $data['purchasecountallsw'] = $purchasecountallsw;   
            
        // $respmanufacturer = $this->itam->getswdashboardmanufacturer(array());

        // if ($respmanufacturer['is_error'])
        // {
        //     $dashboardmanufacturer = array();
        // }
        // else
        // {
        //     $dashboardmanufacturer= _isset($respmanufacturer, 'content');
        // }
        // $data['dashboardmanufacturer'] = $dashboardmanufacturer;
            
        $data['includeView'] = view("Cmdb/store_dashboard", $data);
        return view('template', $data,compact($dashboard));
    }


    
}
