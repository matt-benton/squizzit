<template>
    <section class="flex justify-center content-center p-2 md:p-10">
        <div class="form-panel">
            <div class="form-group">
                <label class="form-label" for="name">Name</label>
                    <input
                            class="form-control"
                            :class="{ 'is-danger': $v.form.name.$error, 'is-success': $v.form.name.$invalid === false }"
                            type="text"
                            name="name"
                            id="quiz-name-input"
                            placeholder="Name Your Quiz"
                            v-model.trim="$v.form.name.$model">
                <p class="validation-text" v-if="$v.form.name.$error">The name field is required.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea 
                    class="textarea" 
                    name="description" 
                    id="quiz-description-input"
                    placeholder="Describe Your Quiz" 
                    v-model="form.description"
                    rows="4"></textarea>
            </div>

            <div class="form-group">
                <div class="form-group">
                    <button class="btn btn-dark" :disabled="$v.$invalid" @click="submit" dusk="save-quiz-button">Save Quiz</button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                form: {
                    name: '',
                    description: '',
                }
            }
        },
        methods: {
            submit() {
                axios.post('/api/quizzes', {
                    name: this.form.name,
                    description: this.form.description,
                })
                .then(response => {
                    if (response.data.hasOwnProperty('id')) {
                        this.$router.push(`/quizzes/${response.data.id}`);
                    }
                })
            }
        },
        validations: {
            form: {
                name: {
                    required
                }
            }
        },
    }
</script>
