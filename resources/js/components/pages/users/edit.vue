<template>
    <v-form ref="form" v-model="valid" lazy-validation>
        <v-toolbar color="#E0E0E0" dark flat></v-toolbar>
        <v-card class="mx-11 my-n11">
            <v-toolbar flat>
                <strong>प्रयोगकर्ता सम्पादन गर्नुहोस्</strong>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="!valid"
                    class="ma-2"
                    @click="saveUserData()"
                    hint="E.g.: save"
                    depressed
                    color="green darken-1"
                >
                    <v-icon>mdi-floppy</v-icon>
                    <span>Save</span>
                </v-btn>
            </v-toolbar>

            <v-divider class="ma-0 pa-0"></v-divider>

            <v-card-text>
                <v-container class="pa-0 ma-0">
          <span
          >सामुदायिक वन विवरण फारममा तपाइहरुलाइ स्वागत छ । कृपया तलकाे फारम
            मार्फत आफ्नाे विवरण सूचना प्रणालीमा सुनिश्चित गर्नुहाेस् ।</span
          >
                    <v-divider></v-divider>
                    <v-row>
                        <v-col cols="4">
                            <v-text-field
                                v-model="userData.name"
                                label="प्रयोगकर्ता नाम"
                                placeholder="प्रयोगकर्ताको नाम राख्नुहोस्"
                                outlined
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="4">
                            <v-text-field
                                v-model="userData.email"
                                label="प्रयोगकर्ता ई-मेल"
                                placeholder="प्रयोगकर्ताको ई-मेल राख्नुहोस्"
                                outlined
                                :rules="emailRules"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="4">
                            <v-select
                                :items="kaaryalaya"
                                v-model="userData.kaaryalaya_id"
                                item-text="name"
                                item-value="id"
                                label="कार्यलय"
                                placeholder="कार्यलय राख्नुहोस्"
                                outlined
                            >
                            </v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="4">
                            <v-text-field
                                v-model="userData.password"
                                label="पासवर्ड"
                                placeholder="पासवर्ड राख्नुहोस्"
                                :hint="isUserEdit ? 'पुरानो पासवर्ड राख्न खाली छोड्नुहोस्' : ''"
                                persistent-hint
                                outlined
                                type="password"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="4">
                            <v-text-field
                                v-model="confirmPassword"
                                label="पासवर्ड सुनिश्चित"
                                placeholder="पासवर्ड सुनिश्चित गर्नुहोस"
                                :rules="[confirmPasswordRule]"
                                outlined
                                type="password"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="8">
                            <v-autocomplete
                                v-model="userData.roles"
                                :items="roles"
                                label="भूमिकाहरु"
                                @input="getPermissionsDataForUser"
                                item-text="name"
                                placeholder="Please assign roles"
                                hint="E.g.: Administrator"
                                multiple
                                chips
                                return-object
                                deletable-chips
                                outlined
                            >
                            </v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row>
                        <v-col v-if="rolePermissions">
                            <h5>Permissions</h5>
                            <v-divider></v-divider>
                            <div class="item" >
                                <div class="sub-item" cols="6" v-for="(titleValue,titleKey,titleIndex) in formattedRolePermissions" :key="titleIndex">
                                    <v-card
                                        max-width="400"
                                        outlined
                                    >
                                        <v-card-text>
                                            <p class="permissionTitle">{{ titleKey.split('_').join(' ') }}</p>
                                            <div v-for="(item,index) in titleValue" :key="index">
                                                <v-checkbox
                                                    v-model="rolePermissions"
                                                    :value="item"
                                                    multiple
                                                    class="ma-0"
                                                    disabled
                                                >
                                                    <template v-slot:label>
                                                    <span class="checkboxLabel">{{
                                                            item.name.split('-')[1].split('_').join(' ')
                                                        }}</span>
                                                    </template>
                                                </v-checkbox>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </div>
                            </div>
                        </v-col>
                        <v-col>
                            <h5>Additional Permissions</h5>
                            <v-divider></v-divider>
                            <div class="item">
                                <div class="sub-item"
                                       v-for="(titleValue,titleKey,titleIndex) in additionalPermissions"
                                       :key="titleIndex">
                                    <v-card
                                        max-width="400"
                                        outlined
                                    >
                                        <v-card-text>
                                            <p class="permissionTitle">{{ titleKey.split('_').join(' ') }}</p>
                                            <div v-for="(item,index) in titleValue" :key="index">
                                                <v-checkbox
                                                    v-model="userData.permissions"
                                                    :value="item"
                                                    multiple
                                                    class="ma-0"
                                                >
                                                    <template v-slot:label>
                                                    <span class="checkboxLabel">{{
                                                            item.name.split('-')[1].split('_').join(' ')
                                                        }}</span>
                                                    </template>
                                                </v-checkbox>
                                            </div>
                                        </v-card-text>
                                    </v-card>
                                </div>
                            </div>

                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </v-form>
</template>

<script>
import {mapGetters, mapState} from "vuex";

export default {
    data() {
        return {
            valid: false,
            confirmPassword: "",
            emailRules: [
                (v) => !!v || "E-mail is required",
                (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
            ],
            rolePermissions: [],
            formattedRolePermissions: [],
            additionalPermissions: [],
        };
    },
    mounted() {
        // console.log(this.selectedPermissions);
        this.getPermissionsDataForUser();
    },
    computed: {
        ...mapState({
            userData: (state) => state.webservice.editUserData,
            isUserEdit: (state) => state.webservice.isUserEdit,
            roles: (state) => state.webservice.resources.roles,
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
        }),
        ...mapGetters(["selectedPermissions"]),
    },
    methods: {
        remove(item) {
            this.userData.roles.splice(item.index, 1);
        },
        confirmPasswordRule(val) {
            if (this.userData.password == val) {
                return true;
            } else {
                return "पासवर्ड मेल खाँदैन!";
            }
        },
        saveUserData() {
            this.$store.dispatch("saveUserData", this.userData);
        },
        getPermissionsDataForUser() {
            var tempThis = this;
            this.$store
                .dispatch("getPermissionsDataForUser", {
                    roles: this.userData.roles,
                    permissions: this.userData.permissions,
                })
                .then(function (response) {
                    tempThis.rolePermissions = response.data.selectedRolePermissions;
                    tempThis.formattedRolePermissions = response.data.formattedSelectedRolePermissions;
                    tempThis.additionalPermissions = response.data.additionalPermissions;
                    tempThis.$store.commit(
                        "SET_SELECTED_ADDITIONAL_PERMISSIONS",
                        response.data.finalSelectedPermissions
                    );
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
};
</script>

<style scoped>
.permissionTitle {
    text-transform: capitalize;
    font-size: 14px;
    font-weight: 600;
    position: relative;
}

h5 {
    font-weight: 600;
}

/*.permissionTitle::before{*/
/*    position: absolute;*/
/*    bottom: -2px;*/
/*    content: "";*/
/*    left: -4px;*/
/*    width: 45px;*/
/*    height: 3px;*/
/*    background: #1b4d1978;*/
/*    border-radius: 42%;*/
/*}*/
.permissions {
    display: flex;
    flex-wrap: wrap;
}

.checkboxLabel {
    text-transform: capitalize;
    font-size: 13px !important;
}

.v-input__slot, .v-input {
    margin-top: 0px;
}

.v-input__control.v-input__slot.label.v-label {
    margin-bottom: 0px !important;

}

v-input__slot {
    display: inline-block;
}

.item {
    width: 100%;
    columns: 2;
    column-gap: 2px;
}

.sub-item {
    width: 100%;
    margin: 0 0 5px;
    padding: 5px;
    overflow: hidden;
    break-inside: avoid;
}
</style>
