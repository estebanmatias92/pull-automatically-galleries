<?php namespace PullAutomaticallyGalleries\Support;

class Collection extends \Illuminate\Support\Collection
{
    /**
     * Filter and returns those models that has not been found into another model.
     *
     * @param  PullAutomaticallyGalleries\Support\Collection|PullAutomaticallyGalleries\Database\Eloquent\Collection $items
     * @param  string $key
     * @return PullAutomaticallyGalleries\Database\Eloquent\Collection;
     */
    public function diffByKey($items, $key = 'id')
    {
        $diff      = new static($this->items);
        $keyValues = $items->lists($key);

        return $diff->filter(function($model) use($key, $keyValues)
        {
            return (! in_array($model[$key], $keyValues));
        });
    }

}
