<?php

namespace App\Http\Middleware;

//use App\Services\FileChecksumService;

use Closure;

class EnFileCheckSum
{
    /**
     * Handle an incoming request.
     * @auther : Namrata Thakur
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @Comment :  Middleware for check file sum and forward requeust or show invalid checksum page
     */
    public function handle($request, Closure $next)
    {	
        $currentRoute = $request->route()->uri();

        if ($currentRoute === 'filechange') 
        {
            return $next($request);
        }

        //$FileChecksumService = new FileChecksumService();

        //$checksum       =  $FileChecksumService->checksum();
        //$checksum_resp  =  $FileChecksumService->checksumfilechange();

        //if ($checksum_resp==='FileDBChange' || $checksum_resp==='FileChange') 
        //{
        //    return redirect('/filechange');
        //}
        //else
        //{
        return $next($request);
        //}

    }
}
