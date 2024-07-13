<?php
// Memanggil library
use App\Libraries\Permission;
use App\Libraries\Settings;
use App\Libraries\Language;

$language = new Language();
$permission = new Permission();
$user_permission = $permission->init();

$setting = new Settings();
$appName = $setting->info['app_name'];
$logo = $setting->info['img_logo'];
$background = $setting->info['img_background'];
$navbarColor = $setting->info['navbar_color'];
$sidebarColor = $setting->info['sidebar_color'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title><?= $title ?> - <?= $appName ?></title>
    <meta name="description" content="<?= $appName; ?>">
    <link rel="shortcut icon" href="<?= base_url() . $logo; ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="<?= base_url('assets/css/materialdesignicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/vuetify.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/quill.core.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/quill.bubble.css') ?>" rel="stylesheet">

    <style>
        input[type="color"] {
            -webkit-appearance: none;
            border: none;
            width: 32px;
            height: 32px;
        }

        input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        input[type="color"]::-webkit-color-swatch {
            border: none;
        }
    </style>

</head>

<body>
    <!-- ========================= preloader start ========================= -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-logo"><img src="<?= base_url() . $logo; ?>" alt="Preloader" width="64" style="margin-top: 5px !important"></div>
            <div class="spinner">
                <div class="spinner-container">
                    <div class="spinner-rotator">
                        <div class="spinner-left">
                            <div class="spinner-circle"></div>
                        </div>
                        <div class="spinner-right">
                            <div class="spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- preloader end -->
    <div id="app">
        <v-app>
            <v-app-bar app color="<?= $navbarColor; ?>" <?= ($navbarColor == 'white' ? 'light' : 'dark'); ?> :color="$vuetify.theme.dark ? '':'<?= $navbarColor; ?>'" elevation="2">
                <v-app-bar-nav-icon @click.stop="sidebarMenu = !sidebarMenu"></v-app-bar-nav-icon>
                <v-toolbar-title></v-toolbar-title>
                <v-spacer></v-spacer>
                <?php if (!empty(session()->get('username'))) : ?>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text v-bind="attrs" v-on="on">
                                <v-icon>mdi-account-circle</v-icon> <span class="d-none d-sm-flex"><?= session()->get('email') ?></span> <v-icon>mdi-chevron-down</v-icon>
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item class="d-flex justify-center">
                                <v-list-item-avatar size="100">
                                    <v-img src="<?= base_url('assets/images/default.png'); ?>"></v-img>
                                </v-list-item-avatar>
                            </v-list-item>
                            <v-list-item link>
                                <v-list-item-content>
                                    <v-list-item-title class="text-h6">
                                        Hai, <?= session()->get('fullname') ?>
                                    </v-list-item-title>
                                    <v-list-item-subtitle><?= session()->get('email') ?></v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <v-subheader>Login: &nbsp;<v-chip color="primary" small><?= session()->get('group'); ?></v-chip>
                            </v-subheader>
                            <v-list-item link href="<?= base_url(); ?>">
                                <v-list-item-icon>
                                    <v-icon>mdi-home</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Back to Home</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item link href="<?= base_url('logout'); ?>" @click="localStorage.removeItem('access_token')">
                                <v-list-item-icon>
                                    <v-icon>mdi-logout</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Logout</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                <?php endif; ?>
                <v-divider class="mx-1" vertical></v-divider>
                <v-btn icon @click.stop="rightMenu = !rightMenu">
                    <v-icon>mdi-cog-outline</v-icon>
                </v-btn>
            </v-app-bar>

            <v-navigation-drawer color="<?= $sidebarColor; ?>" <?= ($sidebarColor == 'white' ? 'light' : 'dark'); ?> :color="$vuetify.theme.dark ? '':'<?= $sidebarColor; ?>'" v-model="sidebarMenu" app floating :permanent="sidebarMenu" :mini-variant.sync="mini" v-if="!isMobile" class="elevation-3">
                <v-list color="<?= $sidebarColor; ?>" :color="$vuetify.theme.dark ? '':'<?= $sidebarColor; ?>'" dense>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon @click.stop="toggleMini = !toggleMini">mdi-chevron-left</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6 py-1">
                                <?= $appName; ?>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list nav>
                    <?php $uri = new \CodeIgniter\HTTP\URI(current_url()); ?>

                    <?php if (in_array('menuDashboard', $user_permission)) : ?>
                        <?php if (in_array('viewDashboard', $user_permission)) : ?>
                            <v-list-item link href="<?= base_url('dashboard'); ?>" <?php if ($uri->getSegment(1) == "dashboard") : ?><?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Dashboard" title="Dashboard">
                                <v-list-item-icon>
                                    <v-icon>mdi-home</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Dashboard</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (in_array('viewGaji', $user_permission)) : ?>
                        <v-list-item link href="<?= base_url('gaji'); ?>" <?php if ($uri->getSegment(1) == "gaji") : ?><?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Daftar Gaji" title="Daftar Gaji">
                            <v-list-item-icon>
                                <v-icon>mdi-cash-multiple</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Daftar Gaji</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    <?php endif; ?>

                    <?php if (in_array('viewGolongan', $user_permission)) : ?>
                        <v-list-item link href="<?= base_url('golongan'); ?>" <?php if ($uri->getSegment(1) == "golongan") : ?><?= 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> title="Golongan" alt="Golongan">
                            <v-list-item-icon>
                                <v-icon>mdi-file-document-outline</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Golongan</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    <?php endif; ?>

                    <?php if (in_array('menuPages', $user_permission)) : ?>
                        <?php if (in_array('viewPages', $user_permission)) : ?>
                            <v-list-item link href="<?= base_url('pages'); ?>" <?php if ($uri->getSegment(1) == "pages") : ?><?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Pages" title="Pages">
                                <v-list-item-icon>
                                    <v-icon>mdi-file-document</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Pages</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (in_array('menuUser', $user_permission)) : ?>
                        <v-list-group color="<?= ($sidebarColor == 'white' ? 'dark' : 'white'); ?>" prepend-icon="mdi-account-multiple" <?php if ($uri->getSegment(1) == "user" || $uri->getSegment(1) == "group") : ?><?= 'value="true"'; ?><?php endif; ?> title="<?= lang('App.users') ?>" alt="<?= lang('App.users') ?>">
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title><?= lang('App.users'); ?></v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <?php if (in_array('viewUser', $user_permission)) : ?>
                                <v-list-item link href="<?= base_url('user'); ?>" <?php if ($uri->getSegment(1) == "user") : ?><?= 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> title="<?= lang('App.users'); ?>" alt="<?= lang('App.users'); ?>">
                                    <v-list-item-icon>
                                        <v-icon>mdi-account</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title><?= lang('App.users'); ?></v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            <?php endif; ?>

                            <?php if (in_array('viewGroup', $user_permission)) : ?>
                                <v-list-item link href="<?= base_url('group'); ?>" <?php if ($uri->getSegment(1) == "group") : ?><?= 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> title="Group" alt="Group">
                                    <v-list-item-icon>
                                        <v-icon>mdi-shield-check</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Group</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            <?php endif; ?>
                        </v-list-group>
                    <?php endif; ?>

                    <?php if (in_array('menuSetting', $user_permission)) : ?>
                        <v-list-group color="<?= ($sidebarColor == 'white' ? 'dark' : 'white'); ?>" prepend-icon="mdi-cog" <?php if ($uri->getSegment(1) == "settings" || $uri->getSegment(1) == "backup") : ?><?= 'value="true"'; ?><?php endif; ?> title="<?= lang('App.settings') ?>" alt="<?= lang('App.settings') ?>">
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title><?= lang('App.settings'); ?></v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <?php if (in_array('viewSetting', $user_permission)) : ?>
                                <v-list-item link href="<?= base_url('settings'); ?>" <?php if ($uri->getSegment(1) == "settings") : ?><?= 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> title="<?= lang('App.application'); ?>" alt="<?= lang('App.application'); ?>">
                                    <v-list-item-icon>
                                        <v-icon>mdi-cog</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title><?= lang('App.application'); ?></v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            <?php endif; ?>

                            <?php if (in_array('viewBackup', $user_permission)) : ?>
                                <v-list-item link href="<?= base_url('backup'); ?>" <?php if ($uri->getSegment(1) == "backup") : ?><?= 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> title="Backup Database" alt="Backup Database">
                                    <v-list-item-icon>
                                        <v-icon>mdi-database</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>Backup DB</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            <?php endif; ?>
                        </v-list-group>
                    <?php endif; ?>


                </v-list>

                <template v-slot:append>
                    <v-divider></v-divider>
                    <div class="text-center">
                        <v-list-item>
                            <v-list-item-icon style="font-size:12px;" v-if="toggleMini">
                                &copy; {{ new Date().getFullYear() }}
                            </v-list-item-icon>
                            <v-list-item-content style="font-size:12px;" v-else>&copy; {{ new Date().getFullYear() }} <?= env('appCompany') ?>. <?= $appName; ?> <?= env('appVersion') ?></v-list-item-content>
                        </v-list-item>
                    </div>
                </template>

            </v-navigation-drawer>

            <v-navigation-drawer v-model="rightMenu" app right bottom temporary>
                <template v-slot:prepend>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title><?= lang('App.settings'); ?></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>

                <v-divider></v-divider>

                <v-list-item>
                    <v-list-item-avatar>
                        <v-icon>mdi-theme-light-dark</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        Tema {{themeText}}
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-switch v-model="dark" inset @click="toggleTheme"></v-switch>
                    </v-list-item-action>
                </v-list-item>

                <v-list-item>
                    <v-list-item-avatar>
                        <v-icon>mdi-earth</v-icon>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        Lang
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn-toggle>
                            <v-btn text small link href="<?= base_url('lang/id'); ?>">
                                ID
                            </v-btn>
                            <v-btn text small link href="<?= base_url('lang/en'); ?>">
                                EN
                            </v-btn>
                        </v-btn-toggle>
                    </v-list-item-action>
                </v-list-item>
            </v-navigation-drawer>

            <v-main>
                <v-container class="pa-5" fluid>
                    <?= $this->renderSection('content') ?>
                </v-container>
            </v-main>

            <v-snackbar v-model="snackbar" :timeout="timeout" style="bottom:20px;">
                <span v-if="snackbar">{{snackbarMessage}}</span>
                <template v-slot:action="{ attrs }">
                    <v-btn text v-bind="attrs" @click="snackbar = false">
                        ok
                    </v-btn>
                </template>
            </v-snackbar>
        </v-app>
    </div>

    <script src="<?= base_url('assets/js/vue.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vuetify.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/axios.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/main.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/quill.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vue-quill-editor.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vuejs-paginate.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vue-masonry-plugin-window.js') ?>"></script>
    <script src="<?= base_url('assets/js/anime.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/Chart.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vue-chartjs.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/dayjs.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/dayjs-locale-id.js') ?>"></script>

    <script>
        dayjs.locale('id');
        dayjs().locale('id').format();
    </script>

    <script>
        var vue = null;
        var computedVue = {
            mini: {
                get() {
                    return this.$vuetify.breakpoint.xsOnly || this.toggleMini;
                },
                set(value) {
                    this.toggleMini = value;
                }
            },
            isMobile() {
                if (this.$vuetify.breakpoint.xsOnly) {
                    return this.sidebarMenu = false
                }
            },
            themeText() {
                return this.$vuetify.theme.dark ? '<?= lang('App.dark') ?>' : '<?= lang('App.light') ?>'
            }
        }
        var createdVue = function() {
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        }
        var mountedVue = function() {
            const theme = localStorage.getItem("dark_theme");
            if (theme) {
                if (theme === "true") {
                    this.$vuetify.theme.dark = true;
                    this.dark = true;
                } else {
                    this.$vuetify.theme.dark = false;
                    this.dark = false;
                }
            } else if (
                window.matchMedia &&
                window.matchMedia("(prefers-color-scheme: dark)").matches
            ) {
                this.$vuetify.theme.dark = false;
                localStorage.setItem(
                    "dark_theme",
                    this.$vuetify.theme.dark.toString()
                );
            }
        }
        var updatedVue = function() {}
        var watchVue = {}
        var dataVue = {
            sidebarMenu: true,
            rightMenu: false,
            toggleMini: false,
            dark: false,
            group: null,
            search: '',
            loading: false,
            loading1: false,
            loading2: false,
            loading3: false,
            loading4: false,
            loading5: false,
            loading6: false,
            loading7: false,
            loading8: false,
            loading9: false,
            loading10: false,
            valid: true,
            notifMessage: '',
            notifType: '',
            snackbar: false,
            timeout: 4000,
            snackbarType: '',
            snackbarMessage: '',
            show: false,
            show1: false,
            show2: false,
            rules: {
                email: v => !!(v || '').match(/@/) || '<?= lang('App.emailValid'); ?>',
                length: len => v => (v || '').length <= len || `<?= lang('App.invalidLength'); ?> ${len}`,
                password: v => !!(v || '').match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/) ||
                    '<?= lang('App.strongPassword'); ?>',
                min: v => (v || '').length >= 8 || '<?= lang('App.minChar'); ?>',
                required: v => !!v || '<?= lang('App.isRequired'); ?>',
                number: v => Number.isInteger(Number(v)) || "<?= lang('App.isNumber'); ?>",
                zero: v => v > 0 || "<?= lang('App.isZero'); ?>",
                varchar: v => (v || '').length <= 255 || 'Maks 255 Karakter'
            },
            editorOption: {
                theme: 'snow',
                modules: {
                    'toolbar': [
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        [{
                            'font': []
                        }],
                        ['bold', 'italic', 'underline'], // toggled buttons
                        ['blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'align': []
                        }],
                        ['link', 'image'],
                        ['clean']
                    ],
                },
            },
        }
        var methodsVue = {
            toggleTheme() {
                this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
                localStorage.setItem("dark_theme", this.$vuetify.theme.dark.toString());
            },
            formatNumber(number) {
                const formattedNumber = new Intl.NumberFormat('<?= $language->siteLang; ?>', {
                    notation: 'compact',
                    compactDisplay: 'short',
                }).format(number);
                return formattedNumber;
            },
        }
        Vue.use(VueQuillEditor);
        var VueMasonryPlugin = window["vue-masonry-plugin"].VueMasonryPlugin;
        Vue.use(VueMasonryPlugin);
        Vue.component('paginate', VuejsPaginate);
    </script>
    <?= $this->renderSection('js') ?>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            computed: computedVue,
            data: dataVue,
            mounted: mountedVue,
            created: createdVue,
            updated: updatedVue,
            watch: watchVue,
            methods: methodsVue,
        })
    </script>
</body>

</html>