import axios from "axios";
import { ref } from "vue";

const response = ref();

<template>
    <div>
        <h1>HOME</h1>
        <router-link to="/test"> Take me to Test page </router-link><br>
        <button @click="getMessageWithAxios">Trigger Endpoint</button><br>
        <button @click="postDataWithAxios">POST data With Axios</button>

        <p v-if="response">{{ response.data }}</p>
    </div>
</template>

<script>

    export default {
        methods: {

            getMessageWithAxios(){
                axios.get('/get-message').then(response => {
                    alert(response.data.message);
                });
            },

            postDataWithAxios(){

                axios.post('/post-data', {full_name : 'Aziz Sancar'}).then(response => {
                    alert(response.data.message);
                });
            },
        }
    }

</script>