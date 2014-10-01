<?php

class PostMeta extends BaseModel
{
    protected $table = 'wp_postmeta';

    protected $primaryKey = 'meta_id';

    protected $fillable = ['post_id', 'meta_key', 'meta_value'];

    public $timestamps = false;

    /**
     * Model relationship.
     *
     * @return \Gallery
     */
    public function gallery()
    {
        return $this->belongsTo('Gallery', 'post_id');
    }

    /**
     * Model relationship.
     *
     * @return \User
     */
    public function user()
    {
        return $this->belongsTo('User', 'meta_value');
    }

}
