<template>
    <v-app>
        <v-navigation-drawer app right v-model="dialog" class="nonPrintableArea">
            <v-container>
                <h4><strong>प्रिन्ट विवरण लेख्नुहोस्</strong></h4>
                <v-text-field
                    label="तयार गर्ने"
                    outlined
                    v-model="tayarGarneNaam"
                ></v-text-field>
                <v-text-field
                    label="आ.प्र.शाखा"
                    outlined
                    v-model="apraNaam"
                ></v-text-field>
                <v-text-field
                    label="प्रमाणित गर्ने"


                    outlined
                    v-model="pramaditNaam"
                ></v-text-field>
                <v-btn depressed color="primary" @click="print()"
                >
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
                                <h5>रास्ट्रपति चुरे-तराई मधेश संरक्षण विकास समिति</h5>
                                <h4>
                                    <strong>{{ traimaasikPragatiReport.trimester }} प्रगति</strong>
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
                                <p>१. आ.व. : २०७७/०७८</p>
                                <p>२. बजेट उपशीर्षक नं. : ३२९००१०४</p>
                                <p>३. मन्त्रालय : वन तथा वातावरण मन्त्रालय&nbsp;</p>
                                <p>
                                    ४. कार्यक्रम / आयोजनाको नाम : चुरे संरक्षण कार्यक्रम (५ वटा
                                    कार्यक्रम कार्यान्वयन इकाइहरु)
                                </p>
                                <p>५. आयोजना / कार्यालय प्रमुखको नाम :&nbsp;</p>
                                <p>६. यस अवधिको बजेट रू. 8468.8६</p>
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
                            <table border="1" style="margin: auto; text-align: left">
                                <thead>
                                <tr>
                                    <td rowspan="2" height="49"><strong>क्र.सं.</strong></td>
                                    <td rowspan="2"><strong>कार्यक्रम /क्रियाकलाप</strong></td>
                                    <td rowspan="2"><strong>खर्च शीर्षक</strong></td>
                                    <td rowspan="2"><strong>इकाई</strong></td>
                                    <td colspan="3"><strong>बार्षिक लक्ष्य</strong></td>
                                    <td colspan="3">
                                        <strong
                                        >{{ traimaasikPragatiReport.trimester }}को लक्ष</strong
                                        >
                                    </td>
                                    <td colspan="3">
                                        <strong
                                        >{{ traimaasikPragatiReport.trimester }}को प्रगति</strong
                                        >
                                    </td>
                                    <td colspan="3">
                                        <strong>प्रतिवेदन अवधिसम्मको यस आ.व.को प्रगति</strong>
                                    </td>
                                    <td colspan="2"><strong>भौतिक प्रगति</strong></td>
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
                                    <td>
                                        <strong>{{ traimaasikPragatiReport.trimester }}</strong>
                                    </td>
                                    <td><strong>हालसम्मको</strong></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td>
                                        <strong>{{ traimaasikPragatiReport.aayojana }}</strong>
                                    </td>
                                    <td v-for="i in 16"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <strong>अ) पूँजीगत खर्च अन्तर्गतका कार्यक्रमहरु</strong>
                                    </td>
                                    <td v-for="i in 16"></td>
                                </tr>

                                <template
                                    v-for="(componentItem) in traimaasikPragatiReport.items.punjigat.components"
                                >
                                    <tr>
                                        <td style="background: #dddddd;">{{ componentItem.id }}</td>
                                        <td colspan="17" style="background: #dddddd;">
                                            {{ componentItem.name }}
                                        </td>
                                    </tr>
                                    <tr v-for="item in componentItem.items">
                                        <td>{{ item.kriyakalap_code }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.kharcha_sirsak }}</td>
                                        <td>{{ item.ikai }}</td>
                                        <td>{{ item.baarsik_lakshya_pariman }}</td>
                                        <td>{{ item.baarsik_lakshya_vaar }}</td>
                                        <td>{{ item.baarsik_lakshya_budget }}</td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_pariman"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_vaar"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_budget"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.pariman
                                                    ? item.traimaasik_pragati.pariman
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.vaarit
                                                    ? item.traimaasik_pragati.vaarit
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.kharcha
                                                    ? item.traimaasik_pragati.kharcha
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.pariman
                                                    ? item.total_till_now.pariman
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.vaarit ? item.total_till_now.vaarit : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.kharcha
                                                    ? item.total_till_now.kharcha
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.vautik_pragati[
                                                traimaasikPragatiReport.initial + "_traimasik"
                                                    ]
                                            }}
                                        </td>
                                        <td>{{ item.vautik_pragati["total_till_now"] }}</td>
                                    </tr>

                                    <!-- individual component sum -->
                                    <tr>
                                        <td></td>
                                        <td style="background: #f3f3f3">{{componentItem.name}} जम्मा:</td>
                                        <td v-for="i in 3"></td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .baarsik_lakshya_vaar
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .baarsik_lakshya_budget
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_vaar"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_budget"
                                                    ]
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_pragati_vaarit"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_pragati_kharcha"
                                                    ]
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .total_till_now_vaarit
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .total_till_now_kharcha
                                            }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </template>
                                <!-- punjigat total -->
                                <tr>
                                    <td></td>
                                    <td><strong>क) पूँजीगत खर्च कार्यक्रमको जम्मा:</strong></td>
                                    <td v-for="i in 3"></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals
                                                .baarsik_lakshya_vaar
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals
                                                .baarsik_lakshya_budget
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_vaar"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_budget"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_vaarit"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_kharcha"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals
                                                .total_till_now_vaarit
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.punjigat.totals
                                                .total_till_now_kharcha
                                        }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>आ) चालु खर्च अन्तर्गतका कार्यक्रमहरु</strong></td>
                                    <td v-for="i in 16"></td>
                                </tr>
                                <template
                                    v-for="(componentItem) in traimaasikPragatiReport.items.chalu.components"
                                >
                                    <tr>
                                        <td style="background: #dddddd;">{{ componentItem.id }}</td>
                                        <td colspan="17" style="background: #dddddd;">
                                            {{ componentItem.name }}
                                        </td>
                                    </tr>
                                    <tr v-for="item in componentItem.items">
                                        <td>{{ item.kriyakalap_code }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.kharcha_sirsak }}</td>
                                        <td>{{ item.ikai }}</td>
                                        <td>{{ item.baarsik_lakshya_pariman }}</td>
                                        <td>{{ item.baarsik_lakshya_vaar }}</td>
                                        <td>{{ item.baarsik_lakshya_budget }}</td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_pariman"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_vaar"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_budget"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.pariman
                                                    ? item.traimaasik_pragati.pariman
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.vaarit
                                                    ? item.traimaasik_pragati.vaarit
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.traimaasik_pragati.kharcha
                                                    ? item.traimaasik_pragati.kharcha
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.pariman
                                                    ? item.total_till_now.pariman
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.vaarit ? item.total_till_now.vaarit : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.total_till_now.kharcha
                                                    ? item.total_till_now.kharcha
                                                    : ""
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                item.vautik_pragati[
                                                traimaasikPragatiReport.initial + "_traimasik"
                                                    ]
                                            }}
                                        </td>
                                        <td>{{ item.vautik_pragati["total_till_now"] }}</td>
                                    </tr>

                                    <!-- individual component sum -->
                                    <tr>
                                        <td></td>
                                        <td style="background: #f3f3f3">{{componentItem.name}} जम्मा:</td>
                                        <td v-for="i in 3"></td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .baarsik_lakshya_vaar
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .baarsik_lakshya_budget
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_vaar"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_lakshya_budget"
                                                    ]
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_pragati_vaarit"
                                                    ]
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals[
                                                traimaasikPragatiReport.initial +
                                                "_traimasik_pragati_kharcha"
                                                    ]
                                            }}
                                        </td>
                                        <td></td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .total_till_now_vaarit
                                            }}
                                        </td>
                                        <td>
                                            {{
                                                componentItem.totals
                                                    .total_till_now_kharcha
                                            }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </template>
                                <!-- chalu total -->
                                <tr>
                                    <td></td>
                                    <td><strong>ख) चालु खर्च कार्यक्रमको जम्मा:</strong></td>
                                    <td v-for="i in 3"></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals
                                                .baarsik_lakshya_vaar
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals
                                                .baarsik_lakshya_budget
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_vaar"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_budget"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_vaarit"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_kharcha"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals
                                                .total_till_now_vaarit
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.chalu.totals
                                                .total_till_now_kharcha
                                        }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><strong>कुल जम्मा खर्च (जिल्लागत)</strong></td>
                                    <td v-for="i in 3"></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals.baarsik_lakshya_vaar
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals.baarsik_lakshya_budget
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_vaar"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_lakshya_budget"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_vaarit"
                                                ]
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals[
                                            traimaasikPragatiReport.initial +
                                            "_traimasik_pragati_kharcha"
                                                ]
                                        }}
                                    </td>
                                    <td></td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals.total_till_now_vaarit
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.totals.total_till_now_kharcha
                                        }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td>प्रतिवेदन अवधिको प्रगति</td>
                                    <td></td>
                                    <td>भारित</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.prdatibedan_awadi_ko_pragati
                                                .vaarit
                                        }}
                                    </td>
                                    <td>प्रतिशत</td>
                                    <td>भौतिक</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.prdatibedan_awadi_ko_pragati
                                                .vautik
                                        }}
                                    </td>
                                    <td>प्रतिशत</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>प्रतिवेदन अवधिको खर्च</td>
                                    <td></td>
                                    <td>पुँजिगत रु.</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.pratibedan_awadi_ko_kharcha
                                                .punjigat
                                        }}
                                    </td>
                                    <td>चालु रू.</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.pratibedan_awadi_ko_kharcha
                                                .chalu
                                        }}
                                    </td>
                                    <td>जम्मा रू.</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.pratibedan_awadi_ko_kharcha
                                                .total
                                        }}
                                    </td>
                                    <td>प्रतिशत</td>
                                    <td>
                                        {{
                                            traimaasikPragatiReport.items.pratibedan_awadi_ko_kharcha
                                                .total_percent
                                        }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <p>स.व.अ.</p>
                            </div>
                            <div>
                                <p><strong>आ.प्र.शाखा</strong></p>
                                <p>नाम: {{ apraNaam }}</p>
                                <p>ले.अ.</p>
                            </div>
                            <div>
                                <p><strong>प्रमाणित गर्ने</strong></p>
                                <p>नाम: {{ pramaditNaam }}</p>
                                <p>सदस्य-सचिव</p>
                            </div>
                        </div>
                    </div>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {
            dialog: false,
            tayarGarneNaam: "",
            apraNaam: "",
            pramaditNaam: "",
        };
    },
    computed: {
        ...mapState({
            traimaasikPragatiReport: (state) =>
                state.webservice.traimaasikPragatiReport,
        }),
    },
    methods: {
        print() {
            window.print();
        },
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

table td {
    padding: 0px 3px 0px 3px;
}

p {
    font-size: 13px;
    margin: 0;
}

table {
    font-size: 13px;
}
</style>
