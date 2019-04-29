<?php

namespace App\Http\Controllers;

use App\Model\Resource;
use App\Model\Collection;
use App\Helpers\CreateSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\StoreAddCollectionResource;
use App\Http\Requests\StoreAddCollectionToResource;

class ResourcesController extends Controller
{
    const INT_LIMIT = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $arrObjResource = Resource::latest()->paginate(self::INT_LIMIT);
        return view('resource.index', compact('arrObjResource'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $objResourcetoCollectionRequest
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddCollectionToResource $objResourceToCollectionRequest) {

        $objResource        = new resource();
        $file_upload      = $objResourceToCollectionRequest->file('file_upload');
        $strNewName       = rand() . '.' . $file_upload->getClientOriginalExtension();
        $file_upload->move(public_path('file_upload'), $strNewName);
        $arrFormData      = array(
            'title'       => $objResourceToCollectionRequest->title,
            'slug'        => (new CreateSlug())->get($objResourceToCollectionRequest->title),
            'description' => $objResourceToCollectionRequest->description,
            'file_upload' => $strNewName
        );



//        Resource::create($arrFormData);
        $objResource->save();

        $intLastKey = $objResource->getKey();

        $objResourceLast = Resource::where('id',$intLastKey)->get();

        return response()->json(['success' => 'add data ','data' => $objResourceLast]);
    }

    /**
     * Display the specified resource.
     * @param  int  $intId
     * @return \Illuminate\Http\Response
     */
    public function show($intId) {
        $arrObjResources      = Resource::findOrFail($intId);
        $arrObjCollection     = Collection::all();
        return view('resource.view', array('arrObjCollection' => $arrObjCollection, 'arrObjResources' => $arrObjResources));

    }

    /**
     * Update the specified resource in storage.
     * @param  $obRequest
     * @param  $intResourceId
     * @return \Illuminate\Http\Response
     */
    public function postAddCollectionToResource(StoreAddCollectionResource $objRequest, $intResourceId) {
        $objResource = Resource::find($intResourceId);
        $objResource->collections()->attach($objRequest->get('collection_id'));
        return response()->json(['success'=>'Add collection to resource ']);

  }

    /**
     * Remove the specified resource from storage.
     * @param  $intResourceId
     * @return \Illuminate\Http\Response
     */
    public function postRemoveCollectionToResource(StoreAddCollectionResource $objRequest, $intResourceId) {
        $objResource = Resource::find($intResourceId);
        $objResource->collections()->detach($objRequest->get('collection_id'));
        return response()->json(['success'=>'Data is successfully deleted']);

    }

    /**
     * Add in favrite
     * @param $intUserId
     * @return redirect
     */
    public function postSetFavorite($intUserId) {
        $boolIsFavoritted = Redis::SISMEMBER('favorite:resource', $intUserId);
        if($boolIsFavoritted == 1) {
            $objRedis = Redis::srem('favorite:resource', $intUserId);
        }
        else {
            $objRedis = Redis::sadd('favorite:resource', $intUserId);
        }
        return response()->json(['success'=>'ghngh']);

    }

    /*public function search() {

        $strSearch = request()->get('search');
        $searchResource = Resource::search($strSearch)->get();
        dd($searchResource);
        return view('search', compact('searchResource'));*/
       /* $objResource  = new Resource;
        $objResource ->save();*/
      /*  $objResource  = Resource::save();*/
//        $test = Resource::all()->searchable();
//        $searchResource = Resource::where('title', '=', $strSearch)->searchable(;
//        $orders = App\Order::search('Star Trek')->get();
//        $searchResource = Resource::searchByQuery(['match' => ['title' => $strSearch]]);

   // }
}
