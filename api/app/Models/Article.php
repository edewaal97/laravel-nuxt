<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property string|null $banner_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static ArticleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article whereBannerImage($value)
 * @method static Builder<static>|Article whereBody($value)
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereSlug($value)
 * @method static Builder<static>|Article whereTitle($value)
 * @method static Builder<static>|Article whereUpdatedAt($value)
 *
 * @mixin Builder
 */
final class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id', 'slug'];
}
