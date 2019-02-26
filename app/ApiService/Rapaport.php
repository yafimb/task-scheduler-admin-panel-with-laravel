<?php
namespace  App\Service;


use App\Http\Controllers\UploadController;
use App\Models\Price;
use GuzzleHttp\Client as GuzzelClient;
use Illuminate\Support\Facades\Storage;


/**
 * Class Rapaport
 * @package App\Service
 */
class Rapaport
{
    private static $priceListDate = null;
    /**
     * @var string
     */
    private static $s3Path = "price-lists/rapaport/prices %date%.csv";
    /**
     * @var
     */
    private static $rapaportClient = null;

    /**
     * @return Array
     */
    public static function getPrices() : Array
    {
        $actionPerformed = "N/A";

        self::initializeGuzzelClient();

        list($httpStatus, $authTicket) = self::auth();

        if($httpStatus==200)
        {
            $pearDiamondsPrices  = self::getPearDiamondsPrices($authTicket);
            $roundDiamondsPrices = self::getRoundDiamondsPrices($authTicket);

            /**
             * Manage files naming
             */
            self::extractPriceListDate([$pearDiamondsPrices]);
            self::definePriceListS3Path([$pearDiamondsPrices]);

            if(!self::isPriceListIsNew())
            {
                $priceList = trim($roundDiamondsPrices)."\r\n".trim($pearDiamondsPrices);
                self::managePriceList($priceList);

                (new UploadController())->uploadPriceList(self::$s3Path);

                $actionPerformed = ['Table updated', 'Price list file saved'];
            }
        }

        return [$httpStatus,$actionPerformed];
    }

    /**
     *
     */
    private static function definePriceListS3Path()
    {
        self::$s3Path = str_replace('%date%', self::$priceListDate, self::$s3Path);
    }

    /**
     * @return bool
     */
    private static function isPriceListIsNew()
    {
        return Storage::disk('s3')->exists(self::$s3Path);
    }

    private static function extractPriceListDate($options)
    {
        list($pricesList) = $options;

        $dataRows       = explode("\r\n", $pricesList);
        $priceListDate  = null;

        foreach ($dataRows as $row)
        {
            if (!empty($row))
            {

                $row                    = explode(',', $row);
                $priceListDate          = array_map('trim', explode('/', $row['6']));
                self::$priceListDate    = "{$priceListDate[2]}-{$priceListDate[0]}-{$priceListDate[1]}";
            }
        }

        return self::$priceListDate;
    }
    /**
     * @param $options
     */
    private static function managePriceList($options)
    {
        list($pricesList) = $options;

        $dataRows       = explode("\r\n", $pricesList);
        $priceListDate  = null;

        foreach ($dataRows as $row)
        {

            if(!empty($row))
            {
                $row = explode(',', $row);

                if(is_null($priceListDate))
                {
                    $priceListDate  = array_map('trim',explode('/',$row['6']));
                    $priceListDate  = "{$priceListDate[2]}-{$priceListDate[0]}-{$priceListDate[1]}";
                }

                $key = [
                    'shape'     => $row[0],
                    'color'     => $row[2],
                    'clarity'   => $row[1],
                    'low_size'  => $row[3],
                    'high_size' => $row[4],

                ];

                $data = [
                    'date'      => $priceListDate,
                    'color'     => $row[2],
                    'price'     => $row[5],
                    'clarity'   => $row[1],
                ];

                Price::updateOrCreate($key, $data);
            }
        }
    }

    /**
     * @param array $options
     */
    private static function savePriceList(Array $options)
    {
        list($data, $stoneType) = $options;

        $priceListDate  = str_replace('/','-',explode(" ",explode("\r\n", $data)[0])[1]);

        if(!\Storage::disk('price_lists')->exists($fileName = "rapaport_{$stoneType}_{$priceListDate}.txt"))
        {
            Storage::disk('price_lists')->put($fileName,$data);
        }
    }
    /**
     * @param $authTicket
     * @return mixed
     */
    private static function getRoundDiamondsPrices($authTicket)
    {
        $callData = [
            env('RAPAPORT_PRICE_ROUND_DIAMONDS_URL'),
            'GET',
            [
                'form_params' => [
                    'ticket'  => $authTicket,
                ],
            ],
        ];

       return self::call($callData)->getBody()->getContents();
    }

    /**
     * @param $authTicket
     * @return mixed
     */
    private static function getPearDiamondsPrices($authTicket)
    {
        $callData = [
            env('RAPAPORT_PRICE_PEAR_DIAMONDS_URL'),
            'GET',
            [
                'form_params' => [
                    'ticket'  => $authTicket,
                ],
            ],
        ];

        return self::call($callData)->getBody()->getContents();
    }

    /**
     *
     */
    private static function initializeGuzzelClient()
    {
        if(!isset(self::$rapaportClient))
        {
            self::$rapaportClient = new GuzzelClient();
        }
    }
    /**
     * @param $options
     * @return Array
     */
    private static function parseResponse($options): Array
    {
        list($response) = $options;

        return [$response->getStatusCode(), $response->getBody()->getContents()];
    }

    /**
     * @return Array
     */
    private static function auth()
    {
        $callData = [
            env('RAPAPORT_API_AUTH_URL'),
            'POST',
            [
                'form_params' => [
                    'username'  => env('RAPAPORT_API_USER'),
                    'password'  => env('RAPAPORT_API_PASSWORD'),
                ],
                'verify' => false,
            ],
        ];

        $response = self::call($callData);

        return self::parseResponse([$response]);
    }

    /**
     * @param array $options
     * @return mixed
     */
    private static function call(Array $options)
    {
        list($url, $method, $payload) = $options;

        $response = self::$rapaportClient->request($method, $url,$payload);

        return $response;
    }
}