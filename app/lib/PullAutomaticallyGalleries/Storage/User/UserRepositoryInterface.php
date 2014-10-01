<?php namespace PullAutomaticallyGalleries\Storage\User;

interface UserRepositoryInterface
{
    public function all($columns = array('*'));
    public function create($input);
    public function delete($id);

}
