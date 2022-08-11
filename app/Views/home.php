<?php $this->extend("layouts/frontend"); ?>
<?php $this->section("content"); ?>
<template>
    <v-container>
        <v-row align="center" justify="center" class="mt-5">
            <v-col class="text-center" cols="12">
                <h1 class="text-h4 font-weight-normal">
                    Starter Project CodeIgniter 4
                </h1>
                <h5 class="text-subtitle-1 font-weight-normal">Starter Project ini menggunakan CodeIgniter 4, Vue.js 2 dan Vuetify.js 2</h5>
            </v-col>
        </v-row>
    </v-container>
</template>
<?php $this->endSection("content") ?>

<?php $this->section("js") ?>
<script>
    computedVue = {
        ...computedVue,
    }

    createdVue = function() {

    }

    watchVue = {

    }

    dataVue = {
        ...dataVue,
        
    }
    
    methodsVue = {
        ...methodsVue,
      
    }
</script>
<?php $this->endSection("js") ?>