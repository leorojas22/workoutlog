<template>
    <section class="app-workout-exercise-set-page" v-if="!isLoading">
        <h2 class="text-center">
            {{ id ? "Edit" : "Add" }} Set
        </h2>
        <p class="text-center">
            <strong>{{ exercise.name }}</strong>
        </p>
        <form @submit="onSaveSet">
            <div class="form-group">
                <label for="statNotes">
                    Stats
                </label>
                <small class="help-text">
                    Template: {{ exercise.statTemplate }}
                </small>
                <textarea rows="3" id="statNotes" v-model="statNotes" name="statNotes" />
            </div>
            <div class="form-group">
                <label for="notes">
                    Notes
                </label>
                <textarea rows="3" id="notes" v-model="notes" name="notes" />
            </div>
            <div class="form-group">
                <AppButton class="btn-main" type="submit">
                    Save
                </AppButton>
            </div>
            <div class="form-group">
                <AppButton class="btn-default" type="button" @click="onClickCancel">
                    Cancel
                </AppButton>
            </div>
        </form>
    </section>
</template>

<script>
export default {
    name: "WorkoutExerciseSet",
    data() {
        return {
            id: 0,
            statNotes: "",
            notes: "",
            exercise: { name: "", statTemplate: "" },
            isLoading: false
        };
    },
    beforeMount() {
        this.handleRouteChange();
    },
    methods: {
        onSaveSet(e) {
            e.preventDefault();

        },
        onClickCancel() {
            // Go back to the main workout exercise page
            this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + this.$route.params.workoutExerciseId);
        },
        handleRouteChange() {
            if(this.workoutExerciseSetId === undefined)
            {
                // Load the exercise
                return this.loadExercise();
            }

            // @todo Existing workout exercise set - load it if needed
        },
        loadExercise() {
            let workoutExercise = this.$store.getters.getWorkoutExerciseById(this.$route.params.workoutExerciseId);
            if(workoutExercise !== undefined)
            {
                // Already in store, don't need to make api call
                this.exercise = workoutExercise.exercise;
                return;
            }

            // Load from api
            this.isLoading = true;
            this.$store.dispatch("loadWorkoutExercise", this.$route.params.workoutExerciseId).then(workoutExercise => {
                this.exercise = workoutExercise.exercise;
                this.isLoading = false;
            })
            .catch(err => {
                console.error(err);
                this.isLoading = false;
            });
        }
    },
    computed: {
        workoutExerciseSetId() {
            return this.$route.params.workoutExerciseSetId;
        }
    }
}
</script>
