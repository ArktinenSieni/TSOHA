<?php

$routes->get('/', function() {
    ChoreController::index();
});

$routes->get('/chore', function() {
    ChoreController::index();
});


$routes->post('/chore/', function() {
    ChoreController::store();
});

$routes->get('/chore/new', function(){
    ChoreController::create();
});

$routes->get('/chore/:id', function($id) {
    ChoreController::show($id);
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/hiekkalaatikko/login', function() {
    HelloWorldController::login();
});

$routes->get('/hiekkalaatikko/chores', function() {
    HelloWorldController::chores();
});

$routes->get('/hiekkalaatikko/chore', function() {
    HelloWorldController::showchore();
});

$routes->get('/hiekkalaatikko/class', function() {
    HelloWorldController::showclass();
});

$routes->get('/hiekkalaatikko/classes', function() {
    HelloWorldController::classes();
});

$routes->get('/hiekkalaatikko/account', function() {
    HelloWorldController::account();
});
