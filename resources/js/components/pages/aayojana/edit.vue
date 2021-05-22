<template>
  <v-form ref="form" v-model="valid" lazy-validation>
    <v-toolbar color="#E0E0E0" dark flat></v-toolbar>
    <v-card class="mx-11 my-n11">
      <v-toolbar flat>
        <strong>आयोजना सम्पादन गर्नुहोस्</strong>
        <v-spacer></v-spacer>
        <v-btn
          :disabled="!valid"
          class="ma-2"
          @click="saveAayojana()"
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
            >कृपया तलकाे फारम मार्फत आफ्नाे विवरण सूचना प्रणालीमा सुनिश्चित
            गर्नुहाेस् ।</span
          >
          <v-divider></v-divider>
          <v-row>
            <v-col cols="4">
                <v-select
                v-model="aayojanaData.aarthik_barsa_id"
                :items="aarthikBarsa"
                label="आर्थिक वर्ष"
                item-text="name"
                item-value="id"
                placeholder="आर्थिक वर्ष"
                outlined
              >
              </v-select>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.budget_no"
                label="बजेट नं"
                placeholder="बजेट नं राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.name"
                label="आयोजनाको नाम"
                placeholder="आयोजनाको नाम राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.mantralaya_name"
                label="मन्त्रालयको नाम"
                placeholder="मन्त्रालयको नाम राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.bivag_sanstha_name"
                label="विभाग संस्थाको नाम"
                placeholder="विभाग संस्थाको नाम राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.sthan_jilla"
                label="स्थान जिल्लाको नाम"
                placeholder="स्थान जिल्लाको नाम राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.aayojana_start_date"
                label="आयोजना सुरु मिति"
                placeholder="आयोजना सुरु मिति राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.aayojana_end_date"
                label="आयोजना अन्त मिति"
                placeholder="आयोजना अन्त मिति राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.aayojana_karyalaya_pramukh_name"
                label="आयोजना कार्यलय प्रमुखको नाम"
                placeholder="आयोजना कार्यलय प्रमुखको नाम राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4">
              <v-text-field
                v-model="aayojanaData.baarsik_budget"
                label="वार्षिक बजेट"
                placeholder="वार्षिक बजेट राख्नुहोस्"
                outlined
              >
              </v-text-field>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-form>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      valid: false,
    };
  },
  computed: {
    ...mapState({
      aayojanaData: (state) => state.webservice.editAayojanaData,
      aarthikBarsa: (state) => state.webservice.resources.aarthik_barsa,
    }),
  },
  methods: {
    saveAayojana() {
      this.$store.dispatch("saveAayojana", this.aayojanaData);
    },
  },
};
</script>

<style scoped>
</style>
