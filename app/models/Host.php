<?php

class Host extends BaseModel
{
    protected $table = 'pal_hosts';

    /**
     * Model relationship
     *
     * @return \User
     */
    public function users()
    {
        return $this->hasMany('User');
    }

}
