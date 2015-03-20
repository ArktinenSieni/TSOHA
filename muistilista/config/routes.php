<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/chores', function() {
    HelloWorldController::chores();
});

$routes->get('/chore',function() {
    HelloWorldController::showchore(); 
});

$routes->get('/class',function() {
    HelloWorldController::showclass(); 
});

$routes->get('/classes',function() {
    HelloWorldController::classes();
});

$routes->get('/account', function() {
    HelloWorldController::account();
});
