<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-5">
        <div class="card" style="margin-top: 25px">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-4">
                <router-link :to="'/polls/view/' + poll.id" class="btn btn-secondary btn-block">View</router-link>
              </div>
              <div class="col-sm-4">
                <button class="btn btn-secondary btn-block" @click="deactivatePoll">Disable</button>
              </div>
              <div class="col-sm-4">
                <button class="btn btn-secondary btn-block" @click="activatePoll">Enable</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card" style="margin-top: 25px">
          <div class="card-body">
            <h5 class="card-title text-center">
              {{ poll.question }}
            </h5>
            <br>
            <div v-for="(answer, index) in poll.answers" :key="index" style="margin-top: 10px">
              <div class="btn btn-secondary btn-block disabled-answer disabled">
                <span>{{ answer.answer }}</span>
                <div class="percent" :style="{width: getAnswerStats(answer.id).percent + '%'}">
                </div>
                <span class="stat-info">{{ getAnswerStats(answer.id).percent }} Percent</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card" style="margin-top: 25px">
          <div class="card-body">
            <h5 class="card-title text-center">Stats</h5>
            <br>
            <div ref="pieChart" style="height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import anychart from 'anychart'
export default {
  name: "ManagePoll",
  mounted() {
    this.getPoll();
  },
  data() {
    return {
      poll: {},
    };
  },
  methods: {
    getPoll() {
      window.api.call('post', '/polls/manage/' + this.$route.params.token, {}).then(({ data }) => {
        this.$store.commit('hideLoader');
        this.poll = data.poll;
        if (this.poll.stats.length > 0) {
          this.drawPieChart();
        }
      });
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
    },
    drawPieChart() {
      let data = [];
      for (var i = this.poll.answers.length - 1; i >= 0; i--) {
        data.push([this.poll.answers[i].answer, this.getAnswerStats(this.poll.answers[i].id).total])
      }
      let chart = anychart.pie(data);
      chart.container(this.$refs.pieChart);
      chart.draw();
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
    },
    activatePoll() {
      this.$Progress.start();
      window.api.call('post', '/polls/manage/' + this.$route.params.token + '/activate', {}).then(({ data }) => {
        window.toastr.success(data.message);
      });
    },
    deactivatePoll() {
      this.$Progress.start();
      window.api.call('post', '/polls/manage/' + this.$route.params.token + '/deactivate', {}).then(({ data }) => {
        window.toastr.success(data.message);
      });
    }
  }
}

</script>
