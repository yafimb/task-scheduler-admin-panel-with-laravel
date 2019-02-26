<?php

namespace App\Exports;

use App\Models\Price;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Class PricesExport
 * @package App\Exports
 */
class PricesExport implements FromCollection, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return ['price','color','shape','clarity','low_size','high_size',];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Price::all(['price','color','shape','clarity','low_size','high_size',]);
    }
}
