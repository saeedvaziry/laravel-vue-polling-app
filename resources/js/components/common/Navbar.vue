<template>
    <div class="header">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand" href="/"></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link to="/">
                            Home
                        </router-link>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li>
                            <router-link to="/polls/create">
                                Create New Poll
                            </router-link>
                        </li>
                        <li>
                            <a href="https://github.com/saeedvaziry/laravel-vue-polling-app" target="_blank">
                                Github
                            </a>
                        </li>
                    </ul>
                </form>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "Header"
    };
</script>
