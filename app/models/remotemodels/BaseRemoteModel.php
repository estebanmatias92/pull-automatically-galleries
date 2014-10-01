<?php

use PullAutomaticallyGalleries\Support\Collection;

class BaseRemoteModel
{
    /**
     * Stores the current host for gallery service.
     *
     * @var string
     */
    protected $host;

    /**
     * Creates mock object to this model.
     *
     * @return \Mockery
     */
    public static function shouldReceive()
    {
        $class = get_called_class();
        $repo  = 'PullAutomaticallyGalleries\\Storage\\' . $class . '\\' . $class . 'RepositoryInterface';
        $mock  = Mockery::mock($repo);

        App::instance($repo, $mock);

        return call_user_func_array([$mock, 'shouldReceive'], func_get_args());
    }

    /**
     * Sets host attribute.
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Creates new RestGallery model instance.
     *
     * @return \RestGalleries\Interfaces\GalleryAdapter
     */
    public function newModel()
    {
        $class = 'PullAutomaticallyGalleries\\RemoteApi\\' . $this->host;

        return new $class;
    }

    /**
     * Returns custom collection.
     *
     * @param  array  $models
     * @return \PullAutomaticallyGalleries\Database\Eloquent\Collection
     */
    public function newCollection(array $models = array())
    {
        return new Collection($models);
    }

}
