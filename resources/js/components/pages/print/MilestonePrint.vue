<template>
  <v-container>
    <!-- single print page -->
    <v-app v-if="fillable">
      <v-navigation-drawer app right v-model="dialog" class="nonPrintableArea">
        <v-container>
          <h4><strong>प्रिन्ट विवरण लेख्नुहोस्</strong></h4>
          <v-text-field label="मिति" outlined v-model="miti"></v-text-field>
          <h5>१.तयार गर्ने</h5>
          <v-text-field
            label="नाम"
            outlined
            v-model="tayarGarneNaam"
          ></v-text-field>
          <v-text-field
            label="पद"
            outlined
            v-model="tayarGarnePad"
          ></v-text-field>
          <h5>२.आ.प्र.शाखा</h5>
          <v-text-field label="नाम" outlined v-model="apraNaam"></v-text-field>
          <v-text-field label="पद" outlined v-model="apraPad"></v-text-field>
          <h5>३.चेक गर्ने</h5>
          <v-text-field label="नाम" outlined v-model="checkNaam"></v-text-field>
          <v-text-field label="पद" outlined v-model="checkPad"></v-text-field>

          <h5>४.प्रमाणित गर्ने</h5>
          <v-text-field
            label="नाम"
            outlined
            v-model="pramaditNaam"
          ></v-text-field>
          <v-text-field
            label="पद"
            outlined
            v-model="pramaditPad"
          ></v-text-field>

          <v-btn depressed color="primary" @click="print()">
            <v-icon>mdi-printer</v-icon>
            <span>Ready to Print</span></v-btn
          >
        </v-container>
      </v-navigation-drawer>

      <v-main app class="printableArea">
        <v-container>
          <v-row class="pa-2 nonPrintableArea">
            <v-spacer></v-spacer>
            <v-btn color="success" dark @click="dialog = !dialog" depressed>
              <v-icon>mdi-printer</v-icon>
              <span> Print </span>
            </v-btn>
          </v-row>

          <v-row>
            <div id="printableArea">
              <div style="position: relative; margin-bottom: 5px">
                <div style="position: absolute; right: 0; text-align: right">
                  <p>बजेट फा.नं. ......</p>
                  <p>रा.यो.आ. .....</p>
                </div>
                <div style="text-align: center">
                  <h5>नेपाल सरकार</h5>
                  <h5>राष्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
                  <h6>खुमालटार, ललितपुर</h6>
                  <h4>
                    <!-- <strong>{{ milestonePragatiReports[0].month }}</strong> -->
                  </h4>
                </div>
              </div>
              <div
                style="
                  display: flex;
                  margin-bottom: 5px;
                  justify-content: space-between;
                "
              >
                <div>
                  <p>१. आ.व. :</p>
                  <p>२. बजेट उपशीर्षक नं. :</p>
                  <p>३. मन्त्रालय :</p>
                  <p>४. कार्यक्रम / आयोजनाको नाम :</p>
                  <p>५. आयोजना / कार्यालय प्रमुखको नाम :&nbsp;</p>
                  <p>६. यस अवधिको बजेट रू.</p>
                  <p>क) आन्तरिक १) नेपाल सरकार :</p>
                  <p>२) संस्था :</p>
                  <p>३) जनसहभागिता :</p>
                  <p>ख) बैदेशिक १) ऋण :</p>
                  <p>२) अनुदान :</p>
                </div>
                <div>
                  <p>७. यस अवधिको खर्च रकम र प्रतिशत</p>
                  <p>क) आन्तरिक १) नेपाल सरकार :</p>
                  <p>२) संस्था :</p>
                  <p>३) जनसहभागिता :</p>
                  <p>ख) बैदेशिक १) ऋण :</p>
                  <p>२) अनुदान :</p>
                  <p>८. चालु आ.व.को हालसम्मको खर्च रकम र प्रतिशत :</p>
                  <p>
                    ९. कूल लागत मध्ये शुरूदेखि यस अवधिसम्मको कूल खर्च रकम र
                    प्रतिशत:&nbsp;
                  </p>
                  <p>
                    १०. आयोजनाको शुरूदेखि यस अवधि सम्मको भौतिक प्रगति र प्रतिशत
                    :
                  </p>
                  <p>११. आयोजनाको कूल अवधि मध्ये वितेको सममय प्रतिशतमा :</p>
                </div>
                <div>
                  <p>१२. सोधभर्ना स्थिति</p>
                  <p>क) माग गर्नु पर्ने रकम</p>
                  <p>ख) माग गरेको रकम</p>
                  <p>ग) प्राप्त हुन बाँकि रकम</p>
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
                      <th>{{milestoneData.mahina}} महिनाको प्रगति</th>
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
                                <td>{{ itemSecond.prarambhik_karya_suru_milestone }}</td>
                                <td>{{ itemSecond.prarambhik_karya_suru_samayavadhi }}</td>
                                <td>प्रारम्भिक कार्यको शुरु प्रगाती</td>
                                <td>{{itemSecond.prarambhik_karya_suru_pragati}}</td>
                                <td>{{ itemSecond.prarambhik_karya_suru_reason }}</td>
                                <td>{{itemSecond.total_prarambhik_karya_suru_pragati}}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td>{{ itemSecond.prarambhik_karya_jari_milestone }}</td>
                                <td>{{ itemSecond.prarambhik_karya_jari_samayavadhi }}</td>
                                <td>प्रारम्भिक कार्यको जारी प्रगति</td>
                                <td>{{itemSecond.prarambhik_karya_jari_pragati}}</td>
                                <td>{{ itemSecond.prarambhik_karya_jari_reason }}</td>
                                <td>{{itemSecond.total_prarambhik_karya_jari_pragati}}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td>{{ itemSecond.prarambhik_karya_sampanna_milestone }}</td>
                                <td>{{ itemSecond.prarambhik_karya_sampanna_samayavadhi }}</td>
                                <td>प्रारम्भिक कार्यको सम्पन्न प्रगति</td>
                                <td>{{itemSecond.prarambhik_karya_sampanna_pragati}}</td>
                                <td>{{ itemSecond.prarambhik_karya_sampanna_reason }}</td>
                                <td>{{itemSecond.total_prarambhik_karya_sampanna_pragati}}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td>{{ itemSecond.karyakram_karyanayan_suru_milestone }}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_suru_samayavadhi }}</td>
                                <td>कार्यक्रम कार्यान्वयनको शुरु प्रगाती</td>
                                <td>{{itemSecond.karyakram_karyanayan_suru_pragati}}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_suru_reason }}</td>
                                <td>{{itemSecond.total_karyakram_karyanayan_suru_pragati}}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td>{{ itemSecond.karyakram_karyanayan_jari_milestone }}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_jari_samayavadhi }}</td>
                                <td>कार्यक्रम कार्यान्वयनको जारी प्रगति</td>
                                <td>{{itemSecond.karyakram_karyanayan_jari_pragati}}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_jari_reason }}</td>
                                <td>{{itemSecond.total_karyakram_karyanayan_jari_pragati}}</td>
                                <td>remarks</td>
                            </tr>
                            <tr>
                                <td>{{ itemSecond.karyakram_karyanayan_sampanna_milestone }}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_sampanna_samayavadhi }}</td>
                                <td>कार्यक्रम कार्यान्वयनको सम्पन्न प्रगति</td>
                                <td>{{itemSecond.karyakram_karyanayan_sampanna_pragati}}</td>
                                <td>{{ itemSecond.karyakram_karyanayan_sampanna_reason }}</td>
                                <td>{{itemSecond.total_karyakram_karyanayan_sampanna_pragati}}</td>
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
        <div style="position: absolute; right: 0; text-align: right">
          <p>बजेट फा.नं. ......</p>
          <p>रा.यो.आ. .....</p>
        </div>
        <div style="text-align: center">
          <h5>नेपाल सरकार</h5>
          <h5>राष्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
          <h6>खुमालटार, ललितपुर</h6>
        </div>
      </div>
      <div
        style="
          display: flex;
          margin-bottom: 5px;
          justify-content: space-between;
        "
      >
        <div>
          <p>१. आ.व. :</p>
          <p>२. बजेट उपशीर्षक नं. :</p>
          <p>३. मन्त्रालय :</p>
          <p>४. कार्यक्रम / आयोजनाको नाम :</p>
          <p>५. आयोजना / कार्यालय प्रमुखको नाम :&nbsp;</p>
          <p>६. यस अवधिको बजेट रू.</p>
          <p>क) आन्तरिक १) नेपाल सरकार :</p>
          <p>२) संस्था :</p>
          <p>३) जनसहभागिता :</p>
          <p>ख) बैदेशिक १) ऋण :</p>
          <p>२) अनुदान :</p>
        </div>
        <div>
          <p>७. यस अवधिको खर्च रकम र प्रतिशत</p>
          <p>क) आन्तरिक १) नेपाल सरकार :</p>
          <p>२) संस्था :</p>
          <p>३) जनसहभागिता :</p>
          <p>ख) बैदेशिक १) ऋण :</p>
          <p>२) अनुदान :</p>
          <p>८. चालु आ.व.को हालसम्मको खर्च रकम र प्रतिशत :</p>
          <p>
            ९. कूल लागत मध्ये शुरूदेखि यस अवधिसम्मको कूल खर्च रकम र
            प्रतिशत:&nbsp;
          </p>
          <p>१०. आयोजनाको शुरूदेखि यस अवधि सम्मको भौतिक प्रगति र प्रतिशत :</p>
          <p>११. आयोजनाको कूल अवधि मध्ये वितेको सममय प्रतिशतमा :</p>
        </div>
        <div>
          <p>१२. सोधभर्ना स्थिति</p>
          <p>क) माग गर्नु पर्ने रकम</p>
          <p>ख) माग गरेको रकम</p>
          <p>ग) प्राप्त हुन बाँकि रकम</p>
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
              <th>{{milestoneData.mahina}} महिनाको प्रगति</th>
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
                        <td><textarea v-model="itemSecond.prarambhik_karya_suru_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_suru_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>प्रारम्भिक कार्यको शुरु प्रगाती</td>
                        <td>{{itemSecond.prarambhik_karya_suru_pragati}}</td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_suru_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_prarambhik_karya_suru_pragati}}</td>
                        <td>remarks</td>
                    </tr>
                    <tr>
                        <td><textarea v-model="itemSecond.prarambhik_karya_jari_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_jari_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>प्रारम्भिक कार्यको जारी प्रगति</td>
                        <td>{{itemSecond.prarambhik_karya_jari_pragati}}</td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_jari_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_prarambhik_karya_jari_pragati}}</td>
                        <td>remarks</td>
                    </tr>
                    <tr>
                        <td><textarea v-model="itemSecond.prarambhik_karya_sampanna_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_sampanna_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>प्रारम्भिक कार्यको सम्पन्न प्रगति</td>
                        <td>{{itemSecond.prarambhik_karya_sampanna_pragati}}</td>
                        <td><textarea v-model="itemSecond.prarambhik_karya_sampanna_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_prarambhik_karya_sampanna_pragati}}</td>
                        <td>remarks</td>
                    </tr>
                    <tr>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_suru_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_suru_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>कार्यक्रम कार्यान्वयनको शुरु प्रगाती</td>
                        <td>{{itemSecond.karyakram_karyanayan_suru_pragati}}</td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_suru_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_karyakram_karyanayan_suru_pragati}}</td>
                        <td>remarks</td>
                    </tr>
                    <tr>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_jari_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_jari_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>कार्यक्रम कार्यान्वयनको जारी प्रगति</td>
                        <td>{{itemSecond.karyakram_karyanayan_jari_pragati}}</td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_jari_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_karyakram_karyanayan_jari_pragati}}</td>
                        <td>remarks</td>
                    </tr>
                    <tr>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_sampanna_milestone" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_sampanna_samayavadhi" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>कार्यक्रम कार्यान्वयनको सम्पन्न प्रगति</td>
                        <td>{{itemSecond.karyakram_karyanayan_sampanna_pragati}}</td>
                        <td><textarea v-model="itemSecond.karyakram_karyanayan_sampanna_reason" placeholder="यहा टाईप गर्नुहोस"></textarea></td>
                        <td>{{itemSecond.total_karyakram_karyanayan_sampanna_pragati}}</td>
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
import { mapState } from "vuex";

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

<style scoped lang="scss">
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
textarea{
  width: 100%;
  &:focus{
    outline:none;
  }
}
</style>

