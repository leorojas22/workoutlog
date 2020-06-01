<template>
    <AppModal
        class="app-stopwatch-modal"
        :isVisible="isVisible"
        :showCloseButton="false"
    >
        <section class="app-stopwatch-component container">
            <div class="clock" :class="clockClass">
                {{ formattedClock }}
            </div>

            <div class="stopwatch-actions">
                <div class="stopwatch-action" @click="onClickResetStopwatch">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stopwatch-action" :class="{'green': !timerInterval, 'red': timerInterval }" @click="onClickToggleStopwatch">
                    <i class="fas fa-play" v-if="!timerInterval"></i>
                    <i class="fas fa-pause" v-else></i>
                </div>
            </div>
            <hr />
            <div class="form-group">
                <AppButton class="btn-default btn-sm" @click="onClickClose">
                    Close
                </AppButton>
            </div>
        </section>
    </AppModal>
</template>

<script>
import AppModal from '@/components/AppModal';
const oneMinute = 60000;
const oneSecond = 1000;
export default {
    name: "Stopwatch",
    components: {
        AppModal
    },
    props: {
        isVisible: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            timerInterval: null,
            counter: 0
        };
    },
    computed: {
        clockClass() {
            let oneHundredMinutes = oneMinute * 100;
            if(this.counter >= oneHundredMinutes)
            {
                return "text-small";
            }

            return "";
        },
        formattedClock() {
            let minutes = 0;
            let seconds = 0;
            let milliseconds = 0;
            let remainingMilliseconds = this.counter;

            if(remainingMilliseconds >= oneMinute)
            {
                minutes = Math.floor(remainingMilliseconds / oneMinute);
                remainingMilliseconds -= minutes * oneMinute;
            }

            if(remainingMilliseconds >= oneSecond)
            {
                seconds = Math.floor(remainingMilliseconds / oneSecond);
                remainingMilliseconds -= seconds * oneSecond;
            }

            if(remainingMilliseconds >= 100)
            {
                remainingMilliseconds = Math.floor(remainingMilliseconds / 10);
            }

            minutes = ("" + minutes).padStart(2, "0");
            seconds = ("" + seconds).padStart(2, "0");



            remainingMilliseconds = ("" + remainingMilliseconds).padStart(2, "0");
            return minutes + ":" + seconds + ":" + remainingMilliseconds;
        }
    },
    methods: {
        onClickToggleStopwatch() {
            if(!this.timerInterval)
            {
                this.timerInterval = window.setInterval(() => {
                    this.counter += 25;
                }, 25);
                return;
            }

            window.clearInterval(this.timerInterval);
            this.timerInterval = null;
        },
        onClickResetStopwatch() {
            if(this.timerInterval)
            {
                window.clearInterval(this.timerInterval);
                this.timerInterval = null;
            }

            this.counter = 0;
        },
        onClickClose() {
            this.$emit("close", true);
        }
    }

}
</script>

<style scoped>
    .app-stopwatch-modal {
        display: flex;
        align-items: center;
    }

    .app-stopwatch-component {
        font-size: 32px;
    }

    .app-stopwatch-component .clock {
        font-size: 3em;
        text-align: center;
    }

    .app-stopwatch-component .stopwatch-actions {
        display: flex;
        width: 100%;
        justify-content: space-between;
        padding: 10% 5%;
    }

    .app-stopwatch-component .stopwatch-actions .stopwatch-action {
        display: flex;
        text-align: center;
        justify-content: center;
        align-items: center;
        width: 100px;
        height: 100px;
        border-radius: 100px;
        background-color: rgba(0,0,0,.25);
        border: solid rgba(0,0,0,.3) 3px;
        box-shadow: 0px 0px 0px 3px rgba(0,0,0,.25);
    }

    .app-stopwatch-component .stopwatch-actions .stopwatch-action.green {
        color: rgba(17,204,0,.75);
        background-color: rgba(17,204,0,.15);
        box-shadow: 0px 0px 0px 3px rgba(17,204,0,.15);
    }

    .app-stopwatch-component .stopwatch-actions .stopwatch-action.red {
        color: rgba(255,0,0,.75);
        background-color: rgba(255,0,0,.15);
        box-shadow: 0px 0px 0px 3px rgba(255,0,0,.15);
    }

    @media (max-width: 768px) {
        .app-stopwatch-component .clock {
            font-size: 2em;
            text-align: center;
        }

        .app-stopwatch-component .clock.text-small {
            font-size: 1.75em;
        }
    }
</style>