<template>
    <v-form ref="form" v-model="valid" lazy-validation>
        <v-toolbar color="#E0E0E0" dark flat></v-toolbar>
        <v-card class="mx-11 my-n11">
            <v-toolbar flat>
                <strong>भूमिका सम्पादन गर्नुहोस्</strong>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="!valid"
                    class="ma-2"
                    @click="saveRoleData()"
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
                  >कृपया तलकाे फारम मार्फत आफ्नाे विवरण सूचना प्रणालीमा सुनिश्चित गर्नुहाेस् ।</span
                  >
                    <v-divider></v-divider>
                    <v-row>
                        <v-col cols="4">
                            <v-text-field
                                v-model="roleData.name"
                                label="प्रयोगकर्ता नाम"
                                placeholder="प्रयोगकर्ताको नाम राख्नुहोस्"
                                outlined
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <div class="item">
                        <div class="sub-item"  v-for="(titleValue,titleKey,titleIndex) in formattedPermissions" :key="titleIndex">
                                <v-card
                                    max-width="400"
                                    outlined
                                >
                                    <v-card-text>
                                        <p class="permissionTitle">{{ titleKey.split('_').join(' ') }}</p>
                                        <div v-for="(item,index) in titleValue" :key="index">
                                            <v-checkbox
                                                v-model="roleData.permissions"
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

                </v-container>
            </v-card-text>
        </v-card>
    </v-form>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            valid: false,
        }
    },
    computed: {
        ...mapState({
            roleData: (state) => state.webservice.editRoleData,
            permissions: (state) => state.webservice.resources.permissions,
            formattedPermissions: (state) => state.webservice.resources.formattedPermissions
        }),
    },
    methods: {
        saveRoleData() {
            this.$store.dispatch('saveRoleData', this.roleData)
        }
    }

};
</script>

<style scoped>
.permissionTitle {
    text-transform: capitalize;
    font-size: 14px;
    font-weight: 600;
    position: relative;
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
    font-size: 14px !important;
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
    columns: 4;
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
