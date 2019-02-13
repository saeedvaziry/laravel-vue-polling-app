<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <div class="card" style="margin-top: 25px">
          <div class="card-body">
            <h5 class="card-title text-center">
              {{ poll.question }}
            </h5>
            <br>
            <div v-for="(answer, index) in poll.answers" :key="index" style="margin-top: 10px">
              <div v-if="poll.vote" class="btn btn-secondary btn-block disabled-answer disabled">
                <span>{{ answer.answer }}</span>
                <div class="percent" :style="{width: getAnswerStats(answer.id).percent + '%'}">
                </div>
                <span class="stat-info">{{ getAnswerStats(answer.id).percent }} Percent</span>
              </div>
              <div v-else>
                <button class="btn btn-secondary btn-block" :disabled="$store.state.submitting" @click="vote(answer.id)">{{ answer.answer }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Fingerprint2 from 'fingerprintjs2';
export default {
  name: "ViewPoll",
  created() {
    Fingerprint2.get(function(components) {
      this.fingerprint = components;
      this.getPoll(this.getBrowserId())
    }.bind(this));
  },
  data() {
    return {
      poll: {},
      fingerprint: []
    };
  },
  methods: {
    getPoll(browserId) {
      window.api.call('post', '/polls/get/' + this.$route.params.id, browserId).then(({ data }) => {
        this.poll = data.poll;
        this.$store.commit('hideLoader');
      });
    },
    vote(id) {
      this.$store.commit('showSubmitting');
      let formData = this.getBrowserId();
      formData['answer_id'] = id;
      window.api.call('post', '/polls/vote/' + this.$route.params.id, formData, [], true).then(({ data }) => {
        this.$store.commit('hideSubmitting');
        this.poll = data.poll;
        window.toastr.success(data.message);
      }).catch(() => {
        this.$store.commit('hideSubmitting');
      });
    },
    getBrowserId() {
      const keys = ['language', 'colorDepth', 'hardwareConcurrency', 'timezoneOffset', 'timezone', 'platform'];
      let browserId = {};
      for (let i = 0; i < this.fingerprint.length; i++) {
        if (keys.includes(this.fingerprint[i].key)) {
          browserId[this.fingerprint[i].key] = this.fingerprint[i].value;
        }
      }

      return browserId;
    },
    getAnswerStats(id) {
      for (var i = this.poll.stats.length - 1; i >= 0; i--) {
        if (this.poll.stats[i].answer_id === id) {
          return this.poll.stats[i];
        }
      }

      return {
        answer_id: id,
        total: 0,
        percent: 0
      };
    }
  }
}

</script>
