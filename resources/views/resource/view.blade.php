@extends('parent')

@section('main')
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Collection</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css"/>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    </head>
    <body>
    <div class="jumbotron text-center">
        <div align="right">
            <a href="{{ route('resources.index') }}" class="btn btn-default">Back</a>
        </div>
        <br/>
        <h2>
            <img src="{{ URL::to('/') }}/file_upload/{{ $arrObjResources->file_upload }}" class="img-thumbnail" width="75" />
           </h2>
        <h3>Title - {{ $arrObjResources->title}} </h3>
        <h3>Slug - {{ $arrObjResources->slug }} </h3>
        <h3>Description - {{ $arrObjResources->description }}</h3>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#collection">Add Resources</button>
    <hr>
    <h3>list of collection items of this resources</h3>
    @if($arrObjResources->collections->count())
        <table class="table table-bordered table-striped">
            <tr>
                <th width="35%">Title</th>
                <th width="35%">Slug</th>
                <th width="35%">Description</th>
                <th width="30%">Action</th>
            </tr>
            @foreach($arrObjResources->collections as $objCollection)
                <tr>
                    <td>{{ $objCollection->title }}</td>
                    <td>{{ $objCollection->slug }}</td>
                    <td>{{ $objCollection->description }}</td>
                    <td>
                        <button class="btn btn-danger btn-removeresource"  value="{{$objCollection->id}}">Remove From Collection</button>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    <div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="favoritesModalLabel">All Collection list</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <form method="post" action="{{ route('resources.store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @if($arrObjCollection->count())
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th width="35%">Title</th>
                                        <th width="35%">Slug</th>
                                        <th width="35%">Description</th>
                                        <th width="30%">Action</th>
                                    </tr>
                                    @foreach($arrObjCollection as $objCollection)
                                        <tr>
                                            <td>{{ $objCollection->title }}</td>
                                            <td>{{ $objCollection->slug }}</td>
                                            <td>{{ $objCollection->description }}</td>
                                            <td>
                                                <button class="btn btn-info btn-addresource" value="{{$objCollection->id}}">Add to resource</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".btn-addresource").click(function(e){

            e.preventDefault();
            var collection_id = $(this).attr("value");
            $('#collection').modal('toggle');
            $.ajax({

                type:'POST',

                url:'/add-to-collection/{{$arrObjResources->id}}',

                data:{collection_id:collection_id},

                success:function(data){
                    window.location="/collections/{{$arrObjResources->id}}"

                }
            });
        });
        $(".btn-removeresource").click(function(e){

            e.preventDefault();
            var collection_id = $(this).attr("value");
            $.ajax({

                type:'POST',

                url:'/remove-to-collection/{{$arrObjResources->id}}',

                data:{collection_id:collection_id},

                success:function(data){
                    window.location="/resources/{{$arrObjResources->id}}"

                }
            });
        });
    </script>
    </body>
    </html>
@endsection
