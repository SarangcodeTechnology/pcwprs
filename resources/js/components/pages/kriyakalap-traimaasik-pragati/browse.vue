<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">

            <v-col cols="3" class="d-flex align-items-center">
                <h5>कृयाकलाप त्रैमासिक प्रगती</h5>
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
                    :disabled="1"
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
                    v-model="filterData.traimaasik"
                    :items="traimaasik"
                    label="त्रैमासिक"
                    item-text="name"
                    item-value="id"
                    placeholder="त्रैमासिक"
                    @input="changeInTraimaasik"
                >
                </v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col v-if="filterData.traimaasik">
                <v-btn  color="primary" elevation="2" @click="saveTraimaasikPragatiTaalika">Save</v-btn>
                 <v-btn color="secondary"  elevation="2"  @click="importFromMaasikPragati">Import from maasik pragati</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :hide-default-footer="false"
                    :items="traiMaasikPragatiTaalika"
                    :items-per-page="5"
                    :loading="loading"
                    :options.sync="options"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:item.traimaasik_pragati.pariman="{ item }">
                        <v-text-field type="number" v-model="item.traimaasik_pragati.pariman" @input="addEditedTraimaasikPragatiTaalikaID(item.id)" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-slot:item.traimaasik_pragati.kharcha="{ item }">
                        <v-text-field type="number" v-model="item.traimaasik_pragati.kharcha" @input="addEditedTraimaasikPragatiTaalikaID(item.id)" class="my-text-field">
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
                aarthikBarsa: "",
                aayojana: 0,
                traimaasik: 0,
                kaaryalaya:0,
                user:0,
            },
            headers:[

            ],
            traiMaasikPragatiTaalika:[

            ],
            editedTraimaasikPragatiTaalikaID:[

            ]
        };
    },
    mounted() {
        this.filterData.kaaryalaya = this.user.kaaryalaya_id;
        this.filterData.user = this.user.id;
    },
    computed: {
        ...mapState({
            traimaasik: (state) => state.webservice.resources.traimaasik,
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
        importFromMaasikPragati(){
            var tempthis = this;
            this.$store
                .dispatch("importFromMaasikPragati", {
                    filterData: this.filterData,
                }).then(function(response){
                var  tempTraiaasikPragatiTaalika = [];
                tempthis.traiMaasikPragatiTaalika.forEach(function (item){
                        item.traimaasik_pragati={
                            traimaasik_id:tempthis.filterData.traimaasik,
                            user_id: tempthis.filterData.user,
                            kaaryalaya_id: tempthis.filterData.kaaryalaya,
                            kriyakalap_lakshya_id:item.id,
                            pariman:null,
                            kharcha:null
                        }
                    tempTraiaasikPragatiTaalika.push(item);
                })
                tempthis.traiMaasikPragatiTaalika = tempTraiaasikPragatiTaalika;

                response.summations.forEach(function(item){
                    tempthis.traiMaasikPragatiTaalika.forEach(function(traimaasikItem,index){
                        if(traimaasikItem.id==item.id){
                            tempthis.traiMaasikPragatiTaalika[index].traimaasik_pragati = item.traimaasik_pragati;
                            tempthis.editedTraimaasikPragatiTaalikaID.push(traimaasikItem.id);
                        }
                    });
                })
            });
        },
        addEditedTraimaasikPragatiTaalikaID(id){
            if(!this.editedTraimaasikPragatiTaalikaID.includes(id)){
                this.editedTraimaasikPragatiTaalikaID.push(id)
            }
        },
        saveTraimaasikPragatiTaalika(){
            let tempthis = this;
            var items = this.traiMaasikPragatiTaalika.filter(function(item){
                    return tempthis.editedTraimaasikPragatiTaalikaID.includes(item.id);
            });

            this.$store
                .dispatch("saveTraimaasikPragatiTaalika", items)
                .then(function (response) {
                   tempthis.editedTraimaasikPragatiTaalikaID = [];
                });
        },
        changeInAayojana(){
            this.filterData.traimaasik = 0;
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
        },
        changeInTraimaasik(){
            this.getDataFromApi();
        },
        getDataFromApi() {
            const tempthis = this;
            this.loading = true;
            const {page, itemsPerPage} = tempthis.options;
            let pageNumber = page - 1;
            this.$store
                .dispatch("getTraimaasikPragatiTaalika", {
                    filterData: this.filterData,
                })
                .then(function (response) {
                    tempthis.headers = response.headers;
                    let tempTraiaasikPragatiTaalika = [];
                    response.traiMaasikPragatiTaalika.forEach(function (item){
                        if(item.traimaasik_pragati==null){
                            item.traimaasik_pragati={
                                traimaasik_id:tempthis.filterData.traimaasik,
                                user_id: tempthis.filterData.user,
                                kaaryalaya_id: tempthis.filterData.kaaryalaya,
                                kriyakalap_lakshya_id:item.id,
                                pariman:null,
                                kharcha:null
                            }
                        }
                        tempTraiaasikPragatiTaalika.push(item);
                    })
                    tempthis.traiMaasikPragatiTaalika = tempTraiaasikPragatiTaalika;
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
