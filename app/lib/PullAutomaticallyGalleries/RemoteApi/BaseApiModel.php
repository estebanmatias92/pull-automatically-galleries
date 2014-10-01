<?php namespace PullAutomaticallyGalleries\RemoteApi;

use RestGalleries\RestGallery;

/**
 * Base model for the extern restful models.
 */
class BaseApiModel extends RestGallery implements RemoteApiModelInterface
{
    /**
     * Resolves cache system and path.
     *
     * @return void
     */
    public function __construct()
    {
        parent::setCache('file');
    }

    /**
     * Returns all galleries (and its photos) from an api service.
     *
     * @return array|null
     */
    public function all()
    {
        return $this->newGallery()->all();
    }

}
