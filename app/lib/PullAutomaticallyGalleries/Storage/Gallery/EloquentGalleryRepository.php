<?php namespace PullAutomaticallyGalleries\Storage\Gallery;

use Gallery;

class EloquentGalleryRepository implements GalleryRepositoryInterface
{
    public function getByUsers($userIds, $columns = array('*'))
    {
        return Gallery::getByUsers($userIds, $columns);
    }

    public function all($columns = array('*'))
    {
        return Gallery::all($columns);
    }

    public function create($input);
    {
        return Gallery::create($input);
    }

    public function delete($id)
    {
        $model = Gallery::find($id);

        return $model->delete();
    }

}
