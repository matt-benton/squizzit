<template>
    <div id="question-editor-container">
        <div class="field">
            <!-- <span class="is-pulled-right has-text-danger" @click="removeQuestion(question.id)"><i class="fas fa-times"></i></span> -->
            <label class="label" for="question_type">Select Type</label>
            <div class="control">
                <div class="select" name="question_type">
                    <select v-model="question.type" name="question_type">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="short_answer">Short Answer</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <textarea
                        v-show="question.type"
                        class="textarea"
                        name="question_text"
                        placeholder="Enter Question"
                        v-model="question.text"></textarea>
            </div>
        </div>

        <div v-show="question.type === 'multiple_choice'">
            <div v-for="(answer, index) in question.answers" :key="answer.id" class="mb-sm">
                    <div class="answer-input-container">
                        <input 
                            type="text" 
                            class="input answer-input" 
                            name="answer_text"
                            v-model="answer.text" 
                            placeholder="Answer Text Here"
                            :dusk="`answer-input-${index}`">
                        <div class="answer-menu">
                            <div class="buttons has-addons">
                                <span class="button is-success is-selected is-outlined">Correct</span>
                                <span class="button">Incorrect</span>
                            </div>
                            <button class="button is-danger is-outlined" @click="removeAnswer(index)" :dusk="`remove-answer-button-${index}`">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                    </div>
            </div>
        </div>

        <button v-show="question.type && question.text" 
            class="button is-rounded is-primary is-pulled-right ml-sm" 
            @click="$emit('question-saved', question)"
            id="save-question-button">
            Save Question
        </button>
        <button class="button is-rounded is-pulled-right" 
            @click="addAnswer(question.id)" v-if="question.type === 'multiple_choice'" id="add-answer-button">
            Add Answer
        </button>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                
            }
        },
        props: ['question'],
        methods: {
            addAnswer() {
                this.question.answers.push({ text: '' });
            },
            removeAnswer(index) {
                this.question.answers.splice(index, 1);
            }
        }
    }
</script>

<style>
    .answer-input-container {
        display: flex;
    }

    .answer-input {
        flex-grow: 1;
        margin-right: 1rem !important; /* ! you might be able to replace this with a utility class */
    }

    .answer-menu {
        display: flex;
        width: 50%;
    }
</style>
