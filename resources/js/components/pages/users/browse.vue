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
                    <h5 class="mb-0 align-self-center">Users</h5>
                    <v-divider class="mx-4 mt-0" inset vertical></v-divider>
                    <v-btn
                      class="d-flex align-self-center"
                      color="primary"
                      @click="goToEditPage"
                    >
                      <v-icon left>mdi-plus-circle-outline</v-icon>
                      <span>New</span></v-btn
                    >
                  </div>
                </v-col>
                <v-col cols="5">
                  <v-text-field
                    v-model="search"
                    dense
                    label="Search"
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

            <v-btn color="red" icon x-small @click="deletePopup(item)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
          <template v-slot:item.permissions="{ item }">
            <v-chip
              v-for="(permission, k) in item.permissions"
              :key="k"
              v-if="k == 0"
            >
              {{ permission.name }}
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
                    >{{ tooltipPermission.name }}</span
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
      deleteItem: "",
      deleteDialog: false,
      search: "",
      page: 1,
      totalCfData: 0,
      numberOfPages: 0,
      loading: true,
      options: {},
      totalItems: 20,
      headers: [
        { text: "Actions", value: "actions" },
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "Roles", value: "roles" },
        { text: "Additional Permissions", value: "permissions" },
        { text: "Created At", value: "date" },
      ],
    };
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
  mounted() {
    this.getDataFromApi();
    console.log(this.users);
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
    deletePopup(item) {
      this.deleteItem = item;
      this.deleteDialog = true;
    },
    editData(item) {
      this.$store.commit("SET_IS_USER_EDIT", true);
      this.$store.dispatch("setUserEditData", item);
    },
    deleteData() {
      let tempthis = this;
      let deleteItem = this.deleteItem;
      let index = this.cfData.indexOf(this.deleteItem);
      this.$store
        .dispatch("deleteCfData", { index: index, id: deleteItem.id })
        .then(function (response) {
          if (response.data.status === 200) {
            tempthis.cfData.splice(index, 1);
            tempthis.deleteDialog = false;
            // popup close
          }
        })
        .catch(function (error) {
          this.$store.dispatch("addNotification", {
            type: "error",
            message: error,
          });
        });
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
