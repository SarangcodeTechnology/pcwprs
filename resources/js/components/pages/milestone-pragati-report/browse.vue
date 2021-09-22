<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">
            <v-col class="d-flex align-items-center" cols="3">
                <h5>माईलस्टोन प्रगती प्रतिवेदन</h5>
                <v-divider class="ml-5" inset vertical></v-divider>
            </v-col>
        </v-row>
        <v-row>
            <v-col class="d-flex align-items-center" cols="9">
                <v-select
                    v-model="filterData.kaaryalaya"
                    :disabled="!$store.getters.CHECK_PERMISSION('maasik_pragati_report-select_kaaryalaya')"
                    :items="kaaryalaya"
                    chips
                    class="mr-2"
                    item-text="name"
                    item-value="id"
                    label="कार्यलय" multiple
                    placeholder="कार्यलय"
                    @input="changeInKaryalaya"
                >
                    <template v-slot:prepend-item>
                        <v-list-item
                            ripple
                            @click="toggle"
                        >
                            <v-list-item-action>
                                <v-icon :color="filterData.kaaryalaya.length > 0 ? 'green darken-4' : ''">
                                    {{ icon }}
                                </v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>
                                    Select All
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider class="mt-2"></v-divider>
                    </template>
                </v-select>
                <v-select
                    v-model="filterData.aarthikBarsa"
                    :items="aarthikBarsa"
                    class="mr-2"
                    item-text="name"
                    item-value="id"
                    label="आर्थिक वर्ष"
                    placeholder="आर्थिक वर्ष"
                    @input="changeInArthikBarsa"
                >
                </v-select>
                <v-select
                    v-model="filterData.aayojana"
                    :items="aayojana"
                    class="mr-2"
                    item-text="name"
                    item-value="id"
                    label="आयोजना"
                    placeholder="आयोजना"
                    @input="changeInAayojana"
                >
                </v-select>
                <v-select
                    v-if="filterData.aayojana"
                    v-model="filterData.mahina"
                    :items="mahina"
                    item-text="name"
                    item-value="id"
                    label="महिना"
                    placeholder="महिना"
                    @input="changeInMahina"
                >
                </v-select>
            </v-col>
        </v-row>
        <v-row v-if="filterData.mahina && milestoneData.length>0">
            <v-col cols="auto">
                <v-btn target="_blank" @click="storeData">
                    Print
                </v-btn>

            </v-col>
            <v-spacer></v-spacer>
            <v-col cols="auto">
                <v-btn color="success" target="_blank" @click="saveData">
                    Save
                </v-btn>
            </v-col>
        </v-row>
        <v-row v-if="filterData.mahina && milestoneData">

            <v-col>
                <milestone-print :passedFillable=false></milestone-print>
            </v-col>
        </v-row>
        <v-row v-else-if="filterData.mahina">
            <v-col cols="12">
                <v-alert
                    dense
                    border="left"
                    type="warning"
                >
                    No Data Available
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            filterData: {
                kaaryalaya: [],
                user: 0,
                aarthikBarsa: "",
                aayojana: 0,
                mahina: 0
            },
            mahina: [],
            maasikPragatiTaalika: [],

        };
    },
    mounted() {
        this.filterData.kaaryalaya.push(this.user.kaaryalaya_id);
        this.filterData.user = this.user.id;
    },
    created() {
        this.mahina = JSON.parse(JSON.stringify(this.stateMahina));
        this.mahina.push({id: 13, name: "वार्षिक"});
        this.mahina.push({id: 14, name: "अर्द वार्षिक"});
    },
    computed: {
        icon() {
            if (this.selectsAllKaryalaya) return 'mdi-close-box'
            if (this.selectsSomeKaryalaya) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
        selectsAllKaryalaya() {
            return this.filterData.kaaryalaya.length === this.kaaryalaya.length
        },
        selectsSomeKaryalaya() {
            return this.filterData.kaaryalaya.length > 0 && !this.selectsAllKaryalaya
        },
        ...mapState({
            stateMahina: (state) => state.webservice.resources.mahina,
            aarthikBarsa: (state) => state.webservice.resources.aarthik_barsa,
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user,
            milestoneData: (state) => state.webservice.milestonePragatiReports,
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
        saveData() {
            const temp = this;
            this.$store.commit('SET_MILESTONE_PRAGATI_REPORT', this.milestoneData);
            this.$store.dispatch('makePostRequest', {
                data: {items: temp.milestoneData},
                route: 'save-milestone-data'
            }).then(function (response) {
                temp.getDataFromApi();
            });
        },
        storeData() {
            this.saveData();
            this.$store.commit('SET_MILESTONE_PRAGATI_REPORT', this.milestoneData);
            window.open('/milestone-print', '_blank').focus();
        },
        toggle() {
            this.$nextTick(() => {
                if (this.selectsAllKaryalaya) {
                    this.filterData.kaaryalaya = []
                } else {
                    this.filterData.kaaryalaya = this.kaaryalaya.slice().map(function (val) {
                        return val.id;
                    })
                }
            })
        },
        printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        },
        changeInKaryalaya() {
            this.filterData.aarthikBarsa = 0;
            this.filterData.aayojana = 0;
            this.filterData.mahina = 0;
        },
        changeInAayojana() {
            this.filterData.mahina = 0;
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
            this.filterData.mahina = 0
        },
        changeInMahina() {
            this.getDataFromApi();
        },
        getDataFromApi() {
            const tempthis = this;
            this.$store
                .dispatch("getMilestonePragatiReport", {
                    filterData: this.filterData,
                })
                .then(function (response) {

                });
        },

    },
};
</script>
<style scoped>
table td {
    padding: 0px 10px 0px 10px;
}

.my-text-field {
    width: 150px;
}

v-select {
    width: 20px;
}

abbr {
    text-decoration: none;
}

v-tab-items {
    min-width: 400px;
}

@media print {
    body * {
        visibility: hidden;
    }

    #printable, #printable * {
        visibility: visible;
    }

    #printable {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
