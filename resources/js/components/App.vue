<template>
    <div data-app>
        <div v-if="this.$store.getters.loader" class="loader">
            <div></div>
        </div>
        <vue-progress-bar/>
        <router-view/>
    </div>
</template>
<script>
    export default {
        name: "App",
        data() {
            return {};
        }
    };
</script>
