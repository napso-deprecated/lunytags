<?php


namespace Napso\Lunytags;


/*
* Taggable classes should implement this trait
*/


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Napso\Lunytags\Models\Tag;

trait TaggableTrait
{

    public function scopePagesWithAnyTag($query, array $tags)
    {
        return $query->whereHas('tags', function ($query) use ($tags) {
            return $query->whereIn('slug', $tags);
        });
    }

    public function scopePagesWithAllTags($query, array $tags)
    {
        foreach ($tags as $tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                return $query->whereIn('slug', [$tag]);
            });
        }

        return $query;
    }

    public function scopeWithAnyTag2($query, array $tags)
    {

    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function tag($tags)
    {
        $this->addTags($this->getWorkableTags($tags));
    }

    public function untag($tags = null)
    {
        if ($tags == null) {
            $this->removeAllTags();
            return;
        }

        $this->removeTags($this->getWorkableTags($tags));

    }

    private function removeTags(Collection $tags)
    {
        $this->tags()->detach($tags);
    }

    private function removeAllTags()
    {
        $this->removeTags($this->tags);
    }

    private function addTags(Collection $tags)
    {
        $sync = $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());
    }

    private function getWorkableTags($tags)
    {
        // we always want to return collection

        // we got array of tags
        if (is_array($tags)) {
            return $this->getTagModels($tags);
        }
        // we got only one tag as a model
        if ($tags instanceof Model) {
            return $this->getTagModels([$tags->slug]);
        }

        // we got a string
        if (is_string($tags)) {
            return $this->getTagModels($tags);
        }

        // or we got a collection, just return it.
        return $tags;
    }

    private function getTagModels($tags)
    {
        if (is_string($tags)) {
            return Tag::where('slug', $this->convertTagNamesToSlugs($tags))->get();
        }
        return Tag::whereIn('slug', $this->convertTagNamesToSlugs($tags))->get();
    }

    /**
     * if we get the name instead of the slug, we convert it to the slug first.
     * @param array $tags
     * @return array
     */
    private function convertTagNamesToSlugs(array $tags)
    {
        return array_map(function ($tag) {
            return str_slug($tag);
        }, $tags);
    }

}
