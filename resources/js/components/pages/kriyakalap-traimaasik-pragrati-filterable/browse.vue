<template>
    <v-container fluid>
        <v-row class="d-flex justify-content-between">

            <v-col cols="3" class="d-flex align-items-center">
                <h5>त्रैमासिक प्रगती</h5>
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
                <v-autocomplete
                    v-model="filterData.aarthikBarsa"
                    :items="aarthikBarsa"
                    label="आर्थिक वर्ष"
                    item-text="name"
                    item-value="id"
                    placeholder="आर्थिक वर्ष"
                    @input="changeInArthikBarsa"
                    class="mr-2"
                    multiple chips
                >
                </v-autocomplete>
                <v-autocomplete
                    v-model="filterData.aayojana"
                    :items="aayojana"
                    label="आयोजना"
                    item-text="name"
                    item-value="id"
                    placeholder="आयोजना"
                    @input="changeInAayojana"
                    class="mr-2"
                    multiple chips
                >
                </v-autocomplete>
                <v-autocomplete
                    v-model="filterData.kharchaPrakar"
                    :items="['चालु','पुँजीगत']"
                    label="खर्च प्रकार"
                    placeholder="खर्च प्रकार"
                    class="mr-2"
                    @input="filterData.traimaasik ? getDataFromApi() : ''"
                    multiple chips
                >
                </v-autocomplete>
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
        <v-row v-if="filterData.traimaasik">
            <v-col cols="12">
                <v-btn target="_blank" href="/traimaasik-print-filterable">
                    Print
                </v-btn>
            </v-col>
            <v-col>
                <v-data-table  disable-pagination :hide-default-footer="true" class="elevation-1" :headers="traimaasikPragatiReportFilterable.headers" :items="traimaasikPragatiReportFilterable.items">
                    <template v-slot:body.append>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>जम्मा</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[5].value)}}</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[6].value)}}</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[7].value)}}</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[8].value)}}</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[9].value)}}</th>
                            <th>{{sumField(traimaasikPragatiReportFilterable.headers[10].value)}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['traimaasik_pragati_pariman']}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['traimaasik_pragati_vaarit']}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['traimaasik_pragati_kharcha']}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['total_till_now_pariman']}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['total_till_now_vaarit']}}</th>
                            <th>{{traimaasikPragatiReportFilterable.sum['total_till_now_kharcha']}}</th>
                        </tr>
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
            filterData: {
                kaaryalaya: 0,
                user: 0,
                aarthikBarsa: [],
                aayojana: 0,
                traimaasik: 0,
                kharchaPrakar: []
            },
            aayojana: [],
            sum:[]
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
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user,
            traimaasikPragatiReportFilterable: (state) => state.webservice.traimaasikPragatiReportFilterable,
        }),


    },
    methods: {
        sumField(key) {
            // sum data in give key (property)
            return Math.round((this.traimaasikPragatiReportFilterable.items.reduce((a, b) => a + (b[key] || 0), 0)  + Number.EPSILON) * 100) / 100;
        },
        changeInAayojana() {
            if (this.filterData.traimaasik) {
                this.getDataFromApi();
            }
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
            const tempthis = this;
            var data = [];
            if (this.filterData.aarthikBarsa) {
                this.aarthikBarsa.forEach(function (item) {
                    if (tempthis.filterData.aarthikBarsa.includes(item.id)) {
                        item.aayojana.forEach(function (aayojanaItem) {
                            data.push(aayojanaItem);
                        })
                    }
                })
            } else {
                data = [];
            }
            this.aayojana = data;
        },
        changeInTraimaasik() {
            this.getDataFromApi();

        },
        getDataFromApi() {
            const tempthis = this;
            this.$store
                .dispatch("getTraimaasikPragatiReportFilterable", {
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
    padding: 0px 3px 0px 3px;
}

p {
    margin: 0;
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
