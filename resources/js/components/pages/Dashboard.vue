<template>
    <v-container class="pa-5" fluid>
        <v-row>
            <v-col v-for="(item,k) in dashboardResources" :key="k" cols="3">
                <v-card
                    class="mt-4 mx-auto"
                    color="#EEEEEE"
                    max-width="400"
                >
                    <v-sheet
                        class="title v-sheet--offset mx-auto d-flex justify-content-center"
                        color="primary"
                        dark
                        elevation="12"
                        max-width="calc(50%)"
                    >
                        <h1 class="pa-2">{{ item.count }}</h1>
                    </v-sheet>

                    <v-card-text class="pt-0">
                        <div class="title font-weight-light mb-2">
                            {{ item.title }}
                        </div>
                        <div class="subheading font-weight-light grey--text">
                            {{ item.subTitle }}
                        </div>
                        <v-divider class="my-2"></v-divider>
                        <v-icon
                            class="mr-2"
                            small
                        >
                            mdi-clock
                        </v-icon>
                        <span class="caption grey--text font-weight-light">last data entered {{ item.lastEntry }}</span>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {mapState} from "vuex";

export default {
    data() {
        return {}
    },
    computed: {
        ...mapState({
            dashboardResources: (state) => state.webservice.resources.dashboard_items,
        })
    }
}
</script>

<style lang="scss" scoped>
.v-sheet--offset {
    top: -24px;
    position: relative;
}

</style>
