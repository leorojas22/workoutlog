<template>
    <div class="text-center app-loading-message-component">
        <slot></slot>
        <i class="fa fa-spinner fa-spin" aria-label="Loading" />
    </div>
</template>

<style scoped>
    .app-loading-message-component {
        margin: 20px 0px;
    }
</style>
