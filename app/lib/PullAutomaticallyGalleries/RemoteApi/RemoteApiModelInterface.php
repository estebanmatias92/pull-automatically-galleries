<?php namespace PullAutomaticallyGalleries\RemoteApi;

/**
 * RemoteModelInterface description.
 */
interface RemoteApiModelInterface
{
    public function all();
    public function find($id);
    public function setAuth(array $tokenCredentials);
    public function setCache($system, array $path = array());
    public static function connect($callback = '');
    public static function verifyCredentials(array $tokenCredentials);

}
