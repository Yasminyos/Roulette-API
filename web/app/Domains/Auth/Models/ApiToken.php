<?php

namespace App\Domains\Auth\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Domains\Auth\Models\ApiToken
 *
 * @property int $user_id
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ApiToken newModelQuery()
 * @method static Builder|ApiToken newQuery()
 * @method static Builder|ApiToken query()
 * @method static Builder|ApiToken whereCreatedAt($value)
 * @method static Builder|ApiToken whereToken($value)
 * @method static Builder|ApiToken whereUpdatedAt($value)
 * @method static Builder|ApiToken whereUserId($value)
 * @mixin Eloquent
 */
class ApiToken extends Model
{
    protected $table = 'api_tokens';
    
    protected $primaryKey = 'user_id';
}
