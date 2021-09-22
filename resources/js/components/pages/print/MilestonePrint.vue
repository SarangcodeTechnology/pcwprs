<template>
    <v-container>
        <!-- single print page -->
        <v-app v-if="fillable">
            <v-navigation-drawer v-model="dialog" app class="nonPrintableArea" right>
                <v-container>
                    <h4><strong>प्रिन्ट विवरण लेख्नुहोस्</strong></h4>
                    <v-text-field v-model="miti" label="मिति" outlined></v-text-field>
                    <h5>१.तयार गर्ने</h5>
                    <v-text-field
                        v-model="tayarGarneNaam"
                        label="नाम"
                        outlined
                    ></v-text-field>
                    <v-text-field
                        v-model="tayarGarnePad"
                        label="पद"
                        outlined
                    ></v-text-field>
                    <h5>२.आ.प्र.शाखा</h5>
                    <v-text-field v-model="apraNaam" label="नाम" outlined></v-text-field>
                    <v-text-field v-model="apraPad" label="पद" outlined></v-text-field>
                    <h5>३.चेक गर्ने</h5>
                    <v-text-field v-model="checkNaam" label="नाम" outlined></v-text-field>
                    <v-text-field v-model="checkPad" label="पद" outlined></v-text-field>

                    <h5>४.प्रमाणित गर्ने</h5>
                    <v-text-field
                        v-model="pramaditNaam"
                        label="नाम"
                        outlined
                    ></v-text-field>
                    <v-text-field
                        v-model="pramaditPad"
                        label="पद"
                        outlined
                    ></v-text-field>

                    <v-btn color="primary" depressed @click="print()">
                        <v-icon>mdi-printer</v-icon>
                        <span>Ready to Print</span></v-btn
                    >
                </v-container>
            </v-navigation-drawer>

            <v-main app class="printableArea">
                <v-container>
                    <v-row class="pa-2 nonPrintableArea">
                        <v-spacer></v-spacer>
                        <v-btn color="success" dark depressed @click="dialog = !dialog">
                            <v-icon>mdi-printer</v-icon>
                            <span> Print </span>
                        </v-btn>
                    </v-row>

                    <v-row>
                        <div id="printableArea">
                            <div style="position: relative; margin-bottom: 5px">

                                <div style="text-align: center">
                                    <h5>नेपाल सरकार</h5>
                                    <h5>राष्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
                                    <h6>खुमालटार, ललितपुर</h6>
                                    <h5><strong>नीति तथा कार्यक्रमको मासिक प्रगति विवरण</strong></h5>
                                    <h4>
                                        <!-- <strong>{{ milestonePragatiReports[0].month }}</strong> -->
                                    </h4>
                                </div>
                            </div>
                            <div style="text-align: center">
                                <table
                                    border="1"
                                    cellspacing="0"
                                    style="margin: auto; text-align: left"
                                >
                                    <thead>
                                    <tr>
                                        <th>नीति तथा कार्यक्रमको बुंदा न.</th>
                                        <th>बजेट बक्तव्यको बुँदा नं</th>
                                        <th>क्रियाकलाप</th>
                                        <th>लक्ष</th>
                                        <th>माइलस्टोन</th>
                                        <th>समयावधि</th>
                                        <th>माईलस्टोन स्थिति ( कार्य शुरु नभएको/काम भईरहेको/सम्पन्न )</th>
                                        <th>{{ milestoneData.mahina }} महिनाको प्रगति</th>
                                        <th>
                                            १. कार्य शुरु हुन नसकेको कारण २. काम भईरहेको तर समयसिमा भित्र
                                            सम्पन्न गर्न नसक्नुका कारण उल्लेख गर्ने
                                        </th>
                                        <th>हालसम्मको प्रगति</th>
                                        <th>कैफियत</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- here loop starts -->
                                    <!-- kaaryalaya loop -->
                                    <template v-for="(itemFirst, indexFirst) in milestoneData.milestonePragatiReports">
                                        <tr style="background: orange">
                                            <td colspan="11">{{ itemFirst.kaaryalaya.name }}</td>
                                        </tr>
                                        <!-- second loop -->
                                        <template v-for="(itemSecond,indexSecond) in itemFirst.items">
                                            <tr>
                                                <td rowspan="6">१२३</td>
                                                <td rowspan="6">२३४</td>
                                                <td rowspan="6">{{ itemSecond.milestone_lakshya.name }}</td>
                                                <td rowspan="6">233</td>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_suru_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_suru_samayavadhi
                                                    }}
                                                </td>
                                                <td>प्रारम्भिक कार्यको शुरु प्रगाती</td>
                                                <td>{{ itemSecond.prarambhik_karya_suru_pragati }}</td>
                                                <td>{{ itemSecond.milestone_report.prarambhik_karya_suru_reason }}</td>
                                                <td>{{ itemSecond.total_prarambhik_karya_suru_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                            <tr>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_jari_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_jari_samayavadhi
                                                    }}
                                                </td>
                                                <td>प्रारम्भिक कार्यको जारी प्रगति</td>
                                                <td>{{ itemSecond.prarambhik_karya_jari_pragati }}</td>
                                                <td>{{ itemSecond.milestone_report.prarambhik_karya_jari_reason }}</td>
                                                <td>{{ itemSecond.total_prarambhik_karya_jari_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                            <tr>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_sampanna_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_sampanna_samayavadhi
                                                    }}
                                                </td>
                                                <td>प्रारम्भिक कार्यको सम्पन्न प्रगति</td>
                                                <td>{{ itemSecond.prarambhik_karya_sampanna_pragati }}</td>
                                                <td>{{
                                                        itemSecond.milestone_report.prarambhik_karya_sampanna_reason
                                                    }}
                                                </td>
                                                <td>{{ itemSecond.total_prarambhik_karya_sampanna_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                            <tr>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_suru_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_suru_samayavadhi
                                                    }}
                                                </td>
                                                <td>कार्यक्रम कार्यान्वयनको शुरु प्रगाती</td>
                                                <td>{{ itemSecond.karyakram_karyanayan_suru_pragati }}</td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_suru_reason
                                                    }}
                                                </td>
                                                <td>{{ itemSecond.total_karyakram_karyanayan_suru_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                            <tr>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_jari_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_jari_samayavadhi
                                                    }}
                                                </td>
                                                <td>कार्यक्रम कार्यान्वयनको जारी प्रगति</td>
                                                <td>{{ itemSecond.karyakram_karyanayan_jari_pragati }}</td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_jari_reason
                                                    }}
                                                </td>
                                                <td>{{ itemSecond.total_karyakram_karyanayan_jari_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                            <tr>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_sampanna_milestone
                                                    }}
                                                </td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_sampanna_samayavadhi
                                                    }}
                                                </td>
                                                <td>कार्यक्रम कार्यान्वयनको सम्पन्न प्रगति</td>
                                                <td>{{ itemSecond.karyakram_karyanayan_sampanna_pragati }}</td>
                                                <td>{{
                                                        itemSecond.milestone_report.karyakram_karyanayan_sampanna_reason
                                                    }}
                                                </td>
                                                <td>{{ itemSecond.total_karyakram_karyanayan_sampanna_pragati }}</td>
                                                <td>remarks</td>
                                            </tr>
                                        </template>
                                    </template>

                                    <!-- here loop ends -->
                                    </tbody>
                                </table>
                            </div>
                            <div
                                style="
                  display: flex;
                  justify-content: space-between;
                  margin-top: 15px;
                "
                            >
                                <div>
                                    <p><strong>तयार गर्ने</strong></p>
                                    <p>नाम: {{ tayarGarneNaam }}</p>
                                    <p>पद: {{ tayarGarnePad }}</p>
                                    <p>मिति: {{ miti }}</p>
                                </div>
                                <div>
                                    <p><strong>आ.प्र.शाखा</strong></p>
                                    <p>नाम: {{ apraNaam }}</p>
                                    <p>पद: {{ apraPad }}</p>
                                    <p>मिति: {{ miti }}</p>
                                </div>
                                <div>
                                    <p><strong>चेक गर्ने</strong></p>
                                    <p>नाम: {{ checkNaam }}</p>
                                    <p>पद: {{ checkPad }}</p>
                                    <p>मिति: {{ miti }}</p>
                                </div>
                                <div>
                                    <p><strong>प्रमाणित गर्ने</strong></p>
                                    <p>नाम: {{ pramaditNaam }}</p>
                                    <p>पद: {{ pramaditPad }}</p>
                                    <p>मिति: {{ miti }}</p>
                                </div>
                            </div>
                        </div>
                    </v-row>
                </v-container>
            </v-main>
        </v-app>

        <!-- inside page -->
        <div v-else>
            <div style="position: relative; margin-bottom: 5px">
                <div style="text-align: center">
                    <h5>नेपाल सरकार</h5>
                    <h5>राष्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
                    <h6>खुमालटार, ललितपुर</h6>
                    <h5><strong>नीति तथा कार्यक्रमको मासिक प्रगति विवरण</strong></h5>
                </div>
            </div>
            <div style="text-align: center">
                <table
                    border="1"
                    cellspacing="0"
                    style="margin: auto; text-align: left"
                >
                    <thead>
                    <tr>
                        <th>नीति तथा कार्यक्रमको बुंदा न.</th>
                        <th>बजेट बक्तव्यको बुँदा नं</th>
                        <th>क्रियाकलाप</th>
                        <th>लक्ष</th>
                        <th>माइलस्टोन</th>
                        <th>समयावधि</th>
                        <th>माईलस्टोन स्थिति ( कार्य शुरु नभएको/काम भईरहेको/सम्पन्न )</th>
                        <th>{{ milestoneData.mahina }} महिनाको प्रगति</th>
                        <th>
                            १. कार्य शुरु हुन नसकेको कारण २. काम भईरहेको तर समयसिमा भित्र
                            सम्पन्न गर्न नसक्नुका कारण उल्लेख गर्ने
                        </th>
                        <th>हालसम्मको प्रगति</th>
                        <th>कैफियत</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- here loop starts -->
                    <!-- kaaryalaya loop -->
                    <template v-for="(itemFirst, indexFirst) in milestoneData.milestonePragatiReports">
                        <tr style="background: orange">
                            <td colspan="11">{{ itemFirst.kaaryalaya.name }}</td>
                        </tr>
                        <!-- second loop -->
                        <template v-for="(itemSecond,indexSecond) in itemFirst.items">
                            <tr>
                                <td rowspan="6">१२३</td>
                                <td rowspan="6">२३४</td>
                                <td rowspan="6">{{ itemSecond.milestone_lakshya.name }}</td>
                                <td rowspan="6">233</td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_suru_milestone"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_suru_samayavadhi"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>प्रारम्भिक कार्यको शुरु प्रगाती</td>
                                <td>{{ itemSecond.prarambhik_karya_suru_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_suru_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_prarambhik_karya_suru_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_jari_milestone"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_jari_samayavadhi"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>प्रारम्भिक कार्यको जारी प्रगति</td>
                                <td>{{ itemSecond.prarambhik_karya_jari_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_jari_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_prarambhik_karya_jari_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_sampanna_milestone"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea
                                    v-model="itemSecond.milestone_report.prarambhik_karya_sampanna_samayavadhi"
                                    placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>प्रारम्भिक कार्यको सम्पन्न प्रगति</td>
                                <td>{{ itemSecond.prarambhik_karya_sampanna_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.prarambhik_karya_sampanna_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_prarambhik_karya_sampanna_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td><textarea v-model="itemSecond.milestone_report.karyakram_karyanayan_suru_milestone"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea
                                    v-model="itemSecond.milestone_report.karyakram_karyanayan_suru_samayavadhi"
                                    placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>कार्यक्रम कार्यान्वयनको शुरु प्रगाती</td>
                                <td>{{ itemSecond.karyakram_karyanayan_suru_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.karyakram_karyanayan_suru_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_karyakram_karyanayan_suru_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td><textarea v-model="itemSecond.milestone_report.karyakram_karyanayan_jari_milestone"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea
                                    v-model="itemSecond.milestone_report.karyakram_karyanayan_jari_samayavadhi"
                                    placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>कार्यक्रम कार्यान्वयनको जारी प्रगति</td>
                                <td>{{ itemSecond.karyakram_karyanayan_jari_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.karyakram_karyanayan_jari_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_karyakram_karyanayan_jari_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td><textarea
                                    v-model="itemSecond.milestone_report.karyakram_karyanayan_sampanna_milestone"
                                    placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td><textarea
                                    v-model="itemSecond.milestone_report.karyakram_karyanayan_sampanna_samayavadhi"
                                    placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>कार्यक्रम कार्यान्वयनको सम्पन्न प्रगति</td>
                                <td>{{ itemSecond.karyakram_karyanayan_sampanna_pragati }}</td>
                                <td><textarea v-model="itemSecond.milestone_report.karyakram_karyanayan_sampanna_reason"
                                              placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                                <td>{{ itemSecond.total_karyakram_karyanayan_sampanna_pragati }}</td>
                                <td>remarks</td>
                            </tr>
                        </template>
                    </template>

                    <!-- here loop ends -->
                    </tbody>
                </table>
            </div>
            <div
                style="display: flex; justify-content: space-between; margin-top: 15px"
            >
                <div>
                    <p><strong>तयार गर्ने</strong></p>
                    <p>नाम:</p>
                    <p>पद:</p>
                    <p>मिति:</p>
                </div>
                <div>
                    <p><strong>आ.प्र.शाखा</strong></p>
                    <p>नाम:</p>
                    <p>पद:</p>
                    <p>मिति:</p>
                </div>
                <div>
                    <p><strong>चेक गर्ने</strong></p>
                    <p>नाम:</p>
                    <p>पद:</p>
                    <p>मिति:</p>
                </div>
                <div>
                    <p><strong>प्रमाणित गर्ने</strong></p>
                    <p>नाम:</p>
                    <p>पद:</p>
                    <p>मिति:</p>
                </div>
            </div>
        </div>
    </v-container>
</template>


<script>
import {mapState} from "vuex";

export default {
    props: ["passedFillable"],
    data() {
        return {
            fillable: false,
            dialog: false,
            tayarGarneNaam: "",
            tayarGarnePad: "",
            miti: "",
            apraPad: "",
            pramaditPad: "",
            apraNaam: "",
            pramaditNaam: "",
            checkNaam: "",
            checkPad: "",
        };
    },
    computed: {
        ...mapState({
            milestoneData: (state) => state.webservice.milestonePragatiReports,
        }),
    },
    methods: {
        print() {
            window.print();
        },
    },
    mounted() {
        if (this.passedFillable != null) {
            this.fillable = this.passedFillable;
        } else {
            this.fillable = true;
        }
    },
};
</script>

<style lang="scss" scoped>
@media print {
    .nonPrintableArea {
        display: none;
    }
}

h5 {
    font-size: 15px;
}

h4 {
    font-size: 19px;
}

table tbody td,
table thead th {
    padding: 0px 3px 0px 3px;
}

p {
    font-size: 13px;
    margin: 0;
}

table {
    font-size: 13px;
}

textarea {
    width: 100%;

    &:focus {
        outline: none;
    }
}
</style>

