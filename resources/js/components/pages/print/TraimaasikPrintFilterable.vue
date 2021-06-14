<template>
    <v-container>
        <div id="printableArea">
            <div style="position: relative; margin-bottom: 5px">
                <div style="position:absolute; right: 0 ;text-align: right">
                    <p>बजेट फा.नं. ......</p>
                    <p>रा.यो.आ. .....</p>
                </div>
                <div style="text-align: center">
                    <h5>नेपाल सरकार</h5>
                    <h5>रास्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
                    <h4><strong>{{ traimaasikPragatiReportFilterable.trimester }} प्रगति</strong></h4>
                </div>
            </div>
            <div style="display: flex; margin-bottom: 5px; justify-content: space-between;">
                <div>
                    <p>१. आ.व. : २०७७/०७८</p>
                    <p>२. बजेट उपशीर्षक नं. : ३२९००१०४</p>
                    <p>३. मन्त्रालय : वन तथा वातावरण मन्त्रालय&nbsp;</p>
                    <p>४. कार्यक्रम / आयोजनाको नाम : चुरे संरक्षण कार्यक्रम (५ वटा कार्यक्रम कार्यान्वयन इकाइहरु)</p>
                    <p>५. आयोजना / कार्यालय प्रमुखको नाम :&nbsp;</p>
                    <p>६. यस अवधिको बजेट रू. 8468.8६</p>
                    <p>क) आन्तरिक १) नेपाल सरकार :</p>
                    <p>२) संस्था :</p>
                    <p>३) जनसहभागिता :</p>
                    <p>ख) बैदेशिक १) ऋण :</p>
                    <p>२) अनुदान :</p>
                </div>
                <div>
                    <p>७. यस अवधिको खर्च रकम र प्रतिशत </p>
                    <p> क) आन्तरिक १) नेपाल सरकार :</p>
                    <p> २) संस्था :</p>
                    <p> ३) जनसहभागिता :</p>
                    <p> ख) बैदेशिक १) ऋण :</p>
                    <p> २) अनुदान :</p>
                    <p>८. चालु आ.व.को हालसम्मको खर्च रकम र प्रतिशत :</p>
                    <p>९. कूल लागत मध्ये शुरूदेखि यस अवधिसम्मको कूल खर्च रकम र प्रतिशत:&nbsp;</p>
                    <p>१०. आयोजनाको शुरूदेखि यस अवधि सम्मको भौतिक प्रगति र प्रतिशत :</p>
                    <p>११. आयोजनाको कूल अवधि मध्ये वितेको सममय प्रतिशतमा :</p>
                </div>
                <div>
                    <p>१२. सोधभर्ना स्थिति </p>
                    <p> क) माग गर्नु पर्ने रकम</p>
                    <p> ख) माग गरेको रकम</p>
                    <p> ग) प्राप्त हुन बाँकि रकम</p>
                </div>
            </div>
            <div style="text-align: center">
                <table border="1"  style="margin:auto; text-align: left">
                    <thead>
                    <tr>
                        <th v-for="headerItem in traimaasikPragatiReportFilterable.headers">{{headerItem.text}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="bodyItem in traimaasikPragatiReportFilterable.items">
                        <td v-for="headerItem in traimaasikPragatiReportFilterable.headers">{{ getData(bodyItem,headerItem.value) }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>जम्मा:</th>
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
                        <th></th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="display:flex; justify-content: space-between; margin-top: 15px">
                <div>
                    <p><strong>तयार गर्ने</strong></p>
                    <p>स.व.अ.</p>
                </div>
                <div>
                    <p><strong>आ.प्र.शाखा</strong></p>
                    <p>ले.अ.</p>
                </div>
                <div>
                    <p><strong>प्रमाणित गर्ने</strong></p>
                    <p>सदस्य-सचिव</p>
                </div>
            </div>
        </div>
    </v-container>
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
            traimaasikPragatiReportFilterable: (state) => state.webservice.traimaasikPragatiReportFilterable,
        })

    },
    methods: {
        sumField(key) {
            // sum data in give key (property)
            return Math.round((this.traimaasikPragatiReportFilterable.items.reduce((a, b) => a + (b[key] || 0), 0)  + Number.EPSILON) * 100) / 100;
        },
        getData(obj,path){
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);

        },


    },
}
</script>

<style scoped>
h5{
    font-size: 15px;
}
h4{
    font-size: 19px;
}
table td, table th{
    padding: 0px 3px 0px 3px;
}
p{
    font-size: 13px;
    margin: 0;
}
table {
    font-size: 13px;
}
</style>
