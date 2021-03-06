<template>
  <v-container fluid>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :hide-default-footer="true"
          :items="users"
          :items-per-page="20"
          :loading="loading"
          :options.sync="options"
          :page="page"
          :pageCount="numberOfPages"
          fixed-header
          loading-text="Loading Data... Please wait"
        >
          <template v-slot:top="{ pagination, options, updateOptions }">
            <v-container fluid>
              <v-row>
                <v-col cols="3">
                  <div class="d-flex align-content-center">
                    <h5 class="mb-0 align-self-center">प्रयोगकर्ताहरु</h5>
                    <v-divider class="mx-4 mt-0" inset vertical></v-divider>
                    <v-btn
                      class="d-flex align-self-center"
                      color="primary"
                      @click="goToEditPage"
                    >
                      <v-icon left>mdi-plus-circle-outline</v-icon>
                      <span>नयाँ</span></v-btn
                    >
                  </div>
                </v-col>
                <v-col cols="5">
                  <v-text-field
                    v-model="search"
                    dense
                    label="खोजी गर्नुहोस्"
                    outlined
                    @change="getDataFromApi"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-data-footer
                    :options="options"
                    :pagination="pagination"
                    items-per-page-text="$vuetify.dataTable.itemsPerPageText"
                  />
                </v-col>
              </v-row>
            </v-container>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-btn icon x-small @click="editData(item)">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>

            <v-btn color="red" icon x-small @click="confirm(item)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          <template v-slot:item.permissions="{ item }">
            <v-chip
              v-for="(permission, k) in item.permissions"
              :key="k"
              v-if="k == 0"
            >
              {{ 'can '+ permission.name.split('-')[1].split('_').join(' ') + ' in ' + permission.name.split('-')[0].split('_').join(' ') }}
            </v-chip>
            <span v-if="item.permissions.length > 1" class="text-caption">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <a v-bind="attrs" v-on="on" href="#"
                    >+{{ item.permissions.length - 1 }} more</a
                  >
                </template>
                <div class="d-flex flex-column">
                  <span
                    v-for="(tooltipPermission, k) in item.permissions"
                    :key="k"
                    v-if="k != 0"
                    >{{ 'can '+ tooltipPermission.name.split('-')[1].split('_').join(' ') + ' in ' + tooltipPermission.name.split('-')[0].split('_').join(' ') }}</span
                  >
                </div>
              </v-tooltip>
            </span>
            <span
              v-if="item.permissions.length == 0"
              class="grey--text text-caption"
            >
              No Permissions Assigned
            </span>
          </template>
          <template v-slot:item.roles="{ item }">
            <v-chip v-for="(role, k) in item.roles" :key="k" v-if="k == 0">
              {{ role.name }}
            </v-chip>
            <span v-if="item.roles.length > 1" class="text-caption">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <a v-bind="attrs" v-on="on" href="#"
                    >+{{ item.roles.length - 1 }} more</a
                  >
                </template>
                <div class="d-flex flex-column">
                  <span
                    v-for="(tooltipRole, k) in item.roles"
                    :key="k"
                    v-if="k != 0"
                    >{{ tooltipRole.name }}</span
                  >
                </div>
              </v-tooltip>
            </span>
            <span v-if="item.roles.length == 0" class="grey--text text-caption">
              No Role Assigned
            </span>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
        search: "",
        page: 1,
        numberOfPages: 0,
        options: {},
      loading: true,
      headers: [
        { text: "कार्यहरु", value: "actions" },
        { text: "नाम", value: "name" },
        { text: "ई-मेल", value: "email" },
        { text: "भूमिकाहरु", value: "roles" },
        { text: "थप अनुमतिहरु", value: "permissions" },
        { text: "सिर्जना गरिएको मिति", value: "date" },
      ],
    };
  },
  mounted() {
    this.getDataFromApi();
  },
  computed: {
    ...mapState({ users: (state) => state.webservice.users }),
  },
  watch: {
    //this one will populate new data set when user changes current page.
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },
  methods: {
      confirm(item) {
          const tempthis = this;
          this.$root.confirm('मेट्ने पुष्टि गर्नुहोस्', 'के तपाईं ' + item.name + ' मेट्न निश्चित हुनुहुन्छ ?', {color: 'red'}).then((confirm) => {
              tempthis.deleteData(item);
          }).catch((error) => {
              console.log(error);
          });
      },
      deleteData(item) {
          let tempthis = this;
          this.$store.dispatch('makePostRequest', {
              data: {items: item, model: "User"},
              route: 'delete-data'
          }).then(function (response) {
              tempthis.getDataFromApi();
          });
      },
    editData(item) {
      this.$store.commit("SET_IS_USER_EDIT", true);
      this.$store.dispatch("setUserEditData", item);
    },
    goToEditPage() {
      this.$store.commit("SET_IS_USER_EDIT", false);
      this.$store.dispatch("setUserEditData", {
        name: "",
        email: "",
      });
    },

    getDataFromApi() {
      const tempthis = this;
      this.loading = true;
      const { page, itemsPerPage } = tempthis.options;
      let pageNumber = page - 1;
      this.$store.dispatch("getUsers", {}).then(function (response) {
        tempthis.loading = false;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.theme--light.v-data-table .v-data-footer {
  border-top: none !important;
}
</style>
