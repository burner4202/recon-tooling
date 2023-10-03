<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class PackageManagerPayments extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'package_manager_payments';

    protected $fillable = ['month_year', 'paid'];
}
