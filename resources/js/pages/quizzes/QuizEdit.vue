<template>
    <div class="container mx-auto px-2" id="quiz-edit-body">
        <!-- <section class="section is-small">
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
        </section> -->
        <div class="my-4">
            <h3 class="text-lg my-1">{{ quiz.name }}</h3>
            <h5 class="text-sm text-gray-600">{{ quiz.description }}</h5>
        </div>

        <hr class="my-8">
        <!-- left side nav -->
        <div class="hidden" id="left-side-column">
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

        <!-- main page section -->
        
        <!-- new question form -->
        <div class="pt-4">
            <textarea 
                class="w-full mb-2 p-2" 
                name="new_question" 
                id="new-question-textarea" 
                rows="4" 
                placeholder="Enter a new question"
                v-model="newQuestionText">
            </textarea>
            <button type="button" class="rounded bg-blue-500 p-1" @click="addQuestion">
                <svg class="h-5 w-5 fill-current text-white" viewBox="0 0 24 24"><path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>
            </button>
        </div>

        <hr class="my-8">

        <!-- question editors -->
        <div v-for="(question, index) in quiz.questions" :key="question.id" :id="`question-${index}-editor`">
            <p class="text-gray-600 my-3 text-sm">Question {{ index + 1 }}</p>
            <question-editor 
                :question="question" 
                @question-saved="saveQuestion($event)"></question-editor>
            <hr class="my-8">
        </div>
    </div>
</template>

<script>
    import QuestionEditor from '../../components/QuestionEditor.vue'

    export default {
        data() {
            return {
                quiz: '',
                newQuestionText: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            next(vm => {
                axios.get(`/api/quizzes/${vm.$route.params.id}/user_role`)
                    .then(response => {
                        if (response.data.role === 'maker') {
                            next();
                        } else {
                            next(`/quizzes`);
                        }
                    });
            });
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
            addQuestion () {
                axios.post('/api/questions', {
                    quiz_id: this.quiz.id,
                    question_text: this.newQuestionText,
                })
                .then(response => {
                    // we have to wait for the question to be added to the list before we can scroll to it
                    this.putQuestionInList(response.data.question)
                        .then(questionId => {
                            this.scrollToQuestion(questionId)
                        })
                    this.clearNewQuestionText();
                })
            },
            putQuestionInList (question) {
                this.quiz.questions.push(question)

                return new Promise((resolve) => {
                    resolve(question.id)
                })
            },
            clearNewQuestionText () {
                this.newQuestionText = ''
            },
            scrollToQuestion (id) {
                const index = this.findQuestionIndex(id)
                const questionElement = document.getElementById(`question-${index}-editor`)
                questionElement.scrollIntoView()
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

#quiz-name-input:focus {
    outline: none;
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

#quiz-description-input {
    outline: none;
}

#left-side-column {
    overflow: scroll;
    height: 66vh;
}

</style>
