<?php namespace PullAutomaticallyGalleries\Storage;

use Illuminate\Support\ServiceProvider;

/**
 * Allow bind the interfaces with their repositories when the interfaces are called.
 */
class StorageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'PullAutomaticallyGalleries\Storage\Gallery\GalleryRepositoryInterface',
            'PullAutomaticallyGalleries\Storage\Gallery\EloquentGalleryRepository'
        );

        $this->app->bind(
            'PullAutomaticallyGalleries\Storage\User\UserRepositoryInterface',
            'PullAutomaticallyGalleries\Storage\User\EloquentUserRepository'
        );

        $this->app->bind(
            'PullAutomaticallyGalleries\Storage\RemoteGallery\RemoteGalleryRepositoryInterface',
            'PullAutomaticallyGalleries\Storage\RemoteGallery\RestgalleryRemoteGalleryRepository'
        );

        $this->app->bind(
            'PullAutomaticallyGalleries\Storage\RemoteUser\RemoteUserRepositoryInterface',
            'PullAutomaticallyGalleries\Storage\RemoteUser\RestgalleryRemoteUserRepository'
        );

    }

}
