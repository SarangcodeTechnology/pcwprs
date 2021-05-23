<template>
        <v-list
            dense
            nav
        >
            <div v-for="(item, i) in items" :key="i">
                <v-list-item
                    v-if="!item.subItems && checkPermission(item.can)"
                    :to="item.route"
                    color="white"
                    link
                    router
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title class="pt-1"
                        ><strong>{{ item.title }}</strong></v-list-item-title
                        >
                    </v-list-item-content>
                </v-list-item>
                <v-list-group v-if="item.subItems && checkPermissionForSubItems(item.subItems)" :prepend-icon="item.icon"
                              color="grey darken-4">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title class="pt-1"
                            ><strong>{{ item.title }}</strong></v-list-item-title
                            >
                        </v-list-item-content>
                    </template>
                    <v-list-item
                        v-for="(subItem, j) in item.subItems"
                        v-if="checkPermission(subItem.can)"
                        :key="j"
                        :to="subItem.route"
                        color="white"
                        link
                        router
                    >
                        <v-list-item-icon>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title class="pt-1"
                            ><strong>{{ subItem.title }}</strong></v-list-item-title
                            >
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
            </div>
        </v-list>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            miniVariant: false,
            items: [
                {title: 'ड्यासबोर्ड', icon: 'mdi-view-dashboard', route: '/dashboard',can: "browse_dashboard"},
                {title: 'गृहपृष्ठ', icon: 'mdi-home', route: '/home',can:"browse_home"},
                {
                    title: "खाताहरु", icon: "mdi-account-circle", route: "/users", subItems: [
                        {title: 'प्रयोगकर्ताहरू', icon: 'mdi-account-circle', route: '/users',can:"browse_users"},
                        {
                            title: 'भूमिकाहरू',
                            icon: 'mdi-account-settings-outline',
                            route: '/roles',
                            can: "browse_roles"
                        },
                        {title: 'अनुमतिहरू', icon: 'mdi-key', route: '/permissions', can: "browse_permissions"},
                    ]
                },
                {title: 'कार्यलय', icon: 'mdi-folder', route: '/kaaryalaya', can: "browse_kaaryalaya"},
                {title: 'आर्थिक वर्ष', icon: 'mdi-calendar', route: '/aarthik-barsa',can:"browse_aarthik_barsa"},
                {title: 'आयोजना', icon: 'mdi-folder', route: '/aayojana',can:"browse_aayojana"},
                {title: 'कृयाकलाप लक्ष', icon: 'mdi-folder', route: '/kriyakalap-lakshya',can:"browse_kriyakalap_lakshya"},
                {title: 'कृयाकलाप मासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-maasik-pragati',can:"browse_kriyakalap_maasik_pragati"},
                {title: 'कृयाकलाप त्रैमासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-traimaasik-pragati',can:"browse_kriyakalap_traimaasik_pragati"},
            ],
        }
    },
    computed: {
        ...mapState({
            userPermissions: (state) => state.webservice.resources.userPermissions
        })
    },
    methods: {
        setMiniVariant() {
            var tempthis = this;
            this.miniVariant = !this.miniVariant;
            this.$store.dispatch("setMiniVariant", tempthis.miniVariant);
        },
        checkPermission(can) {
            return this.userPermissions.includes(can);
        },
        checkPermissionForSubItems(subItems) {
            var count = 0;
            let tempthis = this;
            subItems.forEach(function (item) {
                if (tempthis.checkPermission(item.can)) {
                    count++;
                }
            });
            if (count === 0) {
                return false;
            }
            return true;
        }
    }
}
</script>

<style scoped>
.v-list-item {
    text-decoration: none;
}
</style>
