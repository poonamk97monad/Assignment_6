<?php

namespace App\Model;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Collection extends Model
{/*
    use Searchable;

    protected $mapping = [
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
            ]
        ]
    ];
    */
    protected $fillable = [
        'title', 'slug','description'
    ];

    public function resource() {

        return $this->belongsToMany(Resource::class);
    }

    public function resources() {

        return $this->belongsToMany(Resource::class, 'collection_resources', 'collection_id', 'resource_id');
    }

    public function isFavortted() {
        return Redis::SISMEMBER('favorite:collection', $this->getKey());
    }
}
