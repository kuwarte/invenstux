<?php

// auth
$router->get('/login', 'AuthController@indexLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

// test
$router->get('/test-accounts', 'TestAccountsController@index');

// dashboard
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/top-revenue', 'DashboardController@indexTopRevenue');
$router->get('/dashboard/filter', 'DashboardController@filter');

// category
$router->get('/categories', 'CategoryController@index');
$router->get('/categories/filter', 'CategoryController@filter');
$router->get('/categories/create', 'CategoryController@indexCreate');
$router->post('/categories/create', 'CategoryController@create');
$router->post('/categories/update', 'CategoryController@update');
$router->post('/categories/delete', 'CategoryController@delete');

// product
$router->get('/products', 'ProductController@index');
$router->get('/products/filter', 'ProductController@filter');
$router->get('/products/create', 'ProductController@indexCreate');
$router->post('/products/create', 'ProductController@create');
$router->get('/products/update', 'ProductController@indexUpdate');
$router->post('/products/update', 'ProductController@update'); 
$router->post('/products/delete', 'ProductController@delete'); 

// warehouse
$router->get('/warehouses', 'WarehouseController@index');
$router->get('/warehouses/filter', 'WarehouseController@filter');
$router->get('/warehouses/create', 'WarehouseController@indexCreate');
$router->post('/warehouses/create', 'WarehouseController@create');
$router->get('/warehouses/update', 'WarehouseController@indexUpdate');
$router->post('/warehouses/update', 'WarehouseController@update');
$router->post('/warehouses/delete', 'WarehouseController@delete');

// stock
$router->get('/stocks', 'StockController@index');
$router->get('/stocks/filter', 'StockController@filter');
$router->post('/stocks/in', 'StockController@stockIn');
$router->post('/stocks/out', 'StockController@stockOut');
$router->post('/stocks/transfer', 'StockController@transfer');
$router->get('/stocks/products-in-warehouse', 'StockController@productsInWarehouse');
$router->get('/stocks/thresholds', 'StockController@indexThresholds');
$router->post('/stocks/thresholds/update', 'StockController@updateThresholds');

// user
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@indexCreate');
$router->post('/users/create', 'UserController@create');
$router->get('/users/update', 'UserController@indexUpdate');
$router->post('/users/update', 'UserController@update');
$router->post('/users/toggle-status', 'UserController@toggleStatus');
$router->post('/users/delete', 'UserController@delete');
$router->get('/users/settings', 'UserController@indexSettings');
$router->post('/users/change-password', 'UserController@changePassword');

// pos
$router->get('/pos', 'POSController@index');
$router->post('/pos/checkout', 'POSController@checkout');
$router->get('/pos/get-products-by-warehouse', 'POSController@getProductsByWarehouse');
$router->get('/pos/search', 'POSController@searchProducts');
$router->get('/pos/check-stock', 'POSController@checkStock');

// sales
$router->get('/sales', 'SalesController@index');
$router->get('/sales/filter', 'SalesController@filter');
$router->get('/sales/view', 'SalesController@indexView');
$router->get('/sales/receipt', 'SalesController@indexSalesReceipt');

// audit
$router->get('/audit', 'AuditController@index');
