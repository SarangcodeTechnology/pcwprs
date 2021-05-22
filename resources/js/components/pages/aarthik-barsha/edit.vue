<template>
  <v-form ref="form" v-model="valid" lazy-validation>
    <v-toolbar color="#E0E0E0" dark flat></v-toolbar>
    <v-card class="mx-11 my-n11">
      <v-toolbar flat>
        <strong>आर्थिक वर्ष सम्पादन गर्नुहोस्</strong>
        <v-spacer></v-spacer>
        <v-btn
          :disabled="!valid"
          class="ma-2"
          @click="saveAarthikBarsa()"
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
            >कृपया तलकाे फारम
            मार्फत आफ्नाे विवरण सूचना प्रणालीमा सुनिश्चित गर्नुहाेस् ।</span
          >
          <v-divider></v-divider>
          <v-row>
            <v-col cols="4">
              <v-text-field
                v-model="aarthikBarsaData.name"
                label="आर्थिक वर्ष"
                placeholder="आर्थिक वर्ष राख्नुहोस्"
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
import { mapState,mapGetters } from "vuex";

export default {
    data(){
        return {
            valid:false,
        }
    },
    computed:{
        ...mapState({
            aarthikBarsaData: (state) => state.webservice.editAarthikBarsaData,
        }),
    },
    methods:{
        saveAarthikBarsa(){
            this.$store.dispatch('saveAarthikBarsa',this.aarthikBarsaData)
        }
    }

};
</script>

<style scoped>


</style>
