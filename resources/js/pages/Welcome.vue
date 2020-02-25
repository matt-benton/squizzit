<template>
    <div>
        <section class="bg-gray-900 h-screen">
            <div class="h-full container mx-auto flex flex-col justify-center items-center px-5">
                <div>
                    <h1 class="mb-5 font-cursive text-yellow-500 text-6xl">
                        Squizzit
                    </h1>
                    <h2 class="text-white text-lg">
                        A simple app for making and taking quizzes
                    </h2>
                    <div class="flex flex-col w-full mt-5 sm:flex-row">
                        <router-link to="/auth/login" class="sm:mr-3">
                            <button class="mb-5 py-2 w-full btn-dark rounded sm:px-8 sm:py-3">
                                Login
                            </button>        
                        </router-link>
                        <router-link to="/auth/signup">
                            <button class="py-2 w-full btn-dark rounded sm:px-8 sm:py-3">
                                Make an Account
                            </button>
                        </router-link>
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
