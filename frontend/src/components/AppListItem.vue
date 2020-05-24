<template>
    <li @click="onClick">
        <slot></slot>
    </li>
</template>

<script>
export default {
    name: "AppListItem",
    methods: {
        onClick() {
            this.$emit("click");
        }
    }
}
</script>
