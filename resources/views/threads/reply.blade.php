<reply :atributtes="{{ $reply }}" inline-template v-cloak>
  <div class="panel panel-default">
      <div class="panel-heading">
          <div class="level">
              <h5 class="flex">
                  <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>
                  said {{ $reply->created_at->diffForHumans() }}
              </h5>
              <div>

                  <form action="{{ url("/replies/$reply->id/favorites") }}" method="post">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                          {{ $reply->favorites_count }} {{ str_plural('Like', $reply->favorites_count) }}
                      </button>
                  </form>
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
          <!--<form action="{{ url("/replies/".$reply->id) }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" class="btn btn-danger btn-xs">Delete</button>
          </form>-->
        </div>
      @endcan
  </div>
</reply>
