<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
//use OwenIt\Auditing\Contracts\Auditable;

class Enquiries extends Eloquent
{
    //use \OwenIt\Auditing\Auditable;
    protected $collection = 'enquiries';
    protected $connection = 'mongodb';
    public $timestamps = true;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = ['_id'];
    protected $primaryKey = "id";
    
    


}