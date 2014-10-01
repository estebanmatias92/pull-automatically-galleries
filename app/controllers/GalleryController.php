<?php

use PullAutomaticallyGalleries\Storage\Gallery\GalleryRepositoryInterface;
use PullAutomaticallyGalleries\Storage\RemoteGallery\RemoteGalleryRepositoryInterface;

class GalleryController extends BaseController
{
    /**
     * Gallery repository.
     *
     * @var \PullAutomaticallyGalleries\Storage\Gallery\GalleryRepositoryInterface
     */
	protected $gallery;

    /**
     * RemoteGallery repository.
     *
     * @var \PullAutomaticallyGalleries\Storage\RemoteGallery\RemoteGalleryRepositoryInterface
     */
    protected $remoteGallery;

    /**
     * Initializes model instance vars.
     *
     * @param  \PullAutomaticallyGalleries\Storage\Gallery\GalleryRepositoryInterface             $gallery
     * @param  \PullAutomaticallyGalleries\Storage\RemoteGallery\RemoteGalleryRepositoryInterface $remoteGallery
     * @return void
     */
	public function __construct(GalleryRepositoryInterface $gallery, RemoteGalleryRepositoryInterface $remoteGallery)
	{
        $this->gallery       = $gallery;
        $this->remoteGallery = $remoteGallery;
	}

    /**
     * Takes all local galleries, and pulls the current galleries from the web-services.
     * Then it compares both and updates the local gallery list by creating new galleries and removing non-existent galleries.
     *
     * @return Response
     */
    public function updateList()
    {
        $users   = Input::get('users');
        $userIds = array_pluck($users, 'id');

        $localGalleries  = $this->gallery->getByUsers($userIds);
        $remoteGalleries = $this->remoteGallery->getByUsers($users);

        $oldGalleries = $localGalleries->diffByKey($remoteGalleries, 'pal_gallery_id');
        $newGalleries = $remoteGalleries->diffByKey($localGalleries, 'pal_gallery_id');

        $removed = 0;
        $created = 0;

        if (! $oldGalleries->isEmpty()) {

            $ids     = $oldGalleries->lists('ID');
            $removed = $this->gallery->destroy($ids);

        }

        if (! $newGalleries->isEmpty()) {

            $models  = $this->gallery->createList($newGalleries);
            $created = $models->count();

        }

        return Redirect::route('users.index')->with('message', "Has been removed {$removed} galleries and created {$created} galleries");

    }

    /**
     * Removes all local galleries for the current user list.
     *
     * @return Response
     */
    public function destroyList()
    {
        $users   = Input::get('users');
        $userIds = array_pluck($users,'id');

        $removed = $this->gallery->destroyByUsers($userIds);

        return Redirect::route('users.index')->with('message', "Has been removed {$removed} galleries");
    }

}
