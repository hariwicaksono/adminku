<?php $this->extend("layouts/backend"); ?>
<?php $this->section("content"); ?>
<template>
    <?php if (empty($getBackups)) { ?>
        <v-alert dense prominent outlined type="error" icon="mdi-database-alert">
            <v-row align="center">
                <v-col class="grow">
                    It looks like today you haven't backup your database
                </v-col>
                <v-col class="shrink">
                    <v-btn color="error" link href="<?= base_url('backup'); ?>">Backup Now</v-btn>
                </v-col>
            </v-row>
        </v-alert>
    <?php } else { ?>
        <v-alert dense prominent outlined type="success" icon="mdi-database-check">
            <v-row align="center">
                <v-col class="grow">
                    Good! It looks like today you already backed up your database
                </v-col>
                <v-col class="shrink">
                    <v-btn color="success" link href="<?= base_url('backup'); ?>">See Backup</v-btn>
                </v-col>
            </v-row>
        </v-alert>
    <?php } ?>
    <v-card>
        <v-card-title class="text-h4 font-weight-medium mb-3"><?= $title; ?></v-card-title>
        <v-card-text>
            <h5 class="text-h5 font-weight-bold mb-3">Pendahuluan</h5>
            <p class="text-subtitle-1">Aplikasi Web <?= env('appName'); ?> <?= env('appVersion') ?> dibuat menggunakan CodeIgniter 4 dan Vue.js 2. Teknologi yang digunakan: PHP 7.4, MySQL, CodeIgniter 4 (<?= CodeIgniter\CodeIgniter::CI_VERSION; ?>) Standar &amp; REST API, Vue.js v2, Vuetify.js v2.6, Axios, Material Design Icons.</p>
            <?php if (session()->get('user_type') == 1) : ?>

            <?php endif; ?>

            <?php if ((session()->get('user_type') == 2) || (session()->get('user_type') == 3)) : ?>

            <?php endif; ?>
        </v-card-text>
    </v-card>
</template>

<?php $this->endSection("content") ?>

<?php $this->section("js") ?>
<script>
    const token = JSON.parse(localStorage.getItem('access_token'));
    const options = {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    };
    computedVue = {
        ...computedVue,

    }
    dataVue = {
        ...dataVue,

    }
    createdVue = function() {

    }
    methodsVue = {
        ...methodsVue,

    }
</script>
<?php $this->endSection("js") ?>