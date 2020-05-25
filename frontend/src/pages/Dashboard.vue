<template>
    <section class="app-dashboard-page container">
        <AppButton class="btn-main" type="button" :isLoading="isStartingNewWorkout" @click="onStartNewWorkoutClick">
            Start New Workout
        </AppButton>
        <hr />
        <p>
            Past Workouts
        </p>
        <AppList :interactive="true">
            <AppListItemPastWorkout
                v-for="(workout, index) in workouts"
                :key="index"
                :workout="workout"
                @click="onClickExistingWorkout"
            />
        </AppList>
        <AppLoadingMessage v-if="isLoadingWorkouts" />
    </section>
</template>

<script>
import AppListItemPastWorkout from '@/components/Dashboard/AppListItemPastWorkout';
import AppList from '@/components/AppList';

export default {
    name: "Dashboard",
    components: {
        AppListItemPastWorkout,
        AppList
    },
    beforeMount() {
        // Load previous workouts
        this.loadMoreWorkouts();
    },
    data() {
        return {
            isStartingNewWorkout: false,
            pageNumber: 0,
            nextPageNumber: 1,
            isLoadingWorkouts: false
        };
    },
    methods: {
        onClickExistingWorkout(workoutId) {
            this.$router.push("/workout/" + workoutId);
        },
        loadMoreWorkouts() {

            if(this.nextPageNumber === null)
            {
                return;
            }

            this.isLoadingWorkouts = true;
            this.$store.dispatch("getWorkoutCollection", this.nextPageNumber).then(response => {
                this.nextPageNumber = response.pagination.nextPage;
                this.pageNumber     = response.pagination.currentPage;
                this.isLoadingWorkouts = false;
            })
            .catch(err => {
                console.error(err);
                this.isLoadingWorkouts = false;
            });
        },
        onStartNewWorkoutClick(e) {
            e.preventDefault();

            console.log("test?");
            // Create new workout
            this.isStartingNewWorkout = true;
            this.$store.dispatch("createWorkout").then((workout) => {
                this.$router.push("/workout/" + workout.id);
                this.isStartingNewWorkout = false;
            })
            .catch(err => {
                console.error(err);
                this.isStartingNewWorkout = false;
            })
        }
    },
    computed: {
        workouts() {
            return this.$store.state.workouts;
        }
    }
}
</script>
