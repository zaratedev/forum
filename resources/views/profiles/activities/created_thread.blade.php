<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                {{ $user->name }} punlished 
                <a href="{{ $activity->subject->path() }}">
                	{{ $activity->subject->title }}
                </a>
            </span>
        </div>
    </div>
    <div class="panel-body">
        {{ $activity->subject->body }}
    </div>
</div>