@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="">{{$thread->creator->name}}</a> posted:
                        {{ $thread->title }}
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach
                {{ $replies->links() }}
                @if(auth()->check())
                  <form action="{{ url($thread->path().'/replies') }}" method="post">
                      {{ csrf_field() }}
                      <div class="form-group">
                          <textarea name="body" id="body" rows="5" placeholder="Have Something to say?" class="form-control"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Post</button>
                  </form>
                @else
                  <div class="alert alert-info" role="alert">
                      Please <a href="{{ route('login') }}">sing in</a> to participate in this discussion.
                  </div>
                @endif
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  This thread was publish {{ $thread->created_at->diffForHumans() }} by
                  <a href="#">{{ $thread->creator->name }}</a>,
                  and currently has {{ $thread->replies_count }} comments.
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection
