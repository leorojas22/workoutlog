<template>
    <section class="app-login-page">
        <h2>Log In</h2>
        <div class="notification error" v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <form @submit="onLoginSubmit">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" v-model="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" v-model="password">
            </div>
            <div class="form-group">
                <AppButton class="btn-main" type="submit" :isLoading="isLoggingIn">
                    Log In
                </AppButton>
            </div>
        </form>
        <p class="text-right">
            <router-link to="/create-account">
                Create Account
            </router-link>
        </p>
    </section>
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            errorMessage: "",
            isLoggingIn: false
        };
    },
    methods: {
        onLoginSubmit(e) {
            e.preventDefault();

            this.errorMessage = "";
            this.isLoggingIn = true;
            this.$store.dispatch("login", {
                email: this.email,
                password: this.password
            })
            .then(response => {
                console.log(response);
                this.isLoggingIn = false;
            })
            .catch(err => {
                let errorMessage = "Unable to login at this time.";
                if(typeof err === 'string')
                {
                    errorMessage = err;
                }

                this.errorMessage = errorMessage;
                this.isLoggingIn = false;
            });
        }
    }
}
</script>

<style scoped>
    .app-login-page {
        max-width: 550px;
    }

    .app-login-page h2 {
        text-align: center;
    }
</style>
