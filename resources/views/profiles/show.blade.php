@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $user->name }}
                <small>Since {{ $user->created_at->diffForHumans() }}</small>
            </h1>
            @can('update', $user)
                <form action="{{ route('avatar', $user) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="avatar">
                    <button type="submit" class="btn btn-primary">Add Avatar</button>
                </form>
            @endcan
            @if ($user->avatar_path)
                <img src="{{ asset($user->avatar_path) }}" alt="" width="50" height="50">
            @endif
        </div>
        @foreach($activities as $data => $activity)
            <h2 class="page-header">{{ $data }}</h2>
            @foreach ($activity as $element)
                @include ("profiles.activities.$element->type", ['activity' => $element])
            @endforeach
        @endforeach
        {{-- {{ $threads->links() }} --}}
    </div>
@endsection