<?php namespace PullAutomaticallyGalleries\Storage\RemoteGallery;

use RemoteGallery;

class RestgalleryRemoteGalleryRepository implements RemoteGalleryRepositoryInterface
{
    public function getByUsers($users)
    {
        return RemoteGallery::getByUsers($users);
    }

}
