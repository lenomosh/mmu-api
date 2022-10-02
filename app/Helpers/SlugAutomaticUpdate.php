<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait SlugAutomaticUpdate
{
    protected static string $sluggableColumn = 'title';

    protected static string $slugColumn = 'slug';

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            static::generateSlug($model);
        });
        static::updating(function (Model $model) {
            static::generateSlug($model);
        });
    }

    protected static function generateSlug(Model $model)
    {

        $slug = Str::slug($model->getAttribute(static::$sluggableColumn));
        $statement = "slug ~ '^{$slug}(-[0-9]+)?$'";
        $unique_id = $model->getAttribute($model->primaryKey);
        if ($unique_id) {
            $statement = "slug ~ '^{$slug}(-[0-9]+)?$' AND id != {$unique_id}";
        }
        $count = static::query()->whereRaw($statement)->count() ? "_" . Str::random(6) : "";
        $model->setAttribute(static::$slugColumn, $slug . $count);
    }

}
