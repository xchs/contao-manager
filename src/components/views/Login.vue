<template>
    <boxed-layout slotClass="view-login">
        <header class="view-login__header">
            <img src="../../assets/images/logo.svg" width="80" height="80" alt="Contao Logo" />
            <p class="view-login__product">Contao Manager</p>
        </header>
        <main class="view-login__form">
            <h1 class="view-login__headline">{{ 'ui.login.headline' | translate }}</h1>
            <p class="view-login__description">{{ 'ui.login.description' | translate }}</p>

            <text-field ref="username" name="username" :label="'ui.login.username' | translate" :placeholder="'ui.login.username' | translate" :class="login_failed ? 'widget--error' : ''" :disabled="logging_in" v-model="username" @enter="login" @input="reset"></text-field>
            <text-field type="password" name="password" :label="'ui.login.password' | translate" :placeholder="'ui.login.password' | translate" :class="login_failed ? 'widget--error' : ''" :disabled="logging_in" v-model="password" @enter="login" @input="reset"></text-field>

            <a href="https://github.com/contao/contao-manager/issues/14" target="_blank" class="view-login__link">{{ 'ui.login.forgotPassword' | translate }}</a>

            <button class="view-login__button widget-button widget-button--primary" @click="login" :disabled="!inputValid || logging_in || login_failed">
                <span v-if="!logging_in">{{ 'ui.login.button' | translate }}</span>
                <loader v-else></loader>
            </button>
        </main>
    </boxed-layout>
</template>

<script>
    import views from '../../router/views';

    import BoxedLayout from '../layouts/Boxed';
    import TextField from '../widgets/TextField';
    import Loader from '../fragments/Loader';

    export default {
        components: { BoxedLayout, TextField, Loader },

        data: () => ({
            username: '',
            password: '',

            logging_in: false,
            login_failed: false,
        }),

        computed: {
            inputValid() {
                return this.username !== '' && this.password !== '' && this.password.length >= 8;
            },
        },

        methods: {
            login() {
                if (!this.inputValid) {
                    return;
                }

                this.logging_in = true;

                this.$store.dispatch('auth/login', {
                    username: this.username,
                    password: this.password,
                }).then((success) => {
                    if (success) {
                        this.$store.commit('setView', views.BOOT);
                    } else {
                        this.logging_in = false;
                        this.login_failed = true;
                    }
                });
            },
            reset() {
                this.login_failed = false;
            },
        },

        mounted() {
            this.$refs.username.focus();
        },
    };
</script>

<style rel="stylesheet/scss" lang="scss">
    @import "../../assets/styles/defaults";

    .view-login {
        &__header {
            max-width: 280px;
            margin: 0 auto 60px;
            padding-top: 40px;
            text-align: center;
        }

        &__product {
            margin-top: 15px;
            font-size: 36px;
            font-weight: $font-weight-light;
            line-height: 1;
        }

        &__form {
            position: relative;
            max-width: 250px;
            margin: 0 auto 80px;

            input,
            select {
                margin: 5px 0 10px;
            }
        }

        &__headline {
            margin-bottom: 0;
        }

        &__description {
            margin-top: .5em;
            margin-bottom: 30px;
        }

        label {
            position: absolute;
            text-indent: -999em;

            &[for=ctrl_username] {
                top: 17px;
                right: 13px;
                width: 16px;
                height: 16px;
                background: url("../../assets/images/person.svg") left top no-repeat;
                background-size: 16px 16px;
                z-index: 10;
            }

            &[for=ctrl_password] {
                top: 17px;
                right: 12px;
                width: 16px;
                height: 16px;
                background: url("../../assets/images/lock.svg") left top no-repeat;
                background-size: 14px 14px;
                z-index: 10;
            }
        }

        &__link {
            display: block;
            font-size: 12px;
        }

        &__button {
            margin-top: 20px;

            .sk-circle {
                color: #fff;
                text-align: center;
            }
        }
    }
</style>
