<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
              <a href="{{ $activity->subject->favorited->path() }}">
                {{ $user->name }} Like an reply
              </a>
            </span>
        </div>
    </div>
    <div class="panel-body">
        {{ $activity->subject->favorited->body }}
    </div>
</div>
