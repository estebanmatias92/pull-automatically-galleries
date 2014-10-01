<?php namespace PullAutomaticallyGalleries\Storage\RemoteUser;

interface RemoteUserRepositoryInterface
{
    public function connect($host, $callback = '');
    public function verifyCredentials($host, $tokenCredentials);

}
