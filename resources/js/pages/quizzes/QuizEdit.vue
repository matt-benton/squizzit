<template>
    <div id="quiz-edit-container">
        <navbar></navbar>
        <div class="container" id="quiz-edit-body">
            <section class="section is-small">
                <p>
                    <input 
                        id="quiz-name-input"
                        type="text"
                        class="mb-sm"
                        placeholder="Quiz Title" 
                        v-model.lazy="quiz.name"
                        @change="updateQuiz">
                    <router-link :to="`/quizzes/${quiz.id}/share`" class="button is-primary is-pulled-right is-rounded" id="share-button">
                        <i class="fas fa-share-square"></i>
                        &nbsp;
                        Share
                    </router-link>
                </p>
                <p>
                    <input
                        id="quiz-description-input"
                        type="text"
                        placeholder="Quiz Description"
                        v-model.lazy="quiz.description"
                        @change="updateQuiz">
                </p>
            </section>

            <hr class="has-background-primary">
            <div class="columns">
                <!-- left side nav -->
                <div class="column is-one-quarter" id="left-side-column">
                    <aside class="menu" id="question-nav-list">
                        <p class="menu-label">
                            Questions 
                            <button class="button is-primary is-rounded is-small is-pulled-right" id="new-question-button" @click="selectQuestion(returnNewQuestion())">+ New</button>
                        </p>
                        <ul class="menu-list">
                            <li v-for="(question, index) in quiz.questions" :key="question.id" @click="selectQuestion(question)">
                                <a :dusk="`question-link-${quiz.id}`">
                                    {{ index + 1 }}.
                                    &nbsp;
                                    {{ question.text.length > 60 ? question.text.substring(0, 60) + '...' : question.text }}
                                </a>
                            </li>
                        </ul>
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
            updateQuiz () {
                if (this.quiz.name) {
                    axios.put(`/api/quizzes/${this.$route.params.id}`, {
                        name: this.quiz.name,
                        description: this.quiz.description
                    })
                    .then(response => {
                        this.quiz = response.data.quiz;
                    })
                }
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

#quiz-edit-container {
    height: 100vh;
}

#quiz-name-input {
    background: whitesmoke;
    border-bottom: 0;
    border-left: 0;
    border-right: 0;
    border-top: 0;
    box-shadow: 0 0;
    border-radius: 0;
    font-size: 2rem;
    padding: 0;
    color: #4a4a4a;
}

#quiz-description-input {
    border: 0;
    box-shadow: 0 0;
    color: #4a4a4a;
    font-size: 1rem;
    font-weight: 300 !important;
    background: whitesmoke;
    width: 100%;
}

#left-side-column {
    overflow: scroll;
    height: 66vh;
}

</style>
