<template lang="html">
  <ul class="pagination" v-if="shouldPaginate">
    <li v-show="prevUrl">
      <a href="#" aria-label="Previous" @click.prevent="page--">
        <span aria-hidden="true">&laquo; Previous</span>
      </a>
    </li>
    <li v-show="nextUrl">
      <a href="#" aria-label="Next" @click.prevent="page++">
        <span aria-hidden="true">Next &raquo;</span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: ['dataSet'],

  data () {
    return {
      page: 1,
      prevUrl: false,
      nextUrl: false,
    }
  },

  watch: {
    dataSet(){
      this.page = this.dataSet.current_page;
      this.prevUrl = this.dataSet.prev_page_url;
      this.nextUrl = this.dataSet.next_page_url;
    },
    page() {
      this.broadcast();
    }
  },

  computed: {
    shouldPaginate() {
      return !! this.prevUrl || !! this.nextUrl;
    }
  },

  methods: {
    broadcast() {
      this.$emit('updated', this.page);
    }
  }
}
</script>

<style lang="css">
</style>
