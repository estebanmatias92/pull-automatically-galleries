<?php

use PullAutomaticallyGalleries\Database\Eloquent\Collection;

class BaseModel extends Illuminate\Database\Eloquent\Model
{
    protected function shouldReceive()
    {
        $class = get_called_class();
        $repo  = 'PullAutomaticallyGalleries\\Storage\\' . $class . '\\' . $class . 'RepositoryInterface';
        $mock  = Mockery::mock($repo);

        App::instance($repo, $mock);

        return call_user_func_array([$mock, 'shouldReceive'], func_get_args());
    }

    /**
     * Returns an array of models as Collection object.
     *
     * @param  array  $model
     * @return \PullAutomaticallyGalleries\Support\Collection
     */
    public function newCollection(array $models = array())
    {
        return new Collection($models);
    }

}
