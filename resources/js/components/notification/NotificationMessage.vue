<template>
    <v-alert :type="type"
             border="left"
             style="border-radius:0px 10px 10px 0px;"
             dismissible>
        {{ message }}
    </v-alert>
</template>

<script>
export default {
    props: ["notification"],
    data() {
        return {
            type: this.notification.type,
            message: this.notification.message,
            timeout:null,
        }
    },
    created() {
        var mypointer=this;
        this.timeout = setTimeout(()=>{
            this.$store.dispatch("removeNotification", {
                id: mypointer.notification.id
            });
        },5000);
    },
    beforeDestroy(){
        clearTimeout(this.timeout);
    }
}
</script>

<style></style>
