<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">

            <v-col cols="3" class="d-flex align-items-center">
                <h5>कृयाकलाप मासिक प्रगती</h5>
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
        <v-row v-if="filterData.mahina">
            <v-col cols="12">
                <v-btn target="_blank" href="/maasik-print">
                    Print
                </v-btn>
            </v-col>
            <v-col>
                <div id="printableArea">
                    <div style="text-align: center">
                        <p><strong>नेपाल सरकार</strong></p>
                        <p><strong>रास्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</strong></p>
                        <p><strong>दोश्रो चौमासिक प्रगति</strong></p>
                        <table border="1" cellspacing="0" style="margin:auto">
                            <thead>
                            <tr>
                                <td rowspan="2" height="49"><strong>क्र.सं.</strong></td>
                                <td rowspan="2"><strong>कार्यक्रम /क्रियाकलाप</strong></td>
                                <td rowspan="2"><strong>खर्च शीर्षक</strong></td>
                                <td rowspan="2"><strong>इकाई</strong></td>
                                <td colspan="3"><strong>बार्षिक लक्ष्य</strong></td>
                                <td colspan="3"><strong>{{ maasikPragatiReport.month }} महिनाको प्रगति</strong></td>
                                <td colspan="3"><strong>प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति</strong></td>
                                <td colspan="2" rowspan="2"><strong>भौतिक प्रगति</strong></td>
                            </tr>
                            <tr>
                                <td><strong>परिमाण</strong></td>
                                <td><strong>भार</strong></td>
                                <td><strong>बजेट</strong></td>
                                <td><strong>परिमाण</strong></td>
                                <td><strong>भारित</strong></td>
                                <td><strong>खर्च</strong></td>
                                <td><strong>परिमाण</strong></td>
                                <td><strong>भारित</strong></td>
                                <td><strong>खर्च</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,index) in maasikPragatiReport.items" :key="index">
                                <td>{{item.kriyakalap_code}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.kharcha_sirsak}}</td>
                                <td>{{item.ikai}}</td>
                                <td>{{item.baarsik_lakshya_pariman}}</td>
                                <td>{{item.baarsik_lakshya_vaar}}</td>
                                <td>{{item.baarsik_lakshya_budget}}</td>
                                <td>{{item.maasik_pragati.pariman ? item.maasik_pragati.pariman :'' }}</td>
                                <td>{{item.maasik_pragati.vaarit ? item.maasik_pragati.vaarit :''}}</td>
                                <td>{{item.maasik_pragati.kharcha ? item.maasik_pragati.kharcha :''}}</td>
                                <td>{{item.total_till_now.pariman ? item.total_till_now.pariman : ''}}</td>
                                <td>{{item.total_till_now.vaarit ? item.total_till_now.vaarit : ''}}</td>
                                <td>{{item.total_till_now.kharcha ? item.total_till_now.kharcha : ''}}</td>
                                <td>{{item.vautik_pragati ? item.vautik_pragati : ''}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                kaaryalaya: 0,
                user: 0,
                aarthikBarsa: "",
                aayojana: 0,
                mahina: 0
            },

            maasikPragatiTaalika: [],

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
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user,
            maasikPragatiReport: (state) => state.webservice.maasikPragatiReport,
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
        printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
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
                .dispatch("getMaasikPragatiTaalikaReport", {
                    filterData: this.filterData,
                })
                .then(function (response) {

                });
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
