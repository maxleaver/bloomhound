/* global Vue */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('account-settings-tabs', require('./components/AccountSettingsTabs.vue'));
Vue.component('contact-list', require('./components/ContactList.vue'));
Vue.component('customer-list', require('./components/CustomerList.vue'));
Vue.component('event-arrangements', require('./components/EventArrangements.vue'));
Vue.component('event-list', require('./components/EventList.vue'));
Vue.component('event-vendor-list', require('./components/EventVendorList.vue'));
Vue.component('flower-list', require('./components/FlowerList.vue'));
Vue.component('item-list', require('./components/ItemList.vue'));
Vue.component('note-list', require('./components/NoteList.vue'));
Vue.component('variety-list', require('./components/FlowerVarietyList.vue'));
Vue.component('vendor-list', require('./components/VendorList.vue'));
Vue.component('update-password', require('./components/UpdatePassword.vue'));
Vue.component('contact-profile', require('./components/ContactProfile.vue'));
Vue.component('customer-profile', require('./components/CustomerProfile.vue'));
Vue.component('event-profile', require('./components/EventProfile.vue'));
Vue.component('item-profile', require('./components/ItemProfile.vue'));
Vue.component('update-profile', require('./components/UpdateProfile.vue'));
Vue.component('vendor-profile', require('./components/VendorProfile.vue'));

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
});
/* eslint-enable no-unused-vars */
