<?php

namespace Thanatos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\UserResolver;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
class PureUser extends Model implements AuditableContract, UserResolver
{
    use Auditable;
    protected $table = 'users';
    /**
     * {@inheritdoc}
     */
    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }
}
