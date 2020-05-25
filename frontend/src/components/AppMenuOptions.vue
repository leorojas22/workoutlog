<template>
    <div class="app-menu-options-component container">
        <router-link to="/" class="menu-option" title="Home">
            <i class="fas fa-home" arial-label="Home" />
        </router-link>
        <router-link to="/exercises" class="menu-option" title="Manage Exercises">
            <i class="fas fa-dumbbell" arial-label="Manage Exercises" />
        </router-link>
        <div class="menu-option" title="Stopwatch">
            <i class="fas fa-stopwatch" arial-label="Stopwatch" />
        </div>
        <div class="menu-option" title="Logout">
            <i class="fas fa-sign-out-alt" arial-label="Logout" />
        </div>
    </div>
</template>

<style scoped>
    .app-menu-options-component {
        display: flex;
        margin-top: 10px;
        margin-bottom: 20px;
        justify-content: center;
        align-items: center;
    }

    .app-menu-options-component .menu-option {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 100px;
        background-color: rgba(0,0,0,.15);
        width: 50px;
        height: 50px;
        margin: 0px 10px;
        box-shadow: 0px 0px 5px 0px rgba(255,255,255,.3);
        cursor: pointer;
        color: white;
        text-decoration: none;
        transition: box-shadow .2s ease-in-out, background-color .2s, color .2s;
    }

    .app-menu-options-component .menu-option:hover {
        box-shadow: 0px 0px 5px 0px rgba(255,255,255,.5);
        background-color: rgba(0,0,0,.05);
    }
</style>