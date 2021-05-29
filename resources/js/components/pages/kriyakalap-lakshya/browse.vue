<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :search="search"
                    :hide-default-footer="false"
                    :items="kriyakalapLakshya"
                    :items-per-page="10"
                    :loading="loading"
                    :options.sync="options"
                    :page="page"
                    :pageCount="numberOfPages"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:top="{ pagination, options, updateOptions }">
                            <v-row class="d-flex justify-content-between">

                                <v-col cols="2" class="d-flex align-items-center">
                                        <h5>कृयाकलाप लक्ष</h5>
                                        <v-divider class="ml-5" inset vertical></v-divider>
                                </v-col>

                                <v-col cols="6" class="d-flex align-items-center">
                                    <v-select
                                        v-model="filterData.kaaryalaya"
                                        :items="kaaryalaya"
                                        label="कार्यलय"
                                        item-text="name"
                                        item-value="id"
                                        placeholder="कार्यलय"
                                        @input="getDataFromApi"
                                        :disabled="editAllData || 1"
                                    >
                                    </v-select>
                                    <v-select
                                        v-model="filterData.aarthikBarsa"
                                        :items="aarthikBarsa"
                                        label="आर्थिक वर्ष"
                                        item-text="name"
                                        item-value="id"
                                        placeholder="आर्थिक वर्ष"
                                        :disabled="editAllData"
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
                                        @input="getDataFromApi"
                                        :disabled="editAllData"
                                        class="mr-2"
                                    >
                                    </v-select>

                                </v-col>
                                <v-col cols="4" >
                                    <div class="d-flex align-items-center">
                                        <v-file-input v-model="csvData" label=".csv फाईल अपलोड गर्नुहोस्" :disabled="!filterData.aayojana"></v-file-input>
                                        <abbr title="Upload">
                                            <v-btn
                                                fab
                                                small
                                                color="primary"
                                                @click="beforeUploadCsvData"
                                                :loading="uploadCsvDataLoad"
                                                :disabled="!filterData.aayojana"
                                            >
                                                <v-icon>mdi-upload</v-icon>
                                            </v-btn
                                            >
                                        </abbr>
                                    </div>
                                </v-col>

                            </v-row>
                            <v-row >
                                <v-col cols="5" class="d-flex justify-space-between align-items-center">
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="खोजी गर्नुहोस्"
                                        single-line
                                        hide-details
                                        solo
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="2" class="d-flex justify-space-between align-items-center">
                                    <abbr title="Edit" v-if="editAllData == false && filterData.aayojana!=0">
                                        <v-btn
                                            color="primary"
                                            fab
                                            small
                                            dark
                                            @click="toggleEditData"
                                            :loading="editDataButtonLoading"
                                        >
                                            <v-icon>mdi-pencil-outline</v-icon>
                                        </v-btn
                                        >
                                    </abbr>
                                    <abbr title="Add" v-if="editAllData == true">
                                        <v-btn
                                            class="d-flex align-self-center"
                                            fab
                                            small
                                            color="primary"
                                            @click="addKriyakalapLakshya"

                                        >
                                            <v-icon>mdi-plus</v-icon>
                                        </v-btn
                                        >
                                    </abbr>
                                    <abbr title="Save" v-if="editAllData == true">
                                        <v-btn
                                            class="d-flex align-self-center"
                                            fab
                                            small
                                            dark
                                            color="success"
                                            @click="saveKriyakalapLakshya"
                                        >
                                            <v-icon>mdi-content-save</v-icon>
                                        </v-btn
                                        >
                                    </abbr>
                                    <abbr title="Cancel" v-if="editAllData == true">
                                        <v-btn
                                            class="d-flex align-self-center"
                                            fab
                                            small
                                            dark
                                            color="red"
                                            @click="cancelSavingData"
                                        >
                                            <v-icon>mdi-close</v-icon>
                                        </v-btn
                                        >
                                    </abbr>
                                </v-col>

                            </v-row>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.actions="{ item }">
                        <div class="d-flex justify-content-center align-items-center">
                            <v-btn color="red" icon x-small @click="deletePopup(item)">
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </div>
                    </template>

                    <template
                        v-if="editAllData == true"
                        v-slot:item.aayojana.name="{ item }"
                    >
                        <v-select
                            v-model="item.aayojana_id"
                            :items="aayojana"
                            label="आयोजना"
                            item-text="name"
                            item-value="id"
                            placeholder="आयोजना"
                            disabled
                        >
                        </v-select>
                    </template>
                    <template
                        v-if="editAllData == true"
                        v-slot:item.kriyakalap_code="{ item }"
                    >
                        <v-text-field v-model="item.kriyakalap_code" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.name="{ item }">
                        <v-text-field v-model="item.name" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.kharcha_sirsak="{ item }">
                        <v-text-field v-model="item.kharcha_sirsak" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.ikai="{ item }">
                        <v-text-field v-model="item.ikai" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.aayojana_kul_kriyakalap_pariman="{ item }">
                        <v-text-field v-model="item.aayojana_kul_kriyakalap_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.aayojana_kul_kriyakalap_laagat="{ item }">
                        <v-text-field v-model="item.aayojana_kul_kriyakalap_laagat" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.gata_aarthik_barsa_sammako_pariman="{ item }">
                        <v-text-field v-model="item.gata_aarthik_barsa_sammako_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.gata_aarthik_barsa_sammako_laagat="{ item }">
                        <v-text-field v-model="item.gata_aarthik_barsa_sammako_laagat" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.baarsik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.baarsik_lakshya_pariman" type="number" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.baarsik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.baarsik_lakshya_budget" type="number" class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.pahilo_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.pahilo_traimasik_lakshya_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.pahilo_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.pahilo_traimasik_lakshya_budget" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.dosro_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.dosro_traimasik_lakshya_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.dosro_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.dosro_traimasik_lakshya_budget" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.tesro_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.tesro_traimasik_lakshya_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.tesro_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.tesro_traimasik_lakshya_budget" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.chautho_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.chautho_traimasik_lakshya_pariman" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.chautho_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.chautho_traimasik_lakshya_budget" type="number"
                                      class="my-text-field">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.kaifiyat="{ item }">
                        <v-text-field v-model="item.kaifiyat" class="my-text-field">
                        </v-text-field>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-dialog
            v-model="csvDialog"
            max-width="290"
        >
            <v-card>
                <v-card-title class="headline">
                    तपाँई के गर्न चाहनुहुन्छ?
                </v-card-title>

                <v-card-text>
                    यस्तो देखिन्छ कि तपाईंसँग यस आयोजनामा पहिले नै अवस्थित डाटा हरु छन्।
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="uploadCsvData(1)"
                    >
                        पुरै बदल्नुहोस्
                    </v-btn>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="uploadCsvData(0)"
                    >
                        मर्ज गर्नुहोस्
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            deleteItems: [],
            search: "",
            page: 1,
            totalCfData: 0,
            numberOfPages: 0,
            cfData: [],
            loading: false,
            options: {},
            totalItems: 20,
            headers: [
                {text: "आयोजना", value: "aayojana.name"},
                {text: "कृयाकलाप कोड", value: "kriyakalap_code"},
                {text: "नाम", value: "name"},
                {text: "खर्च शिर्षक", value: "kharcha_sirsak"},
                {text: "खर्च प्रकार", value: "kharcha_prakar"},
                {text: "कम्पोनेन्ट", value: "component"},
                {text: "इकाई", value: "ikai"},
                {
                    text: "आयोजना कुल कृयाकलाप परिमान",
                    value: "aayojana_kul_kriyakalap_pariman",
                },
                {
                    text: "आयोजना कुल कृयाकलाप लागत",
                    value: "aayojana_kul_kriyakalap_laagat",
                },
                {
                    text: "गत आर्थिक वर्ष सम्मको परिमान",
                    value: "gata_aarthik_barsa_sammako_pariman",
                },
                {
                    text: "गत आर्थिक वर्ष सम्मको लागत",
                    value: "gata_aarthik_barsa_sammako_laagat",
                },
                {text: "वार्षिक लक्ष परिमान", value: "baarsik_lakshya_pariman"},
                {text: "वार्षिक लक्ष बजेट", value: "baarsik_lakshya_budget"},
                {
                    text: "पहिलो त्रैमासिक लक्ष परिमान",
                    value: "pahilo_traimasik_lakshya_pariman",
                },
                {
                    text: "पहिलो त्रैमासिक लक्ष बजेट",
                    value: "pahilo_traimasik_lakshya_budget",
                },
                {
                    text: "दोश्रो त्रैमासिक लक्ष परिमान",
                    value: "dosro_traimasik_lakshya_pariman",
                },
                {
                    text: "दोश्रो त्रैमासिक लक्ष बजेट",
                    value: "dosro_traimasik_lakshya_budget",
                },
                {
                    text: "तेश्रो त्रैमासिक लक्ष परिमान",
                    value: "tesro_traimasik_lakshya_pariman",
                },
                {
                    text: "तेश्रो त्रैमासिक लक्ष बजेट",
                    value: "tesro_traimasik_lakshya_budget",
                },
                {
                    text: "चौथो त्रैमासिक लक्ष परिमान",
                    value: "chautho_traimasik_lakshya_pariman",
                },
                {
                    text: "चौथो त्रैमासिक लक्ष बजेट",
                    value: "chautho_traimasik_lakshya_budget",
                },
                {text: "कैफियत", value: "kaifiyat"},

            ],
            filterData: {
                aarthikBarsa: "",
                aayojana: 0,
                kaaryalaya: 0,
                user:0,
            },
            editAllData: false,
            editDataButtonLoading: false,
            csvData:null,
            uploadCsvDataLoad:false,
            csvDialog:false,
            kriyakalapLakshya:[

            ]
        };
    },
    mounted() {
        this.filterData.kaaryalaya = this.user.kaaryalaya_id;
        this.filterData.user = this.user.id;
        this.getDataFromApi();
    },
    computed: {
        ...mapState({
            aarthikBarsa: (state) => state.webservice.resources.aarthik_barsa,
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user
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
        beforeUploadCsvData(){
            if(!this.csvData){
                alert('upload first');
            }
            else {
                if (this.kriyakalapLakshya.length > 0) {
                    this.csvDialog = true;
                }
                else{
                    this.uploadCsvData(1);
                }
            }
        },
        uploadCsvData(replace){
            this.csvDialog =false;
            this.uploadCsvDataLoad = true;
            var tempthis = this;
            let formData = new FormData();
            formData.append('csvData',this.csvData);
            formData.append('aarthikBarsa',this.filterData.aarthikBarsa);
            formData.append('aayojana',this.filterData.aayojana);
            formData.append('kaaryalaya',this.filterData.kaaryalaya);
            formData.append('user',this.filterData.user);
            formData.append('replace',replace);
            this.$store.dispatch('uploadKriyakalapLakshya',formData).then(function(response){
                tempthis.uploadCsvDataLoad = false;
                tempthis.csvData = null;
                tempthis.getDataFromApi();
            });
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
            this.getDataFromApi();
        },
        toggleEditData() {
            this.editDataButtonLoading = true;
            this.editAllData = true;
            this.headers.unshift({text: "कार्यहरु", value: "actions"});
            this.editDataButtonLoading = false;
        },
        deletePopup(item) {
            console.log(item);
            if (confirm("Are you sure?")) {
                this.deleteItems.push(item.id);
                this.kriyakalapLakshya.splice(this.kriyakalapLakshya.indexOf(item), 1);
            }
        },
        cancelSavingData() {
            this.getDataFromApi();
            this.editAllData = false;
            this.headers.shift();
        },
        getDataFromApi() {
            const tempthis = this;
            this.loading = true;
            const {page, itemsPerPage} = tempthis.options;
            let pageNumber = page - 1;
            this.$store
                .dispatch("getKriyakalapLakshya", {
                    filterData: this.filterData,
                })
                .then(function (response) {
                    tempthis.loading = false;
                    tempthis.kriyakalapLakshya = response.kriyakalapLakshya
                });
        },
        saveKriyakalapLakshya() {
            var tempthis = this;
            this.$store
                .dispatch("saveKriyakalapLakshya", {
                    items: this.kriyakalapLakshya,
                    deletedItems: this.deleteItems,
                })
                .then(function (response) {
                    tempthis.editAllData = false;
                    tempthis.getDataFromApi();
                    tempthis.headers.shift();
                });

        },
        addKriyakalapLakshya() {
            this.kriyakalapLakshya.unshift({
                aayojana_id: this.filterData.aayojana,
                user_id: this.filterData.user,
                kaaryalaya_id: this.filterData.kaaryalaya,
                kriyakalap_code: "",
                name: "",
                kharcha_sirsak: "",
                ikai: "",
                aayojana_kul_kriyakalap_pariman: "",
                aayojana_kul_kriyakalap_laagat: "",
                gata_aarthik_barsa_sammako_pariman: "",
                gata_aarthik_barsa_sammako_laagat: "",
                baarsik_lakshya_pariman: "",
                baarsik_lakshya_budget: "",
                pahilo_traimasik_lakshya_pariman: "",
                pahilo_traimasik_lakshya_budget: "",
                dosro_traimasik_lakshya_pariman: "",
                dosro_traimasik_lakshya_budget: "",
                tesro_traimasik_lakshya_pariman: "",
                tesro_traimasik_lakshya_budget: "",
                chautho_traimasik_lakshya_pariman: "",
                chautho_traimasik_lakshya_budget: "",
                kaifiyat: "",
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
</style>
