<?php

// Askareiden toiminnot
//Listaus
$routes->get('/', function() {
    ChoreController::index();
});

//Listaus
$routes->get('/chore', function() {
    ChoreController::index();
});

//Askareen tallentaminen
$routes->post('/chore/', function() {
    ChoreController::store();
});

//Askareen luomissivu
$routes->get('/chore/new', function(){
    ChoreController::create();
});

//Askareen tallentaminen
$routes->get('/chore/:id', function($id) {
    ChoreController::show($id);
});

//Askareen esittelysivu
$routes->post('/chore/:id', function($id){
    ChoreController::update($id);
});

//Askareen merkitseminen tehdyksi
$routes->post('/chore/:id/finish', function($id){
    ChoreController::finish($id);
});

//Askareen poistaminen
$routes->post('/chore/:id/delete', function($id){
    ChoreController::delete($id);
});

//Käyttäjän toiminnot
// Kirjautumislomakkeen esittäminen
$routes->get('/login', function(){
  AccountController::login();
});

// Kirjautumisen käsittely
$routes->post('/login', function(){
  AccountController::handle_login();
});

//Uloskirjautumisen käsittely
$routes->post('/logout', function() {
    AccountController::logout();
});

//Rekisteröitymislomakkeen haku
$routes->get('/register', function() {
    AccountController::create();
});

//Rekisteröityminen, käyttäjän luominen
$routes->post('/register', function() {
    AccountController::store();
});

//Kategorioiden toiminnot
$routes->post('/removeconnection/:pid/:cid', function($pid, $cid){
    CategoryController::remove_subcategory($pid, $cid);
});

$routes->post('/addconnection/:id', function($id){
    CategoryController::add_subcategory($id);
});

//Kategorioiden listaaminen
$routes->get('/category', function() {
    CategoryController::index();
});

//Katogerian luomissivun haku
$routes->get('/category/new', function(){
    CategoryController::create();
});

//Kategorian luonti
$routes->post('/category', function() {
    CategoryController::store();
});

//Kategorian esittelysivu
$routes->get('/category/:id', function($id) {
    CategoryController::show($id);
});

//Kategorian poisto
$routes->post('/category/:id/delete', function($id) {
    CategoryController::delete($id);
});

//Hiekkalaatikko ja suunnitelmat
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
