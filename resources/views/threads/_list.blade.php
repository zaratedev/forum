@foreach($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ url($thread->path() ) }}">
                            @if (auth()->check() && $thread->hasUpdatesFor(Auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else

                                {{ $thread->title }}

                            @endif

                        </a>
                    </h4>
                    <h5>Posted by: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a></h5>
                </div>

                <a href="#">
                    <strong>{{ $thread->replies_count }} {{str_plural('reply', $thread->replies_count)}}</strong>
                </a>
            </div>
        </div>

        <div class="panel-body">
            <div class="body">{{ $thread->body }}</div>
        </div>
        <div class="panel-body">
            {{ $thread->visits }} Visits
        </div>
    </div>
@endforeach