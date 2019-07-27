<template>
    <div>
        <navbar></navbar>
        <div class="container">
            <section class="section">
                <p>
                    <span class="is-size-3">{{ quiz.name }}</span>
                    <span class="has-text-weight-light">{{ quiz.description }}</span>
                </p>
            </section>
            <hr>
            <section class="section">
                <div class="columns">
                    <div class="column is-one-quarter">
                        <aside class="menu">
                            <p class="menu-label">
                                Questions
                            </p>
                            <ul class="menu-list">
                                <li v-for="question in quiz.questions" :key="question.id"><a :href="'#question-container-' + question.id">{{ question.text.length > 60 ? question.text.substring(0, 60) + '...' : question.text }}</a></li>
                            </ul>
                        </aside>
                    </div>

                    <div class="column">
                        <div v-for="question in quiz.questions" :key="question.id" :id="'question-container-' + question.id">
                            <div class="field">
                                <span class="is-pulled-right has-text-danger" @click="removeQuestion(question.id)"><i class="fas fa-times"></i></span>
                                <label class="label" for="question_type">Question Type</label>
                                <div class="control">
                                    <div class="select" name="question_type">
                                        <select v-model="question.type" @change="updateQuestion(question.id)">
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="short_answer">Short Answer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label" for="question_text">Question Text</label>
                                <div class="control">
                                    <textarea
                                            class="textarea"
                                            name="question_text"
                                            placeholder="Enter Question"
                                            v-model.lazy="question.text"
                                            @change="updateQuestion(question.id)"></textarea>
                                </div>
                            </div>

                            <div v-show="question.type === 'multiple_choice'">
                                <div v-for="answer in question.answers" :key="answer.id">
                                    <div class="field">
                                        <label class="label" for="answer_text">Answer</label>
                                        <input type="text" class="input" name="answer_text" v-model="answer.text" @change="updateAnswer(answer.id, question.id)">
                                    </div>

                                    <label class="answer_correct">
                                        <input type="checkbox" v-model="answer.correct" @change="updateAnswer(answer.id, question.id)">
                                        Correct Answer
                                    </label>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <button class="button is-rounded" @click="addAnswer(question.id)">Add Answer</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div class="field">
                            <div class="control">
                                <button class="button is-rounded" @click="addQuestion">New Question</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'

    export default {
        data() {
            return {
                quiz: ''
            }
        },
        created() {
            this.getQuiz();
        },
        methods: {
            getQuiz () {
                axios.get(`/api/quizzes/${this.$route.params.id}`)
                .then(response => {
                    this.quiz = response.data.quiz;
                })
            },
            addQuestion () {
                axios.post('/api/questions', {
                    quiz_id: this.quiz.id
                })
                .then(response => {
                    this.quiz.questions.push(response.data.question);
                })
            },
            updateQuestion (id) {
                let question = this.findQuestion(id);

                axios.put(`/api/questions/${id}`, {
                    text: question.text,
                    type: question.type
                })
                .then(response => {
                    question = response.data.question;
                })
            },
            addAnswer (question_id) {
                let question = this.findQuestion(question_id);

                axios.post('/api/answers', {
                    question_id: question.id,
                    text: '',
                    correct: false
                })
                .then(response => {
                    question.answers.push(response.data.answer);
                })
            },
            updateAnswer (answer_id, question_id) {
                let answer = this.findAnswer(answer_id, this.findQuestion(question_id));

                axios.put(`/api/answers/${answer_id}`, {
                    text: answer.text,
                    correct: answer.correct
                })
                .then(response => {
                    answer = response.data.answer;
                })
            },
            findQuestion (id) {
                return this.quiz.questions.find(question => question.id === id);
            },
            findQuestionIndex (id) {
                return this.quiz.questions.findIndex(question => question.id === id);
            },
            findAnswer (id, question) {
                return question.answers.find(answer => answer.id === id);
            },
            removeQuestion (id) {
                let index = this.findQuestionIndex(id);

                axios.delete(`/api/questions/${id}`)
                .then(response => {
                    this.quiz.questions.splice(index, 1);
                })
            }
        },
        components: {
            'navbar': Navbar
        }
    }
</script>
