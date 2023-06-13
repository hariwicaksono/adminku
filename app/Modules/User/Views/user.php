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
            <v-text-field v-model="search" v-on:keydown.enter="handleSubmit" @click:clear="handleSubmit" append-icon="mdi-magnify" label="<?= lang("App.search") ?>" single-line hide-details clearable>
            </v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="data" :options.sync="options" :server-items-length="totalData" :items-per-page="10" :loading="loading" :search="search" class="elevation-1" loading-text="<?= lang('App.loadingWait'); ?>" dense>
            <template v-slot:item="{ item }">
                <tr>
                    <td>{{item.id_user}}</td>
                    <td>{{item.email}}</td>
                    <td>{{item.username}}</td>
                    <td>
                        <span v-if="item.username == 'admin'">
                            <v-select v-model="item.id_group" name="group" :items="groups" item-text="nama_group" item-value="id_group" label="Select" single-line disabled></v-select>
                        </span>
                        <span v-else>
                            <v-select v-model="item.id_group" name="group" :items="groups" item-text="nama_group" item-value="id_group" label="Select" single-line @change="setGroup(item)"></v-select>
                        </span>
                    </td>
                    <td>
                        <span v-if="item.username == 'admin'">
                            <v-switch v-model="item.is_active" name="is_active" false-value="0" true-value="1" color="success" disabled></v-switch>
                        </span>
                        <span v-else>
                            <v-switch v-model="item.is_active" name="is_active" false-value="0" true-value="1" color="success" @click="setActive(item)"></v-switch>
                        </span>
                    </td>
                    <td>
                        <v-btn color="primary" class="mr-3" @click="editItem(item)" title="Edit" alt="Edit" icon>
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn color="grey darken-2" @click="changePassword(item)" class="mr-3" title="Password" alt="Password" icon>
                            <v-icon>mdi-key-variant</v-icon>
                        </v-btn>
                        <span v-if="item.username == 'admin'">
                            <v-btn color="error" icon disabled>
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </span>
                        <span v-else>
                            <v-btn color="error" @click="deleteItem(item)" title="Delete" alt="Delete" icon>
                                <v-icon>mdi-delete</v-icon>
                            </v-btn>
                        </span>
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
                <v-card-title><?= lang('App.add') ?> User
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalAddClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form v-model="valid" ref="form">
                        <v-select v-model="idGroup" name="role" :items="groups" item-text="nama_group" item-value="id_group" label="Select Group *" :error-messages="id_groupError" outlined></v-select>

                        <v-text-field v-model="email" :rules="[rules.email]" label="E-mail" :error-messages="emailError" outlined></v-text-field>

                        <v-text-field v-model="userName" label="Username" maxlength="20" :error-messages="usernameError" outlined required></v-text-field>

                        <v-text-field label="Nama Lengkap *" v-model="fullname" :error-messages="fullnameError" outlined></v-text-field>

                        <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.min]" :type="show1 ? 'text' : 'password'" label="Password" hint="<?= lang('App.minChar') ?>" counter @click:append="show1 = !show1" :error-messages="passwordError" outlined></v-text-field>

                        <v-text-field block v-model="verify" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[passwordMatch]" :type="show1 ? 'text' : 'password'" label="Confirm Password" counter @click:append="show1 = !show1" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="saveUser" :loading="loading">
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
                <v-card-title><?= lang('App.editUser') ?> {{emailEdit}}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="modalEditClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form ref="form" v-model="valid">
                        <v-text-field label="Email *" v-model="emailEdit" :rules="[rules.email]" outlined></v-text-field>

                        <v-text-field label="Username *" v-model="userNameEdit" :error-messages="usernameError" outlined disabled></v-text-field>

                        <v-text-field label="Nama Lengkap *" v-model="fullnameEdit" :error-messages="fullnameError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="updateUser" :loading="loading">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.update') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal Edit -->

<!-- Modal Password -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalPassword" persistent max-width="700px">
            <v-card>
                <v-card-title>Password {{emailEdit}}
                    <v-spacer></v-spacer>
                    <v-btn icon @click="changePassClose">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="py-5">
                    <v-form ref="form" v-model="valid">

                        <v-text-field label="Email *" v-model="emailEdit" :rules="[rules.email]" outlined disabled></v-text-field>

                        <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.min]" :type="show1 ? 'text' : 'password'" label="Password Baru" hint="<?= lang('App.minChar') ?>" counter @click:append="show1 = !show1" :error-messages="passwordError" outlined></v-text-field>

                        <v-text-field block v-model="verify" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[passwordMatch]" :type="show1 ? 'text' : 'password'" label="Confirm Password" counter @click:append="show1 = !show1" :error-messages="verifyError" outlined></v-text-field>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large color="primary" @click="updatePassword" :loading="loading">
                        <v-icon>mdi-content-save</v-icon> <?= lang('App.update') ?>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal -->

<!-- Modal Delete -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalDelete" persistent max-width="600px">
            <v-card class="pa-2">
                <v-card-title>
                    <v-icon color="error" class="mr-2" x-large>mdi-alert-octagon</v-icon> Konfirmasi Hapus
                </v-card-title>
                <v-card-text>
                    <div class="mt-4">
                        <h2 class="font-weight-medium"><?= lang('App.delConfirm') ?></h2>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text large @click="modalDelete = false"><?= lang("App.no") ?></v-btn>
                    <v-btn color="primary" dark large @click="deleteUser" :loading="loading"><?= lang("App.yes") ?></v-btn>
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
        startDate: "",
        endDate: "",
        headers: [{
            text: '# ',
            value: 'id_user'
        }, {
            text: 'E-mail',
            value: 'email'
        }, {
            text: 'Username',
            value: 'username'
        }, {
            text: 'Group',
            value: ''
        }, {
            text: '<?= lang("App.active") ?>',
            value: 'is_active'
        }, {
            text: '<?= lang('App.action') ?>',
            value: 'actions',
            sortable: false
        }, ],
        dataUsers: [],
        totalData: 0,
        data: [],
        options: {},
        roles: [{
            label: 'Admin',
            value: '1'
        }, {
            label: 'User',
            value: '2'
        }, ],
        modalAdd: false,
        modalEdit: false,
        modalDelete: false,
        modalPassword: false,
        userName: "",
        email: "",
        fullname: "",
        user_type: "",
        is_active: "",
        userIdEdit: "",
        userNameEdit: "",
        emailEdit: "",
        fullnameEdit: "",
        userIdDelete: "",
        userNameDelete: "",
        show1: false,
        password: "",
        verify: "",
        verifyError: "",
        emailError: "",
        fullnameError: "",
        usernameError: "",
        passwordError: "",
        user_typeError: "",
        is_activeError: "",
        groups: [],
        idGroup: "",
        id_groupError: ""
    }

    // Vue Created
    // Created: Dipanggil secara sinkron setelah instance dibuat
    createdVue = function() {
        this.getUsers();
        this.getGroups();
    }

    // Vue Computed
    // Computed: Properti-properti terolah (computed) yang kemudian digabung kedalam Vue instance
    computedVue = {
        ...computedVue,
        passwordMatch() {
            return () => this.password === this.verify || "<?= lang('App.samePassword') ?>";
        }
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

        dataUsers: function() {
            if (this.dataUsers != '') {
                // Call server-side paginate and sort
                this.getDataFromApi();
            }
        }
    }

    // Vue Methods
    // Methods: Metode-metode yang kemudian digabung ke dalam Vue instance
    methodsVue = {
        ...methodsVue,
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

                let items = this.dataUsers
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
            this.userName = "";
            this.email = "";
            this.modalAdd = false;
            this.$refs.form.resetValidation();
        },

        // Filter Date
        reset: function() {
            this.startDate = "";
            this.endDate = "";
        },
        tujuhHari: function() {
            this.startDate = "<?= $tujuhHari; ?>";
            this.endDate = "<?= $hariini; ?>";
        },
        hariini: function() {
            this.startDate = "<?= $hariini; ?>";
            this.endDate = "<?= $hariini; ?>";
        },
        bulanIni: function() {
            this.startDate = "<?= $awalBulan; ?>";
            this.endDate = "<?= $akhirBulan; ?>";
        },
        tahunIni: function() {
            this.startDate = "<?= $awalTahun; ?>";
            this.endDate = "<?= $akhirTahun; ?>";
        },

        // Handle Submit Filter
        handleSubmit: function() {
            //if (this.startDate != '' && this.endDate != '') {
            //this.getUsersFiltered();
            //this.menu = false;
            //} else {
            this.getUsers();
            this.startDate = "";
            this.endDate = "";
            this.menu = false;
            //}
        },

        // Get User
        getUsers: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/user', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.dataUsers = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataUsers = data.data;
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

        // Save User
        saveUser: function() {
            this.loading = true;
            axios.post('<?= base_url(); ?>api/user/save', {
                    email: this.email,
                    username: this.userName,
                    fullname: this.fullname,
                    password: this.password,
                    id_group: this.idGroup
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
                        this.userName = "";
                        this.email = "";
                        this.fullname = "";
                        this.password = "";
                        this.idGroup = "";
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
            this.userIdEdit = user.id_user;
            this.userNameEdit = user.username;
            this.emailEdit = user.email;
            this.fullnameEdit = user.fullname;
        },
        modalEditClose: function() {
            this.modalEdit = false;
            this.$refs.form.resetValidation();
        },

        //Update
        updateUser: function() {
            this.loading = true;
            axios.put(`<?= base_url(); ?>api/user/update/${this.userIdEdit}`, {
                    user: this.userNameEdit,
                    email: this.emailEdit,
                    fullname: this.fullnameEdit,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
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
            this.userIdDelete = item.id_user;
            this.userNameDelete = item.username;
        },

        // Delete
        deleteUser: function() {
            this.loading = true;
            axios.delete(`<?= base_url(); ?>api/user/delete/${this.userIdDelete}`, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
                        this.modalDelete = false;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.modalDelete = true;
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

        // Set Item Active
        setActive: function(item) {
            this.loading = true;
            this.userIdEdit = item.id_user;
            this.active = item.active;
            axios.put(`<?= base_url(); ?>api/user/setActive/${this.userIdEdit}`, {
                    is_active: this.is_active,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
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

        // Set Role
        setRole: function(item) {
            this.loading = true;
            this.userIdEdit = item.id_user;
            this.user_type = item.user_type;
            axios.put(`<?= base_url(); ?>api/user/setRole/${this.userIdEdit}`, {
                    user_type: this.user_type,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
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

        // Change Password
        changePassword: function(user) {
            this.modalPassword = true;
            this.userIdEdit = user.id_user;
            this.userNameEdit = user.username;
            this.emailEdit = user.email;
            this.fullnameEdit = user.fullname;
        },
        changePassClose: function() {
            this.modalPassword = false;
            this.$refs.form.resetValidation();
        },

        updatePassword() {
            this.loading = true;
            axios.post('<?= base_url() ?>api/user/changePassword', {
                    email: this.emailEdit,
                    password: this.password,
                    verify: this.verify
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false
                    var data = res.data;
                    if (data.status == true) {
                        this.submitted = true;
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.password = "";
                        this.verify = "";
                        this.modalPassword = false;
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
                        this.modalPassword = true;
                        this.$refs.form.validate();
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false
                })
        },

        // Get Group
        getGroups: function() {
            this.loading = true;
            axios.get('<?= base_url(); ?>api/groups', options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        //this.snackbar = true;
                        //this.snackbarMessage = data.message;
                        this.groups = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.groups = data.data;
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

        // Set Group
        setGroup: function(item) {
            this.loading = true;
            this.userIdEdit = item.id_login;
            this.idGroup = item.id_group;
            axios.put(`<?= base_url(); ?>api/user/setgroup/${this.userIdEdit}`, {
                    id_group: this.idGroup,
                }, options)
                .then(res => {
                    // handle success
                    this.loading = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.getUsers();
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
    }
</script>
<?php $this->endSection("js") ?>