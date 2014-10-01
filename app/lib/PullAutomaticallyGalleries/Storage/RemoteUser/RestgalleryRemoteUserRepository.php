<?php namespace PullAutomaticallyGalleries\Storage\RemoteUser;

use RemoteUser;

class RestgalleryRemoteUserRepository implements RemoteUserRepositoryInterface
{
    public function connect($host, $callback = '')
    {
        return RemoteUser::connect($host, $callback);
    }

    public function verifyCredentials($host, $tokenCredentials)
    {
        return RemoteUser::verifyCredentials($host, $tokenCredentials);
    }

}
