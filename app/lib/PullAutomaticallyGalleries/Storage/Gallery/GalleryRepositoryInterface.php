<?php namespace PullAutomaticallyGalleries\Storage\Gallery;

interface GalleryRepositoryInterface
{
    public function getByUsers($userIds, $columns = array('*'));
    public function all($columns = array('*'));
    public function create($input);
    public function delete($id);

}
