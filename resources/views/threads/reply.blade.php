<reply :atributtes="{{ $reply }}" inline-template v-cloak>
  <div class="panel panel-default">
      <div class="panel-heading">
          <div class="level">
              <h5 class="flex">
                  <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>
                  said {{ $reply->created_at->diffForHumans() }}
              </h5>
              <div>
                <favorite :reply="{{ $reply }}"></favorite>
              </div>
          </div>
      </div>
      <div class="panel-body">
        <div v-if="editing">
          <div class="form-group">
              <textarea name="name" rows="3" cols="80" class="form-control" v-model="body"></textarea>
              <button class="btn btn-primary btn-xs" @click="update">Update</button>
              <button class="btn btn-default btn-xs" @click="editing = false">Cancel</button>
          </div>

        </div>
        <div v-else v-text="body"></div>
      </div>
      @can ('update', $reply)
        <div class="panel-footer level">
          <button class="btn-primary btn-xs btn" style="margin-right: 1em;" @click="editing = true">
            Edit
          </button>

          <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
        </div>
      @endcan
  </div>
</reply>
