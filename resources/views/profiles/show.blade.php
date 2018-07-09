@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $user->name }}
                <small>Since {{ $user->created_at->diffForHumans() }}</small>
            </h1>
        </div>
        @foreach($threads as $thread)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">{{$thread->creator->name}}</a> posted:
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                        </span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>

                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
        @endforeach
        {{ $threads->links() }}
    </div>
@endsection