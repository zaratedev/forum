<template lang="html">
  <div class="panel panel-default">
      <div class="panel-heading">
          <div class="level">
              <h5 class="flex">
                  <a :href="'/profile/' + data.owner.name" v-text="data.owner.name"></a>
                  said {{ data.created_at }}
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
        <div v-else v-text="body"></div>
      </div>
      <div class="panel-footer level" v-if="canUpdate">
        <button class="btn-primary btn-xs btn" style="margin-right: 1em;" @click="editing = true">
          Edit
        </button>

        <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
      </div>
  </div>
</template>

<script>
  import Favorite from './FavoriteComponent';

  export default {
    props: ['data'],
    components: {Favorite},
    data () {
      return {
        editing: false,
        body: this.data.body
      }
    },
    computed: {
      signedIn() {
        return window.App.signedIn;
      },
      canUpdate() {
        return this.authorize( user => this.data.user_id == user.id);
        // return this.data.user_id == window.App.user.id;
      }
    },
    methods: {
      update() {
        axios.patch('/replies/' + this.data.id, {
          body: this.body
        });
        this.editing = false;

        flash('Reply Updated');
      },
      destroy() {
        axios.delete('/replies/' + this.data.id);
        this.$emit('deleted', this.data.id);
      }
    }
  }
</script>
