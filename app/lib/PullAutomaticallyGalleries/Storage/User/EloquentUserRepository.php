<?php namespace PullAutomaticallyGalleries\Storage\User;

use User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function all($columns = array('*'))
    {
        return User::all($columns);
    }

    public function create($input)
    {
        return User::create($input);
    }

    public function delete($id)
    {
        $model = User::find($id);

        return $model->delete();
    }

}
