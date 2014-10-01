<?php

class User extends BaseModel
{
    protected $table = 'pal_users';

    protected $fillable = ['id', 'realname', 'username', 'url', 'credentials', 'host_id'];

    public static $rules = [
        'save' => [
            'id'          => 'required',
            'host_id'     => 'required',
            'realname'    => 'required',
            'username'    => 'required',
            'url'         => 'required',
            'credentials' => 'required',
        ],
        'create' => [
            'id'          => 'unique:pal_users',
            'credentials' => 'unique:pal_users',
        ],
        'update' => [],
    ];

    public $incrementing = false;

    /**
     * Modify credentials attribute before persist it into db.
     *
     * @param  array $value
     * @return void
     */
    public function setCredentialsAttribute($value)
    {
        $this->attributes['credentials'] = http_build_query($value, '', ';');
    }

    /**
     * Modify credentials attribute before access it from outside the model.
     *
     * @param  string $value
     * @return array
     */
    public function getCredentialsAttribute($value)
    {
        return explode_assoc($value, '=', ';');
    }

    /**
     * Model relationship
     *
     * @return \PostMeta
     */
    public function meta()
    {
        return $this->hasMany('PostMeta', 'meta_value');
    }

    /**
     * Model relationship
     *
     * @return \Host
     */
    public function host()
    {
        return $this->belongsTo('Host');
    }

    /**
     * Model events.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleted(function($user)
        {
            $user->meta()->delete();
        });
    }

}
