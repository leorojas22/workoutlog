<template>
    <div id="app">
        <header>
            <h1>
                Workout Log
            </h1>
        </header>
        <router-view></router-view>
    </div>
</template>

<script>

export default {
    name: 'App',
    components: {

    }
}
</script>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Lato', arial, sans-serif;
        background-image: url('/assets/images/bg.png');
        color: white;
        font-size: 18px;
    }

    a {
        color: #00a2ff;
        text-decoration: none;
    }

    a:hover {
        color: #008cdd;
        text-decoration: underline;
    }

    p {
        margin: 5px 0px;
    }

    hr {
        border: 0px;
        border-top: solid #444444 1px;
        margin: 20px 0px;
    }

    header {
        text-align: center;
    }

    h2 {
        margin-top: 0px;
    }

    header h1 {
        font-size: 2.67em;
    }

    section {
        position: relative;
        margin-left: auto;
        margin-right: auto;
        width: 95%;
        max-width: 720px;
        border: solid #444444 1px;
        border-radius: 4px;
        padding: 15px;
        box-shadow: 0px 0px 10px 5px rgba(0,0,0,.25);
        background-color: rgba(0,0,0,.3);
    }

    input, textarea {
        font-family: 'Lato', arial, sans-serif;
        border: 0px;
        padding: 10px;
        background-color: white;
        border-radius: 4px;
        outline: none;
        font-size: 1.5em;
        box-shadow: 0px 0px 15px 5px rgba(255,255,255,.2);
        width: 100%;
    }

    input.input-sm, textarea {
        font-size: 1.25em;
    }

    textarea {
        background-color: rgba(0,0,0,.1);
        color: white;
        box-shadow: 0px 0px 10px 0px rgba(255,255,255,.1);
        border: inset rgba(0,0,0,.1) 1px;
    }

    .form-group {
        display: block;
        padding-bottom: 15px;
    }

    label {
        display: block;
        padding-bottom: 8px;
        font-weight: bold;
    }

    button {
        font-family: 'Lato', arial, sans-serif;
        padding: 20px 15px;
        width: 100%;
        border: 0px;
        outline: none;
        border-radius: 4px;
        font-size: 1.75em;
        font-weight: bold;
        color: white;
        cursor: pointer;
        transition: background-color .2s;
    }

    button:disabled {
        cursor: not-allowed;
        opacity: .75;
    }

    button.btn-close {
        width: auto;
        color: #cfcfcf;
        padding: 0px;
        background-color: transparent;
        transition: color .2s;
    }

    button.btn-close:hover {
        color: white;
    }

    button.btn-sm {
        padding: 10px 15px;
        font-size: 1.25em;
    }

    button.btn-main {
        background-color: #00a2ff;
        box-shadow: 0px 0px 15px 0px rgba(0, 162, 255, .5);
    }

    button.btn-main:hover {
        background-color: #008cdd;
    }

    button.btn-default {
        background-color: #cfcfcf;
        color: #1d1d1d;
        box-shadow: 0px 0px 15px 0px rgba(207, 207, 207, .25);
    }

    button.btn-default:hover {
        background-color: #bebdbd;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    ul {
        list-style-type: none;
        margin: 0px;
        padding: 0px;
    }

    ul li {
        background-color: #404040;
        padding: 15px;
        color: #cfcfcf;
        font-size: 1.333em;
        border-radius: 4px;
        box-shadow: 0px 0px 5px 5px rgba(0,0,0,.1);
        margin: 12px 0px;
        transition: background-color .2s, color .2s;
    }

    ul.interactive-list li:hover {
        background-color: #515151;
        color: white;
        cursor: pointer;
    }

    ul li hr {
        border-top-color: #5d5d5d;
    }

    .form-group .help-text {
        display: block;
        padding-bottom: 10px;
        color: #cfcfcf;
    }

    .notification {
        position: relative;
        padding: 10px;
        border-radius: 4px;
        border: solid #444444 1px;
        margin-bottom: 20px;
    }

    .notification.error {
        border-color: red;
        background-color: rgba(255, 0, 0, .25);
        color: white;
    }
</style>
