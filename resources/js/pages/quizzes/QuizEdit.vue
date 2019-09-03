<template>
    <div class="page-container has-background-light">
        <navbar></navbar>
        <div class="container">
            <section class="section is-small">
                <p><span class="is-size-3">{{ quiz.name }}</span></p>
                <p><span class="has-text-weight-light">{{ quiz.description }}</span></p>
            </section>
            <hr class="has-background-primary">
            <section class="section">
                <div class="columns">
                    <!-- left side nav -->
                    <div class="column is-one-quarter">
                        <aside class="menu" id="question-nav-list">
                            <p class="menu-label">
                                Questions 
                                <button class="button is-primary is-rounded is-small is-pulled-right" id="new-question-button" @click="selectQuestion(returnNewQuestion())">+ New</button>
                            </p>
                            <ol class="menu-list">
                                <li v-for="question in quiz.questions" :key="question.id" @click="selectQuestion(question)">
                                    <a :dusk="`question-link-${quiz.id}`">{{ question.text.length > 60 ? question.text.substring(0, 60) + '...' : question.text }}</a>
                                </li>
                            </ol>
                        </aside>
                    </div>

                    <!-- questions/form -->
                    <div class="column ml-md">
                        <question-editor 
                            v-if="selectedQuestion" 
                            :question="selectedQuestion" 
                            @question-saved="saveQuestion($event)"></question-editor>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/Navbar.vue'
    import QuestionEditor from '../../components/QuestionEditor.vue'

    export default {
        data() {
            return {
                quiz: '',
                selectedQuestion: ''
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
            addQuestion (question) {
                axios.post('/api/questions', {
                    question
                })
                .then(response => {
                    this.quiz.questions.push(response.data.question);
                })
            },
            updateQuestion (question) {
                axios.put(`/api/questions/${question.id}`, {
                    question
                })
                .then(response => {
                    let oldQuestion = this.quiz.questions.find(q => q.id === response.data.question.id);
                    oldQuestion = response.data.question;
                })
            },
            selectQuestion (question) {
                this.selectedQuestion = question;
            },
            deselectQuestion () {
                this.selectedQuestion = '';
            },
            returnNewQuestion () {
                return {
                    answers: [],
                    quiz_id: this.quiz.id,
                    text: '',
                    type: ''
                }
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
            },
            saveQuestion(question) {
                if (question.hasOwnProperty('id')) {
                    this.updateQuestion(question);
                } else {
                    this.addQuestion(question);
                }

                this.deselectQuestion();
            }
        },
        components: {
            'navbar': Navbar,
            'question-editor': QuestionEditor
        }
    }
</script>

<style>

.page-container {
    height: 100vh;
}

</style>
