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
        <v-data-table :headers="headers" :items="data" :options.sync="options" :server-items-length="totalData" :items-per-page="10" :loading="loading" :search="search" class="elevation-1" loading-text="<?= lang('App.loadingWait'); ?>">
            <template v-slot:item="{ item }">
                <tr>
                    <td>{{item.role_id}}</td>
                    <td>{{item.name}}</td>
                    <td>
                        <v-chip v-for="(perm, index) in item.permissions.slice(0, 5)" :key="perm.id" class="ma-1" small>
                            {{ perm.name }}
                        </v-chip>
                        <!-- Tampilkan jika ada lebih dari 5 -->
                        <span v-if="item.permissions.length > 5" class="text-caption grey--text">
                            +{{ item.permissions.length - 5 }} more
                        </span>
                    </td>
                    <td>{{item.created_at}}</td>
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
                <v-card-title><?= lang('App.add') ?> Role
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalAddClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form v-model="valid" ref="form">
                        <v-text-field v-model="name" label="Name *" :error-messages="nameError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="saveRole" :loading="loading1">
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
        <v-dialog v-model="modalEdit" persistent scrollable max-width="700px">
            <v-card>
                <v-card-title><?= lang('App.edit') ?> Role ID: {{roleIdEdit}}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalEditClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form ref="form" v-model="valid">
                        <v-text-field v-model="nameEdit" label="Name *" :error-messages="nameError" outlined></v-text-field>
                        <label>Permissions</label>
                        <v-checkbox
                            v-for="perm in permissions"
                            :key="perm.permission_id"
                            v-model="roleEdit.permission_ids"
                            :label="perm.name"
                            :value="perm.permission_id"
                            dense
                            hide-details></v-checkbox>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="updateRole" :loading="loading1">
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
                    <v-btn class="font-weight-medium" text large @click="deleteRole" :loading="loading1"><?= lang("App.yes") ?>, <?= lang("App.delete"); ?></v-btn>
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
            value: 'role_id'
        }, {
            text: 'Name',
            value: 'name'
        }, {
            text: 'Permissions',
            value: 'permissions'
        }, {
            text: 'Tanggal',
            value: 'created_at'
        }, {
            text: '<?= lang('App.action') ?>',
            value: 'actions',
            sortable: false
        }, ],
        dataRole: [],
        totalData: 0,
        data: [],
        options: {},

        modalAdd: false,
        modalEdit: false,
        modalDelete: false,

        roleId: "",
        roleIdEdit: "",
        roleIdDelete: "",
        name: "",
        nameEdit: "",
        nameError: "",

        roles: [],
        permissions: [],
        roleEdit: {
            permission_ids: []
        },
    }

    // Vue Created
    // Created: Dipanggil secara sinkron setelah instance dibuat
    createdVue = function() {
        this.getRole();
        this.getPermissions();
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

        dataRole: function() {
            if (this.dataRole != '') {
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

                let items = this.dataRole
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
            this.getRole();
        },

        // Get Role
        getRole: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/role', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataRole = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataRole = data.data;
                        this.data = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false
                    var error = err.response;
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Save Role
        saveRole: function() {
            this.loading1 = true;
            axios.post('<?= base_url(); ?>api/role/save', {
                    name: this.name,
                }, options)
                .then(res => {
                    // handle success
                    this.loading1 = false
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.name = "";
                        this.getRole();
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
                    this.loading1 = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response;
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        // Get Item Edit
        editItem: function(item) {
            this.modalEdit = true;
            this.show = false;
            this.roleIdEdit = item.role_id;
            this.nameEdit = item.name;
            this.roleEdit.permission_ids = []; // Reset terlebih dahulu

            // Ambil permission ID yang dimiliki role ini
            axios.get(`<?= base_url(); ?>api/role/${item.role_id}/permissions`, options)
                .then(res => {
                    this.roleEdit.permission_ids = res.data.permission_ids;
                });
        },
        modalEditClose: function() {
            this.modalEdit = false;
            this.$refs.form.resetValidation();
        },

        //Update Role
        updateRole: function() {
            this.loading1 = true;
            axios.put(`<?= base_url(); ?>api/role/update/${this.roleIdEdit}`, {
                    name: this.nameEdit,
                }, options)
                .then(res => {
                    // handle success
                    this.loading1 = false;
                    var data = res.data;

                    // Permissions
                    const PermissionIds = this.roleEdit.permission_ids.map(id => parseInt(id));
                    axios.post(`<?= base_url(); ?>api/role/update-permissions/${this.roleIdEdit}`, {
                        permission_ids: this.roleEdit.permission_ids
                    }, options);

                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getRole();
                        setTimeout(() => window.location.href = data.data.url, 1000);
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
                    this.loading1 = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response;
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
            this.roleIdDelete = item.role_id;
        },

        // Delete Role
        deleteRole: function() {
            this.loading1 = true;
            axios.delete(`<?= base_url(); ?>api/role/delete/${this.roleIdDelete}`, options)
                .then(res => {
                    // handle success
                    this.loading1 = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getRole();
                        this.modalDelete = false;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.modalDelete = true;
                    }
                })
                .catch(err => {
                    // handle error
                    this.loading1 = false;
                    this.snackbar = true;
                    this.snackbarMessage = err;
                    console.log(err);
                    var error = err.response;
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        getPermissions: function() {
            axios.get("<?= base_url(); ?>api/permission", options)
                .then(res => {
                    this.permissions = res.data.data;
                });
        },

        getRolePermissions: function(role_id) {

            axios.get(`<?= base_url(); ?>api/role/${role_id}/permissions`, options)
                .then(res => {
                    this.$set(role, 'permission_ids', res.data.permission_ids);
                });

        },

        savePermissions(role) {
            this.loading1 = true;
            axios.post(`<?= base_url(); ?>role/update-permissions/${role.role_id}`, {
                permission_ids: role.permission_ids
            }, options).then(res => {
                this.snackbar = true;
                this.snackbarMessage = res.data.message;
                this.loading1 = false;
            }).catch(() => {
                this.loading1 = false;
            });
        },
    }
</script>
<?php $this->endSection("js") ?>