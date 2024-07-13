<?php $this->extend("layouts/backend"); ?>
<?php $this->section("content"); ?>
<template>
    <h1 class="font-weight-medium mb-2"><?= $title; ?></h1>
    <v-card>
        <v-card-title>
            <v-btn color="primary" dark @click="modalAddOpen" large elevation="1">
                <v-icon>mdi-plus</v-icon> <?= lang('App.add') ?>
            </v-btn>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" v-on:keydown.enter="handleSearch" @click:clear="handleSearch" append-icon="mdi-magnify" label="<?= lang("App.search") ?>" single-line hide-details clearable>
            </v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="data" :options.sync="options" :server-items-length="totalData" :items-per-page="10" :loading="loading" :search="search" class="elevation-1" loading-text="<?= lang('App.loadingWait'); ?>" dense>
            <template v-slot:item="{ item }">
                <tr>
                    <td>{{item.gaji_id}}</td>
                    <td>{{item.gaji_golongan}}</td>
                    <td>{{item.gaji_masa_kerja}}</td>
                    <td>{{RibuanNoRp(item.gaji_pokok)}}</td>
                    <td>
                        <v-btn color="primary" class="mr-3" @click="editItem(item)" title="Edit" alt="Edit" icon>
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn color="error" @click="deleteItem(item)" title="Delete" alt="Delete" icon>
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </td>
                </tr>
            </template>
        </v-data-table>
    </v-card>
    <!-- End Table List -->
</template>

<!-- Modal Add -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalAdd" persistent max-width="700px">
            <v-card>
                <v-card-title><?= lang('App.add') ?> Gaji
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalAddClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form v-model="valid" ref="form">
                        <v-select v-model="golonganId" name="golongan" :items="dataGolongan" item-text="golongan_nama" item-value="golongan_id" label="Golongan *" @change="getGolonganByID" :loading="loading2" :error-messages="golongan_idError" outlined></v-select>

                        <v-text-field v-model="gajiGolongan" label="Golongan Nama *" :error-messages="gaji_golonganError" outlined></v-text-field>

                        <v-text-field v-model="gajiMasakerja" label="Masa Kerja *" :error-messages="gaji_masa_kerjaError" outlined></v-text-field>

                        <v-text-field v-model="gajiPokok" label="Gaji Pokok *" :error-messages="gaji_pokokError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="saveGaji" :loading="loading">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.save') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Add -->

<!-- Modal Edit -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalEdit" persistent max-width="700px">
            <v-card>
                <v-card-title><?= lang('App.edit') ?> Gaji ID: {{gajiIdEdit}}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalEditClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form ref="form" v-model="valid">
                        <v-select v-model="golonganIdEdit" name="golongan" :items="dataGolongan" item-text="golongan_nama" item-value="golongan_id" label="Golongan *" @change="getGolonganByID" :loading="loading2" :error-messages="golongan_idError" outlined></v-select>

                        <v-text-field v-model="gajiGolonganEdit" label="Golongan Nama *" :error-messages="gaji_golonganError" outlined></v-text-field>
                        
                        <v-text-field v-model="gajiMasakerjaEdit" label="Masa Kerja *" :error-messages="gaji_masa_kerjaError" outlined></v-text-field>

                        <v-text-field v-model="gajiPokokEdit" label="Gaji Pokok *" :error-messages="gaji_pokokError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="updateGaji" :loading="loading">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.update') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Edit -->

<!-- Modal Delete -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalDelete" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title>
                    <v-icon color="error" class="mr-2" x-large>mdi-alert-octagon</v-icon> Konfirmasi <?= lang("App.delete"); ?>
                </v-card-title>
                <v-card-text class="my-5">
                    <h2 class="font-weight-medium"><?= lang('App.delConfirm') ?></h2>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="font-weight-medium" text large @click="deleteGaji" :loading="loading"><?= lang("App.yes") ?>, <?= lang("App.delete"); ?></v-btn>
                    <v-btn color="error" text large @click="modalDelete = false"><?= lang("App.no") ?></v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Delete -->

<!-- Loading2 -->
<v-dialog v-model="loading2" hide-overlay persistent width="300">
    <v-card>
        <v-card-text class="pt-3">
            <?= lang('App.loadingWait'); ?>
            <v-progress-linear indeterminate color="primary" class="mb-0"></v-progress-linear>
        </v-card-text>
    </v-card>
</v-dialog>
<!-- -->
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

    var errorKeys = []

    dataVue = {
        ...dataVue,
        search: "",
        menu: false,
        startDate: "<?= $awalTahun; ?>",
        endDate: "<?= $akhirTahun; ?>",
        headers: [{
            text: '# ',
            value: 'gaji_id'
        }, {
            text: 'Golongan',
            value: 'gaji_golongan'
        }, {
            text: 'Masa Kerja',
            value: 'gaji_masa_kerja'
        }, {
            text: 'Gaji Pokok',
            value: 'gaji_pokok'
        }, {
            text: '<?= lang('App.action') ?>',
            value: 'actions',
            sortable: false
        }, ],
        dataGaji: [],
        totalData: 0,
        data: [],
        options: {},

        dataGolongan: [],
        dataGolonganById: [],

        modalAdd: false,
        modalEdit: false,
        modalDelete: false,

        gajiId: "",
        gajiIdEdit: "",
        gajiIdDelete: "",
        golonganId: "",
        golonganIdEdit: "",
        golongan_idError: "",
        gajiGolongan: "",
        gajiGolonganEdit: "",
        gaji_golonganError: "",
        gajiMasakerja: "",
        gajiMasakerjaEdit: "",
        gaji_masa_kerjaError: "",
        gajiPokok: 0,
        gajiPokokEdit: 0,
        gaji_pokokError: ""

    }

    // Vue Created
    // Created: Dipanggil secara sinkron setelah instance dibuat
    createdVue = function() {
        this.getGaji();
        this.getGolongan();
    }

    // Vue Computed
    // Computed: Properti-properti terolah (computed) yang kemudian digabung kedalam Vue instance
    computedVue = {
        ...computedVue,

    }

    // Vue Watch
    // Watch: Sebuah objek dimana keys adalah expresi-expresi untuk memantau dan values adalah callback-nya (fungsi yang dipanggil setelah suatu fungsi lain selesai dieksekusi).
    watchVue = {
        ...watchVue,
        options: {
            handler() {
                this.getDataFromApi()
            },
            deep: true,
        },

        dataGaji: function() {
            if (this.dataGaji != '') {
                // Call server-side paginate and sort
                this.getDataFromApi();
            }
        }
    }

    // Vue Methods
    // Methods: Metode-metode yang kemudian digabung ke dalam Vue instance
    methodsVue = {
        ...methodsVue,
        // Format Ribuan Rupiah versi 1
        RibuanLocale(key) {
            const rupiah = 'Rp' + Number(key).toLocaleString('id-ID');
            return rupiah
        },
        RibuanLocaleNoRp(key) {
            const rupiah = Number(key).toLocaleString('id-ID');
            return rupiah
        },

        // Format Ribuan Rupiah versi 2
        Ribuan(key) {
            const format = key.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            const rupiah = 'Rp' + convert.join('.').split('').reverse().join('');
            return rupiah;
        },

        RibuanNoRp(key) {
            const format = key.toString().split('').reverse().join('');
            const convert = format.match(/\d{1,3}/g);
            const rupiah = '' + convert.join('.').split('').reverse().join('');
            return rupiah;
        },

        // Server-side paginate and sort
        getDataFromApi() {
            this.loading = true
            this.fetchData().then(data => {
                this.data = data.items
                this.totalData = data.total
                this.loading = false
            })
        },
        fetchData() {
            return new Promise((resolve, reject) => {
                const {
                    sortBy,
                    sortDesc,
                    page,
                    itemsPerPage
                } = this.options

                let search = this.search ?? "".trim();

                let items = this.dataGaji
                const total = items.length

                if (search == search.toLowerCase()) {
                    items = items.filter(item => {
                        return Object.values(item)
                            .join(",")
                            .toLowerCase()
                            .includes(search);
                    });
                } else {
                    items = items.filter(item => {
                        return Object.values(item)
                            .join(",")
                            .includes(search);
                    });
                }

                if (sortBy.length === 1 && sortDesc.length === 1) {
                    items = items.sort((a, b) => {
                        const sortA = a[sortBy[0]]
                        const sortB = b[sortBy[0]]

                        if (sortDesc[0]) {
                            if (sortA < sortB) return 1
                            if (sortA > sortB) return -1
                            return 0
                        } else {
                            if (sortA < sortB) return -1
                            if (sortA > sortB) return 1
                            return 0
                        }
                    })
                }

                if (itemsPerPage > 0) {
                    items = items.slice((page - 1) * itemsPerPage, page * itemsPerPage)
                }

                setTimeout(() => {
                    resolve({
                        items,
                        total,
                    })
                }, 100)
            })
        },

        modalAddOpen: function() {
            this.modalAdd = true;
        },
        modalAddClose: function() {
            this.modalAdd = false;
            this.$refs.form.resetValidation();
        },

        // Handle Search Filter
        handleSearch: function() {
            this.getGaji();
        },

        // Get Golongan
        getGolongan: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/golongan', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataGolongan = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataGolongan = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Golongan By ID
        getGolonganByID: function() {
            this.loading2 = true;
            axios.get(`<?= base_url(); ?>api/golongan/${this.golonganId}`, options)
                .then(res => {
                    // handle success
                    this.loading2 = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataGolonganById = data.data;
                        this.gajiGolongan = this.dataGolonganById.golongan_nama;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataGolonganById = data.data;
                        this.gajiGolongan = this.dataGolonganById.golongan_nama;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Gaji
        getGaji: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/gaji', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataGaji = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataGaji = data.data;
                        this.data = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Save Gaji
        saveGaji: function() {
            this.loading = true;
            axios.post('<?= base_url(); ?>api/gaji/save', {
                    golongan_id: this.golonganId,
                    gaji_golongan: this.gajiGolongan,
                    gaji_masa_kerja: this.gajiMasakerja,
                    gaji_pokok: this.gajiPokok,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.golonganId = "";
                        this.gajiGolongan = "";
                        this.gajiMasakerja = "";
                        this.gajiPokok = 0;
                        this.getGaji();
                        this.modalAdd = false;
                        this.$refs.form.resetValidation();
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        errorKeys = Object.keys(data.data);
                        errorKeys.map((el) => {
                            this[`${el}Error`] = data.data[el];
                        });
                        if (errorKeys.length > 0) {
                            setTimeout(() => errorKeys.map((el) => {
                                this[`${el}Error`] = "";
                            }), 4000);
                        }
                        this.modalAdd = true;
                        this.$refs.form.validate();
                    }
                })
                .catch(err => {
                    // handle error
                    this.loading = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Item Edit
        editItem: function(user) {
            this.modalEdit = true;
            this.show = false;
            this.gajiIdEdit = user.gaji_id;
            this.golonganIdEdit = user.golongan_id,
            this.gajiGolonganEdit = user.gaji_golongan;
            this.gajiMasakerjaEdit = user.gaji_masa_kerja;
            this.gajiPokokEdit = user.gaji_pokok;
        },
        modalEditClose: function() {
            this.modalEdit = false;
            this.$refs.form.resetValidation();
        },

        //Update Gaji
        updateGaji: function() {
            this.loading = true;
            axios.put(`<?= base_url(); ?>api/gaji/update/${this.gajiIdEdit}`, {
                    golongan_id: this.golonganIdEdit,
                    gaji_golongan: this.gajiGolonganEdit,
                    gaji_masa_kerja: this.gajiMasakerjaEdit,
                    gaji_pokok: this.gajiPokokEdit,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getGaji();
                        this.modalEdit = false;
                        this.$refs.form.resetValidation();
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        errorKeys = Object.keys(data.data);
                        errorKeys.map((el) => {
                            this[`${el}Error`] = data.data[el];
                        });
                        if (errorKeys.length > 0) {
                            setTimeout(() => errorKeys.map((el) => {
                                this[`${el}Error`] = "";
                            }), 4000);
                        }
                        this.modalEdit = true;
                        this.$refs.form.validate();
                    }
                })
                .catch(err => {
                    // handle error
                    this.loading = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Item Delete
        deleteItem: function(item) {
            this.modalDelete = true;
            this.gajiIdDelete = item.gaji_id;
        },

        // Delete Gaji
        deleteGaji: function() {
            this.loading = true;
            axios.delete(`<?= base_url(); ?>api/gaji/delete/${this.gajiIdDelete}`, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getGaji();
                        this.modalDelete = false;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.modalDelete = true;
                    }
                })
                .catch(err => {
                    // handle error
                    this.loading = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },
    }
</script>
<?php $this->endSection("js") ?>