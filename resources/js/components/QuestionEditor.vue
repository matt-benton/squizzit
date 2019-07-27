<template>
    <div>
        <div class="field">
            <!-- <span class="is-pulled-right has-text-danger" @click="removeQuestion(question.id)"><i class="fas fa-times"></i></span> -->
            <label class="label" for="question_type">Select Type</label>
            <div class="control">
                <div class="select" name="question_type">
                    <select v-model="question.type">
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
            <div v-for="answer in question.answers" :key="answer.id">
                <div class="field">
                    <input 
                        type="text" 
                        class="input" 
                        name="answer_text"
                        v-model="answer.text" 
                        placeholder="Answer Text Here"
                        @change="updateAnswer(answer.id, question.id)">
                </div>

                <!-- <label class="answer_correct">
                    <input type="checkbox" v-model="answer.correct" @change="updateAnswer(answer.id, question.id)">
                    Correct
                </label> -->
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-rounded is-pulled-right" @click="addAnswer(question.id)">Add Answer</button>
                </div>
            </div>
        </div>

        <button v-show="question.type && question.text" class="button is-rounded is-pulled-right ml-sm" @click="$emit('question-saved', question)">Save Question</button>
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
            
        }
    }
</script>
