<?php

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

// Home > Customers
Breadcrumbs::register('customers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Customers', route('customers.index'));
});

// Home > Customers > [Customer]
Breadcrumbs::register('customer', function ($breadcrumbs, $customer) {
    $breadcrumbs->parent('customers');
    $breadcrumbs->push($customer->name, route('customers.show', $customer->id));
});

// Home > Contacts
Breadcrumbs::register('contacts', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Contacts', route('contacts.index'));
});

// Home > Contacts > [Contact]
Breadcrumbs::register('contact', function ($breadcrumbs, $contact) {
    $breadcrumbs->parent('contacts');
    $breadcrumbs->push($contact->name, route('contacts.show', $contact->id));
});

// Home > Events
Breadcrumbs::register('events', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Events', route('events.index'));
});

// Home > Events > [Event]
Breadcrumbs::register('event', function ($breadcrumbs, $event) {
    $breadcrumbs->parent('events');
    $breadcrumbs->push($event->name, route('events.show', $event->id));
});

// Home > Vendors
Breadcrumbs::register('vendors', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Vendors', route('vendors.index'));
});

// Home > Vendors > [Vendor]
Breadcrumbs::register('vendor', function ($breadcrumbs, $vendor) {
    $breadcrumbs->parent('vendors');
    $breadcrumbs->push($vendor->name, route('vendors.show', $vendor->id));
});

// Home > Flowers
Breadcrumbs::register('flowers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Flowers', route('flowers.index'));
});

// Home > Flowers > [Flower]
Breadcrumbs::register('flower', function ($breadcrumbs, $flower) {
    $breadcrumbs->parent('flowers');
    $breadcrumbs->push($flower->name, route('flowers.show', $flower->id));
});

// Home > Items
Breadcrumbs::register('items', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Items', route('items.index'));
});

// Home > Items > [Item]
Breadcrumbs::register('item', function ($breadcrumbs, $item) {
    $breadcrumbs->parent('items');
    $breadcrumbs->push($item->name, route('items.show', $item->id));
});

// Home > My Profile
Breadcrumbs::register('profile', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('My Profile', route('my.profile'));
});

// Home > Items
Breadcrumbs::register('account_settings', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Account Settings', route('account.settings'));
});
