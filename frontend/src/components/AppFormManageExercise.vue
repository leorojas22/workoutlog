<template>
    <form>
        <div class="form-group">
            <label for="exerciseName">
                Name
            </label>
            <input type="text" id="exerciseName" v-model="name" name="exerciseName">
        </div>
        <div class="form-group">
            <label for="statTemplate">
                Stat Template
            </label>
            <input type="text" id="statTemplate" v-model="statTemplate" name="statTemplate">
        </div>
        <div class="form-group">
            <label for="sumStat">
                Stat to SUM
            </label>
            <input type="text" id="sumStat" v-model="sumStat" name="sumStat">
        </div>
        <div class="form-group">
            <button class="btn-main" type="button">
                Save
            </button>
        </div>
    </form>
</template>

<script>
export default {
    name: "AppFormManageExercise",
    data() {
        return {
            name: "",
            statTemplate: "",
            sumStat: ""
        };
    }
}
</script>