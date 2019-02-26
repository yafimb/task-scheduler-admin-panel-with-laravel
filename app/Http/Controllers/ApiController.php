<?php

namespace App\Http\Controllers;

use App\Service\Rapaport;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getRapnetPriceList()
    {

        list($httpResponse, $actionPerformed) = Rapaport::getPrices();

        switch ($httpResponse)
        {
            case '200':
                $res = ResponseBuilder::success($actionPerformed);
                break;
                default;
                $res = ResponseBuilder::error($httpResponse);
                break;
        }

        return $res;
    }

    public function getPrise(...$options)
    {
        var_dump($options);
    }

}
