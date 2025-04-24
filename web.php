<?php
use App\Http\Controllers\Homecontroller;
use App\Http\Controllers\categorycontroller;
use App\Http\Controllers\subcategorycontroller;
use App\Http\Controllers\brandcontroller;
use App\Http\Controllers\discountcontroller;
use App\Http\Controllers\retailcontroller;
use App\Http\Controllers\groupcontroller;
use App\Http\Controllers\ledgercontroller;
use App\Http\Controllers\purchasecontroller;
use App\Http\Controllers\taxcontroller;
use App\Http\Controllers\Daybookcontroller;
use App\Http\Controllers\Itemcontroller;
use App\Http\Controllers\Reports\GeneralLedgerController;
use App\Http\Controllers\salecontroller;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Vouchercontroller;
use App\Http\Controllers\unitcontroller;
use App\Http\Controllers\subchildcategorycontroller;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\adjustcontroller;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Paymentcontroller;
use App\Http\Controllers\EstimatesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\supcategorycontroller;
use App\Http\Controllers\supsubcategorycontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\login;
use App\Http\Middleware\isnotlogin;
use Illuminate\Support\Facades\Artisan;
use Picqer\Barcode\BarcodeGeneratorPNG;
//optimize All facade value:
//optimize All facade value:
//optimize All facade value:
Route::get('/op-cache', function() {
$exitCode = Artisan::call('optimize:clear');
return '<h1>Cache facade value cleared</h1>';
});
//optimize All facade value:
//optimize All facade value:
//optimize All facade value:
Route::get('barcode', function () {
$generatorPNG = new BarcodeGeneratorPNG();
$image = $generatorPNG->getBarcode('3232232323', $generatorPNG::TYPE_CODE_128);
return response($image)->header('Content-type', 'image/png');
});
Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('generate-pdf');
Route::get('/', [Homecontroller::class, 'index'])->middleware(login::class);
Route::post('/login', [Homecontroller::class, 'login'])->name('login');
Route::any('/forgot', [Homecontroller::class, 'forgot'])->name('forgot');
Route::any('/otp', [Homecontroller::class, 'otp'])->name('otp');
Route::any('/setpassword', [Homecontroller::class, 'setpassword'])->name('setpassword');
Route::post('/reqretailer', [Homecontroller::class, 'reqretailer'])->name('reqretailer');
Route::any('/getgstcode', [Homecontroller::class, 'getgstcode'])->name('getgstcode');
Route::any('/generatecode', [Homecontroller::class, 'generatecode'])->name('generatecode');
// Route::any('/generatecode', [Homecontroller::class, 'generatecode'])->name('generatecode');
Route::any('/getbarcode', [Homecontroller::class, 'getbarcode'])->name('getbarcode');




Route::any('/changesession', [Homecontroller::class, 'changesession'])->name('changesession');
Route::any('/recheckemail', [Homecontroller::class, 'recheckemail'])->name('recheckemail');
Route::any('/recheckusername', [Homecontroller::class, 'recheckusername'])->name('recheckusername');
Route::any('/echeckemail', [Homecontroller::class, 'echeckemail'])->name('echeckemail');
Route::any('/echeckusername', [Homecontroller::class, 'echeckusername'])->name('echeckusername');
Route::any('/acheckemail', [Homecontroller::class, 'acheckemail'])->name('acheckemail');
Route::any('/acheckusername', [Homecontroller::class, 'acheckusername'])->name('acheckusername');


Route::middleware([isnotlogin::class])->group(function () {
Route::any('/dashboard', [Homecontroller::class, 'dash'])->name('dash');
// Route::any('/profile', [Homecontroller::class, 'profile'])->name('profile');
// Route::any('/dashboard', [Homecontroller::class, 'dashboard'])->name('dashboard');
Route::any('/purchaseorder', [brandcontroller::class, 'purchaseorder'])->name('purchaseorder');
Route::any('/purchaseorder/table', [brandcontroller::class, 'ptable'])->name('purchaseorder.table');
Route::any('/brandlist', [brandcontroller::class, 'brandlist'])->name('brandlist');


Route::any('/addbrand', [brandcontroller::class, 'addbrand'])->name('addbrand');
Route::any('/editbrand/{id}', [brandcontroller::class, 'editbrand'])->name('editbrand');
Route::any('/deletebrand/{id}', [brandcontroller::class, 'deletebrand'])->name('deletebrand');


Route::any('/brandlist/table', [brandcontroller::class, 'table'])->name('brandlist.table');
Route::any('/unitlist', [unitcontroller::class, 'unitlist'])->name('unitlist');
Route::any('/addunit', [unitcontroller::class, 'addunit'])->name('addunit');
Route::any('/editunit/{id}', [unitcontroller::class, 'editunit'])->name('editunit');
Route::any('/deleteunit/{id}', [unitcontroller::class, 'deleteunit'])->name('deleteunit');
Route::any('/unitlist/table', [unitcontroller::class, 'table'])->name('unitlist.table');
Route::any('/categorylist', [categorycontroller::class, 'categorylist'])->name('categorylist');


Route::any('/addcategory', [categorycontroller::class, 'addcategory'])->name('addcategory');
Route::any('/editcategory/{id}', [categorycontroller::class, 'editcategory'])->name('editcategory');
Route::any('/deletecategory/{id}', [categorycontroller::class, 'deletecategory'])->name('deletecategory');



Route::any('/categorylist/table', [categorycontroller::class, 'table'])->name('categorylist.table');
Route::any('/subcategorylist', [subcategorycontroller::class, 'subcategorylist'])->name('subcategorylist');
Route::any('/addsubcategory', [subcategorycontroller::class, 'addsubcategory'])->name('addsubcategory');
Route::any('/editsubcategory/{id}', [subcategorycontroller::class, 'editsubcategory'])->name('editsubcategory');
Route::any('/deletesubcategory/{id}', [subcategorycontroller::class, 'deletesubcategory'])->name('deletesubcategory');
Route::any('/subcategorylist/table', [subcategorycontroller::class, 'table'])->name('subcategorylist.table');
Route::any('/subchildcategorylist', [subchildcategorycontroller::class, 'subchildcategorylist'])->name('subchildcategorylist');
Route::any('/addsubchildcategory', [subchildcategorycontroller::class, 'addsubchildcategory'])->name('addsubchildcategory');
Route::any('/editsubchildcategory/{id}', [subchildcategorycontroller::class, 'editsubchildcategory'])->name('editsubchildcategory');
Route::any('/deletesubchildcategory/{id}', [subchildcategorycontroller::class, 'deletesubchildcategory'])->name('deletesubchildcategory');
Route::any('/subchildcategorylist/table', [subchildcategorycontroller::class, 'table'])->name('subchildcategorylist.table');
Route::any('/taxlist', [taxcontroller::class, 'taxlist'])->name('taxlist');
Route::any('/addtax', [taxcontroller::class, 'addtax'])->name('addtax');
Route::any('/edittax/{id}', [taxcontroller::class, 'edittax'])->name('edittax');
Route::any('/deletetax/{id}', [taxcontroller::class, 'deletetax'])->name('deletetax');
Route::any('/taxlist/table', [taxcontroller::class, 'table'])->name('taxlist.table');
Route::any('/taxlist/getgstcode', [taxcontroller::class, 'getgstcode'])->name('taxlist.getgstcode');
Route::any('/taxlist/ledger', [taxcontroller::class, 'ledger'])->name('taxlist.ledger');
Route::any('/rediscount', [discountcontroller::class, 'rediscount'])->name('rediscount');
Route::any('/itemsdata', [discountcontroller::class, 'itemsdata'])->name('rediscount.itemsdata');
Route::any('/submitrediscount', [discountcontroller::class, 'submitrediscount'])->name('submitrediscount');
Route::any('/discountlist', [discountcontroller::class, 'discountlist'])->name('discountlist');
Route::any('/adddiscount', [discountcontroller::class, 'adddiscount'])->name('adddiscount');
Route::any('/editdiscount/{id}', [discountcontroller::class, 'editdiscount'])->name('editdiscount');
Route::any('/deletediscount/{id}', [discountcontroller::class, 'deletediscount'])->name('deletediscount');
Route::any('/discountlist/table', [discountcontroller::class, 'table'])->name('discountlist.table');
Route::any('/retaillist', [retailcontroller::class, 'retaillist'])->name('retaillist');







Route::any('/addsupcategory', [supcategorycontroller::class, 'addsupcategory'])->name('addsupcategory');
Route::any('/editsupcategory/{id}', [supcategorycontroller::class, 'editsupcategory'])->name('editsupcategory');
Route::any('/deletesupcategory/{id}', [supcategorycontroller::class, 'deletesupcategory'])->name('deletesupcategory');





Route::any('/addsupsubcategory', [supsubcategorycontroller::class, 'addsupsubcategory'])->name('addsupsubcategory');
Route::any('/editsupsubcategory/{id}', [supsubcategorycontroller::class, 'editsupsubcategory'])->name('editsupsubcategory');
Route::any('/deletesupsubcategory/{id}', [supsubcategorycontroller::class, 'deletesupsubcategory'])->name('deletesupsubcategory');



Route::any('/addretail/{name}', [retailcontroller::class, 'addretail'])->name('addretail');

Route::any('/editretail/{name}/{id}', [retailcontroller::class, 'editretail'])->name('editretail');
Route::any('/deleteretail/{id}', [retailcontroller::class, 'deleteretail'])->name('deleteretail');
Route::any('/retaillist/table', [retailcontroller::class, 'table'])->name('retaillist.table');
Route::any('/retaillist/generatecode', [retailcontroller::class, 'generatecode'])->name('retaillist.generatecode');
Route::any('/changeprintprice', [Itemcontroller::class, 'changeprintprice'])->name('changeprintprice');
Route::any('/changeprintname', [Itemcontroller::class, 'changeprintname'])->name('changeprintname');
Route::any('/changep', [Itemcontroller::class, 'changep'])->name('changep');
Route::any('/barcodemaster/table', [Itemcontroller::class, 'bartable'])->name('barcodemaster.table');
Route::any('/barcodemaster', [Itemcontroller::class, 'barcodemaster'])->name('barcodemaster');
Route::any('/itemlist', [Itemcontroller::class, 'itemlist'])->name('itemlist');
Route::any('/additem', [Itemcontroller::class, 'additem'])->name('additem');
Route::any('/edititem/{id}', [Itemcontroller::class, 'edititem'])->name('edititem');
Route::any('/deleteitem/{id}', [Itemcontroller::class, 'deleteitem'])->name('deleteitem');
Route::any('/itemlist/fetchcategory', [Itemcontroller::class, 'fetchcategory'])->name('itemlist.fetchcategory');
Route::any('/itemlist/fetchsubcategory', [Itemcontroller::class, 'fetchsubcategory'])->name('itemlist.fetchsubcategory');
Route::any('/itemlist/fetchsubchildcategory', [Itemcontroller::class, 'fetchsubchildcategory'])->name('itemlist.fetchsubchildcategory');
Route::any('/itemlist/getamoutgst', [Itemcontroller::class, 'getamoutgst'])->name('itemlist.getamoutgst');
Route::any('/itemlist/table', [Itemcontroller::class, 'table'])->name('itemlist.table');
Route::any('/itemlist/gettaxr', [Itemcontroller::class, 'gettaxr'])->name('itemlist.gettaxr');
Route::any('/itemlist/purgettaxr', [Itemcontroller::class, 'purgettaxr'])->name('itemlist.purgettaxr');
Route::any('/itemlist/getgstrates', [Itemcontroller::class, 'getgstrates'])->name('itemlist.getgstrates');
Route::any('/itemlist/getsearchresults', [Itemcontroller::class, 'getsearchresults'])->name('itemlist.getsearchresults');
Route::any('/grouplist', [groupcontroller::class, 'grouplist'])->name('grouplist');
Route::any('/addgroup', [groupcontroller::class, 'addgroup'])->name('addgroup');
Route::any('/editgroup/{id}', [groupcontroller::class, 'editgroup'])->name('editgroup');
Route::any('/deletegroup/{id}', [groupcontroller::class, 'deletegroup'])->name('deletegroup');
Route::any('/grouplist/table', [groupcontroller::class, 'table'])->name('grouplist.table');
Route::any('/grouplist/generatecode', [groupcontroller::class, 'generatecode'])->name('grouplist.generatecode');
Route::any('/ledgerlist', [ledgercontroller::class, 'ledgerlist'])->name('ledgerlist');
Route::any('/addledger', [ledgercontroller::class, 'addledger'])->name('addledger');
Route::any('/editledger/{id}', [ledgercontroller::class, 'editledger'])->name('editledger');
Route::any('/deleteledger/{id}', [ledgercontroller::class, 'deleteledger'])->name('deleteledger');
Route::any('/ledgerlist/fetchgroupcode', [ledgercontroller::class, 'fetchgroupcode'])->name('ledgerlist.fetchgroupcode');
Route::any('/ledgerlist/table', [ledgercontroller::class, 'table'])->name('ledgerlist.table');
Route::any('/ledgerlist/generatecode', [ledgercontroller::class, 'generatecode'])->name('ledgerlist.generatecode');
Route::post('/submitpurchase', [purchasecontroller::class, 'submitpurchase'])->name('submitpurchase');
Route::post('/submitpurchasehold', [purchasecontroller::class, 'submitpurchasehold'])->name('submitpurchasehold');

Route::get('/purchase', [purchasecontroller::class, 'purchaseProduct'])->name('purchaseProduct');
Route::get('/purchases', [purchasecontroller::class, 'purchases'])->name('purchases');

Route::any('/adjust', [adjustcontroller::class, 'adjust'])->name('adjust');
Route::any('/adjust/{id}', [adjustcontroller::class, 'eadjust'])->name('eadjust');

Route::post('/getitems', [purchasecontroller::class, 'getitems'])->name('getitems');
Route::post('/getretaildata', [purchasecontroller::class, 'getretaildata'])->name('getretaildata');
Route::post('/itemgetitems', [purchasecontroller::class, 'itemgetitems'])->name('itemgetitems');
Route::post('/itemgetitemsname', [purchasecontroller::class, 'itemgetitemsname'])->name('itemgetitemsname');
Route::post('/getitemdata', [purchasecontroller::class, 'getitemdata'])->name('getitemdata');
Route::post('/getitemdataunit', [purchasecontroller::class, 'getitemdataunit'])->name('getitemdataunit');
Route::post('/getinviocenumber', [purchasecontroller::class, 'getinviocenumber'])->name('getinviocenumber');
Route::post('/getdatabyinvoice', [purchasecontroller::class, 'getdatabyinvoice'])->name('getdatabyinvoice');
Route::post('/deletepurchase', [purchasecontroller::class, 'deletepurchase'])->name('deletepurchase');





Route::get('/estimates', [EstimatesController::class, 'estimates'])->name('estimates');
Route::get('/estimate', [EstimatesController::class, 'estimate'])->name('estimate');
Route::post('/submitestimate', [EstimatesController::class, 'submitestimate'])->name('submitestimate');
Route::post('/egetdatabyinvoice', [EstimatesController::class, 'egetdatabyinvoice'])->name('egetdatabyinvoice');
Route::post('/sendemailestimate', [EstimatesController::class, 'sendemailestimate'])->name('sendemailestimate');





Route::get('/sales', [salecontroller::class, 'sales'])->name('sales');
Route::get('/sale', [salecontroller::class, 'sale'])->name('sale');
Route::post('/addtopurchase', [salecontroller::class, 'addtopurchase'])->name('addtopurchase');
Route::post('/sgetitems', [salecontroller::class, 'sgetitems'])->name('sgetitems');
Route::post('/sgetretaildata', [salecontroller::class, 'sgetretaildata'])->name('sgetretaildata');
Route::post('/sitemgetitems', [salecontroller::class, 'sitemgetitems'])->name('sitemgetitems');
Route::post('/sitemgetitemsname', [salecontroller::class, 'sitemgetitemsname'])->name('sitemgetitemsname');
Route::post('/sgetitemdata', [salecontroller::class, 'sgetitemdata'])->name('sgetitemdata');
Route::post('/sgetitemdataunit', [salecontroller::class, 'sgetitemdataunit'])->name('sgetitemdataunit');
Route::post('/submitsale', [salecontroller::class, 'submitsale'])->name('submitsale');
Route::post('/sgetinviocenumber', [salecontroller::class, 'sgetinviocenumber'])->name('sgetinviocenumber');
Route::post('/sgetdatabyinvoice', [salecontroller::class, 'sgetdatabyinvoice'])->name('sgetdatabyinvoice');
Route::post('/deletesale', [salecontroller::class, 'deletesale'])->name('deletesale');
Route::get('/getholdsale', [salecontroller::class, 'getholdsale'])->name('getholdsale');
Route::post('/getbybarcodenumber', [salecontroller::class, 'getbybarcodenumber'])->name('getbybarcodenumber');


Route::any('/paymentin', [Paymentcontroller::class, 'paymentin'])->name('paymentin');
Route::any('/paymentin/{id}', [Paymentcontroller::class, 'paymentinn'])->name('paymentinn');
Route::any('/deletepaymentin/{id}', [Paymentcontroller::class, 'deletepaymentin'])->name('deletepaymentin');

Route::any('/getbill', [Paymentcontroller::class, 'getbill'])->name('getbill');
Route::any('/getledger', [Paymentcontroller::class, 'getledger'])->name('getledger');


Route::any('/paymentout', [Paymentcontroller::class, 'paymentout'])->name('paymentout');
Route::any('/paymentout/{id}', [Paymentcontroller::class, 'paymentoutt'])->name('paymentoutt');
Route::any('/deletepaymentout/{id}', [Paymentcontroller::class, 'deletepaymentout'])->name('deletepaymentout');
Route::any('/getbillp', [Paymentcontroller::class, 'getbillp'])->name('getbillp');
// Route::any('/getledger', [Paymentcontroller::class, 'getledger'])->name('getledger');




Route::get('/logout', [Homecontroller::class, 'logout'])->name('logout');
/*Reports Routes*/
Route::get('/itemledgerreport', [GeneralLedgerController::class,'itemledgerreport'])->name('itemledgerreport');
Route::post('/itemledgerreportdetail', [GeneralLedgerController::class,'itemledgerreportdetail'])->name('itemledgerreportdetail');
Route::post('/itemledgerreportdetaill', [GeneralLedgerController::class,'itemledgerreportdetaill'])->name('itemledgerreportdetaill');
Route::get('/generalLedgerReport', [GeneralLedgerController::class,'generalLedgerReport'])->name('generalLedgerReport');
Route::any('/groupledgers', [GeneralLedgerController::class, 'groupledgers'])->name('groupledgers');
Route::any('/ledgerwiseDetails',[GeneralLedgerController::class,'ledgerwiseDetails'])->name('ledgerwiseDetails');




Route::get('/receiptDisbursment ',[GeneralLedgerController::class, 'receiptDisbursment'])->name('receiptDisbursment');
Route::any('/receiptDisbursmentgetData', [GeneralLedgerController::class, 'receiptDisbursmentgetData'])->name('receiptDisbursmentgetData');


Route::any('/reOrdergetData',[GeneralLedgerController::class,'reOrdergetData'])->name('reOrdergetData');
Route::get('/reorderlevel ',[GeneralLedgerController::class, 'reorderlevel'])->name('reorderlevel');
Route::get('/profitandloss ',[GeneralLedgerController::class, 'profitandloss'])->name('profitandloss');
Route::post('/plfetch',[GeneralLedgerController::class, 'plfetch'])->name('plfetch');

Route::get('/tradingac',[GeneralLedgerController::class, 'tradingac'])->name('tradingac');
Route::any('/tradingacgetData',[GeneralLedgerController::class, 'tradingacgetData'])->name('tradingacgetData');
Route::get('/reOrder',[GeneralLedgerController::class,'reOrder'])->name('reOrder');
Route::any('/reOrderfetchdata',[GeneralLedgerController::class,'reOrderfetchdata'])->name('reOrderfetchdata');
Route::any('/reOrderfetchdataForCategory',[GeneralLedgerController::class,'reOrderfetchdataForCategory'])->name('reOrderfetchdataForCategory');
Route::any('/reorderData',[GeneralLedgerController::class, 'reorderData'])->name('reorderData');
Route::any('/voucher', [Vouchercontroller::class, 'voucher'])->name('voucher');
Route::any('/vsearch', [Vouchercontroller::class, 'vsearch'])->name('vsearch');
Route::any('/getvoucherdata', [Vouchercontroller::class, 'getvoucherdata'])->name('getvoucherdata');
Route::any('/getfirstgetvoucherdata', [Vouchercontroller::class, 'getfirstgetvoucherdata'])->name('getfirstgetvoucherdata');
Route::any('/getlastgetvoucherdata', [Vouchercontroller::class, 'getlastgetvoucherdata'])->name('getlastgetvoucherdata');
Route::any('/previosgetvoucherdata', [Vouchercontroller::class, 'previosgetvoucherdata'])->name('previosgetvoucherdata');
Route::any('/nextgetvoucherdata', [Vouchercontroller::class, 'nextgetvoucherdata'])->name('nextgetvoucherdata');
Route::any('/submitvoucher', [Vouchercontroller::class, 'submitvoucher'])->name('submitvoucher');
Route::any('/getled', [Vouchercontroller::class, 'getled'])->name('getled');
Route::any('/getdatadat', [Vouchercontroller::class, 'getdatadat'])->name('getdatadat');
Route::any('/daybook', [Daybookcontroller::class, 'daybook'])->name('daybook');
Route::any('/daybookdata', [Daybookcontroller::class, 'daybookdata'])->name('daybookdata');

Route::any('/admin', [AdminController::class, 'admin'])->name('admin');
Route::any('/editadmin/{id}', [AdminController::class, 'editadmin'])->name('admin');
Route::any('/admin/table', [AdminController::class, 'table'])->name('admin.table');

Route::any('/employe', [EmployeController::class, 'employe'])->name('employe');
Route::any('/editemploye/{id}', [EmployeController::class, 'editemploye'])->name('employe');
Route::any('/deleteemploye/{id}', [EmployeController::class, 'deleteemploye'])->name('employe');
Route::any('/employe/table', [EmployeController::class, 'table'])->name('employe.table');




Route::any('/employee', [EmployeeController::class, 'employee'])->name('employee');
Route::any('/editemployee/{id}', [EmployeeController::class, 'editemployee'])->name('employee');
Route::any('/deleteemployee/{id}', [EmployeeController::class, 'deleteemployee'])->name('employee');
Route::any('/employee/table', [EmployeeController::class, 'table'])->name('employee.table');
Route::any('/checkempid', [EmployeeController::class, 'checkempid'])->name('employee.checkempid');


Route::any('/partywiseledger',[GeneralLedgerController::class, 'partywiseledger'])->name('partywiseledger');
Route::any('/session',[SessionController::class, 'session'])->name('session');
Route::any('/session/table',[SessionController::class, 'table'])->name('session.table');
Route::any('/editsession/{id}', [SessionController::class, 'editsession'])->name('editsession');
Route::any('/deletesession/{id}', [SessionController::class, 'deletesession'])->name('deletesession');



// Route::any('/attendance', [AttendanceController::class, 'attendance'])->name('attendance');
Route::any('/permissions', [RouteController::class, 'showRoutes'])->name('showRoutes');


// use App\Http\Controllers\AttendanceController;

Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance');
Route::post('/getattendance', [AttendanceController::class, 'getattendance'])->name('getattendance');
Route::post('/update-attendance-status', [AttendanceController::class, 'updateStatus'])->name('updateAttendanceStatus');
Route::post('/update-attendance-time', [AttendanceController::class, 'updateTime'])->name('updateAttendanceTime');


Route::any('/priorleave', [AttendanceController::class, 'priorleave'])->name('priorleave');
Route::any('/priorleave/{id}', [AttendanceController::class, 'editpriorleave'])->name('editpriorleave');
Route::any('/deletepriorleave/{id}', [AttendanceController::class, 'deletepriorleave'])->name('deletepriorleave');
Route::any('/attendancelist', [AttendanceController::class, 'attendancelist'])->name('attendancelist');



});

