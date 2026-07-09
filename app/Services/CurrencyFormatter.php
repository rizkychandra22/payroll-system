<?php

namespace App\Services;

class CurrencyFormatter
{
    public static function rupiah(float | int | string | null $amount): string
    {
        return 'Rp' . number_format((float) $amount, 0, ',', '.');
    }
}
