<?php

namespace Thanatos\Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use OwenIt\Auditing\Contracts\Auditable;

class Customers extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'customer';
    public $timestamps = true;
    use Eloquence;
    use SoftDeletes;
    protected $searchableColumns = ['name', 'email'];
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    
    

}