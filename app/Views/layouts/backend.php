<!DOCTYPE html>
<html lang="en">
<?php

use App\Libraries\Settings;

$setting = new Settings();
$appname = $setting->info['app_name'];
$logo = $setting->info['app_logo'];
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title><?= $title ?> - <?= $appname ?></title>
    <link rel="shortcut icon" href="<?= base_url(); ?>/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="<?= base_url('assets/css/materialdesignicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/vuetify.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet">

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
            <div class="loader-logo"><img src="<?= base_url() . "/" . $logo; ?>" alt="Preloader" width="64" style="margin-top: 5px !important"></div>
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
            <v-app-bar app color="white" light elevation="2">
                <v-app-bar-nav-icon @click.stop="sidebarMenu = !sidebarMenu"></v-app-bar-nav-icon>
                <v-toolbar-title></v-toolbar-title>
                <v-spacer></v-spacer>
                <?php if (!empty(session()->get('username'))) : ?>
                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text v-bind="attrs" v-on="on">
                                <v-icon>mdi-account-circle</v-icon> <?= session()->get('username') ?> <v-icon>mdi-chevron-down</v-icon>
                            </v-btn>
                        </template>

                        <v-list>
                            <v-subheader>Login: &nbsp;<v-chip color="primary" small><?= session()->get('user_type') == 1 ? 'admin' : 'user'; ?></v-chip>
                            </v-subheader>
                            <v-list-item><?= session()->get('email') ?></v-list-item>
                            <v-list-item link href="<?= base_url(); ?>">
                                <v-list-item-icon>
                                    <v-icon>mdi-home</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Kembali ke Home</v-list-item-title>
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

            <v-navigation-drawer color="blue darken-3" dark v-model="sidebarMenu" app floating :permanent="sidebarMenu" :mini-variant.sync="mini" v-if="!isMobile" class="elevation-3">
                <v-list color="blue darken-3" dense>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon @click.stop="toggleMini = !toggleMini">mdi-chevron-left</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6">
                                DISFO
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list nav>
                    <?php $uri = new \CodeIgniter\HTTP\URI(current_url()); ?>

                    <v-list-item link href="<?= base_url('dashboard'); ?>" <?php if ($uri->getSegment(1) == "dashboard") : ?><?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Dashboard" title="Dashboard">
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Dashboard</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <?php if (session()->get('user_type') == 1) : ?>
                        <v-list-group v-for="(item, i) in settings" :key="item.title" v-model="item.active" :prepend-icon="item.action" color="white">
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.title"></v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <v-list-item v-for="child in item.items" :key="child.title" link :href="child.url" v-model="child.active">
                                <v-list-item-icon>
                                    <v-icon>mdi-cog</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="child.title"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>

                        <v-divider></v-divider>

                        <v-list-item link href="<?= base_url('user'); ?>" <?php if ($uri->getSegment(1) == "user") : ?> <?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Daftar User" title="Daftar User">
                            <v-list-item-icon>
                                <v-icon>mdi-account-multiple</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Pengguna</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item link href="<?= base_url('backup'); ?>" <?php if ($uri->getSegment(1) == "backup") : ?><?php echo 'class="v-item--active v-list-item--active"'; ?><?php endif; ?> alt="Backup Database" title="Backup Database">
                            <v-list-item-icon>
                                <v-icon>mdi-database</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>Backup DB</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    <?php endif; ?>

                    <?php if ((session()->get('user_type') == 2) || (session()->get('user_type') == 3)) : ?>

                    <?php endif; ?>
                </v-list>

                <template v-slot:append>
                    <v-divider></v-divider>
                    <div class="text-center">
                        <v-list-item>
                            <v-list-item-icon style="font-size:12px;" v-if="toggleMini">
                                &copy; {{ new Date().getFullYear() }} ITSP DISFO v2
                            </v-list-item-icon>
                            <v-list-item-content style="font-size:12px;" v-else>&copy; {{ new Date().getFullYear() }} IT Shop Purwokerto. DISFO v2</v-list-item-content>
                        </v-list-item>
                    </div>
                </template>

            </v-navigation-drawer>

            <v-navigation-drawer v-model="rightMenu" app right bottom temporary>
                <template v-slot:prepend>
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Pengaturan</v-list-item-title>
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
            settings: [{
                title: 'Pengaturan',
                action: 'mdi-cog',
                active: <?php if ($uri->getSegment(2) == "general" || $uri->getSegment(2) == "app") { ?><?php echo 'true'; ?><?php } else { ?><?php echo 'false'; ?><?php } ?>,
                items: [{
                        title: 'Umum',
                        url: '<?= base_url('setting/general'); ?>',
                        active: <?php if ($uri->getSegment(2) == "general") { ?><?php echo 'true'; ?><?php } else { ?><?php echo 'false'; ?><?php } ?>,
                    },
                    {
                        title: 'Aplikasi',
                        url: '<?= base_url('setting/app'); ?>',
                        active: <?php if ($uri->getSegment(2) == "app") { ?><?php echo 'true'; ?><?php } else { ?><?php echo 'false'; ?><?php } ?>,
                    },
                ],

            }, ],
        }
        var methodsVue = {
            toggleTheme() {
                this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
                localStorage.setItem("dark_theme", this.$vuetify.theme.dark.toString());
            }
        }
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
            watch: watchVue,
            methods: methodsVue,
            components: {
                apexchart: VueApexCharts,
            },
        })
    </script>
</body>

</html>