<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityRecords extends Model
{
    protected $connection = 'mysql';
    protected $table = "activity_records";
}
