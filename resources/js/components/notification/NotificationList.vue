<template>
    <v-container class="notification-list">
        <notification-message v-for="notification in notifications" :key="notification.id"
                              :notification="notification"/>
    </v-container>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {

        }
    },
    computed: {
        ...mapState({notifications: state => state.notifications}),
    }
}
</script>

<style>
.notification-list {
    position: fixed;
    bottom: 0;
    right: 0;
    margin-right: 15px;
    width: 320px;
    z-index: 2000;
}
</style>
