<template lang="">
    <div class="tw-flex tw-h-screen tw-overflow-y-hidden">
        <!-- Sidebar -->
        <div class="tw-w-[184px] tw-overflow-y-hidden tw-flex tw-pl-4 tw-py-4 tw-border-r-[2px] tw-border-grey-90 tw-flex-col tw-gap-8 tw-max-h-screen">
            <div class="tw-flex tw-flex-col tw-gap-3 tw-items-center">
                <img src="/images/Logo Master IOH-R2-01.png" alt="Indosat logo" class="tw-w-[99.69px] tw-object-contain" />
            </div>
            <div class="tw-flex tw-flex-col">
                <Link v-for="link in (isAdmin ? adminLinks: partnerLinks)" :key="link.title" :href="link.href">
                    <div :class="clsx(['!tw-flex !flex-1 hover:!tw-flex focus:!tw-flex tw-px-3 tw-py-4 tw-gap-3 tw-items-center', { 'tw-bg-primary-main/5 tw-border-r-[3px] tw-border-primary-main': getIsActive(link.href, link.exact), 'tw-text-black-90': !getIsActive(link.href, link.exact) }])" >
                        <v-icon :class="clsx(['tw-w-5 tw-h-5', { '!tw-text-neutral-100': getIsActive(link.href, link.exact), '!tw-text-black-90': !getIsActive(link.href, link.exact) }])">{{ link.icon }}</v-icon>
                        <p class="tw-font-indosat tw-text-neutral-100 tw-text-sm">{{ link.title }}</p>
                    </div>
                </Link>
            </div>
        </div>
        <div class="tw-flex-1 tw-flex tw-flex-col tw-max-h-screen">
            <!-- Header -->
            <header class="tw-h-17 tw-flex tw-items-center tw-border-b-[2px] tw-border-grey-90 tw-justify-between tw-px-12">
                <h2 class="tw-font-indosat-bold tw-text-neutral-100">Dashboard</h2>
                <div class="tw-relative">
                    <button @click="toggleDropdown" class="tw-flex tw-flex-col text-xs">
                        <h3 class="tw-font-indosat-bold tw-text-neutral-100">{{ user }}</h3>
                        <p v-if="role == 7" class="tw-text-neutral-90/75 tw-font-indosat">Admin</p>
                        <p v-else-if="role == 6" class="tw-text-neutral-90/75 tw-font-indosat">User</p>
                    </button>
                    <div v-if="isOpen" class="tw-absolute tw-top-0 tw-mt-14 tw-min-w-[160px] tw-right-0 tw-shadow-md tw-bg-white tw-font-indosat tw-text-neutral-80 tw-text-sm tw-rounded-lg tw-overflow-auto tw-max-h-40">
                        <button @click="signOut()" class="hover:tw-bg-neutral-30 tw-px-4 tw-py-2 tw-w-full">
                            <p>Logout</p>
                        </button>
                    </div>
                </div>
            </header>
            <!-- Main -->
            <main class="tw-px-7 tw-flex tw-flex-col tw-gap-2 tw-overflow-auto tw-flex-1">
                <div class="tw-py-2">
                    <a href="/" class="tw-text-xs tw-text-black-90">Dashboard</a>
                </div>
                <div>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
<script>
    import { Link } from '@inertiajs/inertia-vue'
    import clsx from 'clsx';
    
    export default {
        props: {
            isAdmin: {
                type: Boolean,
                required: false,
            },
        },
        data: () => ({
            partnerLinks: [
                { title: 'Dashboard', icon: 'mdi-view-dashboard-outline', href: '/dashboard', exact: true },
                { title: 'Product', icon: 'mdi-package-variant-closed', href: '/service' },
                { title: 'Inject Tools', icon: 'mdi-clipboard-text-clock-outline', href: '/inject' },
            ],
            adminLinks: [
                { title: 'Dashboard', icon: 'mdi-view-dashboard-outline', href: '/', exact: true },
            ],
            isOpen: false
        }),
        components: {
            Link,
        },
        computed: {
            user() {
                return this.$page.props.email
            },
            role() {
                return this.$page.props.role
            }
        },
        methods: {
            clsx,
            getIsActive(url, exact = false){
                return exact ? this.$page.url === url || this.$page.url === url + '/' : this.$page.url.includes(url)
            },
            signOut() {
                this.$inertia.get("/signout");
            },
            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    document.addEventListener('click', this.closeDropdown);
                } else {
                    document.removeEventListener('click', this.closeDropdown);
                }
            },
            closeDropdown(event) {
                if (!this.$el.contains(event.target)) {
                    this.isOpen = false;
                    document.removeEventListener('click', this.closeDropdown);
                }
            },
        },
        mounted(){
            console.log(this.$page.props)
        }
    }
</script>
<style >
    /* For Webkit-based browsers (Chrome, Safari and Opera) */
    body::-webkit-scrollbar {
        display: none;
    }

    /* For IE, Edge and Firefox */
    body {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>