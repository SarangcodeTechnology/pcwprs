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
                              color="grey lighten-3" >
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
                {title: 'ड्यासबोर्ड', icon: 'mdi-view-dashboard', route: '/dashboard',can: "dashboard-browse"},
                {title: 'गृहपृष्ठ', icon: 'mdi-home', route: '/home',can:"home-browse"},
                {title: 'सम्पादन अनुरोधहरु', icon: 'mdi-account-edit', route: '/edit-requests', can: "kaaryalaya-browse"},
                {title: 'लकहरु', icon: 'mdi-lock', route: '/locks',can:"lock-browse"},
                {
                    title: "खाताहरु", icon: "mdi-account-box-multiple", route: "/users", subItems: [
                        {title: 'प्रयोगकर्ताहरू', icon: 'mdi-account-circle', route: '/users',can:"users-browse"},
                        {
                            title: 'भूमिकाहरू',
                            icon: 'mdi-account-settings-outline',
                            route: '/roles',
                            can: "roles-browse"
                        },
                        {title: 'अनुमतिहरू', icon: 'mdi-key', route: '/permissions', can: "permissions-browse"},
                    ]
                },
                {
                    title: "फारम", icon: "mdi-note", subItems: [
                        {title: 'कृयाकलाप लक्ष', icon: 'mdi-folder', route: '/kriyakalap-lakshya',can:"kriyakalap_lakshya-browse"},
                        {title: 'मासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-maasik-pragati',can:"maasik_pragati_form-browse"},
                        {title: 'त्रैमासिक प्रगती', icon: 'mdi-folder', route: '/kriyakalap-traimaasik-pragati',can:"traimaasik_pragati_form-browse"},
                    ]
                },
                {
                    title: "प्रतिवेदन", icon: "mdi-file-document", subItems: [
                        {title: 'मासिक प्रगती प्रतिवेदन', icon: 'mdi-calendar-month-outline', route: '/kriyakalap-maasik-pragati-report',can:"maasik_pragati_report-browse"},
                        {title: 'त्रैमासिक प्रगती प्रतिवेदन', icon: 'mdi-file-chart', route: '/kriyakalap-traimaasik-pragati-report', can: "traimaasik_pragati_report-browse"},
                    ]
                },
                {
                    title: "संसाधनहरु", icon: "mdi-folder", subItems: [
                        {title: 'आर्थिक वर्ष', icon: 'mdi-calendar', route: '/aarthik-barsa',can:"aarthik_barsa-browse"},
                        {title: 'आयोजना', icon: 'mdi-folder', route: '/aayojana',can:"aayojana-browse"},
                        {title: 'कार्यलय', icon: 'mdi-folder', route: '/kaaryalaya', can: "kaaryalaya-browse"},
                    ]
                },
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

<style lang="scss" scoped>
.v-list-item {
    text-decoration: none;
}

.v-list-group {
    &--active{
        background: #0e360c;
        border-radius: 5px;
    }

}

</style>
