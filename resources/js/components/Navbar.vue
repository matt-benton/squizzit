<template>
    <header class="bg-gray-900 sm:flex sm:justify-between sm:items-center sm:px-4 sm:py-3">
        <div class="flex items-center justify-between px-4 py-3 sm:p-0">
            <div>
                <router-link to="/quizzes" class="font-cursive text-yellow-500">
                    Squizzit
                </router-link>
            </div>
            <div class="sm:hidden">
                <button @click="menuOpen = !menuOpen" type="button" class="block text-gray-500 hover:text-white focus:text-white focus:outline-none">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path v-if="menuOpen" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                        <path v-else d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
                    </svg>
                </button>
            </div>
        </div>
        <div :class="menuOpen ? 'block' : 'hidden'" class="px-2 pt-2 pb-4 sm:flex sm:p-0">
            <div class="relative">
                <button @click="quizzesDropdownOpen = !quizzesDropdownOpen" class="relative z-10 block h-8">
                    Quizzes
                </button>
                <button v-if="quizzesDropdownOpen" @click="quizzesDropdownOpen = false" class="fixed inset-0 h-full w-full bg-black opacity-50 cursor-default"></button>
                <div :class="quizzesDropdownOpen ? 'block' : 'hidden'" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-lg shadow-xl">
                    <router-link to="/quizzes/create" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">
                        Make a New Quiz
                    </router-link>
                    <router-link to="/quizzes" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">
                        My Quizzes
                    </router-link>
                </div>
            </div>

            <router-link to="/invites" class="block px-2 py-1 text-white rounded hover:bg-gray-800">
                Invites
                <span class="tag is-white is-rounded ml-xs" id="quiz-invite-counter" v-show="numQuizInvites > 0">{{ numQuizInvites }}</span>
            </router-link>

            <div class="relative ml-6">
                <button @click="emailDropdownOpen = !emailDropdownOpen" class="relative z-10 block h-8">
                    {{ email }}
                </button>
                <button v-if="emailDropdownOpen" @click="emailDropdownOpen = false" class="fixed inset-0 h-full w-full bg-black opacity-50 cursor-default"></button>
                <div :class="emailDropdownOpen ? 'block' : 'hidden'" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-lg shadow-xl">
                    <a class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white" @click="logout">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
    import Auth from '../services/AuthService'

    const auth = new Auth();

    export default {
        data() {
            return {
                email: localStorage.getItem('email'),
                emailDropdownOpen: false,
                quizzesDropdownOpen: false,
                menuOpen: false,
            }
        },
        created() {
            this.getNumQuizInvites();
        },
        computed: {
            numQuizInvites: function () {
                return this.$store.state.numInvites;
            }
        },
        methods: {
            logout() {
                localStorage.clear();
                auth.clearRequestHeaders();
                this.$store.dispatch('clearUser');
                this.$router.push('/');
            },
            getNumQuizInvites() {
                axios.get('/api/quiz_invites')
                    .then(response => {
                        this.$store.dispatch('storeNumInvites', response.data);
                    })
            }
        }
    }
</script>
