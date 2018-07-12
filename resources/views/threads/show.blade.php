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
                <replies @removed="repliesCount--"></replies>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-body">
                  This thread was publish {{ $thread->created_at->diffForHumans() }} by
                  <a href="#">{{ $thread->creator->name }}</a>,
                  and currently has
                  <span v-text="repliesCount"></span> comments.
                    <p>
                        <subscribe-button :active="{{ json_encode($thread->IsSubscribedTo) }}"></subscribe-button>
                    </p>
                </div>
              </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
