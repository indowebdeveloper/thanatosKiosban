<?php

namespace Thanatos\Modules\PointOfSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Website extends Model
{
    protected $table = 'website';
    public $timestamps = true;
    use Eloquence;
    use SoftDeletes;
    protected $searchableColumns = ['name', 'email'];
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    

}