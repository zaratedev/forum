@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <avatar-form :user="{{ $user }}"></avatar-form>
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