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
                {title: 'Roles', icon: 'mdi-account-settings-outline', route: '/roles'},
                {title: 'Permissions', icon: 'mdi-key', route: '/permissions'},
                {title: 'कार्यलय', icon: 'mdi-folder', route: '/kaaryalaya'},
                {title: 'आर्थिक वर्ष', icon: 'mdi-calendar', route: '/aarthik-barsa'},
                {title: 'आयोजना', icon: 'mdi-folder', route: '/aayojana'},
                {title: 'कृयाकलाप लक्ष', icon: 'mdi-folder', route: '/kriyakalap-lakshya'},
                {title: 'कृयाकलाप मासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-maasik-pragati'},
                {title: 'कृयाकलाप त्रैमासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-traimaasik-pragati'},
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
