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
            <a href="{{ route('collections.index') }}" class="btn btn-default">Back</a>
        </div>
        <br/>
        <h3>Title - {{ $objCollection->title}} </h3>
        <h3>Slug - {{ $objCollection->slug }} </h3>
        <h3>Description - {{ $objCollection->description }}</h3>
    </div>
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#resources">Add Resources</button>
    <hr>
    <h3>list of resources items of this collection</h3>
    @if($objCollection->resources->count())
        <table class="table table-bordered table-striped">
            <tr>
                <th width="35%">File</th>
                <th width="35%">Title</th>
                <th width="35%">Slug</th>
                <th width="35%">Description</th>
                <th width="30%">Action</th>
            </tr>
            @foreach($objCollection->resources as $objResource)
                <tr>
                    <td><img src="{{ URL::to('/') }}/file_upload/{{ $objResource->file_upload }}" class="img-thumbnail" width="75"/></td>
                    <td>{{ $objResource->title }}</td>
                    <td>{{ $objResource->slug }}</td>
                    <td>{{ $objResource->description }}</td>
                    <td>
                        <button class="btn btn-danger btn-removecollection" value="{{ $objResource->id}}">Remove From Collection</button>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    <div class="modal fade" id="resources" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="favoritesModalLabel">All Resources list</h4>
                </div>
                <div class="modal-body">
                    <div>
                        @if($arrObjResources->count())
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="35%">File</th>
                                    <th width="35%">Title</th>
                                    <th width="35%">Slug</th>
                                    <th width="35%">Description</th>
                                    <th width="30%">Action</th>
                                </tr>
                                    @foreach($arrObjResources as $objResource)
                                <tr>
                                    <td><img src="{{ URL::to('/') }}/file_upload/{{ $objResource->file_upload }}" class="img-thumbnail" width="75"/></td>
                                    <td>{{ $objResource->title }}</td>
                                    <td>{{ $objResource->slug }}</td>
                                    <td>{{ $objResource->description }}</td>
                                    <td><button class="btn btn-info btn-addcollection" value="{{ $objResource->id}}" >Add to collection</button></td>
                                </tr>
                                @endforeach
                            </table>
                        @endif
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
        $(".btn-addcollection").click(function(e){

            e.preventDefault();
            var resource_id = $(this).attr("value");
            $('#resources').modal('toggle');
            console.log(resource_id);
            $.ajax({

                type:'POST',

                url:'/add-to-resources/{{$objCollection->id}}',

                data:{resource_id:resource_id},

                success:function(data){

                    alert(data.success);
                    window.location="/collections/{{$objCollection->id}}";

                }
            });
        });
        $(".btn-removecollection").click(function(e){

            e.preventDefault();
            var resource_id = $(this).attr("value");
            $.ajax({

                type:'POST',

                url:'/remove-to-resources/{{$objCollection->id}}',

                data:{resource_id:resource_id},

                success:function(data){

                    alert(data.success);
                    window.location="/collections/{{$objCollection->id}}";
                }
            });
        });
    </script>
    </body>
    </html>
@endsection
