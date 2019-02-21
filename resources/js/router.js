import Vue from 'vue'
import Router from 'vue-router'

import Home from './components/home/Home'
import CreatePoll from './components/polls/Create'
import ViewPoll from './components/polls/View'
import ManagePoll from './components/polls/Manage'
import NotFound from './components/errors/NotFound'

Vue.use(Router);

const router = new Router({
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home
        },
        {
            path: '/polls/create',
            name: 'CreatePoll',
            component: CreatePoll
        },
        {
            path: '/polls/view/:id',
            name: 'ViewPoll',
            component: ViewPoll
        },
        {
            path: '/polls/manage/:token',
            name: 'ManagePoll',
            component: ManagePoll
        },
        {
            path: '/*',
            name: 'NotFound',
            component: NotFound
        }
    ],
    mode: 'history'
});

export default router
