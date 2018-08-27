<template lang="html">
  <div>
    <div v-if="signedIn">
      <div class="form-group" >
        <textarea
          name="body"
          id="body"
          rows="5"
          placeholder="Have Something to say?"
          class="form-control"
          required
          v-model="body"
        ></textarea>
      </div>
      <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
    </div>
    <div class="alert alert-info" role="alert" v-else>
      Please <a href="/login">sing in</a> to participate in this discussion.
    </div>
  </div>
</template>

<script>
  import 'jquery.caret';
  import 'at.js';
export default {
  props: ['endpoint'],
  data() {
    return {
      body: '',
    }
  },
    mounted() {
      $('#body').atwho({
          at: "@",
          delay: 750,
          callbacks: {
              remoteFilter: function (query, callback) {
                  $.getJSON("/api/users", {name: query}, function (usernames) {
                     callback(usernames);
                  });
              }
          }
      })
    },
  methods: {
    addReply() {
      axios.post(this.endpoint, { body: this.body })
          .catch( error => {
              flash(error.response.data, 'danger')
          })
      .then( ({data}) => {
        this.body = '';
        flash('Your reply has been posted');
        this.$emit('created', data);
      });
    }
  }
}
</script>

<style lang="css">
</style>
