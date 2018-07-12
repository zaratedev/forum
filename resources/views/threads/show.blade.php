@extends('layouts.app')

@section('content')
<thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ route('profile', $thread->creator) }}">{{$thread->creator->name}}</a> posted:
                                {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                                <form action="{{ url($thread->path()) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
                <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

                {{-- {{ $replies->links() }} --}}
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
                  and currently has
                  <span v-text="repliesCount"></span> comments.
                </div>
              </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
