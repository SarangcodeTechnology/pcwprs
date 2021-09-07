<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">
            <v-col cols="3" class="d-flex align-items-center">
                <h5>माईलस्टोन प्रगती</h5>
                <v-divider class="ml-5" inset vertical></v-divider>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="9" class="d-flex align-items-center">
                <v-select
                    v-model="filterData.kaaryalaya"
                    :items="kaaryalaya"
                    label="कार्यलय"
                    item-text="name"
                    item-value="id"
                    placeholder="कार्यलय"
                    @input="filterData.mahina ? getDataFromApi() : '' "
                    class="mr-2"
                    :disabled="!$store.getters.CHECK_PERMISSION('milestone_pragati_form-select_kaaryalaya')"
                >
                </v-select>
                <v-select
                    v-model="filterData.aarthikBarsa"
                    :items="aarthikBarsa"
                    label="आर्थिक वर्ष"
                    item-text="name"
                    item-value="id"
                    placeholder="आर्थिक वर्ष"
                    @input="changeInArthikBarsa"
                    class="mr-2"
                >
                </v-select>
                <v-select
                    v-model="filterData.aayojana"
                    :items="aayojana"
                    label="आयोजना"
                    item-text="name"
                    item-value="id"
                    placeholder="आयोजना"
                    @input="changeInAayojana"
                    class="mr-2"
                >
                </v-select>
                <v-select
                    v-if="filterData.aayojana"
                    v-model="filterData.mahina"
                    :items="mahina"
                    label="महिना"
                    item-text="name"
                    item-value="id"
                    placeholder="महिना"
                    @input="changeInMahina"
                >
                </v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col v-if="showSaveButton || showSubmitButton">
                <v-btn v-if="showSaveButton && !locked" color="primary" @click="saveMilestonePragatiTaalika(false)">Save</v-btn>
                <v-btn v-if="showSubmitButton && !locked"  color="primary" @click="saveMilestonePragatiTaalika(true)"><span v-if="submitted">Re-</span>Submit</v-btn>
            </v-col>
            <v-col v-if="showEditRequestButton && !locked">
                <v-btn color="primary" @click="editRequest">सम्पादन अनुरोध</v-btn>
            </v-col>
            <v-col>
                <v-alert
                    dense
                    type="info"
                    v-if="showDataNotSubmittedYet"
                >
                    फारम बुझाइएको छैन
                </v-alert>
                <v-alert
                    dense
                    type="info"
                    v-if="showRequestedAlert && !locked"
                >
                    तपाईले आफ्नो <strong>सम्पादन अनुरोध</strong> पठाउनु भईसकेको छ।कृपया धैर्य गर्नुहोस्! हामी यसमा काम गर्दैछौं।
                </v-alert>
                <v-alert
                    dense
                    type="warning"
                    v-if="locked"
                >
                    तपाईको फारम लक गरिएको छ। कृपया सम्बन्धित निकायमा सम्पर्क गर्नुहोस्।
                </v-alert>
            </v-col>
        </v-row>
        <v-row v-if="!showDataNotSubmittedYet">
            <v-col>
                <v-data-table
                    :headers="headers"
                    :hide-default-footer="false"
                    :items="milestonePragatiTaalika"
                    :items-per-page="5"
                    :loading="loading"
                    :options.sync="options"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:item.milestone_pragati.prarambhik_karya_suru_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.prarambhik_karya_suru_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone_pragati.prarambhik_karya_jari_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.prarambhik_karya_jari_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone_pragati.prarambhik_karya_sampanna_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.prarambhik_karya_sampanna_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone_pragati.karyakram_karyanayan_suru_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.karyakram_karyanayan_suru_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone_pragati.karyakram_karyanayan_jari_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.karyakram_karyanayan_jari_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone_pragati.karyakram_karyanayan_sampanna_pragati="{ item }">
                        <v-text-field :disabled="!editable || locked" type="number" v-model="item.milestone_pragati.karyakram_karyanayan_sampanna_pragati" @input="addEditedMilestonePragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            loading: false,
            options: {},
            totalItems: 20,
            filterData: {
                kaaryalaya:0,
                user:0,
                aarthikBarsa: "",
                aayojana: 0,
                mahina: 0,
                milestone:1,
            },
            headers:[],
            milestonePragatiTaalika:[],
            editedMilestonePragatiTaalikaID:[],
            submitted:false,
            requested:false,
            editable: true,
            showSaveButton:false,
            showSubmitButton:false,
            showSampadhanAnurodh:false,
            showEditRequestButton:false,
            showRequestedAlert:false,
            showDataNotSubmittedYet:false,
            showFillingData:false
        };
    },
    mounted() {
        this.filterData.kaaryalaya = this.user.kaaryalaya_id;
        this.filterData.user = this.user.id;
    },
    computed: {
        ...mapState({
            mahina: (state) => state.webservice.resources.mahina,
            aarthikBarsa: (state) => state.webservice.resources.aarthik_barsa,
            kaaryalaya: (state) =>state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user,
            locked: (state) => state.webservice.resources.locked,
        }),
        aayojana: function () {
            const tempthis = this;
            var data = "";
            if (this.filterData.aarthikBarsa) {
                var aarthikBarsa = this.aarthikBarsa.filter(function (item) {
                    return tempthis.filterData.aarthikBarsa == item.id;
                })[0];
                data = aarthikBarsa.aayojana ? aarthikBarsa.aayojana : "";
            } else {
                data = [];
            }
            return data;
        }
    },
    methods: {
        isAdmin(){
           return this.$store.getters.CHECK_PERMISSION('milestone_pragati_form-select_kaaryalaya');
        },
        checkStatus(){
            var tempthis = this;
            //if admin
            if(this.$store.getters.CHECK_PERMISSION('milestone_pragati_form-select_kaaryalaya')){
                if(tempthis.submitted){
                    tempthis.showEditRequestButton = false;
                    tempthis.showRequestedAlert = false;
                    tempthis.showDataNotSubmittedYet = false;
                    tempthis.showSampadhanAnurodh = false;
                    tempthis.showRequestedAlert = false;
                    tempthis.showSaveButton =true;
                    tempthis.editable =true;
                }
                else{
                    if(tempthis.filterData.kaaryalaya == tempthis.user.kaaryalaya_id){
                        tempthis.showEditRequestButton = false;
                        tempthis.showRequestedAlert = false;
                        tempthis.showDataNotSubmittedYet = false;
                        tempthis.showSampadhanAnurodh = false;
                        tempthis.showRequestedAlert = false;
                        tempthis.showSaveButton =true;
                        tempthis.editable =true;
                    }
                    else{
                        tempthis.showEditRequestButton = false;
                        tempthis.showRequestedAlert = false;
                        tempthis.showSampadhanAnurodh = false;
                        tempthis.showRequestedAlert = false;
                        tempthis.showSaveButton =false;
                        tempthis.editable = false;
                        tempthis.showDataNotSubmittedYet = true;

                    }
                }
            }
            //if not admin
            else{
                if(tempthis.submitted){
                    if(tempthis.editable){
                        tempthis.showEditRequestButton = false;
                        tempthis.showRequestedAlert = false;
                        tempthis.showDataNotSubmittedYet = false;
                        tempthis.showSaveButton =true;
                        tempthis.showSubmitButton = true;
                    }
                    else{
                        if(tempthis.requested){
                            tempthis.showEditRequestButton = false;
                            tempthis.showRequestedAlert = true;
                            tempthis.showDataNotSubmittedYet = false;
                            tempthis.showSaveButton =false;
                            tempthis.showSubmitButton = false;
                        }
                        else{
                            tempthis.showEditRequestButton = true;
                            tempthis.showRequestedAlert = false;
                            tempthis.showDataNotSubmittedYet = false;
                            tempthis.showSaveButton =false;
                            tempthis.showSubmitButton = false;
                        }
                    }
                }
                else{
                    tempthis.showEditRequestButton = false;
                    tempthis.showRequestedAlert = false;
                    tempthis.showDataNotSubmittedYet = false;
                    tempthis.showSaveButton =true;
                    tempthis.showSubmitButton = true;
                }
            }
        },
        addEditedMilestonePragatiTaalikaID(id){
            if(!this.editedMilestonePragatiTaalikaID.includes(id)){
                this.editedMilestonePragatiTaalikaID.push(id)
            }
        },
        editRequest(){
            var tempthis = this;
            this.$store.dispatch("editRequest",{filterData:this.filterData}).then(
                function (response){
                    tempthis.requested = response.requested;
                    tempthis.checkStatus();
                }
            );
        },
        saveMilestonePragatiTaalika(submitted){
            let tempthis = this;
            var items = this.milestonePragatiTaalika.filter(function(item){
                    return tempthis.editedMilestonePragatiTaalikaID.includes(item.id);
            });

            this.$store
                .dispatch("saveMilestonePragatiTaalika", {items:items,submitted:submitted,filterData:this.filterData })
                .then(function (response) {
                    tempthis.getDataFromApi();
                    tempthis.checkStatus();
                });
        },
        changeInAayojana(){
            this.filterData.mahina = 0;
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
            this.filterData.mahina = 0
        },
        changeInMahina(){
            this.getDataFromApi();
        },
        getDataFromApi() {
            const tempthis = this;
            this.loading = true;
            const {page, itemsPerPage} = tempthis.options;
            let pageNumber = page - 1;
            this.$store
                .dispatch("getMilestonePragatiTaalika", {
                    filterData: this.filterData,
                })
                .then(function (response) {
                    tempthis.headers = response.headers;
                    if(!tempthis.locked){
                        tempthis.submitted = response.submitted;
                        tempthis.requested = response.requested;
                        tempthis.editable = response.editable;
                    }else{
                        tempthis.editable = false;
                    }
                    tempthis.checkStatus();

                    let tempMilestonePragatiTaalika = [];
                    response.milestonePragatiTaalika.forEach(function (item){
                        if(item.milestone_pragati==null){
                            item.milestone_pragati={
                                milestone_lakshya_id:item.id,
                                mahina_id:tempthis.filterData.mahina,
                                user_id: tempthis.filterData.user,
                                kaaryalaya_id: tempthis.filterData.kaaryalaya,
                                prarambhik_karya_suru_pragati: item.prarambhik_karya_suru_pragati,
                                prarambhik_karya_jari_pragati: item.prarambhik_karya_jari_pragati,
                                prarambhik_karya_sampanna_pragati: item.prarambhik_karya_sampanna_pragati,
                                karyakram_karyanayan_suru_pragati: item.karyakram_karyanayan_suru_pragati,
                                karyakram_karyanayan_jari_pragati: item.karyakram_karyanayan_jari_pragati,
                                karyakram_karyanayan_sampanna_pragati: item.karyakram_karyanayan_sampanna_pragati,
                            }
                        }
                        tempMilestonePragatiTaalika.push(item);
                    })
                    tempthis.milestonePragatiTaalika = tempMilestonePragatiTaalika;
                    tempthis.loading = false;
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

v-select {
    width: 20px;
}

abbr {
    text-decoration: none;
}
v-tab-items{
    min-width: 400px;
}
</style>
