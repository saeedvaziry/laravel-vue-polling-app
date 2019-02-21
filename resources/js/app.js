import Vue from 'vue'
import vueProgressBar from 'vue-progressbar'
import vueValidate, {Validator} from 'vee-validate'

import App from './components/App.vue'

import store from './store'
import router from './router'

import Api from './api.js'

Vue.use(vueValidate);
Vue.use(vueProgressBar, {
    color: '#dc3545',
    failedColor: '#dc3545',
    thickness: '5px'
});

window.Event = new Vue();
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');
window.toastr = require('toastr');

window.toastr.options = {
    positionClass: 'toast-bottom-center',
    preventDuplicates: true
};

Vue.config.productionTip = false;

window.api = new Api();

export default new Vue({
    router,
    store,
    data: {
        spinner: {
            color: '#0058b1',
            size: '50px'
        }
    },
    render: h => h(App)
}).$mount('#app')
