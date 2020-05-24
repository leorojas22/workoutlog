<template>
    <section class="app-workout-page">
        <AppButton class="btn-main" type="button" @click="onClickAddExercise">
            Add Exercise
        </AppButton>
    </section>
</template>

<script>
export default {
    name: "Workout",
    methods: {
        onClickAddExercise(e) {
            e.preventDefault();
            const workoutId = this.$route.params.id;
            this.$router.push("/workout/" + workoutId + "/exercise");
        }
    }
}
</script>

<style scoped>

</style>