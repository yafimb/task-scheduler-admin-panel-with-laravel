<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Excel as type;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PricesExport;

/**
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends Controller
{
    /**
     *
     */
    public function uploadPriceList($path)
    {
        Excel::store(new PricesExport(), $path,'s3', type::CSV);
    }
}
