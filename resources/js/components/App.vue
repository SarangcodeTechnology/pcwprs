<template>
    <v-app>
        <v-navigation-drawer v-model="expandDrawer" :expand-on-hover="expandOnHover" :mini-variant="miniVariant"
                             app color="#2E7D32" dark>
            <v-list
                dense
                nav
            >
                <v-list-item class="pl-1 d-flex align-items-center">
                    <v-list-item-avatar>
                        <v-btn class="mr-1"
                               icon
                               @click.stop="miniVariant = !miniVariant"
                        >
                            <v-icon v-if="!miniVariant" class="fas fa-chevron-left"></v-icon>
                            <v-icon v-if="miniVariant" class="fas fa-chevron-right"></v-icon>
                        </v-btn>
                    </v-list-item-avatar>
                    <v-list-item-title class="title">
                        <span class="logo"><img src="/images/slogan1.png" alt=""><span><p>राष्ट्रपति चुरे-तराई </p><p>मधेश संरक्षण विकास</p><p>समिति</p></span></span>
                    </v-list-item-title>

                </v-list-item>
            </v-list>
            <navigation-drawer/>
        </v-navigation-drawer>

        <v-app-bar app color="#f4f5f6" flat>
            <v-app-bar-nav-icon
                @click.stop="expandDrawer = !expandDrawer"
            ></v-app-bar-nav-icon>
            <v-btn
                icon
                @click="goBack"
            >
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
            <v-btn
                icon
                @click="goForward"
            >
                <v-icon>mdi-arrow-right</v-icon>
            </v-btn>
            <v-btn
                icon
                @click="refresh"
            >
                <v-icon>mdi-refresh</v-icon>
            </v-btn>
            <v-container fluid>
                <v-breadcrumbs :items="breadcrumbs">
                    <template v-slot:item="{ item }">
                        <v-breadcrumbs-item
                            :to="item.meta.breadcrumb.link"
                            router
                        >
                            {{ item.meta.breadcrumb.text }}
                        </v-breadcrumbs-item>
                    </template>
                </v-breadcrumbs>
            </v-container>


            <app-bar/>
        </v-app-bar>
        <v-main>
            <router-view>
            </router-view>
        </v-main>
        <!--        <v-footer app>-->
        <!--        </v-footer>-->
        <notification-list/>
        <confirm-dialog ref="confirm"/>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            expandDrawer: true,
            expandOnHover: false,
            miniVariant: false,
        }
    },
    methods: {
        goBack() {
            this.$router.go(-1);
        },
        goForward() {
            this.$router.go(1);
        },
        refresh() {
            this.$router.go(0);
        }
    },
    computed: {
        breadcrumbs: function () {
            return this.$route.matched;
        }
    },
    mounted() {
        this.$root.confirm = this.$refs.confirm.open;
    }

}
</script>
<style scoped lang="scss">
    .logo{
        display: flex;
        align-items: center;
        img{
            width: 53px;
            padding: 3px;
            background: #fff;
            border-radius: 28px;
        }
        p{
            margin-bottom: 0px;
            margin-left: 9px;
            font-size: 13px;
            font-weight: 600;
        }
    }
</style>

<style lang="scss">
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-track {
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb {
    background-color: #9E9E9E;
}

</style>
