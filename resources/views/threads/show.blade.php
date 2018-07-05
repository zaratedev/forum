@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="">{{$thread->creator->name}}</a> posted:
                        {{ $thread->title }}
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>
        @if(auth()->check())
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <form action="{{ url('/threads/'.$thread->id.'/replies') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" rows="5" placeholder="Have Something to say?" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-info" role="alert">
                        Please <a href="{{ route('login') }}">sing in</a> to participate in this discussion.
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
