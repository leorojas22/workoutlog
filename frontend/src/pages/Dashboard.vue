<template>
    <section class="app-dashboard-page">
        <AppButton class="btn-main" type="button" :isLoading="isStartingNewWorkout" @click="onStartNewWorkoutClick">
            Start New Workout
        </AppButton>
        <hr />
        <p>
            Past Workouts
        </p>
        <AppList :interactive="true">
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
            <AppListItemPastWorkout
                :workoutId="1"
                :date="new Date()"
            />
        </AppList>
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
    data() {
        return {
            isStartingNewWorkout: false
        };
    },
    methods: {
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
    }
}
</script>
