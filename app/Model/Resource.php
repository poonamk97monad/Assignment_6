<?php

namespace App\Model;

//use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /*use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    /*protected $mapping = [
        'properties' => [
            'id' => [
                'type' => 'integer',
                'index' => 'not_analyzed'
            ],
            'title' => [
                'type' => 'string',
                'analyzer' => 'english'
            ],
            'slug' => [
                'type' => 'string',
                'index' => 'not_analyzed'
            ],
            'description' => [
                'type' => 'string',
                'analyzer' => 'english'
            ],
            'file_upload' => [
                'type' => 'integer',
                'index' => 'not_analyzed'
            ]
        ]
    ];*/

   /* public function toSearchableArray() {
        $array = $this->toArray();
        return $array;
    }*/

    protected $fillable = [
        'title','slug', 'description', 'file_upload'
    ];

    public function collection() {
        return $this->belongsToMany(Collection::class);
    }

    public function collections() {
        return $this->belongsToMany(Collection::class, 'collection_resources', 'resource_id', 'collection_id');
    }
    public function isFavortted() {
        return Redis::SISMEMBER('favorite:resource', $this->getKey());
    }
}
