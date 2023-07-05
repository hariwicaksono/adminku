<?php $this->extend("layouts/frontend"); ?>
<?php $this->section("content"); ?>
<template>
    <v-container>
        <v-row align="center" justify="center" class="mt-5">
            <v-col class="text-center" cols="12">
                <h1 class="text-h3 font-weight-regular">
                    Starter Project CodeIgniter 4
                </h1>
                <h2 class="font-weight-regular">Starter Project ini menggunakan CodeIgniter 4, Vue.js 2 dan Vuetify.js 2</h2>
            </v-col>
        </v-row>
    </v-container>
</template>
<?php $this->endSection("content") ?>

<?php $this->section("js") ?>
<script>
    dataVue = {
        ...dataVue,
        
    }

    computedVue = {
        ...computedVue,
    }

    createdVue = function() {

    }

    watchVue = {
        ...watchVue,

    }
    
    methodsVue = {
        ...methodsVue,
      
    }
</script>
<?php $this->endSection("js") ?>