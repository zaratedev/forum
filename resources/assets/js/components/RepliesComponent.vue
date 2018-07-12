<template lang="html">
  <div class="">
    <div v-for="(reply, index) in items">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>

    <new-reply :endpoint="endpoint" @created="add"></new-reply>
  </div>
</template>

<script>
import Reply from './ReplyComponent.vue';
import NewReply from './NewReplyComponent.vue';

export default {
  props: ['data'],

  data() {
    return {
      items: this.data,
      endpoint: location.pathname + '/replies'
    }
  },

  components: {
    Reply,
    NewReply
  } ,

  methods: {
    add(reply) {
      this.items.push(reply);
    },
    remove(index) {
      this.items.splice(index, 1);

      this.$emit('removed');

      flash('Reply deleted');
    }
  }
}
</script>

<style lang="css">
</style>
