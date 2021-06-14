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
                        <template v-if="item.locked">
                            <v-btn color="primary" dark @click="approve(item,'unlock')">
                                    Unlock
                                    <v-icon>mdi-lock-open-variant</v-icon>
                            </v-btn>
                        </template>
                        <template v-else>
                            <v-btn color="red" dark @click="approve(item,'lock')">
                                Lock
                                <v-icon>mdi-lock</v-icon>
                            </v-btn>
                        </template>
                    </template>
                    <template v-slot:item.aarthikBarsa="{item}">
                            {{ item.aayojana.aarthik_barsa.name }}
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
                {text: "कार्यलयको नाम", value: "name"},
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
        approve(item,type) {
            if(window.confirm(`के तपाइँ निश्चित रुपमा यो अनुरोधलाई अनुमोदन गर्न चाहानुहुन्छ?`)){
                var tempthis = this;
                this.$store.dispatch('makePostRequest',{
                    route: 'change-lock',
                    data: { id: item.id, type: type}
                }).then(function(response){
                    tempthis.getDataFromApi();
                })
            }
        },
        getDataFromApi() {
            var tempthis = this;
            this.$store.dispatch("makeGetRequest", {
                route: 'locks'
            }).then(function(response){
                tempthis.items = response.data.data.kaaryalaya;
                tempthis.loading = false;
            });
        },
    },
};
</script>
