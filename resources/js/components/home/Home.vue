<template>
    <div>
        <Navbar/>
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4">Polling system with laravel and vue.js</h1>
                <br>
                <router-link class="btn btn-primary btn-lg" to="/polls/create" role="button">Create your first poll</router-link>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="poll" v-for="(poll, index) in polls" :key="index">
                        <router-link :to="'/polls/view/' + poll.id">{{ poll.question }}</router-link>
                        <span>{{ poll.votes_count }} Vote</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Navbar from '../common/Navbar';

    export default {
        name: 'Home',
        components: {
            Navbar
        },
        data() {
            return {
                polls: []
            }
        },
        created() {
            this.getPolls();
        },
        methods: {
            getPolls() {
                window.api.call('post', '/polls/all', {}).then(({data}) => {
                    this.polls = data.polls;
                    this.$store.commit('hideLoader');
                });
            }
        }
    }

</script>
