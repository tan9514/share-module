<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| @author renjianhong
| @date 2021-7-28 16:04
*/

/**
 * 分销中心
 */
Route::any('share/setting', 'ShareController@setting');//分销基础设置
Route::any('share/commission', 'ShareController@commission');//分销佣金设置
Route::get('share/custom', 'ShareController@custom');//分销中心自定义设置
Route::any('share/editCustom', 'ShareController@editCustom');//编辑分销中心自定义设置

//分销用户
Route::get('share_user/list', 'ShareUserController@list');//分销用户列表
Route::get('share_user/ajaxList', 'ShareUserController@ajaxList');//ajax获取列表
Route::post('share_user/delete', 'ShareUserController@delete');//删除
Route::post('share_user/batchDelete', 'ShareUserController@batchDelete');//批量删除
Route::post('share_user/saveContent', 'ShareUserController@saveContent');//添加备注
Route::post('share_user/saveStatus', 'ShareUserController@saveStatus');//审核分销商
Route::get('share_user/getParentUser', 'ShareUserController@getParentUser');//查看下级列表
Route::get('share_user/ajaxParentUser', 'ShareUserController@ajaxParentUser');//ajax获取下级数据


//分销提现
Route::get('share_cash/list', 'ShareCashController@list');//分销提现列表
Route::get('share_cash/ajaxList', 'ShareCashController@ajaxList');//ajax获取列表
Route::post('share_cash/batchDelete', 'ShareCashController@batchDelete');//批量删除
Route::post('share_cash/adopt', 'ShareCashController@adopt');//通过提现申请
Route::post('share_cash/reject', 'ShareCashController@reject');//驳回提现申请
Route::post('share_cash/confirmPayment', 'ShareCashController@confirmPayment');//驳回提现申请

//分销订单
Route::get('share_order/list', 'ShareOrderController@list');//分销订单列表
Route::get('share_order/ajaxList', 'ShareOrderController@ajaxList');//ajax获取列表
Route::post('share_order/batchDelete', 'ShareOrderController@batchDelete');//批量删除
