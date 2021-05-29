<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">

            <v-col cols="3" class="d-flex align-items-center">
                <h5>मासिक प्रगती</h5>
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
                    @input="getDataFromApi"
                    class="mr-2"
                    :disabled="true"
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
            <v-col v-if="filterData.mahina && editable">
                <v-btn color="primary" @click="saveMaasikPragatiTaalika(false)">Save</v-btn>
                <v-btn color="primary" @click="saveMaasikPragatiTaalika(true)"><span v-if="submitted">Re-</span>Submit</v-btn>
            </v-col>
            <v-col v-if="filterData.mahina && !requested && !editable">
                <v-btn color="primary" @click="editRequest">सम्पादन अनुरोध</v-btn>
            </v-col>
            <v-col>
                <v-alert
                    dense
                    type="info"
                    v-if="filterData.mahina && submitted && requested"
                >
                    तपाईले आफ्नो <strong>सम्पादन अनुरोध</strong> पठाउनु भईसकेको छ।कृपया धैर्य गर्नुहोस्! हामी यसमा काम गर्दैछौं।
                </v-alert>
            </v-col>

        </v-row>
        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :hide-default-footer="false"
                    :items="maasikPragatiTaalika"
                    :items-per-page="5"
                    :loading="loading"
                    :options.sync="options"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:item.maasik_pragati.pariman="{ item }">
                        <v-text-field :disabled="submitted && !editable" type="number" v-model="item.maasik_pragati.pariman" @input="addEditedMaasikPragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.maasik_pragati.kharcha="{ item }">
                        <v-text-field :disabled="submitted && !editable" type="number" v-model="item.maasik_pragati.kharcha" @input="addEditedMaasikPragatiTaalikaID(item.id)" class="my-text-field">
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
                mahina: 0
            },
            headers:[

            ],
            maasikPragatiTaalika:[

            ],
            editedMaasikPragatiTaalikaID:[

            ],
            submitted:false,
            requested:false,
            editable: true
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
        addEditedMaasikPragatiTaalikaID(id){
            if(!this.editedMaasikPragatiTaalikaID.includes(id)){
                this.editedMaasikPragatiTaalikaID.push(id)
            }
        },
        editRequest(){
            var tempthis = this;
            this.$store.dispatch("editRequest",{filterData:this.filterData}).then(
                function (response){
                    tempthis.requested = response.requested;
                }
            );

        },
        saveMaasikPragatiTaalika(submitted){
            let tempthis = this;
            var items = this.maasikPragatiTaalika.filter(function(item){
                    return tempthis.editedMaasikPragatiTaalikaID.includes(item.id);
            });

            this.$store
                .dispatch("saveMaasikPragatiTaalika", {items:items,submitted:submitted,filterData:this.filterData })
                .then(function (response) {
                    tempthis.getDataFromApi();
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
                .dispatch("getMaasikPragatiTaalika", {
                    filterData: this.filterData,
                })
                .then(function (response) {
                    tempthis.headers = response.headers;
                    tempthis.submitted = response.submitted;
                    tempthis.requested = response.requested;
                    tempthis.editable = response.editable;
                    let tempMaasikPragatiTaalika = [];
                    response.maasikPragatiTaalika.forEach(function (item){
                        if(item.maasik_pragati==null){
                            item.maasik_pragati={
                                mahina_id:tempthis.filterData.mahina,
                                user_id: tempthis.filterData.user,
                                kaaryalaya_id: tempthis.filterData.kaaryalaya,
                                kriyakalap_lakshya_id:item.id,
                                pariman:null,
                                kharcha:null
                            }
                        }
                        tempMaasikPragatiTaalika.push(item);
                    })
                    tempthis.maasikPragatiTaalika = tempMaasikPragatiTaalika;
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
