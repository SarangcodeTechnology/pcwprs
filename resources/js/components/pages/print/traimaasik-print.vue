<template>
    <div class="container">
        <div id="printableArea">
            <div style="text-align: center">
                <p>नेपाल सरकार</p>
                <p>रास्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</p>
                <p>दोश्रो चौमासिक प्रगति</p>
                <table border="1" cellspacing="0" style="margin:auto">
                    <thead>
                    <tr>
                        <td rowspan="2" height="49"><strong>क्र.सं.</strong></td>
                        <td rowspan="2"><strong>कार्यक्रम /क्रियाकलाप</strong></td>
                        <td rowspan="2"><strong>खर्च शीर्षक</strong></td>
                        <td rowspan="2"><strong>इकाई</strong></td>
                        <td colspan="3"><strong>बार्षिक लक्ष्य</strong></td>
                        <td colspan="3"><strong>{{ traimaasikPragatiReport.trimester }}को लक्ष</strong></td>
                        <td colspan="3"><strong>{{ traimaasikPragatiReport.trimester }}को प्रगति</strong></td>
                        <td colspan="3"><strong>प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति</strong></td>
                        <td colspan="2" ><strong>भौतिक प्रगति</strong></td>
                    </tr>
                    <tr>
                        <td><strong>परिमाण</strong></td>
                        <td><strong>भार</strong></td>
                        <td><strong>बजेट</strong></td>
                        <td><strong>परिमाण</strong></td>
                        <td><strong>भार</strong></td>
                        <td><strong>बजेट</strong></td>
                        <td><strong>परिमाण</strong></td>
                        <td><strong>भारित</strong></td>
                        <td><strong>खर्च</strong></td>
                        <td><strong>परिमाण</strong></td>
                        <td><strong>भारित</strong></td>
                        <td><strong>खर्च</strong></td>
                        <td><strong>{{ traimaasikPragatiReport.trimester }}</strong></td>
                        <td><strong>हालसम्मको</strong></td>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in traimaasikPragatiReport.items" :key="index">
                        <td>{{item.kriyakalap_code}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.kharcha_sirsak}}</td>
                        <td>{{item.ikai}}</td>
                        <td>{{item.baarsik_lakshya_pariman}}</td>
                        <td>{{item.baarsik_lakshya_vaar}}</td>
                        <td>{{item.baarsik_lakshya_budget}}</td>
                        <td>{{item[traimaasikPragatiReport.initial+'_traimasik_lakshya_pariman']}}</td>
                        <td>{{item[traimaasikPragatiReport.initial+'_traimasik_lakshya_vaar']}}</td>
                        <td>{{item[traimaasikPragatiReport.initial+'_traimasik_lakshya_budget']}}</td>
                        <td>{{item.traimaasik_pragati.pariman ? item.traimaasik_pragati.pariman :'' }}</td>
                        <td>{{item.traimaasik_pragati.vaarit ? item.traimaasik_pragati.vaarit :''}}</td>
                        <td>{{item.traimaasik_pragati.kharcha ? item.traimaasik_pragati.kharcha :''}}</td>
                        <td>{{item.total_till_now.pariman ? item.total_till_now.pariman : ''}}</td>
                        <td>{{item.total_till_now.vaarit ? item.total_till_now.vaarit : ''}}</td>
                        <td>{{item.total_till_now.kharcha ? item.total_till_now.kharcha : ''}}</td>
                        <td>{{item.vautik_pragati[traimaasikPragatiReport.initial+'_traimasik']}}</td>
                        <td>{{item.vautik_pragati['total_till_now']}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {

        };
    },
    computed: {
        ...mapState({
            traimaasikPragatiReport: (state) => state.webservice.traimaasikPragatiReport,
        })

    },
    methods: {


    },
}
</script>

<style scoped>

</style>
