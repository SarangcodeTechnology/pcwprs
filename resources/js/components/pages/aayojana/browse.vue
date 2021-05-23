<template>
  <v-container fluid>
    <v-row>
      <v-col>
        <v-data-table
          :headers="headers"
          :hide-default-footer="true"
          :items="aayojana"
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
                    <h5 class="mb-0 align-self-center">आयोजना</h5>
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
            <div class="d-flex justify-content-center align-items-center">
              <v-btn icon x-small @click="editData(item)">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>

              <v-btn color="red" icon x-small @click="deletePopup(item)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </div>
          </template>
            <template v-if="editAllData == true" v-slot:item.budget_no="{ item }">
              <v-text-field v-model="item.budget_no" class="my-text-field">
              </v-text-field>
            </template>
            <template v-if="editAllData == true" v-slot:item.name="{ item }">
              <v-text-field v-model="item.name" class="my-text-field">
              </v-text-field>
            </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["aarthikBarsaId"],
  data() {
    return {
      deleteItem: "",
      deleteDialog: false,
      search: "",
      page: 1,
      totalCfData: 0,
      numberOfPages: 0,
      cfData: [],
      loading: true,
      options: {},
      totalItems: 20,
      headers: [
        { text: "कार्यहरु", value: "actions" },
        { text: "आर्थिक वर्ष", value: "aarthik_barsa.name" },
        { text: "बजेट नं", value: "budget_no" },
        { text: "आयोजनाको नाम", value: "name" },
        { text: "मन्त्रालयको नाम", value: "mantralaya_name" },
        { text: "विभाग संस्थाको नाम", value: "bivag_sanstha_name" },
        { text: "स्थान जिल्ला", value: "sthan_jilla" },
        { text: "आयोजना सुरु मिति", value: "aayojana_start_date" },
        { text: "आयोजना अन्त मिति", value: "aayojana_end_date" },
        {
          text: "आयोजना कार्यलय प्रमुखको नाम",
          value: "aayojana_karyalaya_pramukh_name",
        },
        { text: "वार्षिक बजेट", value: "baarsik_budget" },
        { text: "सिर्जना गरिएको मिति", value: "date" },
      ],
      filterData: {
        aarthik_barsa: [],
      },
      loading: true,
      editAllData: false,
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
    if (this.aarthikBarsaId)
      this.filterData.aarthik_barsa.push(this.aarthikBarsaId);

    this.getDataFromApi();
  },
  computed: {
    ...mapState({ aayojana: (state) => state.webservice.aayojana }),
  },
  methods: {
    getDataFromApi() {
      const tempthis = this;
      this.loading = true;
      const { page, itemsPerPage } = tempthis.options;
      let pageNumber = page - 1;
      this.$store
        .dispatch("getAayojana", {
          filterData: this.filterData,
          page: this.numberOfPages,
          totalItems: this.totalItems,
          search: this.search,
        })
        .then(function (response) {
          tempthis.loading = false;
        });
    },
    goToEditPage() {
      this.$store.dispatch("setAayojanaEditData", {
        aarthik_barsa_id: "",
        budget_no: "",
        name: "",
        mantralaya_name: "",
        bivag_sanstha_name: "",
        sthan_jilla: "",
        aayojana_start_date: "",
        aayojana_end_date: "",
        aayojana_karyalaya_pramukh_name: "",
        baarsik_budget: "",
      });
    },
    editData(item) {
      this.$store.dispatch("setAayojanaEditData", item);
    },
  },
};
</script>
<style scoped>
.my-text-field {
  width: 150px;
}
</style>
