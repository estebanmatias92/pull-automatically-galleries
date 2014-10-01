<?php

use Illuminate\Support\Str;

class RemoteGallery extends BaseRemoteModel
{
    /**
     * Returns all gallery models from a user list.
     *
     * @param  \PullAutomaticallyGalleries\Database\Eloquent\Collection|array $users
     * @return \PullAutomaticallyGalleries\Support\Collection
     */
    public static function getByUsers($users)
    {
        $instance = new static;

        $models = [];

        foreach ($users as $user) {

            $instance->setHost($user['host']);

            $newModels = $instance->newModel()
                ->setAuth($user['credentials'])
                ->all();

            $models = array_merge($models, $newModels);

        }

        if (! empty($models)) {
            array_walk($models, [$instance, 'modifyAttributes']);
        }

        return $instance->newCollection($models);

    }

    /**
     * Returns all the gallery models from a specific user and api service.
     *
     * @param  string $host
     * @param  array  $credentials
     * @return \PullAutomaticallyGalleries\Support\Collection
     */
    public static function all($host, $credentials)
    {
        $instance = new static;

        $instance->setHost($host);

        $models = (array) $instance->newModel()
                ->setAuth($credentials)
                ->all();

        if (! empty($models)) {
            array_walk($models, [$instance, 'modifyAttributes']);
        }

        return $instance->newCollection($models);

    }

    /**
     * Returns a gallery model from a specific user and api service.
     *
     * @param  string $id
     * @param  string $host
     * @param  array  $credentials
     * @return \Illuminate\Support\Fluent|null
     */
    public static function find($host, $credentials, $id)
    {
        $instance = new static($host);

        $instance->setHost($host);

        $model = $instance->newModel()
            ->setAuth($credentials)
            ->find($id);

        if (! is_null($model)) {
            $instance->modifyAttributes($model);
        }

        return $model;

    }

    /**
     * Custom function to modify the returned data from a gallery.
     *
     * @param  \Illuminate\Suppport\Fluent $object
     * @return void
     */
    private function modifyAttributes(&$object)
    {
        $object->pal_gallery_id = $object->id;
        $object->pal_user_id    = Str::slug(($this->host . ' ' .  $object->user_id), '_');
        $object->post_content   = $object->description;
        $object->post_title     = $object->title;
        $object->post_date      = date('Y-m-d H:i:s', $object->created);

        unset($object->id);
        unset($object->user_id);
        unset($object->description);
        unset($object->created);
        unset($object->title);

        unset($object->photos);

    }

}
