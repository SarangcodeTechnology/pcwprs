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
                label="Roles"
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
            <v-col>
              <v-checkbox
                v-for="(item, rolePermissionIndex) in rolePermissions"
                :key="rolePermissionIndex"
                disabled
                :label="item.name"
                :value="item"
                v-model="rolePermissions"
              ></v-checkbox>
            </v-col>
              <v-col>
                <v-checkbox
                  v-for="(item, index) in additionalPermissions"
                  :key="index"
                  :label="item.name"
                  :value="item"
                  v-model="userData.permissions"
                  multiple
                ></v-checkbox>
              </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-form>
</template>

<script>
import { mapGetters, mapState } from "vuex";

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
