<?php

use Illuminate\Support\Str;

class Gallery extends BaseModel
{
    protected $table = 'wp_posts';

    protected $primaryKey = 'ID';

    protected $attributes = [
        'post_type'   => 'gallery',
        'post_status' => 'publish',
        'post_author' => 1,
    ];

    protected $fillable = [
        'post_author',
        'post_content',
        'post_title',
        'post_date',
        'post_status',
        'post_name',
        'post_type'
    ];

    protected $metaKeys = ['pal_gallery_id', 'pal_user_id'];

    protected static $metaAttributes = [];

    public $timestamps = false;

    /**
     * Returns all gallery models from a user list.
     *
     * @param  array  $userIds
     * @param  array  $columns
     * @return \PullAutomaticallyGalleries\Database\Eloquent\Collection
     */
    public static function getByUsers(array $userIds, $columns = array('*'))
    {
        $instance = new static;

        $select = array_map(function($column) use($instance)
        {
            return $instance->table . '.' . $column;
        }, $columns);

        return $instance->newQuery()
            ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->where('wp_posts.post_type', '=', $instance->attributes['post_type'])
            ->where('wp_postmeta.meta_key', '=', 'pal_user_id')
            ->whereIn('wp_postmeta.meta_value', $userIds)
            ->select($select)
            ->get();
    }

    /**
     * Create all galleries from the given models.
     *
     * @param  \PullAutomaticallyGalleries\Support\Collection|array $items
     * @return \PullAutomaticallyGalleries\Database\Eloquent\Collection
     */
    public function createList($items)
    {
        $models = [];

        foreach ($items as $item) {
            $models[] = static::create($item->toArray());
        }

        return $this->newCollection($models);
    }

    /**
     * Remove all gallery models of the given users from the databse.
     *
     * @param  array  $userIds
     * @return integer
     */
    public static function destroyByUsers(array $userIds)
    {
        $instance = new static;

        $models = $instance->newQuery()
            ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->where('wp_posts.post_type', '=', $instance->attributes['post_type'])
            ->where('wp_postmeta.meta_key', '=', 'pal_user_id')
            ->whereIn('wp_postmeta.meta_value', $userIds)
            ->select(['wp_posts.ID'])
            ->get();

        return static::destroy($models->lists('ID'));
    }

    /**
     * Meta data relationship.
     *
     * @return \PostMeta
     */
    public function meta()
    {
        return $this->hasMany('PostMeta', 'post_id');
    }

    /**
     * Gallery model events.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function($gallery)
        {
            $gallery->filterMetaAttributes($gallery->attributes);
            $gallery->saveDefaultAttributes();
        });

        static::saved(function($gallery)
        {
            if (empty($gallery::$metaAttributes)) {
                return;
            }

            $gallery->meta()->saveMany(
                $gallery->createMetaList($gallery::$metaAttributes)
            );
        });

        static::deleted(function($gallery)
        {
            $gallery->meta()->delete();
        });

    }
    /**
     * Modify default behavior of getAttribute to include Meta value into this model attributes.
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->metaKeys)) {
            return $this->getMetaKeyValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Returns Meta value from current gallery model.
     *
     * @param  string $key
     * @return mixed
     */
    public function getMetaKeyValue($key)
    {
        return $this->meta()
            ->where('meta_key', '=', $key)
            ->select(['meta_value'])
            ->first()
            ->meta_value;
    }

    /**
     * Separates attributes for PostMeta models from Gallery attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    protected function filterMetaAttributes(array &$attributes)
    {
        static::$metaAttributes = array_separate($attributes, $this->metaKeys);
    }

    /**
     * Sets several attributes with value by default before save them.
     *
     * @return void
     */
    protected function saveDefaultAttributes()
    {
        $this->attributes['post_name']         = Str::slug($this->attributes['post_title']);
        $this->attributes['post_date_gmt']     = $this->attributes['post_date'];
        $this->attributes['post_modified']     = $this->attributes['post_date'];
        $this->attributes['post_modified_gmt'] = $this->attributes['post_date'];

    }

    /**
     * Custom function to create PostMeta models and return them.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function createMetaList(array $attributes)
    {
        $models = [];

        foreach ($attributes as $key => $value) {
            $models[] = new PostMeta([
                'meta_key'   => $key,
                'meta_value' => $value
            ]);
        }

        return $models;
    }

}
