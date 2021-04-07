<template>
    <v-app>
        <v-container class="login-page-bg" fill-height fluid>
            <v-row justify="center">
                <v-col cols="4">
                    <v-card class="pa-3" elevation="10">
                        <v-container>
                            <v-row justify="center">
                                <v-col cols="3">
                                    <v-img contain height="100%" width="100%" src="/images/nepal_emblem.png"></v-img>
                                </v-col>
                                <v-col cols="9">
                                    <h5>नेपाल सरकार</h5>
                                    <h5>वन तथा बातावरण मन्त्रालय</h5>
                                    <h4><strong>सामुदायिक वन अध्ययन केन्द्र </strong></h4>
                                    <h3>व्यवस्थापन सूचना प्रणाली (MIS)</h3>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col align="center">
                                    <v-divider></v-divider>
                                    <p>सामुदायिक वन अध्ययन केन्द्र वव्यवस्थापन सूचना प्रणालीमा तपाइलाइ स्वागत छ । सूरू
                                        गर्नको लागी आफ्नाे खातामा साईन ईन गर्नुहोस् ।</p>
                                    <v-divider></v-divider>
                                    <v-form>
                                        <v-text-field
                                            v-model="user.email"
                                            append-icon="fas fa-user"
                                            autocomplete="on"
                                            label="यूजरनेम"
                                            outlined
                                            placeholder="यूजरनेम हाल्नुहा्ेस्"
                                            v-on:keyup.enter="login"
                                        ></v-text-field>
                                        <v-text-field
                                            v-model="user.password"
                                            append-icon="fas fa-key"
                                            autocomplete="on"
                                            label="पासवर्ड"
                                            outlined
                                            placeholder="पासवर्ड हाल्नुहा्ेस्"
                                            type="password"
                                            v-on:keyup.enter="login"
                                        ></v-text-field>
                                        <v-divider></v-divider>
                                        <v-btn block class="ma-2" color="#2E7D32" dark x-large
                                               @click="login">
                                            <span>साईन ईन</span>
                                        </v-btn>
                                    </v-form>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <notification-list/>
    </v-app>

</template>

<script>
export default {
    data() {
        return {
            user: {
                email: "",
                password: "",
            },
        };
    },
    methods: {
        login() {
            let tempthis = this;
            this.$store
                .dispatch("login", tempthis.user)
                .then(function (response) {
                    if (response.data.status == 200) {
                        tempthis.$router.push("/dashboard").catch(() => {
                        });
                        tempthis.$store.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    } else {
                        tempthis.$store.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }
                })
                .catch(function (error) {
                    tempthis.$store.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                });
        },
    },
};
</script>

<style scoped>

</style>
