<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   return redirect('login');
});

Auth::routes();

Route::middleware(['auth', 'check_access'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('departments', 'DepartmentController');
    Route::resource('leave', 'LeaveController');
    Route::get('leaves_request/list', 'LeaveController@requestList')->name('leave.leavelist');
    Route::get('leaves_request/layout', 'LeaveController@requestLeave')->name('leave.leavepost');
    Route::get('leaves/{leaf}/edit', 'LeaveController@requestEdit')->name('leave.leaveedit');
    Route::post('leaves_request/leaverequest', 'LeaveController@requestPost')->name('leave.leaverequest');
    Route::patch('leaves_request/layout/{leaf}', 'LeaveController@requestUpdate')->name('leave.leaveupdate');
    Route::delete('leaves_request/{leaf}', 'LeaveController@requestDestroy')->name('leave.leavedestroy');
    Route::get('leaves_request/layout/{leaf}/edit', 'LeaveController@requestEdit')->name('leave.leaveedit');
    Route::any('leave/create', 'LeaveController@create')->name('leave.leavecreate');
    Route::get('leave/filter/search', 'LeaveController@search')->name('leave.leavesearch');
    Route::get('leaves/filter/result','LeaveController@resultlist');
    Route::get('leaves_request/search','LeaveController@search_filter')->name('leave.leaves_search_filter');
    Route::any('leave-list', 'LeaveController@leave_list')->name('leave.leave_list');
    Route::get('leave-list/edit/{id}', 'LeaveController@leave_edit')->name('leave.leave_edit');

    Route::resource('sss', 'SocialSecurityController');
    Route::resource('tax', 'TaxController');
    Route::resource('philhealth', 'PhilhealthController');
    Route::resource('pagibig', 'PagibigController');

    Route::get('pagibigs/pagibig_list', 'PagibigController@pagibig_list');

    Route::get('employees/search', 'EmployeeController@employeeSearchView');
    Route::get('employees/searchEmp', 'EmployeeController@empSearch')->name('employee.empSearch');
    Route::resource('employees', 'EmployeeController');
    Route::any('generate_paystub/{emp_id}', 'EmployeeController@generatePDF');
    Route::any('generate_payroll/{emp_id}', 'EmployeeController@generatePDF1');


    Route::resource('attendance', 'AttendanceController');

    Route::get('roles/department/{id}',[
        'uses' => 'RoleController@edit_department',
        'as' => 'role.edit_department'
    ]);

    Route::get('roles/filter/department',[
        'uses' => 'RoleController@edit_department_filter',
        'as' => 'role.edit_department_filter'
    ]);


    Route::resource('roles', 'RoleController');
    Route::post('rolesenable', 'RoleController@enable')->name('role.enable');
    Route::post('modulepermission', 'RoleController@module_permission_store')->name('role.modulepermission');

    Route::resource('users', 'UserController');
    Route::post('user/isenable', 'UserController@isenable')->name('user.isenable');

    Route::resource('employment-status', 'EmploymentStatusController');
    // Route::post('employees/update', 'EmployeeController@update')->name('employees.update');
    // Route::get('employees/destroy/{id}', 'EmployeeController@destroy');
    Route::any('employees_list_ajax','EmployeeController@emp_list');

    Route::any('myprofile','UserController@settings');
    Route::any('user/change_password','UserController@change_password');
    Route::any('user/update_email','UserController@update_email');
    Route::any('user/update_username','UserController@update_username');
    Route::any('user/update_profile','UserController@update_profile');

    Route::get('dailytime',[
        'uses' => 'AttendanceController@dailyTime',
        'as' => 'dailytime'
    ]);

    Route::post('proccessDTR',[
        'uses' => 'AttendanceController@processDTR',
        'as' => 'proccessdtr'
    ]);

    Route::post('dtr/timein',[
        'uses' => 'AttendanceController@timeIn',
        'as' => 'proccessTimein'
    ]);

    Route::post('dtr/timeout',[
        'uses' => 'AttendanceController@timeOut',
        'as' => 'proccessTimeout'
    ]);

    Route::post('dtr/uploadcsv',[
        'uses' => 'AttendanceController@uploadCsv',
        'as' => 'uploadcsv'
    ]);

    Route::resource('payroll','PayrollController');
    Route::resource('holidays', 'HolidayController');

    Route::group(['prefix'=>'payroll'], function(){
        // Route::post('/generatess','PayrollController@generate')->name('payroll.generate');
        Route::get('/summary/{billing_number}/{employee_id}','PayrollController@summary')->name('payroll.summary');
        Route::get('/filter/search/query','PayrollController@search')->name('payroll.search');
        Route::any('/filter/search/input','PayrollController@search_filter')->name('payroll.search_filter');
        Route::any('/filter/generate_pdf/{payroll_id}','PayrollController@generate_pdf')->name('payroll.generate_pdf');
        Route::any('/filter/search_view_billing_number/{payroll_id}','PayrollController@search_view_billing_number')->name('payroll.search_view_billing_number');
        Route::get('/details/{payroll_id}','PayrollController@generate_pdf');
    });
    Route::any('bulk-payroll','PayrollController@index')->name('payroll.index');
    Route::any('bulk-payroll/generate', 'PayrollController@generate')->name('payroll.generate');
	Route::get('/payroll_settings','PayrollController@payroll_settings')->name('payroll.payroll_settings');
	Route::any('get_sss_table', 'PayrollController@get_sss_table')->name('payroll.get_sss_table');
	Route::any('get_tax_table', 'PayrollController@get_tax_table')->name('payroll.get_tax_table');
	Route::any('get_philhealth_table', 'PayrollController@get_philhealth_table')->name('payroll.get_philhealth_table');

    Route::get('attendances/search','AttendanceController@searchIndex')->name('search.index');

    Route::get('payrollledger/preview-pdf/create/', 'PayrollLedgerController@preview_pdf')->name('payroll.preview_pdf');

    Route::resource('payrollledger', 'PayrollLedgerController');
    Route::post('/payrollledger/generate', 'PayrollLedgerController@generate');//->name('payrollledger.generate');
    Route::resource('overtime', 'OvertimeRequestController');
	Route::get('overtime/filter/search','OvertimeRequestController@search')->name('overtime_request.search');
	Route::get('overtime_requests/search','OvertimeRequestController@search_filter')->name('overtime_requests.search_filter');

    Route::resource('company', 'CompanyController');
	
    Route::group(['prefix'=>'report'], function(){
        Route::resource('dtr', 'DailyTimeRecordController');
        
        Route::get('/attendance',
            [
                'uses'  => 'DailyTimeRecordController@history',
                'as'    => 'dtr.history'
            ]
        );
		Route::get('/payrolls',
            [
                'uses'  => 'DailyTimeRecordController@payrolls',
                'as'    => 'dtr.payrolls'
            ]
        );	
		Route::get('/get_payrolls',
            [
                'uses'  => 'DailyTimeRecordController@get_payrolls',
                'as'    => 'dtr.get_payrolls'
            ]
        );
        Route::get('/get_history',
            [
                'uses'  => 'DailyTimeRecordController@get_history',
                'as'    => 'dtr.get_history'
            ]
        );
        Route::get('/search-filter',
            [
                'uses'  => 'DailyTimeRecordController@filter',
                'as'    => 'dtr.filter'
            ]
        );
        Route::any('/filter_list',
            [
                'uses'  => 'DailyTimeRecordController@filter_list',
                'as'    => 'dtr.filter_list'
            ]
        );

        Route::get('/tardiness',
            [
                'uses'  => 'DailyTimeRecordController@tardiness',
                'as'    => 'dtr.tardiness'
            ]
        );
        Route::get('/absences',
            [
                'uses'  => 'DailyTimeRecordController@absences',
                'as'    => 'dtr.absences'
            ]
        );
        Route::any('/absence_list',
            [
                'uses'  => 'DailyTimeRecordController@absence_list',
                'as'    => 'dtr.absence_list'
            ]
        );
        Route::get('/excel_tardiness',[
                'uses'  => 'DailyTimeRecordController@excel_tardiness',
                'as'    => 'dtr.excel_tardiness'
        ]);
        Route::any('/tardiness_list',
            [
                'uses'  => 'DailyTimeRecordController@tardiness_list',
                'as'    => 'dtr.tardiness_list'
            ]
        );
        Route::get('/download_filter_export',[
                'uses'  => 'DailyTimeRecordController@download_filter_export',
                'as'    => 'dtr.download_filter_export'
        ]);
        Route::get('/download_absence_excel',[
                'uses'  => 'DailyTimeRecordController@download_absence_excel',
                'as'    => 'dtr.download_absence_excel'
        ]);

        Route::get('/download_history_report',[
            'uses'  => 'DailyTimeRecordController@download_history_report',
            'as'    => 'dtr.download_history_report'
        ]);
		Route::get('/download_payroll_report',[
            'uses'  => 'DailyTimeRecordController@download_payroll_report',
            'as'    => 'dtr.download_payroll_report'
        ]);

    });
    
	Route::any('report',"DailyTimeRecordController@reports")->name("dtr.reports");
    Route::get('attendance/search/q','AttendanceController@searchIndex')->name('attendance.search');
    Route::any('role_list_ajax','RoleController@role_list');

    Route::get('/payrollledger/getDepartment/{id}','PayrollLedgerController@getDepartment');

    Route::get('user/search_filter','UserController@search_filter')->name('user.search_filter');
    Route::post('user/create_new_password','UserController@create_new_password')->name('user.create_new_password');
    Route::post('user/{id}/deactivate_user','UserController@deactivate_user')->name('user.deactivate_user');
    Route::post('user/{id}/activate_user','UserController@activate_user')->name('user.activate_user');

    Route::get('payrollledger/preview','PayrollLedgerController@generate')->name('payrollledger.preview');

    Route::get('holiday/search_filter','HolidayController@search_filter')->name('holiday.search_filter');

    Route::get('payroll/preview/{payroll_number}', 'PayrollLedgerController@edit');
    Route::get('payroll/print/{payroll_number}', 'PayrollController@search_view_billing_number');

    Route::resource('banks', 'BankController');
    Route::any('banks_list_ajax','BankController@bank_list');
    Route::any('settings','BankController@settings')->name('banks.settings');
    Route::any('update_bank_settings','BankController@update_settings')->name('banks.update_settings');
    Route::get('bank/searchBank', 'BankController@bankSearch')->name('banks.bankSearch');


    /* ASSET */
    Route::group(['prefix'=>'asset'], function(){
        Route::resource('AssetLocation', 'AssetLocationController');
        Route::resource('AssetSupplier', 'AssetSupplierController');
        Route::resource('AssetCategory', 'AssetCategoryController');
        Route::resource('AssetScannedBarcode', 'AssetScannedBarcodeController');

        // ASSET CATEGORY
        Route::group(['prefix'=>'category'], function(){
            Route::get('/',
                [
                    'uses'  => 'AssetCategoryController@index',
                    'as'    => 'asset_category.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetCategoryController@create',
                    'as'    => 'asset_category.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetCategoryController@search_filter',
                    'as'    => 'asset_category.search_filter'
                ]
            );
            Route::get('/{id}/edit',
                [
                    'uses'  => 'AssetCategoryController@edit',
                    'as'    => 'asset_category.edit'
                ]
            );
            Route::delete('/{id}/destroy',
                [
                    'uses'  => 'AssetCategoryController@destroy',
                    'as'    => 'asset_category.destroy'
                ]
            );
            Route::post('/store',
                [
                    'uses'  => 'AssetCategoryController@store',
                    'as'    => 'asset_category.store'
                ]
            );
            Route::put('/{id}/update',
                [
                    'uses'  => 'AssetCategoryController@update',
                    'as'    => 'asset_category.update'
                ]
            );
        });

        // ASSET SUPPLIER
        Route::group(['prefix'=>'supplier'], function(){
            Route::get('/',
                [
                    'uses'  => 'AssetSupplierController@index',
                    'as'    => 'asset_supplier.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetSupplierController@create',
                    'as'    => 'asset_supplier.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetSupplierController@search_filter',
                    'as'    => 'asset_supplier.search_filter'
                ]
            );
            Route::get('/{id}/edit',
                [
                    'uses'  => 'AssetSupplierController@edit',
                    'as'    => 'asset_supplier.edit'
                ]
            );
            Route::delete('/{id}/destroy',
                [
                    'uses'  => 'AssetSupplierController@destroy',
                    'as'    => 'asset_supplier.destroy'
                ]
            );
            Route::post('/store',
                [
                    'uses'  => 'AssetSupplierController@store',
                    'as'    => 'asset_supplier.store'
                ]
            );
            Route::put('/{id}/update',
                [
                    'uses'  => 'AssetSupplierController@update',
                    'as'    => 'asset_supplier.update'
                ]
            );
        });
        
        //ASSET LOCATION
        Route::group(['prefix'=>'location'], function(){
            Route::get('/',
                [
                    'uses'  => 'AssetLocationController@index',
                    'as'    => 'asset_location.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetLocationController@create',
                    'as'    => 'asset_location.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetLocationController@search_filter',
                    'as'    => 'asset_location.search_filter'
                ]
            );
            Route::get('/{id}/edit',
                [
                    'uses'  => 'AssetLocationController@edit',
                    'as'    => 'asset_location.edit'
                ]
            );
            Route::delete('/{id}/destroy',
                [
                    'uses'  => 'AssetLocationController@destroy',
                    'as'    => 'asset_location.destroy'
                ]
            );
            Route::post('/store',
                [
                    'uses'  => 'AssetLocationController@store',
                    'as'    => 'asset_location.store'
                ]
            );
            Route::put('/{id}/update',
                [
                    'uses'  => 'AssetLocationController@update',
                    'as'    => 'asset_location.update'
                ]
            );
        });

        //ASSET SCANNED BARCODE
        Route::group(['prefix'=>'scanned-barcode'], function(){
            Route::get('/',
                [
                    'uses'  => 'AssetScannedBarcodeController@index',
                    'as'    => 'asset_scanned_barcode.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetScannedBarcodeController@create',
                    'as'    => 'asset_scanned_barcode.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetScannedBarcodeController@search_filter',
                    'as'    => 'asset_scanned_barcode.search_filter'
                ]
            );
            Route::get('/{id}/edit',
                [
                    'uses'  => 'AssetScannedBarcodeController@edit',
                    'as'    => 'asset_scanned_barcode.edit'
                ]
            );
            Route::delete('/{id}/destroy',
                [
                    'uses'  => 'AssetScannedBarcodeController@destroy',
                    'as'    => 'asset_scanned_barcode.destroy'
                ]
            );
            Route::post('/store',
                [
                    'uses'  => 'AssetScannedBarcodeController@store',
                    'as'    => 'asset_scanned_barcode.store'
                ]
            );
            Route::put('/{id}/update',
                [
                    'uses'  => 'AssetScannedBarcodeController@update',
                    'as'    => 'asset_scanned_barcode.update'
                ]
            );
            Route::get('/barcode-entry',
                [
                    'uses'  => 'AssetScannedBarcodeController@barcode_entry',
                    'as'    => 'asset_scanned_barcode.barcode_entry'
                ]
            );
        });

        //ASSET INVENTORY
        Route::group(['prefix'=>'inventory'], function(){
            Route::resource('Asset', 'AssetController');
            Route::get('/',
                [
                    'uses'  => 'AssetController@index',
                    'as'    => 'asset_inventory.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetController@create',
                    'as'    => 'asset_inventory.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetController@search_filter',
                    'as'    => 'asset_inventory.search_filter'
                ]
            );
            Route::get('/{slug_token}/edit',
                [
                    'uses'  => 'AssetController@edit',
                    'as'    => 'asset_inventory.edit'
                ]
            );
            Route::delete('/{slug_token}/destroy',
                [
                    'uses'  => 'AssetController@destroy',
                    'as'    => 'asset_inventory.destroy'
                ]
            );
            Route::post('/store',
                [
                    'uses'  => 'AssetController@store',
                    'as'    => 'asset_inventory.store'
                ]
            );
            Route::put('/{slug_token}/update',
                [
                    'uses'  => 'AssetController@update',
                    'as'    => 'asset_inventory.update'
                ]
            );
        });
        
        //ASSET SCANNED BARCODE UPLOAD
        Route::group(['prefix'=>'scanned-barcode-upload'], function(){
            Route::resource('AssetScannedBarcodeUpload', 'AssetScannedBarcodeUploadController');
            Route::get('/',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@index',
                    'as'    => 'asset_scanned_barcode_upload.index'
                ]
            );
            Route::get('/create',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@create',
                    'as'    => 'asset_scanned_barcode_upload.create'
                ]
            );
            Route::get('/search_filter',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@search_filter',
                    'as'    => 'asset_scanned_barcode_upload.search_filter'
                ]
            );
            Route::get('/{slug_token}/edit',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@edit',

                    'as'    => 'asset_scanned_barcode_upload.edit'
                ]
            );

            Route::delete('/{slug_token}/destroy',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@destroy',

                    'as'    => 'asset_scanned_barcode_upload.destroy'
                ]
            );

            Route::post('/store',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@store',
                    'as'    => 'asset_scanned_barcode_upload.store'
                ]
            );
            Route::put('/{slug_token}/update',
                [
                    'uses'  => 'AssetScannedBarcodeUploadController@update',
                    'as'    => 'asset_scanned_barcode_upload.update'
                ]
            );
        });
        Route::group(['prefix'=>'print-custom-barcode'], function(){
            Route::resource('AssetPrintCustomBarcode', 'AssetPrintCustomBarcodeController');
            Route::get('/',
                [
                    'uses'  => 'AssetPrintCustomBarcodeController@index',
                    'as'    => 'asset_print_custom_barcode.index'
                ]
            );
            Route::get('/print_preview_custom_barcode/{property_number}/{layout_type}',
            [
                'uses'  => 'AssetPrintCustomBarcodeController@print_preview_custom_barcode',
                'as'    => 'asset_print_custom_barcode.print_preview_custom_barcode'
            ]
        );
        });
    });
    //ASSET REPORT 
    Route::group(['prefix'=>'report'], function(){
        Route::get('/inventory',
        [
            'uses'  => 'AssetController@report_index',
            'as'    => 'asset_report.index'
        ]
        );
        Route::get('/inventory_report',
        [
            'uses'  => 'AssetController@inventory_report',
            'as'    => 'asset_report.inventory'
        ]
        );
    });

});


Route::middleware('auth')->group(function () {
    Route::get('employees/card/list',function(){
        return view('employees.list');
    })->name('emp.list');
    Route::get('attendance/card/list','AttendanceController@indexCard')->name('att.list');

});
Route::get('report/comming_soon','DailyTimeRecordController@comming_soon')->name('report.comming_soon');
Route::resource('module', 'ModulesController');
Route::resource('page-role', 'PageRoleController');
Route::resource('module_table', 'ModulesTableController');
Route::resource('module_permission', 'ModulePermissionController');