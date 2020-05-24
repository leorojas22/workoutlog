<template>
    <section class="app-workout-exercise-page" v-if="!isLoading">
        <div class="form-group">
            <AppSelect
                :options="exerciseOptions"
                :alwaysShowOptionValue="0"
                v-model="selectedExercise"
                placeholder="Select Exercise"
                @input="onSelectExercise"
                :disabled="isSaving"
            />
        </div>

        <div class="set-wrapper" v-if="workoutExerciseSets.length > 0">
            <p>
                <strong>Sets</strong>
            </p>
            <AppList :interactive="true">
                <AppListItemWorkoutSet
                    v-for="(workoutExerciseSet, index) in workoutExerciseSets"
                    :key="'workout_set_list_item_' + index"
                    :workoutExerciseSet="workoutExerciseSet"
                    :setNumber="index + 1"
                    @click="onClickEditSet(workoutExerciseSet)"
                />
            </AppList>
        </div>

        <div class="set-totals-wrapper" v-if="statTotals.length > 0">
            <hr />
            <p><strong>Totals</strong></p>
            <AppList>
                <AppListItem v-for="(setTotal, index) in statTotals" :key="index">
                    {{ setTotal }}
                </AppListItem>
            </AppList>
        </div>

        <div class="form-group" v-if="selectedExercise">
            <hr />
            <div v-if="!isSaving">
                <div class="form-group">
                <AppButton
                        class="btn-main"
                        type="button"
                        @click="onClickAddSet"
                    >
                        Add Set
                    </AppButton>
                </div>
                <div class="form-group">
                    <AppButton
                        class="btn-default"
                        type="button"
                        @click="onClickBack"
                    >
                        Go Back
                    </AppButton>
                </div>
            </div>
            <p v-else class="text-center">
                <i class="fa fa-spinner fa-spin" aria-label="Loading" />
            </p>

        </div>

        <AppModal v-if="selectedExercise === 0" @close="onCloseAddNewExercise">
            <p class="text-center">
                <strong>Add New Exercise</strong>
            </p>
            <hr />
            <AppFormManageExercise />
        </AppModal>

    </section>
</template>

<script>
import AppSelect from '@/components/AppSelect';
import AppModal from '@/components/AppModal';
import AppFormManageExercise from '@/components/AppFormManageExercise';
import AppListItemWorkoutSet from '@/components/WorkoutExercise/AppListItemWorkoutSet';

export default {
    name: "WorkoutExercise",
    components: {
        AppSelect,
        AppModal,
        AppFormManageExercise,
        AppListItemWorkoutSet
    },
    beforeMount() {
        this.handleRouteChange();
    },
    data() {
        return {
            exerciseOptions: [
                {
                    value: 1,
                    text: "pullups"
                },
                {
                    value: 4,
                    text: "pushups"
                },
                {
                    value: 0,
                    text: "Add New Exercise"
                }
            ],
            selectedExercise: "",
            workoutExercise: null,
            workoutExerciseSets: [],
            isLoading: false,
            isSaving: false
        };
    },
    methods: {
        onClickBack() {
            this.$router.push("/workout/" + this.$route.params.workoutId);
        },
        handleRouteChange() {
            if(this.workoutExerciseId === undefined)
            {
                // Don't need to load anything if it's a new exercise
                this.selectedExercise    = "";
                this.workoutExerciseSets = [];
                this.workoutExercise     = null;
                return;
            }

            let workoutExercise = this.$store.getters.getWorkoutExerciseById(this.workoutExerciseId);

            if(workoutExercise !== undefined)
            {
                // Found already loaded version - don't need to call api
                this.workoutExercise = workoutExercise;
                this.selectedExercise = workoutExercise.exercise.id;
                this.workoutExerciseSets = workoutExercise.workoutExerciseSets;
                return;
            }

            // Need to load from api
            this.isLoading = true;
            this.$store.dispatch("loadWorkoutExerciseById", this.workoutExerciseId).then(workoutExercise => {
                this.workoutExercise     = workoutExercise;
                this.selectedExercise    = workoutExercise.exercise.id;
                this.workoutExerciseSets = workoutExercise.workoutExerciseSets;
                this.isLoading = false;
            })
            .catch(err => {
                console.error(err);
                this.isLoading = false;
            });
        },
        onSelectExercise(exerciseId) {

            if(!exerciseId)
            {
                // Don't do anything if adding a new exercise
                return;
            }


            if(this.workoutExerciseId === undefined)
            {
                // Need to create new workout exercise
                this.isSaving = true;
                this.$store.dispatch("createWorkoutExercise", {
                    workoutId: this.$route.params.workoutId,
                    exerciseId: exerciseId
                }).then(workoutExercise => {
                    this.isSaving = false;
                    this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + workoutExercise.id);
                });
            }
            else if(this.workoutExercise.exercise.id !== exerciseId)
            {
                // Need to update the exercise

                // Set the exercise id for now to semi-update the view
                //this.workoutExercise.exercise.id = exerciseId;
                this.isSaving = true;
                this.$store.dispatch("updateWorkoutExercise", {
                    id: this.workoutExerciseId,
                    exerciseId
                }).then(workoutExercise => {
                    this.isSaving = false;
                    this.handleRouteChange();
                })
                .catch(err => {
                    this.isSaving = false;
                });
            }

        },
        onCloseAddNewExercise() {
            // Closing add new exercise, need to clear out the selected exercise
            this.selectedExercise = "";
        },
        onClickAddSet() {
            this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + this.workoutExerciseId + "/set");
        },
        onClickEditSet(workoutExerciseSet) {
            this.$router.push("/workout/" + this.$route.params.workoutId + "/exercise/" + this.workoutExerciseId + "/set/" + workoutExerciseSet.id);
        }
    },
    computed: {
        exerciseHasStatTemplate() {
            // @todo
            return true;
        },
        statTotals() {
            return [];
        },
        workoutExerciseId() {
            return parseInt(this.$route.params.workoutExerciseId);
        }
    },
    watch: {
        $route(to, from) {
            this.handleRouteChange();
        }
    }
}
</script>

<style scoped>
    .app-workout-exercise-page .set-details-wrapper {
        display: flex;
        flex-direction: row;
    }

    .app-workout-exercise-page .set-details-wrapper .set-stats {
        padding-left: 10px;
    }
</style>