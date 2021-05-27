<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-data-table
                    :headers="headers"
                    :items="items"
                    :items-per-page="20"
                    :loading="loading"
                    fixed-header
                    loading-text="Loading Data... Please wait"
                >
                    <template v-slot:item.actions="{ item }">
                        <v-btn color="primary" dark @click="approve(item)">
                            स्वीकृत गर्नुहोस्
                            <v-icon>mdi-check</v-icon>
                        </v-btn>
                    </template>
                    <template v-slot:item.aarthikBarsa="{item}">
                            {{ item.aayojana.aarthik_barsa.name }}
                    </template>
                    <template v-slot:item.mahinaOrTraimaasik="{item}">
                        <span v-if="item.mahina">
                            {{ item.mahina.name }} महिना
                        </span>
                        <span v-else>
                            {{ item.traimaasik.name }}
                        </span>
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
            loading: true,
            options: {},
            totalItems: 20,
            items: [],
            headers: [
                {text: "कार्यहरु", value: "actions"},
                {text: "कार्यलयको नाम", value: "kaaryalaya.name"},
                {text: "आर्थिक वर्ष", value: "aarthikBarsa"},
                {text: "अनुरोध गर्नेको नाम", value: "requested_by.name"},
                {text: "महिना/त्रैमासिक", value: "mahinaOrTraimaasik"},
                {text: "आयोजनाको नाम", value: "aayojana.name"},
            ],
        };
    },
    watch: {
        //this one will populate new data set when user changes current page.
        options: {
            handler() {
                this.getDataFromApi();
            },
            deep: true,
        },
    },
    mounted() {
        this.getDataFromApi();
    },
    computed: {
        ...mapState(),
    },
    methods: {
        approve(item) {
            if(window.confirm(`के तपाइँ निश्चित रुपमा यो अनुरोधलाई अनुमोदन गर्न चाहानुहुन्छ?`)){
                var tempthis = this;
                this.$store.dispatch('approveRequest', item).then(function (response) {
                    tempthis.getDataFromApi();
                });
            }
        },
        getDataFromApi() {
            const tempthis = this;
            this.loading = true;
            const {page, itemsPerPage} = tempthis.options;
            let pageNumber = page - 1;
            this.$store.dispatch("getRequests").then(function (response) {
                tempthis.loading = false;
                tempthis.items = response.requests;
            });
        },
        goToEditPage() {
            this.$store.dispatch("setPermissionEditData", {
                name: ""
            });
        },
        editData(item) {
            this.$store.dispatch("setPermissionEditData", item)
        }
    },
};
</script>
