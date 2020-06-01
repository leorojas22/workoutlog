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
        <hr />
        <div class="form-group text-right">
            <AppButton
                class="btn-danger btn-xs btn-inline"
                @click="onClickDelete"
            >
                <i class="fas fa-trash-alt" aria-label="Delete"></i>
            </AppButton>
        </div>

        <AppModalConfirm
            v-if="confirmDelete"
            :isVisible="confirmDelete"
            :isLoading="isDeleting"
            @close="confirmDelete = false"
            @confirmed="onClickDeleteConfirm"
        >
            <p class="text-center">
                <strong>Are you sure you want to delete this workout?</strong>
            </p>
        </AppModalConfirm>
    </section>
</template>

<script>
import AppModalConfirm from '@/components/AppModalConfirm';

export default {
    name: "Workout",
    components: {
        AppModalConfirm
    },
    beforeMount() {
        this.handleRouteChange();
    },
    data() {
        return {
            workout: null,
            isLoading: false,
            confirmDelete: false,
            isDeleting: false
        };
    },
    methods: {
        onClickDeleteConfirm() {
            this.isDeleting = true;
            this.$store.dispatch("deleteWorkout", this.workout.id).then(() => {
                // Redirect to dashboard after deleting
                this.isDeleting = false;
                this.confirmDelete = false;
                this.$router.push("/");
            })
            .catch(err => {
                this.isDeleting = false;
                this.confirmDelete = false;
                console.error(err);
            });
        },
        onClickDelete() {
            this.confirmDelete = true;
        },
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