<template>
    <div class="container mx-auto px-2" id="quiz-edit-body">

        <!-- quiz edit form -->
        <div class="my-2" v-if="editQuizFormVisible">
            <input 
                id="quiz-name-input"
                type="text"
                class="px-3 py-2 rounded w-full"
                placeholder="Quiz Title" 
                v-model.lazy="quiz.name">
            <input
                id="quiz-description-input"
                class="px-3 py-2 my-2 rounded w-full"
                type="text"
                placeholder="Quiz Description"
                v-model.lazy="quiz.description">
            <button 
                class="btn bg-blue-500 text-white text-sm" 
                @click="updateQuiz()">
                Save
            </button>
            <button class="btn text-sm" @click="toggleEditQuizForm">
                Cancel
            </button>
        </div>

        <!-- quiz headings -->
        <div class="my-4" v-else>
            <h3 class="text-lg my-1">
                {{ quiz.name }}
                <button type="button" class="rounded bg-gray-100 p-1" @click="toggleEditQuizForm">
                    <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current text-gray-600">
                        <path d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
                    </svg>
                </button>

                <router-link :to="`/quizzes/${quiz.id}/share`">
                    <button class="float-right py-1 px-2 text-sm text-blue-500 bg-gray-300 rounded" id="share-button">
                        Share
                    </button>
                </router-link>
            </h3>
            <h5 class="text-sm text-gray-600">{{ quiz.description }}</h5>
        </div>
        <hr class="mt-8">

        <div class="flex">
            <!-- left side nav -->
            <div class="hidden lg:block w-1/4 p-2 border-r border-solid border-gray-300 overflow-y-auto h-full" id="left-side-column">
                <aside class="h-screen" id="question-nav-list">
                    <p class="text-sm text-gray-600">
                        Questions 
                    </p>
                    <ul class="text-sm font-light text-gray-700">
                        <li v-for="(question, index) in quiz.questions" :key="question.id" @click="scrollToQuestion(question.id)" class="py-1">
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
            <div class="w-full lg:px-4 lg:w-3/4 h-screen lg:overflow-y-auto">
                <!-- new question form -->
                <div class="pt-8">
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
                        @question-saved="saveQuestion($event)"
                        @question-removed="removeQuestion($event)"></question-editor>
                    <hr class="my-8">
                </div>
            </div>
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
                editQuizFormVisible: '',
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
                        this.quiz = response.data.quiz
                        this.toggleEditQuizForm()
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
            },
            toggleEditQuizForm () {
                if (this.editQuizFormVisible) {
                    this.editQuizFormVisible = false
                } else {
                    this.editQuizFormVisible = true
                }
            },
        },
        components: {
            'question-editor': QuestionEditor
        }
    }
</script>
