<template>
    <div>
        <Navbar/>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div v-if="step === 'poll'" class="card" style="margin-top: 25px">
                        <div class="card-body">
                            <h5 class="card-title">
                                Create New Poll
                            </h5>
                            <br>
                            <div class="form-group">
                                <textarea v-model="question" v-validate="'required'" type="text" class="form-control form-control-lg" name="question" :class="{'invalid': errors.has('question') }" placeholder="Enter Poll Question" rows="3"></textarea>
                            </div>
                            <div v-for="(item, index) in answers" :key="index" class="form-group" style="position:relative">
                                <input v-model="answers[index]" v-validate="'required'" type="text" class="form-control form-control-lg" :placeholder="'Reply ' + (index + 1)" :name="'answers'" :class="{'invalid': errors.has('answers') }" autocomplete="off">
                                <button v-if="index > 1" class="btn btn-lg btn-danger" style="position: absolute; right:0; top:0" @click="deleteAnswer(index)">
                                    Delete
                                </button>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-link" @click="addAnswer">
                                    Add New Answer
                                </button>
                            </div>
                            <br>
                            <button class="btn btn-lg btn-primary" style="margin-left: 5px" @click="submitPoll">
                                Next
                            </button>
                            <button class="btn btn-lg btn-secondary" @click="reset">
                                Clear Form
                            </button>
                        </div>
                    </div>
                    <div v-if="step === 'info'" class="card" style="margin-top: 25px">
                        <div class="card-body">
                            <h5 class="card-title">
                                Complete
                            </h5>
                            <br>
                            <div class="form-group">
                                <input v-model="email" type="email" class="form-control form-control-lg" placeholder="Your E-Mail Address" autocomplete="off">
                            </div>
                            <br>
                            <button class="btn btn-lg btn-primary" style="margin-left: 5px" :disabled="this.$store.getters.submitting" @click="submitInfo">
                                {{ this.$store.getters.submitting ? 'Please Wait...' : 'Submit Poll' }}
                            </button>
                            <button class="btn btn-lg btn-secondary" style="margin-left: 5px" @click="goToStep('poll')">
                                Previews
                            </button>
                            <button class="btn btn-lg btn-secondary" @click="reset">
                                Clear Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Navbar from "../common/Navbar";

    export default {
        name: "Create",
        components: {
            Navbar
        },
        created() {
            document.title = 'Create New Poll';
            this.$store.commit("hideLoader");
        },
        data() {
            return {
                step: "poll",
                question: "",
                answers: ["", ""],
                email: ""
            };
        },
        methods: {
            reset() {
                this.step = "poll";
                this.question = "";
                this.answers = ["", ""];
                this.email = "";
            },
            addAnswer() {
                this.answers.push("");
            },
            deleteAnswer(index) {
                this.answers.splice(index, 1);
            },
            submitPoll() {
                this.$validator
                    .validateAll({
                        question: this.question,
                        answers: this.answers
                    })
                    .then(response => {
                        if (response) {
                            this.step = "info";
                        } else {
                            window.toastr.error("Please complete all fields");
                        }
                    });
            },
            submitInfo() {
                this.$validator.validateAll({
                    question: this.question,
                    answers: this.answers,
                    email: this.email
                }).then(response => {
                    if (response) {
                        this.$Progress.start();
                        this.$store.commit("showSubmitting");
                        window.api.call("post", "/polls/create", {
                            question: this.question,
                            answers: this.answers,
                            email: this.email
                        }).then(({data}) => {
                            this.$Progress.finish();
                            this.$store.commit("hideSubmitting");
                            window.toastr.success(data.message);
                            this.$router.push("/polls/manage/" + data.token);
                        });
                    } else {
                        window.toastr.error("Please complete all fields");
                    }
                });
            },
            goToStep(step) {
                this.step = step;
            }
        }
    };
</script>
