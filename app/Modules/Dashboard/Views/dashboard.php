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
    <v-card class="mb-5">
        <v-card-title class="text-h4 font-weight-medium mb-3"><?= $title; ?></v-card-title>
        <v-card-text>
            <h5 class="text-h5 font-weight-bold mb-3">Pendahuluan</h5>
            <p class="text-subtitle-1">Aplikasi Web <?= env('appName'); ?> <?= env('appVersion') ?> dibuat menggunakan CodeIgniter 4 dan Vue.js 2. Tech stack yang digunakan: PHP 8.1, MySQL, CodeIgniter 4 (<?= CodeIgniter\CodeIgniter::CI_VERSION; ?>) Standar &amp; REST API, Vue.js v2, Vuetify.js v2.6, Axios, Material Design Icons.</p>
            <p>Aplikasi Web <?= env('appName'); ?> adalah Produk dari ITSHOP Purwokerto yaitu milik dari <?= env('appCompany'); ?> yang terdaftar di AHU Online dari KEMENKUMHAM RI dan memiliki Legalitas NOMOR INDUK BERUSAHA</p>
            <p>Kunjungi Link Toko Online Official kami:
            <ul>
                <li><a href="https://itshop.biz.id" target="_blank">www.itshop.biz.id</a></li>
                <li><a href="https://tokopedia.com/itshoppwt" target="_blank">Tokopedia.com/itshoppwt</a></li>
                <li><a href="https://shopee.co.id/itshoppwt" target="_blank">Shopee.co.id/itshoppwt</a></li>
                <li><a href="https://toco.id/store/itshop-purwokerto" target="_blank">Toco.id/store/itshop-purwokerto</a></li>
            </ul>
            </p>
            <?php if (session()->get('user_type') == 1) : ?>

            <?php endif; ?>

            <?php if ((session()->get('user_type') == 2) || (session()->get('user_type') == 3)) : ?>

            <?php endif; ?>
        </v-card-text>
    </v-card>
</template>
<?php if (session()->get('role') == '1') : ?>
    <template>
        <h1 class="text-h4 font-weight-medium mb-4">Charts</h1>
        <v-card class="mb-5">
            <v-card-title>Visitor Harian</v-card-title>
            <v-card-subtitle><?= date('d-m-Y'); ?></v-card-subtitle>
            <v-card-text>
                <bar-chart1 :chart-harian="chartHarian"></bar-chart1>
            </v-card-text>
        </v-card>

        <v-card class="mb-5">
            <v-card-title>Visitor Tahunan</v-card-title>
            <v-card-subtitle></v-card-subtitle>
            <v-card-text>
                <bar-chart2 :chart-tahunan="chartTahunan" :chart-labels="chartLabels"></bar-chart2>
            </v-card-text>
        </v-card>

        <v-card>
            <v-card-title>Visitor Berdasarkan Jenis</v-card-title>
            <v-card-text>
                <bar-chart3 :chart-jenis="chartJenis" :chart-jumlah="chartJumlah"></bar-chart3>
            </v-card-text>
        </v-card>
    </template>
<?php endif; ?>
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
    dataVue = {
        ...dataVue,
        countMobile: 0,
        countDesktop: 0,
        countOther: 0,
        chartHarian: [],
        chartTahunan: [],
        chartLabels: [],
        chartJenis: [],
        chartJumlah: [],
    }
    createdVue = function() {
        this.getDashboard();
        this.chart1();
        this.chart2();
        this.chart3();
    }
    methodsVue = {
        ...methodsVue,
        // Get Data
        getDashboard: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/visitors', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        const dataAgenda = data.data;
                        this.countMobile = dataAgenda.countMobile;
                        this.countDesktop = dataAgenda.countDesktop;
                        this.countOther = dataAgenda.countOther;
                        this.chartHarian = dataAgenda.cHarian;
                        this.chartTahunan = dataAgenda.cTahunan;
                        this.chartLabels = dataAgenda.cLabels;
                        this.chartJenis = dataAgenda.cjJenis;
                        this.chartJumlah = dataAgenda.cjJumlah;

                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        chart1: function() {
            Vue.component('bar-chart1', {
                extends: VueChartJs.Bar,
                props: ['chartHarian'],
                watch: {
                    chartHarian: {
                        handler(val) {
                            if (val && val.length) {
                                this.render()
                            }
                        },
                        immediate: true
                    }
                },
                methods: {
                    render() {
                        this.renderChart({
                            labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
                                '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '00'
                            ],
                            datasets: [{
                                label: 'Visitor',
                                data: this.chartHarian.map(Number), // ✅ array number
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1
                            }]
                        }, {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        maxTicksLimit: 24
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 1
                                    }
                                }]
                            }
                        })
                    }
                }
            })
        },

        chart2: function() {
            Vue.component('bar-chart2', {
                extends: VueChartJs.Bar,
                props: ['chartTahunan', 'chartLabels'],
                watch: {
                    chartTahunan: {
                        handler(val) {
                            if (val && val.length) {
                                this.render()
                            }
                        },
                        immediate: true
                    }
                },
                methods: {
                    render() {
                        this.renderChart({
                            labels: this.chartLabels,
                            datasets: [{
                                label: 'Visitor 1 Tahun',
                                data: this.chartTahunan.map(Number), // ✅ array number
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1
                            }]
                        }, {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        maxTicksLimit: 12
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 1
                                    }
                                }]
                            }
                        })
                    }
                }
            })
        },

        chart3: function() {
            Vue.component('bar-chart3', {
                extends: VueChartJs.Bar,
                props: ['chartJenis', 'chartJumlah'],
                watch: {
                    chartJenis: {
                        handler() {
                            this.render()
                        },
                        immediate: true
                    },
                    chartJumlah: {
                        handler() {
                            this.render()
                        },
                        immediate: true
                    }
                },
                methods: {
                    render() {
                        if (!this.chartJenis || !this.chartJenis.length) return
                        if (!this.chartJumlah || !this.chartJumlah.length) return

                        this.renderChart({
                            labels: this.chartJenis,
                            datasets: [{
                                data: this.chartJumlah.map(Number),
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(0, 128, 0, 0.6)',
                                    'rgba(244, 67, 54, 0.6)',
                                ],
                                borderColor: [
                                    'rgb(54, 162, 235)',
                                    'rgb(0, 255, 0)',
                                    'rgb(244, 67, 54)',
                                ],
                                borderWidth: 1,
                            }]
                        }, {
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Jenis Device'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    ticks: {
                                        beginAtZero: true,
                                        steps: 10,
                                        stepValue: 5,
                                        max: 10
                                    }
                                }]
                            },
                        })
                    }
                }
            })
        },
    }
</script>
<?php $this->endSection("js") ?>