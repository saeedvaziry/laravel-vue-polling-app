import app from './app'
import store from './store'
import axios from 'axios'

class Api {
  constructor() {
    this.baseUrl = '';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  }

  call(requestType, url, data = null, headers = [], r = false) {
    return new Promise((resolve, reject) => {
      axios[requestType](this.baseUrl + url, data, headers).then(response => {
        app.$Progress.finish();
        resolve(response);
      }).catch(({ response }) => {
        app.$Progress.fail();
        store.commit('hideSubmitting');
        if (response.status === 422) {
          window.toastr.error(response.data.message);
        }
        if (response.status === 404) {
          window.toastr.error(response.data.message);
          app.$router.push('/404');
        }
        if (response.status === 403) {
          window.toastr.error(response.data.message);
          app.$router.push('/404');
        }
        if (r) {
          reject(response);
        }
      });
    });
  }
}

export default Api;
