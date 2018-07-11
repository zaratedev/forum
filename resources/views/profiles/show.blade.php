@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $user->name }}
                <small>Since {{ $user->created_at->diffForHumans() }}</small>
            </h1>
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