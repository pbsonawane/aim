<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FileChecksumService;
use Session;

/**
 * Checksum Controller class is implemented to operation of checksum
 * @author Sagar Sainkar
 * @package Dashboard
 */
class ChecksumController extends Controller
{
   
    public function __construct(Request $request)
    {

        $this->FileChecksumService = new FileChecksumService();
    }

    public function filechange()
    {
       $result = $this->FileChecksumService->checksumfilechange();
       $result2 = $this->FileChecksumService->checksum();

       $data['checksum'] = $result2;

        return view('filechange', $data);
    }
}