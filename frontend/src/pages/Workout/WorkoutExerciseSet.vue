<template>
    <section class="app-workout-exercise-set-page" v-if="!isLoading">
        <h2 class="text-center">
            {{ id ? "Edit" : "Add" }} Set #{{ setNumber }}
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
                <AppButton class="btn-main" type="submit" :isLoading="isSaving">
                    Save
                </AppButton>
            </div>
            <div class="form-group">
                <AppButton class="btn-default" type="button" @click="onClickCancel" :disabled="isSaving">
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
            isLoading: false,
            isSaving: false,
            setNumber: 1
        };
    },
    beforeMount() {
        this.handleRouteChange();
    },
    methods: {
        onSaveSet(e) {
            e.preventDefault();
            this.isSaving = true;
            this.$store.dispatch("saveWorkoutExerciseSet", {
                workoutExerciseId: this.$route.params.workoutExerciseId,
                statNotes: this.statNotes,
                notes: this.notes,
                id: this.id
            })
            .then(response => {
                this.isSaving = false
                this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + this.$route.params.workoutExerciseId);
            })
            .catch(err => {
                console.error(err);
            });
        },
        onClickCancel() {
            // Go back to the main workout exercise page
            this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + this.$route.params.workoutExerciseId);
        },
        handleRouteChange() {
            this.loadExercise().then(workoutExercise => {
                if(this.workoutExerciseSetId === undefined)
                {
                    // Dont need to do anything further when adding a new set
                    this.id = 0;
                    this.setNumber = workoutExercise.workoutExerciseSets.length + 1;
                    return;
                }

                // Find the set
                let setIndex = workoutExercise.workoutExerciseSets.findIndex(workoutSet => {
                    return parseInt(workoutSet.id) === parseInt(this.$route.params.workoutExerciseSetId);
                });

                if(setIndex === -1)
                {
                    // This should not happen since the workout exercise should be fully loaded
                    console.log(this.$route.params.workoutExerciseSetId);
                    throw new Error("Unable to find workout set info.");
                }

                let setInfo = workoutExercise.workoutExerciseSets[setIndex];

                this.id = setInfo.id;
                this.statNotes = setInfo.statNotes;
                this.notes = setInfo.notes;
                this.setNumber = setIndex + 1;
            });

            // Existing workout exercise set - load it if needed
        },
        loadExercise() {
            let workoutExercise = this.$store.getters.getWorkoutExerciseById(this.$route.params.workoutExerciseId);
            if(workoutExercise !== undefined)
            {
                // Already in store, don't need to make api call
                this.exercise = workoutExercise.exercise;
                return Promise.resolve(workoutExercise);
            }

            // Load from api
            this.isLoading = true;
            return this.$store.dispatch("loadWorkoutExerciseById", this.$route.params.workoutExerciseId).then(workoutExercise => {
                this.exercise = workoutExercise.exercise;
                this.isLoading = false;
                return workoutExercise;
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
