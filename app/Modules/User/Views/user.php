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
                    <td>{{item.user_id}}</td>
                    <td>{{item.email}}</td>
                    <td>{{item.username}}</td>
                    <td>
                        <v-select
                            v-model="userRoleSelection[item.user_id]"
                            :items="allRoles"
                            item-text="name"
                            item-value="role_id"
                            label="Roles"
                            multiple
                            chips
                            dense
                            hide-details
                            :loading="roleLoading[item.user_id] === true"
                            :disabled="roleLoading[item.user_id] === true || item.user_id == '1'"
                            @change="(val) => updateRoles(item.user_id, val)"
                            class="w-100"></v-select>
                    </td>
                    <td>
                        <span v-if="item.username == 'admin'">
                            <v-switch v-model="item.is_active" name="is_active" false-value="0" true-value="1" color="success" disabled></v-switch>
                        </span>
                        <span v-else>
                            <v-switch v-model="item.is_active" name="is_active" false-value="0" true-value="1" color="success" @click="setActive(item)" :disabled="item.user_id == <?= session('id'); ?>"></v-switch>
                        </span>
                    </td>
                    <td>
                        <v-btn color="primary" class="mr-3" @click="editItem(item)" title="Edit" alt="Edit" icon>
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon color="indigo" @click="openModalLog(item)" class="mr-3" title="Activity Log" alt="Activity Log">
                            <v-icon>mdi-clipboard-text-clock</v-icon>
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
                            <v-btn color="error" @click="deleteItem(item)" title="Delete" alt="Delete" icon :disabled="item.user_id == <?= session('id'); ?>">
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
                        <v-select v-model="idGroup" name="role" :items="groups" item-text="group_name" item-value="group_id" label="Select Group *" :error-messages="group_idError" outlined></v-select>

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

<!-- Modal User Log -->
<template>
    <v-row justify="center">
        <v-dialog v-model="modalLog" scrollable persistent max-width="900px">
            <v-card>
                <v-card-title>User Log Activity
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeModalLog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text class="my-5">
                    <p class="mb-1"><strong>Filter:</strong></p>
                    <div class="mb-3">
                        <a @click="hariini" title="Hari Ini" alt="Hari Ini">Hari Ini</a> &bull;
                        <a @click="tujuhHari" title="7 Hari Kemarin" alt="7 Hari Kemarin">7 Hari Kemarin</a> &bull;
                        <a @click="bulanIni" title="Bulan Ini" alt="Bulan Ini">Bulan Ini</a> &bull;
                        <a @click="tahunIni" title="Tahun Ini" alt="Tahun Ini">Tahun Ini</a> &bull;
                        <a @click="tahunLalu" title="Tahun Lalu" alt="Tahun Lalu">Tahun Lalu</a> &bull;
                        <a @click="reset" title="Reset" alt="Reset">Reset</a>
                    </div>
                    <v-row>
                        <v-col>
                            <v-text-field v-model="startDate" type="date"></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field v-model="endDate" type="date"></v-text-field>
                        </v-col>
                        <v-col>
                            <v-btn large color="primary" text outlined @click="handleSubmit" :loading="loading">Filter</v-btn>
                        </v-col>
                    </v-row>

                    <v-data-table :headers="tbUserLog" :items="dataUserLog" :items-per-page="5" class="elevation-1" :loading="loading1" dense>
                        <template v-slot:item="{ item }">
                            <tr>
                                <td>{{item.keterangan}}</td>
                                <td>{{item.created_at}}</td>
                            </tr>
                        </template>
                    </v-data-table>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn large @click="closeModalLog" elevation="0"><?= lang("App.close") ?></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<!-- End Modal User Log -->

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
                    <v-btn text large @click="deleteUser" :loading="loading"><?= lang("App.yes") ?>, <?= lang("App.delete"); ?></v-btn>
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
            value: 'user_id'
        }, {
            text: 'E-mail',
            value: 'email'
        }, {
            text: 'Username',
            value: 'username'
        }, {
            text: 'Roles',
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
        group_idError: "",
        modalLog: false,
        dataUserLog: [],
        tbUserLog: [{
            text: 'Keterangan',
            value: 'keterangan'
        }, {
            text: 'Date',
            value: 'created_at'
        }, ],

        allRoles: [], // [{ id: 1, name: 'Admin' }, ...]
        userRoleSelection: {}, // { user_id: [role_ids] }
        roleLoading: {},
    }

    // Vue Created
    // Created: Dipanggil secara sinkron setelah instance dibuat
    createdVue = function() {
        this.getUsers();
        this.getRoles();
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
            this.startDate = "<?= $awalTahun; ?>";
            this.endDate = "<?= $akhirTahun; ?>";
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
        tahunLalu: function() {
            this.startDate = "<?= $awalTahunLalu; ?>";
            this.endDate = "<?= $akhirTahunLalu; ?>";
        },

        // Handle Search Filter
        handleSearch: function() {
            this.getUsers();
        },

        // Handle Submit Filter
        handleSubmit: function() {
            this.userLog();
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
                        // Init selected roles per user
                        res.data.data.forEach(user => {
                            this.$set(this.userRoleSelection, user.user_id, user.roles.map(role => role.role_id));
                        });
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataUsers = data.data;
                        this.data = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading = false;
                    var error = err.response;
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
                    group_id: this.idGroup
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
                    this.loading = false;
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
        editItem: function(user) {
            this.modalEdit = true;
            this.show = false;
            this.userIdEdit = user.user_id;
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
                    this.loading = false;
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
            this.userIdDelete = item.user_id;
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
                    this.loading = false;
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

        // Set Item Active
        setActive: function(item) {
            this.loading = true;
            this.userIdEdit = item.user_id;
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
                    this.loading = false;
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

        // Set Role
        setRole: function(item) {
            this.loading = true;
            this.userIdEdit = item.user_id;
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
                    this.loading = false;
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

        // Change Password
        changePassword: function(user) {
            this.modalPassword = true;
            this.userIdEdit = user.user_id;
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
                    this.loading = false;
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
                    this.loading = false;
                    var error = err.response;
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
            this.userIdEdit = item.user_id;
            this.idGroup = item.group_id;
            axios.put(`<?= base_url(); ?>api/user/setgroup/${this.userIdEdit}`, {
                    group_id: this.idGroup,
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
                    this.loading = false;
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

        // Get User Log
        openModalLog: function(user) {
            this.modalLog = true;
            this.userIdEdit = user.user_id;
            this.userLog();
        },

        userLog: function() {
            this.loading1 = true;
            axios.get(`<?= base_url(); ?>api/log/${this.userIdEdit}?tgl_start=${this.startDate}&tgl_end=${this.endDate}`, options)
                .then(res => {
                    // handle success
                    this.loading1 = false;
                    var data = res.data;
                    if (data.status == true) {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataUserLog = data.data;
                    } else {
                        this.snackbar = true;
                        this.snackbarMessage = data.message;
                        this.dataUserLog = data.data;
                    }
                })
                .catch(err => {
                    // handle error
                    console.log(err);
                    this.loading1 = false;
                    var error = err.response;
                    if (error.data.expired == true) {
                        this.snackbar = true;
                        this.snackbarMessage = error.data.message;
                        setTimeout(() => window.location.href = error.data.data.url, 1000);
                    }
                })
        },

        closeModalLog: function() {
            this.modalLog = false;
            this.dataUserLog = [];
            this.reset();
        },

        getRoles: function() {
            axios.get('<?= base_url('api/role') ?>').then((res) => {
                this.allRoles = res.data.data; // Assumes [{id, name}]
            });
        },

        updateRoles: function(userId, newRoleIds) {
            this.$set(this.roleLoading, userId, true);
            axios.post(`<?= base_url('api/user/update-roles/') ?>${userId}`, {
                roles: newRoleIds
            }).then(res => {
                this.snackbar = true;
                this.snackbarMessage = res.data.message;
            }).catch(err => {
                this.snackbar = true;
                this.snackbarMessage = err.response?.data?.message || 'Gagal memperbarui roles';
            }).finally(() => {
                this.$set(this.roleLoading, userId, false);
            });
        }
    }
</script>
<?php $this->endSection("js") ?>