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
use App\Http\Controllers\branchcontroller;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\TehsilController;
use App\Http\Controllers\PostOfficeController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\LoanTypeController;
use App\Http\Controllers\LoanMasterController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NarrationController;
use App\Http\Controllers\AgentMasterController;
use App\Http\Controllers\DailyCollectioncontroller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\loancontroller;
use App\Http\Controllers\disbursmentcontroller;
use App\Http\Controllers\logbookController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\login;
use App\Http\Middleware\isnotlogin;
use Illuminate\Support\Facades\Artisan;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Http\Controllers\Reports\BalanceSheetController;
use App\Http\Controllers\Reports\LoanPersonalLedger;
use App\Http\Controllers\Reports\OverdueReportController;
use App\Http\Controllers\Reports\DueEmisController;
use App\Http\Controllers\Reports\CommitteeReportController;
use App\Http\Controllers\Commeteecontroller;
use App\Http\Controllers\FileRecordController;

//optimize All facade value:
//optimize All facade value:
//optimize All facade value:
Route::get('/op-cache', function () {
$exitCode = Artisan::call('optimize:clear');
return '<h1>Cache facade value cleared</h1>';
});
//optimize All facade value:
//optimize All facade value:
//optimize All facade value:
Route::get('barcode', function () {
$generatorPNG = new BarcodeGeneratorPNG();
$image = $generatorPNG->

getBarcode('3232232323', $generatorPNG::TYPE_CODE_128);
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
Route::any('/setcurrentdate', [Homecontroller::class, 'setcurrentdate'])->name('setcurrentdate');
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
Route::any('/commetee', [Commeteecontroller::class, 'commetee'])->name('commetee');
Route::any('/commeteesubmit', [Commeteecontroller::class, 'commeteesubmit'])->name('commeteesubmit');
Route::any('/deleteeditcometee/{id}', [Commeteecontroller::class, 'deleteeditcometee'])->name('deleteeditcometee');
Route::any('/addmemcometee/{id}', [Commeteecontroller::class, 'addmemcometee'])->name('addmemcometee');
Route::any('/submitaddmemcometee', [Commeteecontroller::class, 'submitaddmemcometee'])->name('submitaddmemcometee');
Route::any('/cometeerecovery', [Commeteecontroller::class, 'cometeerecovery'])->name('cometeerecovery');
Route::any('/widrawcometeerecovery', [Commeteecontroller::class, 'widrawcometeerecovery'])->name('widrawcometeerecovery');
Route::any('/getmembersforwidrawl', [Commeteecontroller::class, 'getmembersforwidrawl'])->name('getmembersforwidrawl');
Route::any('/widrawlcometeee', [Commeteecontroller::class, 'widrawlcometeee'])->name('widrawlcometeee');
Route::any('/getmemberstotal', [Commeteecontroller::class, 'getmemberstotal'])->name('getmemberstotal');
Route::any('/getcometeemembers', [Commeteecontroller::class, 'getcometeemembers'])->name('getcometeemembers');
Route::any('/recoverycometeemembers', [Commeteecontroller::class, 'recoverycometeemembers'])->name('recoverycometeemembers');
Route::any('/deleterecoverycometti', [Commeteecontroller::class, 'deleterecoverycometti'])->name('deleterecoverycometti');


Route::any('/purchaseorder', [brandcontroller::class, 'purchaseorder'])->name('purchaseorder');
Route::any('/purchaseorder/table', [brandcontroller::class, 'ptable'])->name('purchaseorder.table');
Route::any('/brandlist', [brandcontroller::class, 'brandlist'])->name('brandlist');
Route::any('/addbrand', [brandcontroller::class, 'addbrand'])->name('addbrand');
Route::any('/editbrand/{id}', [brandcontroller::class, 'editbrand'])->name('editbrand');
Route::any('/deletebrand/{id}', [brandcontroller::class, 'deletebrand'])->name('deletebrand');
Route::any('/addbranch', [branchcontroller::class, 'addbranch'])->name('addbranch');
Route::post('update', [branchcontroller::class, 'update'])->name('masterupdate');
Route::any('/editbranch/{id}', [branchcontroller::class, 'editbranch'])->name('editbranch');
Route::any('/deletebranch/{id}', [branchcontroller::class, 'deletebranch'])->name('deletebranch');
Route::any('/disbursment', [disbursmentcontroller::class, 'disbursment'])->name('disbursment');
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
Route::any('/addretail', [retailcontroller::class, 'addretail'])->name('addretail');
Route::any('/editretail/{id}', [retailcontroller::class, 'editretail'])->name('editretail');
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
// Route::any('/grouplist', [groupcontroller::class, 'grouplist'])->name('grouplist');
// Route::any('/addgroup', [groupcontroller::class, 'addgroup'])->name('addgroup');
// Route::any('/editgroup/{id}', [groupcontroller::class, 'editgroup'])->name('editgroup');
// Route::any('/deletegroup/{id}', [groupcontroller::class, 'deletegroup'])->name('deletegroup');
// Route::any('/grouplist/table', [groupcontroller::class, 'table'])->name('grouplist.table');
// Route::any('/grouplist/generatecode', [groupcontroller::class, 'generatecode'])->name('grouplist.generatecode');
// Route::any('/ledgerlist', [ledgercontroller::class, 'ledgerlist'])->name('ledgerlist');
// Route::any('/addledger', [ledgercontroller::class, 'addledger'])->name('addledger');
// Route::any('/editledger/{id}', [ledgercontroller::class, 'editledger'])->name('editledger');
// Route::any('/deleteledger/{id}', [ledgercontroller::class, 'deleteledger'])->name('deleteledger');
// Route::any('/ledgerlist/fetchgroupcode', [ledgercontroller::class, 'fetchgroupcode'])->name('ledgerlist.fetchgroupcode');
// Route::any('/ledgerlist/table', [ledgercontroller::class, 'table'])->name('ledgerlist.table');
// Route::any('/ledgerlist/generatecode', [ledgercontroller::class, 'generatecode'])->name('ledgerlist.generatecode');
// Route::post('/submitpurchase', [purchasecontroller::class, 'submitpurchase'])->name('submitpurchase');
// Route::post('/submitpurchasehold', [purchasecontroller::class, 'submitpurchasehold'])->name('submitpurchasehold');
Route::get('/purchase', [purchasecontroller::class, 'purchaseProduct'])->name('purchaseProduct');
Route::get('/purchases', [purchasecontroller::class, 'purchases'])->name('purchases');
Route::any('/adjust', [adjustcontroller::class, 'adjust'])->name('adjust');
Route::post('/getitems', [purchasecontroller::class, 'getitems'])->name('getitems');
Route::post('/getretaildata', [purchasecontroller::class, 'getretaildata'])->name('getretaildata');
Route::post('/itemgetitems', [purchasecontroller::class, 'itemgetitems'])->name('itemgetitems');
Route::post('/itemgetitemsname', [purchasecontroller::class, 'itemgetitemsname'])->name('itemgetitemsname');
Route::post('/getitemdata', [purchasecontroller::class, 'getitemdata'])->name('getitemdata');
Route::post('/getitemdataunit', [purchasecontroller::class, 'getitemdataunit'])->name('getitemdataunit');
Route::post('/getinviocenumber', [purchasecontroller::class, 'getinviocenumber'])->name('getinviocenumber');
Route::post('/getdatabyinvoice', [purchasecontroller::class, 'getdatabyinvoice'])->name('getdatabyinvoice');
Route::post('/deletepurchase', [purchasecontroller::class, 'deletepurchase'])->name('deletepurchase');
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
Route::get('/logout', [Homecontroller::class, 'logout'])->name('logout');
Route::any('/changepassword', [Homecontroller::class, 'changepassword'])->name('changepassword');
/*Reports Routes*/
Route::get('/itemledgerreport', [GeneralLedgerController::class, 'itemledgerreport'])->name('itemledgerreport');
Route::post('/itemledgerreportdetail', [GeneralLedgerController::class, 'itemledgerreportdetail'])->name('itemledgerreportdetail');
Route::post('/itemledgerreportdetaill', [GeneralLedgerController::class, 'itemledgerreportdetaill'])->name('itemledgerreportdetaill');
Route::get('/generalLedgerReport', [GeneralLedgerController::class, 'generalLedgerReport'])->name('generalLedgerReport');
Route::any('/groupledgers', [GeneralLedgerController::class, 'groupledgers'])->name('groupledgers');
Route::any('/ledgerwiseDetails', [GeneralLedgerController::class, 'ledgerwiseDetails'])->name('ledgerwiseDetails');
Route::get('/receiptDisbursment ', [GeneralLedgerController::class, 'receiptDisbursment'])->name('receiptDisbursment');
Route::any('/receiptDisbursmentgetData', [GeneralLedgerController::class, 'receiptDisbursmentgetData'])->name('receiptDisbursmentgetData');
Route::get('/tradingac', [GeneralLedgerController::class, 'tradingac'])->name('tradingac');
Route::any('/tradingacgetData', [GeneralLedgerController::class, 'tradingacgetData'])->name('tradingacgetData');
Route::get('/reOrder', [GeneralLedgerController::class, 'reOrder'])->name('reOrder');
Route::any('/reOrderfetchdata', [GeneralLedgerController::class, 'reOrderfetchdata'])->name('reOrderfetchdata');
Route::any('/reOrderfetchdataForCategory', [GeneralLedgerController::class, 'reOrderfetchdataForCategory'])->name('reOrderfetchdataForCategory');
// Route::any('/reOrdergetData',[GeneralLedgerController::class,'reOrdergetData'])->name('reOrdergetData');
Route::any('/reorderData', [GeneralLedgerController::class, 'reorderData'])->name('reorderData');
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
Route::any('/admin', [AdminController::class, 'admin'])->name('admin');
Route::any('/editadmin/{id}', [AdminController::class, 'editadmin'])->name('admin');
Route::any('/admin/table', [AdminController::class, 'table'])->name('admin.table');
Route::any('/employe', [EmployeController::class, 'employe'])->name('employe');
Route::any('/editemploye/{id}', [EmployeController::class, 'editemploye'])->name('employe');
Route::any('/employe/table', [EmployeController::class, 'table'])->name('employe.table');
Route::any('/partywiseledger', [GeneralLedgerController::class, 'partywiseledger'])->name('partywiseledger');
Route::any('/agentsreport', [GeneralLedgerController::class, 'agentsreport'])->name('agentsreport');
Route::any('/profitlossIndex', [GeneralLedgerController::class, 'profitlossIndex'])->name('profitlossIndex');
Route::any('/profitandloss', [GeneralLedgerController::class, 'profitandloss'])->name('profitandloss');
Route::any('/session', [SessionController::class, 'session'])->name('session');
Route::any('/sessioname', [SessionController::class, 'sessioname'])->name('sessioname');
Route::any('/checkexitsessionname', [SessionController::class, 'checkexitsessionname'])->name('checkexitsessionname');
Route::any('/session/table', [SessionController::class, 'table'])->name('session.table');
Route::any('/editsession/{id}', [SessionController::class, 'editsession'])->name('editsession');
Route::any('/deletesession/{id}', [SessionController::class, 'deletesession'])->name('deletesession');
Route::any('/state', [StateController::class, 'state'])->name('state');
Route::any('/editstate/{id}', [StateController::class, 'editstate'])->name('editstate');
Route::any('/deletestate/{id}', [StateController::class, 'deletestate'])->name('deletestate');
Route::any('/district', [DistrictController::class, 'district'])->name('district');
Route::any('/editdistrict/{id}', [DistrictController::class, 'editdistrict'])->name('editdistrict');
Route::any('/deletedistrict/{id}', [DistrictController::class, 'deletedistrict'])->name('deletedistrict');
Route::any('/tehsil', [TehsilController::class, 'tehsil'])->name('tehsil');
Route::any('/edittehsil/{id}', [TehsilController::class, 'edittehsil'])->name('edittehsil');
Route::any('/deletetehsil/{id}', [TehsilController::class, 'deletetehsil'])->name('deletetehsil');
Route::any('/postoffice', [PostOfficeController::class, 'postoffice'])->name('postoffice');
Route::any('/editpostoffice/{id}', [PostOfficeController::class, 'editpostoffice'])->name('editpostoffice');
Route::any('/deletepostoffice/{id}', [PostOfficeController::class, 'deletepostoffice'])->name('deletepostoffice');
//+++++++++++ Village Route's
Route::any('/village', [VillageController::class, 'village'])->name('village');
Route::any('/editvillage/{id}', [VillageController::class, 'editvillage'])->name('editvillage');
Route::any('/deletevillage/{id}', [VillageController::class, 'deletevillage'])->name('deletevillage');
Route::any('/loginmaster', [LoginController::class, 'loginmaster'])->name('loginmaster');
Route::any('/loginmaster/{id}', [LoginController::class, 'eloginmaster'])->name('eloginmaster');
Route::any('/deleteloginmaster/{id}', [LoginController::class, 'deleteloginmaster'])->name('deleteloginmaster');
Route::any('/loantype', [LoanTypeController::class, 'loantype'])->name('loantype');
Route::any('/loantype/{id}', [LoanTypeController::class, 'editloantype'])->name('editloantype');
Route::any('/deleteloantype/{id}', [LoanTypeController::class, 'deleteloantype'])->name('deleteloantype');
Route::any('/loanmaster', [LoanMasterController::class, 'loanmaster'])->name('loantype');
Route::any('/loanmaster/{id}', [LoanMasterController::class, 'edloanmaster'])->name('editloantype');
Route::any('/deleteloanmaster/{id}', [LoanMasterController::class, 'deleteloanmaster'])->name('deleteloanmaster');
Route::any('/purposemaster', [PurposeController::class, 'purposemaster'])->name('purposemaster');
Route::any('/purposemaster/{id}', [PurposeController::class, 'epurposemaster'])->name('epurposemaster');
Route::any('/deletepurposemaster/{id}', [PurposeController::class, 'deletepurposemaster'])->name('deletepurposemaster');
//++++++++++++ Group Masters
Route::any('/group', [groupcontroller::class, 'group'])->name('group');
Route::any('/generateGroupCode', [groupcontroller::class, 'generateGroupCode'])->name('generateGroupCode');
Route::any('/editgroups/{id}', [groupcontroller::class, 'editgroups'])->name('editgroups');
Route::any('/deletegroups/{id}', [groupcontroller::class, 'deletegroups'])->name('deletegroups');
//++++++++++++ Ledger Masters
Route::any('/ledger', [ledgercontroller::class, 'ledger'])->name('ledger');
Route::any('/generateLedgerCode', [ledgercontroller::class, 'generateLedgerCode'])->name('generateLedgerCode');
Route::any('/groupnature', [ledgercontroller::class, 'groupnature'])->name('groupnature');
Route::any('/editledger/{id}', [ledgercontroller::class, 'editledger'])->name('editledger');
Route::any('/deleteledger/{id}', [ledgercontroller::class, 'deleteledger'])->name('deleteledger');
//+++++++++++++ Narration Master
Route::any('/narration', [NarrationController::class, 'narration'])->name('narration');
Route::any('/edinarration/{id}', [NarrationController::class, 'edinarration'])->name('edinarration');
Route::any('/deletenarration/{id}', [NarrationController::class, 'deletenarration'])->name('deletenarration');
Route::get('/autocomplete', [loancontroller::class, 'search'])->name('autocomplete.search');
Route::any('/import-members', [AccountController::class, 'import']);
Route::any('/formimport', [AccountController::class, 'formimport'])->name('formimport');
Route::any('/accounts', [AccountController::class, 'accounts'])->name('accounts');
Route::any('/account_opening', [AccountController::class, 'account_opening'])->name('account_opening');
Route::any('/account_opening/{id}', [AccountController::class, 'eaccount_opening'])->name('account_opening');
Route::any('/deleteaccount/{id}', [AccountController::class, 'deleteaccount'])->name('deleteaccount');
Route::get('account/data', [AccountController::class, 'getAccountData'])->name('account.data');
Route::any('/foreclosure', [loancontroller::class, 'foreclosure'])->name('foreclosure');
Route::any('/getinterestType', [loancontroller::class, 'getinterestType'])->name('getinterestType');







Route::any('/addfourcloserecovery', [loancontroller::class, 'addfourcloserecovery'])->name('addfourcloserecovery');
Route::any('/getforclosure', [loancontroller::class, 'getforclosure'])->name('getforclosure');
Route::any('/quickrecovery', [loancontroller::class, 'quickrecovery'])->name('quickrecovery');
Route::any('/getgroupsLedgers', [loancontroller::class, 'getgroupsLedgers'])->name('getgroupsLedgers');
Route::any('/addrecovery', [loancontroller::class, 'addrecovery'])->name('addrecovery');
Route::any('/editaddrecovery', [loancontroller::class, 'editaddrecovery'])->name('editaddrecovery');
Route::any('/loaneditpaymentForm', [loancontroller::class, 'loaneditpaymentForm'])->name('loaneditpaymentForm');
Route::any('/editfourcloseraddrecovery', [loancontroller::class, 'editfourcloseraddrecovery'])->name('editfourcloseraddrecovery');
Route::any('/getquickrecovery', [loancontroller::class, 'getquickrecovery'])->name('getquickrecovery');
Route::any('/getquickrecoveryfor', [loancontroller::class, 'getquickrecoveryfor'])->name('getquickrecoveryfor');
Route::any('/recconfirmDeletere', [loancontroller::class, 'recconfirmDeletere'])->name('recconfirmDeletere');
Route::any('/recconfirmDeleterefourclose', [loancontroller::class, 'recconfirmDeleterefourclose'])->name('recconfirmDeleterefourclose');
Route::any('/getdatarecovery', [loancontroller::class, 'getdatarecovery'])->name('getdatarecovery');
Route::any('/loangetdatarecovery', [loancontroller::class, 'loangetdatarecovery'])->name('loangetdatarecovery');
Route::any('/loan', [loancontroller::class, 'loan'])->name('loan');
Route::any('/advancement', [loancontroller::class, 'advancement'])->name('advancement');
Route::any('/getcashbankledgers', [loancontroller::class, 'getcashbankledgers'])->name('getcashbankledgers');
Route::any('/editadvancement', [loancontroller::class, 'editadvancement'])->name('editadvancement');
Route::any('/deleteadvancement', [loancontroller::class, 'deleteadvancement']);
Route::any('/getdetail', [loancontroller::class, 'getdetail'])->name('getdetail');
Route::any('/getemi', [loancontroller::class, 'getemi'])->name('getemi');
Route::any('/getintrest', [loancontroller::class, 'getintrest'])->name('getintrest');
Route::any('/getloanname', [loancontroller::class, 'getloanname'])->name('getloanname');
Route::any('/get-customer-Details',[loancontroller::class,'getCustomerDetails'])->name('get-customer-Details');
Route::any('/get-details',[loancontroller::class,'getDetails'])->name('get-details');
Route::any('/check-loan-limit',[loancontroller::class, 'checkLoanLimit'])->name('check-loan-limit');
Route::any('/takerecovery', [loancontroller::class, 'takerecovery'])->name('takerecovery');
Route::any('/confirmDeletere', [loancontroller::class, 'confirmDeletere'])->name('confirmDeletere');
Route::any('/editadvancementre', [loancontroller::class, 'editadvancementre'])->name('editadvancementre');
//+++++++++++++ Agent Master
Route::any('/agent', [AgentMasterController::class, 'agent'])->name('agent');
Route::any('/agentCodeCheck', [AgentMasterController::class, 'agentCodeCheck'])->name('agentCodeCheck');
Route::any('/editagent/{id}', [AgentMasterController::class, 'editagent'])->name('editagent');
Route::any('/deleteagent/{id}', [AgentMasterController::class, 'deleteagent'])->name('deleteagent');
//+++++++ Daily Collection Master
Route::any('/dailyCollSechme', [DailyCollectioncontroller::class, 'dailyCollSechme'])->name('dailyCollSechme');
Route::any('/editdailyCollSechme/{id}', [DailyCollectioncontroller::class, 'editdailyCollSechme'])->name('editdailyCollSechme');
Route::any('/deletedailyCollSechme/{id}', [DailyCollectioncontroller::class, 'deletedailyCollSechme'])->name('deletedailyCollSechme');
//++++++++++++ Account Opening
Route::any('/account_opening', [AccountController::class, 'account_opening'])->name('account_opening');
Route::any('/checkCustomerId', [AccountController::class, 'checkCustomerId'])->name('checkCustomerId');
Route::any('/generate-customer-number',[AccountController::class, 'generateCustomerNumber'])->name('generate-customer-number');
Route::any('/daybook', [Daybookcontroller::class, 'daybook'])->name('daybook');
Route::any('/daybookdata', [Daybookcontroller::class, 'daybookdata'])->name('daybookdata');
Route::any('/balancesheetindex', [BalanceSheetController::class, 'balanceSheetIndex'])->name('balancesheetindex');
Route::any('/getbalanceSheet', [BalanceSheetController::class, 'getbalanceSheet'])->name('getbalanceSheet');
//___________Agent Master
Route::post('/checkexitsusername', [AgentMasterController::class, 'checkExitsUserName'])->name('checkexitsusername');
//__________Loan Personal Ledger Reports
Route::get('/loanpersonalledgers',[LoanPersonalLedger::class,'loanPersonalLedgers'])->name('loanpersonalledgers');
Route::post('/get-customer-loan-account',[LoanPersonalLedger::class,'getCustomerLoanAccount'])->name('get-customer-loan-account');
Route::post('/get-loan-details',[LoanPersonalLedger::class,'getLoanDetails'])->name('get-loan-details');
//_______________OverDue Loan Report
Route::get('/pending-emi-report', [OverdueReportController::class, 'PendingEmiReport'])->name('pending-emi-report');
Route::post('/get-pending-emis', [OverdueReportController::class, 'GetPendingEmis'])->name('get-pending-emis');
//____________Due Emi's Report
Route::get('/due-emi-report', [DueEmisController::class, 'dueEmiReport'])->name('due-emi-report');
Route::get('/emi-report', [DueEmisController::class, 'EmiReport'])->name('EmiReport');
Route::post('/get-pending-emi', [DueEmisController::class, 'GetPendingEmi'])->name('get-pending-emi');
Route::post('/get-emi', [DueEmisController::class, 'GetEmi'])->name('get-emi');
Route::any('/Log-Book', [logbookController::class, 'logbook'])->name('logbook');
Route::any('/getdataofloan', [logbookController::class, 'getdataofloan'])->name('getdataofloan');
Route::any('/emireport', [logbookController::class, 'emireport'])->name('emireport');
Route::any('/emireportloan', [logbookController::class, 'emireportloan'])->name('emireportloan');
Route::any('/alllogbook', [logbookController::class, 'alllogbook'])->name('alllogbook');
Route::any('/allgetdataofloan', [logbookController::class, 'allgetdataofloan'])->name('allgetdataofloan');
Route::any('/getnamebycstmid', [logbookController::class, 'getnamebycstmid'])->name('getnamebycstmid');


//__________Changes
Route::any('/gecustomertloans', [loancontroller::class, 'gecustomertloans'])->name('gecustomertloans');
Route::any('/checkduplicateentryaccount', [loancontroller::class, 'checkduplicateentryaccount'])->name('checkduplicateentryaccount');


//_________File Entries
Route::any('/filecustomerindex', [FileRecordController::class, 'filecustomerindex'])->name('filecustomerindex');
Route::any('/checkalreadymember',[FileRecordController::class, 'checkalreadymember'])->name('checkalreadymember');
Route::any('/fileinsert', [FileRecordController::class, 'fileinsert'])->name('fileinsert');
Route::any('/editfiles', [FileRecordController::class, 'editfiles'])->name('editfiles');
Route::any('/fileupdate', [FileRecordController::class, 'fileupdate'])->name('fileupdate');



//____________Committee Report Routes
Route::any('/committeereportIndex', [CommitteeReportController::class, 'committeereportIndex'])->name('committeereportIndex');
Route::any('/getcommittes', [CommitteeReportController::class, 'getcommittes'])->name('getcommittes');












});
Route::get('/privacy-policy', [Homecontroller::class, 'privacy'])->name('privacy');
Route::get('/terms-condition', [Homecontroller::class, 'terms'])->name('terms');
