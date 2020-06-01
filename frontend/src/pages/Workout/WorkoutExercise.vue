<template>
    <section class="app-workout-exercise-page container" v-if="!isLoading">
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

        <div class="set-totals-wrapper" v-if="setTotals > 0">
            <hr />
            <p><strong>Totals</strong></p>
            <AppList>
                <AppListItem>
                    {{ workoutExercise.exercise.sumStat }}: {{ setTotals }}
                </AppListItem>
            </AppList>
        </div>

        <div class="form-group">
            <hr />
            <div v-if="!isSaving">
                <div class="form-group" v-if="selectedExercise">
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
            <AppLoadingMessage v-else />
        </div>

        <div class="form-group" v-if="workoutExerciseHistory.length > 0">
            <p>
                <strong>Exercise History</strong>
            </p>
            <AppList :interactive="true">
                <AppListItemPastWorkout
                    v-for="(history, index) in workoutExerciseHistory"
                    :key="index"
                    :workout="history.workout"
                    @click="onClickPastWorkout(history)"
                />
            </AppList>
        </div>

        <div
            v-if="workoutExercise"
            class="form-group text-right"
        >
            <hr />
            <AppButton
                class="btn-danger btn-inline btn-xs"
                @click="onClickDelete"
            >
                <i class="fas fa-trash-alt" aria-label="Delete"></i>
            </AppButton>
        </div>

        <AppModal
            :isVisible="selectedExercise === 0"
            v-if="selectedExercise === 0"
            @close="onCloseAddNewExercise"
        >
            <p class="text-center">
                <strong>Add New Exercise</strong>
            </p>
            <hr />
            <AppFormManageExercise
                @savedExercise="onSelectExercise"
            />
        </AppModal>

        <AppModal
            :isVisible="selectedWorkoutExerciseHistory ? true : false"
            v-if="selectedWorkoutExerciseHistory"
            @close="onCloseWorkoutExerciseHistory"
        >
            <p class="text-center">
                <strong>
                    {{ selectedWorkoutExerciseHistory.exercise.name }}
                </strong>
            </p>
            <AppList>
                <AppListItemPastWorkout
                    :workout="selectedWorkoutExerciseHistory.workout"
                />
                <AppListItemWorkoutSet
                    v-for="(workoutExerciseSet, index) in selectedWorkoutExerciseHistory.workoutExerciseSets"
                    :key="'workout_set_list_item_' + index"
                    :workoutExerciseSet="workoutExerciseSet"
                    :setNumber="index + 1"
                />
            </AppList>
            <div class="set-totals-wrapper" v-if="selectedWorkoutExerciseHistory.setTotals > 0">
                <hr />
                <p><strong>Totals</strong></p>
                <AppList>
                    <AppListItem>
                        {{ selectedWorkoutExerciseHistory.exercise.sumStat }}: {{ selectedWorkoutExerciseHistory.setTotals }}
                    </AppListItem>
                </AppList>
            </div>
            <hr />
            <AppButton
                class="btn-default btn-sm"
                @click="onCloseWorkoutExerciseHistory"
            >
                Go Back
            </AppButton>
        </AppModal>

        <AppModalConfirm
            v-if="confirmDelete"
            :isVisible="confirmDelete"
            :isLoading="isDeleting"
            @close="confirmDelete = false"
            @confirmed="onConfirmDelete"
        >
            <p class="text-center">
                <strong>Are you sure you want to delete this exercise from the workout?</strong>
            </p>
        </AppModalConfirm>
    </section>
</template>

<script>
import AppSelect from '@/components/AppSelect';
import AppModal from '@/components/AppModal';
import AppModalConfirm from '@/components/AppModalConfirm';
import AppFormManageExercise from '@/components/AppFormManageExercise';
import AppListItemWorkoutSet from '@/components/WorkoutExercise/AppListItemWorkoutSet';
import AppListItemPastWorkout from '@/components/Dashboard/AppListItemPastWorkout';

export default {
    name: "WorkoutExercise",
    components: {
        AppSelect,
        AppModal,
        AppFormManageExercise,
        AppListItemWorkoutSet,
        AppListItemPastWorkout,
        AppModalConfirm
    },
    beforeMount() {
        this.handleRouteChange();
    },
    data() {
        return {
            selectedExercise: "",
            workoutExercise: null,
            workoutExerciseSets: [],
            isLoading: false,
            isSaving: false,
            workoutExerciseHistory: [],
            selectedWorkoutExerciseHistory: null,
            confirmDelete: false,
            isDeleting: false
        };
    },
    methods: {
        onConfirmDelete() {
            if(!this.workoutExercise)
            {
                return false;
            }

            this.isDeleting = true;
            this.$store.dispatch("deleteWorkoutExercise", this.workoutExercise.id).then(() => {
                // Reset deleting flag
                this.isDeleting = false;

                // Redirect back to the workout page
                this.$router.push("/workout/" + this.workoutExercise.workout.id);
            })
            .catch(err => {
                console.error(err);
                this.isDeleting = false;
            });
        },
        onClickDelete() {
            this.confirmDelete = true;
        },
        onCloseWorkoutExerciseHistory() {
            this.selectedWorkoutExerciseHistory = null;
        },
        onClickPastWorkout(workoutExercise) {
            this.selectedWorkoutExerciseHistory = workoutExercise;
        },
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

            // Load workout exercise history
            let workoutExerciseHistory = this.$store.getters.getWorkoutExerciseHistoryById(this.workoutExerciseId);
            let workoutHistoryPromise = Promise.resolve();
            if(workoutExerciseHistory === undefined)
            {
                workoutHistoryPromise = this.$store.dispatch("loadWorkoutExerciseHistoryById", this.workoutExerciseId).then(history => {
                    this.workoutExerciseHistory = history;
                });
            }
            else
            {
                this.workoutExerciseHistory = workoutExerciseHistory;
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

            this.selectedExercise = parseInt(exerciseId);
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
        workoutExerciseId() {
            return !isNaN(this.$route.params.workoutExerciseId) ? parseInt(this.$route.params.workoutExerciseId) : this.$route.params.workoutExerciseId;
        },
        setTotals() {
            if(!this.workoutExercise)
            {
                // No exercise found
                return 0;
            }

            if(this.workoutExerciseSets.length === 0)
            {
                // No sets stored yet
                return 0;
            }

            if(!this.workoutExercise.exercise.sumStat)
            {
                // No stat to get total for
                return 0;
            }

            let sumStat = this.workoutExercise.exercise.sumStat;
            let sets = this.workoutExerciseSets;
            let total = 0;

            // Sum up the set totals
            sets.forEach(exerciseSet => {
                if(!exerciseSet.stats)
                {
                    return;
                }

                exerciseSet.stats.forEach(stat => {
                    if(stat[sumStat] !== undefined)
                    {
                        total += parseFloat(stat[sumStat]);
                    }
                });
            });

            return total;
        },
        exerciseOptions() {
            const exercises = this.$store.state.exercise.exercises;
            let exerciseOptions = [];

            exercises.forEach(exercise => {
                exerciseOptions.push({
                    value: exercise.id,
                    text: exercise.name
                });
            });

            exerciseOptions.push({
                value: 0,
                text: "Add New Exercise"
            });

            return exerciseOptions;
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