<?php

use App\Libraries\Settings;

$setting = new Settings();
$appName = $setting->info['app_name'];
$logo = $setting->info['img_logo'];
$background = $setting->info['img_background'];
$navbarColor = $setting->info['navbar_color'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <title><?= $title; ?> - <?= $appName; ?></title>
    <meta name="description" content="<?= $title; ?>">
    <link rel="shortcut icon" href="<?= base_url() . $logo; ?>" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" type="text/css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/materialdesignicons.min.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/vuetify.min.css') ?>" type="text/css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet" />
</head>

<body>
    <!-- ========================= preloader start ========================= -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-logo"><img src="<?= base_url() . $logo; ?>" alt="Preloader" width="64" style="margin-top: 5px;"></div>
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
            <v-app-bar app color="<?= $navbarColor; ?>" <?= ($navbarColor == 'white' ? 'light':'dark'); ?> :color="$vuetify.theme.dark ? '':'<?= $navbarColor; ?>'" elevation="2">
                <v-btn href="<?= base_url() ?>" text>
                    <v-toolbar-title style="cursor: pointer"><?= $appName; ?></v-toolbar-title>
                </v-btn>
                <v-spacer></v-spacer>
                <?php if (empty(session()->get('username'))) : ?>
                    <v-btn text @click="modalAuthOpen">
                        <v-icon>mdi-login-variant</v-icon> <span class="d-none d-sm-flex">Login</span>
                    </v-btn>
                <?php endif; ?>

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
                            <v-list-item link href="<?= base_url(); ?><?= session()->get('user_type') == 1 ? 'dashboard' : 'dashboard'; ?>">
                                <v-list-item-icon>
                                    <v-icon>mdi-view-dashboard</v-icon>
                                </v-list-item-icon>

                                <v-list-item-content>
                                    <v-list-item-title><?= lang('App.dashboard') ?> App</v-list-item-title>
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
                <v-btn icon @click.stop="rightMenu = !rightMenu">
                    <v-icon>mdi-cog-outline</v-icon>
                </v-btn>
            </v-app-bar>

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
                        <?= lang('App.theme'); ?> {{themeText}}
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
                <?= $this->renderSection('content') ?>
            </v-main>

            <p class="mx-auto pt-5 text-center subtitle-2">
                <v-btn small v-for="link in links" :key="link" text rounded class="my-2" link :href="link.link">
                    {{ link.text }}
                </v-btn>
                <br />
                &copy; {{ new Date().getFullYear() }} <a href="<?= env('appWebsite'); ?>" target="_blank"><?= env('appCompany') ?></a> - <?= $appName; ?> <?= env('appVersion') ?>.<br />Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.
            </p>

            <?= $this->include('App\Views\partials/login'); ?>

            <v-snackbar v-model="snackbar" :timeout="timeout" style="bottom:40px;">
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
    <script src="<?= base_url('assets/js/vuejs-paginate.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('assets/js/vue-masonry-plugin-window.js') ?>"></script>
    <script src="<?= base_url('assets/js/anime.min.js') ?>" type="text/javascript"></script>

    <script>
        var computedVue = {
            mini: {
                get() {
                    return this.$vuetify.breakpoint.smAndDown || !this.toggleMini;
                },
                set(value) {
                    this.toggleMini = value;
                }
            },
            themeText() {
                return this.$vuetify.theme.dark ? '<?= lang("App.dark") ?>' : '<?= lang("App.light") ?>'
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
            snackbarMessage: '',
            show: false,
            rules: {
                email: v => !!(v || '').match(/@/) || '<?= lang('App.emailValid'); ?>',
                length: len => v => (v || '').length <= len || `<?= lang('App.invalidLength'); ?> ${len}`,
                password: v => !!(v || '').match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/) ||
                    '<?= lang('App.strongPassword'); ?>',
                min: v => v.length >= 8 || '<?= lang('App.minChar'); ?>',
                required: v => !!v || '<?= lang('App.isRequired'); ?>',
                number: v => Number.isInteger(Number(v)) || "<?= lang('App.isNumber'); ?>",
                zero: v => v > 0 || "<?= lang('App.isZero'); ?>",
                varchar: v => (v || '').length <= 255 || 'Maks 255 Karakter'
            },
            links: [{
                    text: '<?= lang('App.aboutUs'); ?>',
                    link: '<?= base_url('about'); ?>'
                }, {
                    text: 'Syarat & Ketentuan',
                    link: '<?= base_url('terms'); ?>'
                }, {
                    text: 'Kebijakan Privasi',
                    link: '<?= base_url('privacy'); ?>'
                },
            ],
        }
        var methodsVue = {
            toggleTheme() {
                this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
                localStorage.setItem("dark_theme", this.$vuetify.theme.dark.toString());
            },
        }
        var VueMasonryPlugin = window["vue-masonry-plugin"].VueMasonryPlugin;
        Vue.use(VueMasonryPlugin);
        Vue.component('paginate', VuejsPaginate);
    </script>
    <!-- Include script khusus dari views partials/login -->
    <?= $this->renderSection('js_auth') ?>
    <!-- Include script dari semua views -->
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
            methods: methodsVue
        })
    </script>
</body>

</html>