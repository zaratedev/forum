<script>
  export default {
    props: ['atributtes'],
    data () {
      return {
        editing: false,
        body: this.atributtes.body
      }
    },
    methods: {
      update() {
        axios.patch('/replies/' + this.atributtes.id, {
          body: this.body
        });
        this.editing = false;
      }
    }
  }
</script>
