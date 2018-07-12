<template>
    <button class="btn" :class="active ? 'btn-danger' : 'btn-success'" @click="subscribe">
        {{ active ? 'Unsubscribe' : 'Subscribe' }}
        <span class="glyphicon glyphicon-bullhorn"></span>
    </button>
</template>

<script>
    export default {
        props: ['active'],

        methods: {
            subscribe() {
                axios[(
                    this.active ? 'delete' : 'post'
                )](location.pathname + '/subscriptions');

                flash(this.active ? 'Unsubscribe' : 'Subscribe');

                this.active = ! this.active;

            }
        }
    }
</script>