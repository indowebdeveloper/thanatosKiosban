<?php
namespace Thanatos;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoAudit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
    use \OwenIt\Auditing\Audit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];
}