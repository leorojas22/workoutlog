<template>
    <section class="app-workout-page container">
        <p v-if="workoutExercises.length > 0">
            <strong>Exercises</strong>
        </p>
        <AppList :interactive="true">
            <AppListItem
                v-for="(workoutExercise, index) in workoutExercises"
                :key="index"
                @click="onClickEditExercise(workoutExercise.id)"
            >
                {{ index + 1}}. {{ workoutExercise.exercise.name }}
            </AppListItem>
        </AppList>
        <AppLoadingMessage v-if="isLoading" />
        <div class="form-group">
            <AppButton class="btn-main" type="button" @click="onClickAddExercise">
                Add Exercise
            </AppButton>
        </div>
        <div class="form-group">
            <AppButton class="btn-default" type="button" @click="onClickGoBack">
                Go Back
            </AppButton>
        </div>
    </section>
</template>

<script>
export default {
    name: "Workout",
    beforeMount() {
        this.handleRouteChange();
    },
    data() {
        return {
            workout: null,
            isLoading: false
        };
    },
    methods: {
        onClickGoBack() {
            this.$router.push("/");
        },
        handleRouteChange() {
            // Get the workout details
            let workout = this.$store.getters.getWorkoutById(this.$route.params.id);
            if(workout !== undefined)
            {
                this.workout = workout;
                return;
            }

            // Need to load from api
            this.isLoading = true;
            this.$store.dispatch("loadWorkoutById", this.$route.params.id).then(workout => {
                this.workout = workout;
                this.isLoading = false;
            })
            .catch(err => {
                console.error(err);
                this.isLoading = false;
            });
        },
        onClickAddExercise(e) {
            const workoutId = this.$route.params.id;
            this.$router.push("/workout/" + workoutId + "/exercise");
        },
        onClickEditExercise(workoutExerciseId) {
            this.$router.push("/workout/" + this.$route.params.id + "/exercise/" + workoutExerciseId);
        }
    },
    computed: {
        workoutExercises() {
            //return this.$store.workouts
            return this.workout ? this.workout.workoutExercises : [];
        }
    }
}
</script>

<style scoped>

</style>