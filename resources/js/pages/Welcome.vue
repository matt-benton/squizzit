<template>
    <div>
        <section class="hero is-info is-bold is-large">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title">
                        Squizzit
                    </h1>
                    <h2 class="subtitle">
                        A simple app for making and taking quizzes
                    </h2>
                    <div class="columns">
                        <div class="column is-1">
                            <router-link to="/login" class="button is-info is-inverted is-rounded">Login</router-link>
                        </div>
                        <div class="column is-1">
                            <router-link to="/signup" class="button is-info is-inverted is-rounded">Make an Account</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>
