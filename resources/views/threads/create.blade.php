@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create new Thread</div>

                    <div class="panel-body">
                        <form action="{{ url('/threads') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <textarea name="body" id="body" rows="10" placeholder="Body" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
