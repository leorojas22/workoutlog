<template>
    <section class="app-create-account-page container">
        <h2 class="text-center">Create Account</h2>
        <div class="notification error" v-if="errorMessage">
            {{ errorMessage }}
        </div>
        <form @submit="onCreateAccountSubmit">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" v-model="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" v-model="password">
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" v-model="confirmPassword">
            </div>
            <div class="form-group">
                <AppButton class="btn-main" type="submit" :isLoading="isCreatingAccount">
                    Create Account
                </AppButton>
            </div>
        </form>
        <p class="text-right">
            <router-link to="/login">
                Login
            </router-link>
        </p>
    </section>
</template>

<script>
export default {
    name: "CreateAccount",
    data() {
        return {
            email: "",
            password: "",
            confirmPassword: "",
            errorMessage: "",
            isCreatingAccount: false
        };
    },
    methods: {
        onCreateAccountSubmit(e) {
            e.preventDefault();
        }
    }
}
</script>

<style scoped>
    .app-create-account-page {
        max-width: 550px;
        margin-top: 30px;
    }
</style>
