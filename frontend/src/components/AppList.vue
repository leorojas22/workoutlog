<template>
    <ul :class="{ 'interactive-list': interactive }">
        <slot></slot>
    </ul>
</template>

<script>
export default {
    name: "AppList",
    props: {
        interactive: {
            type: Boolean,
            default: false
        }
    }
}
</script>
