<?php $this->extend("layouts/backend"); ?>
<?php $this->section("content"); ?>
<template>
    <h1 class="font-weight-medium mb-2"><?= $title ?></span></h1>
    <v-card>
        <v-card-title>

        </v-card-title>
        <v-card-text>
            <?= form_open('/group/update/' . $id) ?>

            <v-text-field type="text" name="nama_group" value="<?= $group['nama_group']; ?>" label="Nama Group" outlined></v-text-field>

            <div class="v-data-table theme--light">
                <div class="v-data-table__wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th width="100"></th>
                                <th class="text-left" width="100">View</th>
                                <th class="text-left" width="100">Create</th>
                                <th class="text-left" width="100">Update</th>
                                <th class="text-left" width="100">Delete</th>
                                <th class="text-left" width="100">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-h6">Dashboard</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="viewDashboard" value="viewDashboard" <?php if ($permissions) { ?> <?php if (in_array('viewDashboard', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="viewDashboard"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="menuDashboard" value="menuDashboard" <?php if ($permissions) { ?><?php if (in_array('menuDashboard', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="menuDashboard"></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5"><?= strtoupper(lang('App.users')); ?> SISTEM</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="menuUser" value="menuUser" <?php if ($permissions) { ?><?php if (in_array('menuUser', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="menuUser"></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-h6">Users</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="viewUser" value="viewUser" <?php if ($permissions) { ?> <?php if (in_array('viewUser', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="viewUser"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="createUser" value="createUser" <?php if ($permissions) { ?> <?php if (in_array('createUser', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="createUser"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="updateUser" value="updateUser" <?php if ($permissions) { ?> <?php if (in_array('updateUser', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="updateUser"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="deleteUser" value="deleteUser" <?php if ($permissions) { ?><?php if (in_array('deleteUser', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="deleteUser"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                            </tr>

                            <tr>
                                <td class="text-h6">Group</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="viewGroup" value="viewGroup" <?php if ($permissions) { ?> <?php if (in_array('viewGroup', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="viewGroup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="createGroup" value="createGroup" <?php if ($permissions) { ?> <?php if (in_array('createGroup', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="createGroup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="updateGroup" value="updateGroup" <?php if ($permissions) { ?> <?php if (in_array('updateGroup', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="updateGroup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="deleteGroup" value="deleteGroup" <?php if ($permissions) { ?><?php if (in_array('deleteGroup', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="deleteGroup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">PENGATURAN</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="menuSetting" value="menuSetting" <?php if ($permissions) { ?><?php if (in_array('menuSetting', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="menuSetting"></label>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-h6">Settings</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="viewSetting" value="viewSetting" <?php if ($permissions) { ?> <?php if (in_array('viewSetting', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="viewSetting"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="updateSetting" value="updateSetting" <?php if ($permissions) { ?> <?php if (in_array('updateSetting', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="updateSetting"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                            </tr>

                            <tr>
                                <td class="text-h6">Backup DB</td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="viewBackup" value="viewBackup" <?php if ($permissions) { ?> <?php if (in_array('viewBackup', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="viewBackup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="createBackup" value="createBackup" <?php if ($permissions) { ?> <?php if (in_array('createBackup', $permissions)) { ?> <?= "checked"; ?> <?php } ?> <?php } ?>>
                                            <label for="createBackup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    <div class="v-input__control">
                                        <div class="v-input__slot">
                                            <input class="v-input--selection-controls__input" type="checkbox" name="permission[]" id="deleteBackup" value="deleteBackup" <?php if ($permissions) { ?><?php if (in_array('deleteBackup', $permissions)) { ?><?= "checked"; ?><?php } ?><?php } ?>><label for="deleteBackup"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    -
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br />
            <button type="submit" class="v-btn v-btn--is-elevated v-btn--has-bg theme--light elevation-2 v-size--large primary">Update</button>
            <?= form_close(); ?>
        </v-card-text>
    </v-card>
</template>

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
    // Mendapatkan Token JWT
    const token = JSON.parse(localStorage.getItem('access_token'));

    // Menambahkan Auth Bearer Token yang didapatkan sebelumnya
    const options = {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    };

    // Deklarasi errorKeys
    var errorKeys = []

    // Initial Data
    dataVue = {
        ...dataVue,
        
    }

    // Vue Created
    // Created: Dipanggil secara sinkron setelah instance dibuat
    createdVue = function() {
        
    }

    watchVue = {
        ...watchVue,
        
    }

    // Vue Methods
    // Methods: Metode-metode yang kemudian digabung ke dalam Vue instance
    methodsVue = {
        ...methodsVue,
        

    }
</script>
<?php $this->endSection("js") ?>