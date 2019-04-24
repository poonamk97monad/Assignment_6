<?php

namespace App\Http\Controllers;

use App\Model\Resource;
use App\Model\Collection;
use Illuminate\Http\Request;
use App\Helpers\CreateSlug;
use Illuminate\Support\Facades\Redis;
/*use App\Http\Requests\StoreAddResourceToCollection;*/

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $arrObjCollections = Collection::latest()->paginate(5);
        return view('collection.index', compact('arrObjCollections'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('create_collections');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $strRequest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $collection = new Collection();
        $collection->title       = $request->input('title');
        $collection->slug        = $request->input('slug');
        $collection->description = $request->input('description');
        $collection->save();
     return redirect('collections')->with('success', 'Data Added successfully.');
//        $input = $request->all();

       return response()->json(['success'=>'Got Simple Ajax Request']);
    }

    /**s
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($intId) {

        $objCollection      = Collection::findOrFail($intId);
        $arrObjResources    = Resource::all();

        return view('collection.view', array('objCollection' => $objCollection, 'arrObjResources' => $arrObjResources));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($intId) {

        $objData = Resource::findOrFail($intId);

        $arrFormData = array(
            'title'              =>   $objData->title,
            'slug'               =>   $objData->slug,
            'description'        =>   $objData->description
        );

        Collection::create($arrFormData);

        return redirect('collection_view',compact('$arrFormData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $intCollectionId,$intResourceId
     * @return \Illuminate\Http\Response
     */
    public function postAddToResource(Request $obRequest,$intCollectionId) {
//        dd($intCollectionId);
//        dd($obRequest->get('resource_id'));
        $objCollection = Collection::find($intCollectionId);
        $objCollection->resources()->attach($obRequest->get('resource_id'));
//        return redirect('collections/'.$intCollectionId);
//     return redirect('collections/'.$intCollectionId);
//        return view('collection.view');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param  int $intCollectionId,$intResourceId
     * @return \Illuminate\Http\Response
     */
    public function postRemoveToResource(Request $obRequest,$intCollectionId) {

        $objCollection = Collection::findOrFail($intCollectionId);
        $objCollection->resources()->detach($obRequest->get('resource_id'));
       return redirect('collections/'.$intCollectionId)->with('success', 'Data is successfully deleted');
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
