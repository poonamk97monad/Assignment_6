<?php

namespace App\Http\Controllers;

use App\Model\Resource;
use App\Model\Collection;
use App\Helpers\CreateSlug;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StoreAddCollectionResource;
use App\Http\Requests\StoreAddResourceToCollection;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $arrObjCollections = Collection::latest()->paginate(5);
        return view('collection.index', compact('arrObjCollections'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $strRequest
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddResourceToCollection $objResourcetoCollectionRequest) {

        $arrFormData = array(
            'title'              =>   $objResourcetoCollectionRequest->title,
            'slug'               =>   (new CreateSlug())->get($objResourcetoCollectionRequest->title),
            'description'        =>   $objResourcetoCollectionRequest->description
        );

        Collection::create($arrFormData);
        return response()->json(['success'=>'add data ']);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($intId) {

        $objCollection      = Collection::findOrFail($intId);
        $arrObjResources    = Resource::all();

        return view('collection.view', array('objCollection' => $objCollection, 'arrObjResources' => $arrObjResources));

    }
    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $objRequest
     * @param  int  $intCollectionId
     * @return \Illuminate\Http\Response
     */
    public function postAddResourceToCollection(StoreAddCollectionResource $objRequest,$intCollectionId) {
        $objCollection = Collection::find($intCollectionId);
        $objCollection->resources()->attach($objRequest->get('resource_id'));
        return response()->json(['success'=>'Add resource to collection']);
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $objRequest
     * @param  int $intCollectionId
     * @return \Illuminate\Http\Response
     */
    public function postRemoveResourceToCollection(StoreAddCollectionResource $objRequest,$intCollectionId) {

        $objCollection = Collection::findOrFail($intCollectionId);
        $objCollection->resources()->detach($objRequest->get('resource_id'));
        return response()->json(['success'=>'Remove resource to collection']);
    }

    /**
     * add in favorites
     * @param $intUserId
     */
    public function postSetFavorite($intUserId) {
        $boolIsFavoritted = Redis::SISMEMBER('favorite:collection', $intUserId);
        if($boolIsFavoritted == 1) {
            $objRedis = Redis::srem('favorite:collection', $intUserId);
        }
        else {
            $objRedis = Redis::sadd('favorite:collection', $intUserId);
        }
        return redirect()->back();
    }

}
