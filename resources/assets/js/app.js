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

Vue.component('flash', require('components/Flash.vue'));
Vue.component('account-settings-tabs', require('components/Account/AccountSettingsTabs.vue'));
Vue.component('contact-list', require('components/Contacts/ContactList.vue'));
Vue.component('contact-profile', require('components/Contacts/ContactProfile.vue'));
Vue.component('customer-list', require('components/Customers/CustomerList.vue'));
Vue.component('customer-profile', require('components/Customers/CustomerProfile.vue'));
Vue.component('event-list', require('components/Events/EventList.vue'));
Vue.component('event-profile', require('components/Events/EventProfile.vue'));
Vue.component('flower-list', require('components/Flowers/FlowerList.vue'));
Vue.component('variety-list', require('components/Flowers/FlowerVarietyList.vue'));
Vue.component('item-list', require('components/Items/ItemList.vue'));
Vue.component('item-profile', require('components/Items/ItemProfile.vue'));
Vue.component('note-list', require('components/Notes/NoteList.vue'));
Vue.component('update-password', require('components/Profile/UpdatePassword.vue'));
Vue.component('update-profile', require('components/Profile/UpdateProfile.vue'));
Vue.component('vendor-list', require('components/Vendors/VendorList.vue'));
Vue.component('vendor-profile', require('components/Vendors/VendorProfile.vue'));

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
});
/* eslint-enable no-unused-vars */
