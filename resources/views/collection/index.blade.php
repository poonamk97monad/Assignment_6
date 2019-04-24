
@extends('parent')

@section('main')
    @if ($strMessage =Session::get('success') )
        <div class="alert alert-success">
            <p>{{$strMessage}}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#collectionsModal">Add New Collections</button>
    <hr>
    <h2>Collections</h2>
    <hr>
    <table class="table table-bordered table-striped" id="collections_table">
        <tr>
            <th width="35%">Title</th>
            <th width="35%">Slug</th>
            <th width="35%">Description</th>
            <th width="30%">Action</th>
            <th width="30%">Favorites</th>
        </tr>
        @foreach($arrObjCollections as $objCollection)
            <tr>
                <td>{{ $objCollection->title }}</td>
                <td>{{ $objCollection->slug }}</td>
                <td>{{ $objCollection->description }}</td>
                <td>
                    <a href="{{route('collections.show',$objCollection->id)}}" class="btn btn-primary">view</a>
                </td>
                <td>
                    <form action="/add_favorites_collection/{{$objCollection->id}}" method="post">
                        {{ csrf_field() }}
                        @if($objCollection->isFavortted() == 1)
                            <button class="btn btn-success">UnFavorites</button>
                        @else
                            <button class="btn btn-success">Favorites</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    {!! $arrObjCollections->links() !!}
    <div class="modal fade" id="collectionsModal" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="favoritesModalLabel">Resources list</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <form>
                            <span id="form_output"></span>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Enter Title of File</label>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="title" class="form-control input-lg" />
                                </div>
                            </div>
                            <br/><br/><br/>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Enter Slug</label>
                                <div class="col-md-8">
                                    <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="post-slug"/>
                                </div>
                            </div>
                            <br/><br/><br/>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Enter Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="description" id="description" class="form-control input-lg" />
                                </div>
                            </div>
                            <br /><br /><br />
                            <div class="form-group text-center">
                               {{-- <input type="hidden" name="button_action" id="button_action" value="insert" />
                                <input type="submit" name="add" id="add" class="btn btn-primary input-lg" value="Add" />--}}
                                <button class="btn btn-primary btn-submit">Submit</button>
                               {{-- <input type="submit" name="add" class="btn btn-primary input-lg" value="Add" />--}}
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
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
        $(".btn-submit").click(function(e){

            e.preventDefault();
            var title = $("input[name=title]").val();
            var slug = $("input[name=slug]").val();
            var description = $("input[name=description]").val();
            $('#collectionsModal').modal('toggle');
            $.ajax({

                type:'POST',

                url:'/collections/post',

                data:{title:title, slug:slug, description:description},

                success:function(data){

                   /* alert(data.success);*/

                }
            });

        });
    </script>
    </body>
    </html>
@endsection
