<template>
        <v-list
            dense
            nav
        >
            <v-list-item
                v-for="item in items"
                :key="item.title"
                link
                :to="item.route"
                router
            >
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
</template>

<script>
export default {
    data() {
        return {
            miniVariant: false,
            items: [
                {title: 'Dashboard', icon: 'mdi-view-dashboard', route: '/dashboard'},
                {title: 'Home', icon: 'mdi-home', route: '/home'},
                {title: 'Users', icon: 'mdi-account-circle', route: '/users'},
                {title: 'Roles', icon: 'mdi-key', route: '/roles'},
            ],
        }
    },
    methods: {
        setMiniVariant() {
            var tempthis = this;
            this.miniVariant = !this.miniVariant;
            this.$store.dispatch("setMiniVariant", tempthis.miniVariant);
        }
    }
}
</script>

<style scoped>
.v-list-item {
    text-decoration: none;
}
</style>
