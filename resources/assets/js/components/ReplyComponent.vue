<script>
  import Favorite from './FavoriteComponent';

  export default {
    props: ['atributtes'],
    components: {Favorite},
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

        flash('Reply Updated');
      },
      destroy() {
        axios.delete('/replies/' + this.atributtes.id);

        $(this.$el).fadeOut(300, () => {
          flash('Reply deleted');
        });
      }
    }
  }
</script>
