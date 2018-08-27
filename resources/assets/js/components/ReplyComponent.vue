<template lang="html">
  <div class="panel " :class="isBest ? 'panel-success' : 'panel-default'">
      <div class="panel-heading">
          <div class="level">
              <h5 class="flex">
                  <a :href="'/profile/' + data.owner.name" v-text="data.owner.name"></a>
                  said
                  <span v-text="ago"></span>
              </h5>
              <div v-if="signedIn">
                <favorite :reply="data"></favorite>
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
        <div v-else v-html="body"></div>
      </div>
      <div class="panel-footer level">
        <div v-if="authorize('updateReply', reply)">
          <button class="btn-primary btn-xs btn" style="margin-right: 1em;" @click="editing = true">
            Edit
          </button>
          <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
        </div>
        
        <button class="btn btn-success btn-xs" style="margin-left: auto;" @click="markBestReply" v-show="! isBest">
          Best Reply?
        </button>
      </div>
  </div>
</template>

<script>
  import Favorite from './FavoriteComponent';
  import moment from 'moment';

  export default {
    props: ['data'],
    components: {Favorite},
    data () {
      return {
        editing: false,
        body: this.data.body,
		reply: this.data,
		thread: window.thread,
      }
    },
    computed: {
		isBest(){
			return this.thread.best_reply_id == this.data.id;
		},
		ago() {
			return moment(this.data.created_at).fromNow();
		},
	},
    methods: {
      update() {
        axios.patch('/replies/' + this.data.id, {
          body: this.body
        }).catch( error => {
            flash(error.response.data, 'danger');
        });
        this.editing = false;

        flash('Reply Updated');
      },
      destroy() {
        axios.delete('/replies/' + this.data.id);
        this.$emit('deleted', this.data.id);
      },
      markBestReply() {
		axios.post('/replies/' + this.data.id + '/best');
		this.thread.best_reply_id = this.data.id;
      }
    }
  }
</script>
