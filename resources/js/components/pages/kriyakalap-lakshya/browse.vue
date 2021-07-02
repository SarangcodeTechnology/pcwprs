<template>
    <v-container fluid>
        <v-row>
            <v-col cols="auto">
                <h4><strong>कृयाकलाप लक्ष</strong></h4>
                <v-divider class="ml-5" inset vertical></v-divider>
            </v-col>
            <v-spacer></v-spacer>
           <v-col cols="auto"> <v-btn class="custom-button mr-3" color="deep-orange" dark
                          href="/downloads/lakshya-template.csv">
               <v-icon>mdi-file-excel</v-icon>
               ढाचा डाउनलोड
           </v-btn></v-col>
        </v-row>
        <v-row>

            <v-col cols="3">
                <v-select
                    v-model="filterData.kaaryalaya"
                    :disabled="!$store.getters.CHECK_PERMISSION('kriyakalap_lakshya-select_kaaryalaya')"
                    :items="kaaryalaya"
                    item-text="name"
                    item-value="id"
                    label="कार्यलय"
                    placeholder="कार्यलय"
                    @input="getDataFromApi"
                >
                </v-select>
            </v-col>
            <v-col cols="3">
                <v-select
                    v-model="filterData.aarthikBarsa"
                    :disabled="editAllData"
                    :items="aarthikBarsa"
                    class="mr-2"
                    item-text="name"
                    item-value="id"
                    label="आर्थिक वर्ष"
                    placeholder="आर्थिक वर्ष"
                    @input="changeInArthikBarsa"
                >
                </v-select>
            </v-col>
            <v-col cols="3">
                <v-select
                    v-model="filterData.aayojana"
                    :disabled="editAllData"
                    :items="aayojana"
                    class="mr-2"
                    item-text="name"
                    item-value="id"
                    label="आयोजना"
                    placeholder="आयोजना"
                    @input="getDataFromApi"
                >
                </v-select>
            </v-col>
            <v-col cols="3">
                <div class="d-flex align-items-center">
                    <v-file-input v-model="csvData" :disabled="!filterData.aayojana"
                                  label=".csv फाईल अपलोड गर्नुहोस्"></v-file-input>
                    <abbr title="Upload">
                        <v-btn
                            :disabled="!filterData.aayojana"
                            :loading="uploadCsvDataLoad"
                            color="primary"
                            fab
                            small
                            @click="beforeUploadCsvData"
                        >
                            <v-icon>mdi-upload</v-icon>
                        </v-btn
                        >
                    </abbr>
                </div>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :hide-default-footer="true"
                    :items="kriyakalapLakshya"
                    :items-per-page="20"
                    :loading="loading"
                    :options.sync="options"
                    :page="page"
                    :pageCount="numberOfPages"
                    :search="search"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:top="{ pagination, options, updateOptions }">
                        <v-row>
                            <v-col  cols="5">
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="खोजी गर्नुहोस्"
                                    outlined
                                    depressed
                                ></v-text-field>
                            </v-col>
                            <v-col cols="auto">
                                <abbr v-if="editAllData == false && filterData.aayojana!=0" title="Edit">
                                    <v-btn
                                        :loading="editDataButtonLoading"
                                        color="primary"
                                        dark
                                        fab
                                        small
                                        @click="toggleEditData"
                                    >
                                        <v-icon>mdi-pencil-outline</v-icon>
                                    </v-btn
                                    >
                                </abbr>
                                <abbr v-if="editAllData == true" title="Add">
                                    <v-btn
                                        class="d-flex align-self-center"
                                        color="primary"
                                        fab
                                        small
                                        @click="addKriyakalapLakshya"

                                    >
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn
                                    >
                                </abbr>
                                <abbr v-if="editAllData == true" title="Save">
                                    <v-btn
                                        class="d-flex align-self-center"
                                        color="success"
                                        dark
                                        fab
                                        small
                                        @click="saveKriyakalapLakshya"
                                    >
                                        <v-icon>mdi-content-save</v-icon>
                                    </v-btn
                                    >
                                </abbr>
                                <abbr v-if="editAllData == true" title="Cancel">
                                    <v-btn
                                        class="d-flex align-self-center"
                                        color="red"
                                        dark
                                        fab
                                        small
                                        @click="cancelSavingData"
                                    >
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn
                                    >
                                </abbr>
                            </v-col>
                            <v-spacer></v-spacer>
                            <v-col cols="auto">
                                <v-data-footer
                                    :items-per-page-options="[20, 50, 100, 500]"
                                    :options="options"
                                    :pagination="pagination"
                                    items-per-page-text="$vuetify.dataTable.itemsPerPageText"
                                    @update:options="updateOptions"
                                />
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
                            disabled
                            item-text="name"
                            item-value="id"
                            label="आयोजना"
                            placeholder="आयोजना"
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
                        <v-text-field v-model="item.name" class="my-text-field"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.kharcha_sirsak="{ item }">
                        <v-text-field v-model="item.kharcha_sirsak" class="my-text-field"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.ikai="{ item }">
                        <v-text-field v-model="item.ikai" class="my-text-field"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.aayojana_kul_kriyakalap_pariman="{ item }">
                        <v-text-field v-model="item.aayojana_kul_kriyakalap_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.aayojana_kul_kriyakalap_laagat="{ item }">
                        <v-text-field v-model="item.aayojana_kul_kriyakalap_laagat"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.gata_aarthik_barsa_sammako_pariman="{ item }">
                        <v-text-field v-model="item.gata_aarthik_barsa_sammako_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.gata_aarthik_barsa_sammako_laagat="{ item }">
                        <v-text-field v-model="item.gata_aarthik_barsa_sammako_laagat"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.baarsik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.baarsik_lakshya_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.baarsik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.baarsik_lakshya_budget"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.pahilo_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.pahilo_traimasik_lakshya_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.pahilo_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.pahilo_traimasik_lakshya_budget"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.dosro_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.dosro_traimasik_lakshya_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.dosro_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.dosro_traimasik_lakshya_budget"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.tesro_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.tesro_traimasik_lakshya_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.tesro_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.tesro_traimasik_lakshya_budget"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.chautho_traimasik_lakshya_pariman="{ item }">
                        <v-text-field v-model="item.chautho_traimasik_lakshya_pariman"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.chautho_traimasik_lakshya_budget="{ item }">
                        <v-text-field v-model="item.chautho_traimasik_lakshya_budget"
                                      class="my-text-field" type="number"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-if="editAllData == true" v-slot:item.kaifiyat="{ item }">
                        <v-text-field v-model="item.kaifiyat" class="my-text-field"
                                      @input="addEditedKriyakalapLakshyaId(item.id)">
                        </v-text-field>
                    </template>
                    <template v-slot:item.milestone="{item}">
                        <span v-if="item.milestone"><strong>माईलस्टोन</strong></span>
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
                {text: "कम्पोनेन्ट आईडी", value: "component_id"},
                {text: "कम्पोनेन्ट", value: "component"},
                {text: "माईलस्टोन", value: "milestone"},
                {text: "कृयाकलाप कोड", value: "kriyakalap_code"},
                {text: "नाम", value: "name"},
                {text: "खर्च शिर्षक", value: "kharcha_sirsak"},
                {text: "खर्च प्रकार", value: "kharcha_prakar"},
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
                user: 0,
            },
            editedKriyakalapLakshyaID: [],
            editAllData: false,
            editDataButtonLoading: false,
            csvData: null,
            uploadCsvDataLoad: false,
            csvDialog: false,
            kriyakalapLakshya: []
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
        addEditedKriyakalapLakshyaId(id) {
            if (!this.editedKriyakalapLakshyaID.includes(id)) {
                this.editedKriyakalapLakshyaID.push(id)
            }
        },
        beforeUploadCsvData() {
            if (!this.csvData) {
                alert('upload first');
            } else {
                if (this.kriyakalapLakshya.length > 0) {
                    this.csvDialog = true;
                } else {
                    this.uploadCsvData(1);
                }
            }
        },
        uploadCsvData(replace) {
            this.csvDialog = false;
            this.uploadCsvDataLoad = true;
            var tempthis = this;
            let formData = new FormData();
            formData.append('csvData', this.csvData);
            formData.append('aarthikBarsa', this.filterData.aarthikBarsa);
            formData.append('aayojana', this.filterData.aayojana);
            formData.append('kaaryalaya', this.filterData.kaaryalaya);
            formData.append('user', this.filterData.user);
            formData.append('replace', replace);
            this.$store.dispatch('uploadKriyakalapLakshya', formData).then(function (response) {
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
            this.$root.confirm('मेट्ने पुष्टि गर्नुहोस्', 'के तपाईं ' + item.name + ' मेट्न निश्चित हुनुहुन्छ ?', {color: 'red'}).then((confirm) => {
                this.deleteItems.push(item.id);
                this.kriyakalapLakshya.splice(this.kriyakalapLakshya.indexOf(item), 1);
            }).catch((error) => {
                console.log(error);
            });
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
            let tempthis = this;
            var items = this.kriyakalapLakshya.filter(function (item) {
                return tempthis.editedKriyakalapLakshyaID.includes(item.id);
            });
            this.$store
                .dispatch("saveKriyakalapLakshya", {
                    items: items,
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

.custom-button {
    text-decoration: none;
}

v-select {
    width: 20px;
}

abbr {
    text-decoration: none;
}
</style>
