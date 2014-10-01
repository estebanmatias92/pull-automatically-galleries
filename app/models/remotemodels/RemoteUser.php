<?php

use Illuminate\Support\Str;

class RemoteUser extends BaseRemoteModel
{
    /**
     * Returns account data, even token credentials or throw an exception.
     *
     * @param  string $callback
     * @return \Illuminate\Suppport\Fluent
     */
    public static function connect($host, $callback = '')
    {
        $instance = new static;

        $instance->setHost($host);

        $model = $instance->newModel();
        $user  = $model::connect($callback);

        $instance->modifyAttributes($user);

        return $user;

    }

    /**
     * Returns account data or throw an exception.
     *
     * @param  array  $tokenCredentials
     * @return \Illuminate\Suppport\Fluent
     */
    public static function verifyCredentials($host, array $tokenCredentials)
    {
        $instance = new static;

        $instance->setHost($host);

        $model = $instance->newModel();
        $user  = $model::verifyCredentials($tokenCredentials);

        $instance->modifyAttributes($user);

        return $user;

    }

    /**
     * Custom function to modify the returned data from a user.
     *
     * @param  \Illuminate\Suppport\Fluent $object
     * @return void
     */
    private function modifyAttributes(&$object)
    {
        $object->host = $this->host;
        $object->id   = Str::slug($object->host . ' ' .  $object->id, '_');

    }

}
