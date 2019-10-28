<template>
    <div>
        <div class="container">
            <section class="section">
                <div class="columns">
                    <div class="column is-4 is-offset-4">
                        <div class="box">
                            <div class="field">
                                <label class="label" for="name">Name</label>
                                <div class="control">
                                    <input
                                            class="input"
                                            :class="{ 'is-danger': $v.form.name.$error, 'is-success': $v.form.name.$invalid === false }"
                                            type="text"
                                            name="name"
                                            placeholder="Name Your Quiz"
                                            v-model.trim="$v.form.name.$model">
                                </div>
                                <p class="help is-danger" v-if="$v.form.name.$error">The name field is required.</p>
                            </div>

                            <div class="field">
                                <label class="label" for="description">Description</label>
                                <div class="control">
                                    <textarea class="textarea" name="description" placeholder="Describe Your Quiz" v-model="form.description"></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <button class="button is-primary is-rounded" :disabled="$v.$invalid" @click="submit" dusk="save-quiz-button">Save Quiz</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
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
