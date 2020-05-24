<template>
    <button :disabled="isLoading" @click="onClick">
        <template v-if="isLoading">
            <i class="fa fa-spinner fa-spin" aria-label="Loading" />
        </template>
        <template v-else>
            <slot></slot>
        </template>
    </button>
</template>

<script>
export default {
    name: "AppButton",
    props: {
        isLoading: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onClick(e) {
            this.$emit("click", e);
        }
    }
}
</script>
