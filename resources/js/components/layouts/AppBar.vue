<template>
    <v-container class="d-flex justify-content-end align-items-center" fluid>
        <v-btn small text @click="loadResources">
            <v-icon>mdi-update</v-icon>
            <span>Reload Resources</span>
        </v-btn>
        <v-menu v-model="menu" :close-on-content-click="false" offset-overflow offset-y
                origin="center center"
                transition="slide-x-reverse-transition">

            <template v-slot:activator="{ on, attrs }">
                <v-btn v-bind="attrs" v-on="on" fab small text>
                    <v-icon>mdi-account-circle</v-icon>
                </v-btn>
            </template>
            <v-expand-transition>

            </v-expand-transition>
            <v-container class="pa-0 ma-0" flat>
                <v-list class="pa-0 ma-0" dense>
                    <v-list-item>
                        <v-list-item-avatar tile>
                            <v-img height="100%" src="/images/nepal_emblem.png"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title class="pa-0 ma-0"><h5>व्यवस्थापन सूचना प्रणाली</h5>
                            </v-list-item-title>
                            <v-list-item-subtitle v-if="guest"><h6>{{ getUser.email }}</h6></v-list-item-subtitle>
                            <v-list-item-subtitle v-if="!guest"><h6>साईन ईन गर्नुहाेस्</h6></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider class="pa-0 ma-0"></v-divider>
                    <v-list-item :to="'/change-password'" router @click="menu = !menu">
                        <v-list-item-icon>
                            <v-icon>mdi-lock-reset</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>पासवर्ड परिवर्तन</v-list-item-title>
                    </v-list-item>

                    <v-list-item @click="logout(), menu = !menu">
                        <v-list-item-icon>
                            <v-icon>mdi-logout-variant</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>साईन आउट</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-container>
        </v-menu>
    </v-container>

</template>

<script>
export default {
    data() {
        return {
            menu: false,
        }
    },
    methods: {
        loadResources() {
            this.$store.dispatch("loadResources");
        },
        logout() {
            const mypointer = this;
            this.$store
                .dispatch("logout")
                .then(function (response) {
                    if (response.data.status == 200) {
                        mypointer.$router.push("/login").catch(() => {
                        });
                        mypointer.$store.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    } else {
                        mypointer.$store.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }
                })
                .catch(function (error) {
                    mypointer.$store.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                });
        },
    },
    computed: {
        guest: {
            get() {
                return this.$store.state.user;
            },
        },

    }
}
</script>

<style scoped>
.v-list-item {
    text-decoration: none;
}
</style>
