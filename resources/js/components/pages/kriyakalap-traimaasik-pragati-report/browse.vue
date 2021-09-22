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
                    multiple chips
                    @input="changeInKaryalaya"
                    :disabled="!$store.getters.CHECK_PERMISSION('traimaasik_pragati_report-select_kaaryalaya')"
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
        <v-row v-if="filterData.traimaasik && traimaasikPragatiReports.length>0">
            <v-col cols="12">
                <v-btn target="_blank" href="/traimaasik-print">
                    Print
                </v-btn>
            </v-col>
            <v-col>
                <traimaasik-print :passedFillable="false"></traimaasik-print>
            </v-col>
        </v-row>
        <v-row v-else-if="filterData.traimaasik">
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
                traimaasik: 0
            },
            traimaasik:[]
        };
    },
    mounted() {
        this.filterData.kaaryalaya.push(this.user.kaaryalaya_id);
        this.filterData.user = this.user.id;
    },
    created(){
        this.traimaasik = JSON.parse(JSON.stringify(this.stateTraimaasik));
        this.traimaasik.push({id:5,initial:"chautho",name:"वार्षिक"});
        this.traimaasik.push({id:6,initial:"dosro",name:"अर्द वार्षिक"});
    },
    computed: {
        ...mapState({
            stateTraimaasik: (state) => state.webservice.resources.traimaasik,
            aarthikBarsa: (state) => state.webservice.resources.aarthik_barsa,
            kaaryalaya: (state) => state.webservice.resources.kaaryalaya,
            user: (state) => state.auth.user,
            traimaasikPragatiReports: (state) => state.webservice.traimaasikPragatiReports,
        }),
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
        changeInKaryalaya() {
            this.filterData.aarthikBarsa = 0;
            this.filterData.aayojana = 0;
            this.filterData.mahina = 0;
        },
        changeInAayojana() {
            this.filterData.traimaasik = 0;
        },
        changeInArthikBarsa() {
            this.filterData.aayojana = 0;
        },
        changeInTraimaasik() {
            this.getDataFromApi();
        },
        getDataFromApi() {
            const tempthis = this;
            this.$store
                .dispatch("getTraimaasikPragatiTaalikaReport", {
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
