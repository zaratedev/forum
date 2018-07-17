@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')

                {{ $threads->render() }}
            </div>
            <div class="col-md-4">
                @if (count($thrending))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Trending Thread
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @foreach ($thrending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path)}}">{{ $thread->title }}</a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
