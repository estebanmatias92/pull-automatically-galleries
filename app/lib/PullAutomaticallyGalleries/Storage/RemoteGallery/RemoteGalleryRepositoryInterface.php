<?php namespace PullAutomaticallyGalleries\Storage\RemoteGallery;

interface RemoteGalleryRepositoryInterface
{
    public function getByUsers($users);
}
