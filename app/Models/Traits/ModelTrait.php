<?php

namespace App\Models\Traits;

use Illuminate\Support\Carbon;

trait ModelTrait
{
    /**
     * Return formatted created date
     *
     * @param [type] $value
     * @return void
     */
    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }

    /**
     * Return formatted Updated date
     *
     * @param [type] $value
     * @return void
     */
    public function getUpdatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }
}
