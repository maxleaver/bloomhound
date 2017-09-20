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
Vue.component('customer-list', require('./components/CustomerList.vue'));
Vue.component('contact-list', require('./components/ContactList.vue'));
Vue.component('event-list', require('./components/EventList.vue'));
Vue.component('flower-list', require('./components/FlowerList.vue'));
Vue.component('note-list', require('./components/NoteList.vue'));
Vue.component('vendor-list', require('./components/VendorList.vue'));
Vue.component('update-password', require('./components/UpdatePassword.vue'));
Vue.component('update-profile', require('./components/UpdateProfile.vue'));
Vue.component('account-profile', require('./components/AccountProfile.vue'));

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
});
/* eslint-enable no-unused-vars */
