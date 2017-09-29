<template>
    <div>
        <div id="myNav" class="overlay" v-if="show">
            <div class="loader"></div>
        </div>
    </div>
</template>
<style scoped>
.overlay {
    height: 100%;
    width: 100%;
    position: fixed;
    z-index: 1100;
    top: 0;
    left: 0;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.9);
    overflow-x: hidden;
    transition: 0.5s;
}

.loader {
    border: 8px solid #f3f3f3;
    border-radius: 50%;
    border-top: 8px solid #3498db;
    width: 50px;
    height: 50px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;

    position: absolute;
    top: 50%;
    left: 50%;
}

@-webkit-keyframes spin {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    }
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
<script>
export default {
    data() {
        return {
            image: ''
        }
    },
    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },
    methods: {

    }
}
</script>