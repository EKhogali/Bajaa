<?php

namespace App\Http\Controllers;

use App\account;
use App\category;
use App\Classification;
use Illuminate\Http\Request;
use app\http\controllers\SittingsController;
use Illuminate\Support\Facades\Session;
use function Sodium\compare;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $acc_type = Request('acc_type');
        switch ($acc_type){
            case 0:
                if(Request('is_search') == true){
                    $accounts = account::where('id',Request('account_id'))->get();
                    $search_accounts = account::where('is_fixed_assets',0)->where('is_details',0)->get();
                }else{
                    $accounts = account::where('is_fixed_assets',0)->where('is_details',0)->get();
                    $search_accounts = account::where('is_fixed_assets',0)->where('is_details',0)->get();
                }
                return view('chartofaccounts.index')
                    ->with('accounts',$accounts)
                    ->with('search_accounts',$search_accounts);
            break;
            case 1:
                if(Request('is_search') == true){
                    $accounts = account::where('id',Request('account_id'))->get();
                    $search_accounts = account::where('is_fixed_assets',1)->where('is_details',0)->get();
                }else{
                    $accounts = account::where('is_fixed_assets',1)->where('is_details',0)->get();
                    $search_accounts = account::where('is_fixed_assets',1)->where('is_details',0)->get();
                }
                return view('chartofaccounts.index_assets')->with('accounts',$accounts)->with('search_accounts',$search_accounts);
            break;
            case 2:
                if(Request('is_search') == true){
                    $accounts = account::where('id',Request('account_id'))->get();
                    $search_accounts = account::where('is_fixed_assets',0)->where('is_details',1)->get();
                }else{
                    $accounts = account::where('is_fixed_assets',0)->where('is_details',1)->get();
                    $search_accounts = account::where('is_fixed_assets',0)->where('is_details',1)->get();
                }
                return view('chartofaccounts.index_details')->with('accounts',$accounts)->with('search_accounts',$search_accounts);
            break;
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = account::all();
        $categories = category::all();
        $categorytxt2_list = account::select('categorytxt2')->distinct()->get();
        $classifications = Classification::where('archived',0)->get();
        return view('chartofaccounts.create')
            ->with('categorytxt2_list',$categorytxt2_list)
            ->with('parents',$parents)
            ->with('classifications',$classifications)
            ->with('acc_type',Request('acc_type'))
            ->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string'
        ]);
        $is_details = -1;
        $is_fixed_assets = -1;
        switch (Request('acc_type')){
            default:
                $is_details = 0;
                $is_fixed_assets = 0;
                break;
            case 1:
                $is_details = 0;
                $is_fixed_assets = 1;
                break;
            case 2:
                $is_details = 1;
                $is_fixed_assets = 0;
                break;
        }

        \DB::begintransaction();
        try {
            $idd = \DB::table('accounts')->insertgetid([
                'company_id'=>session::get('company_id'),
                'name'=>Request('name'),
                'code'=>Request('code'),
                'categorytxt2'=>Request('categorytxt2') ?? '',
                'parent_id'=>Request('parent_id'),
                'category_id'=>Request('category_id'),
                'classification_id'=>Request('classification_id') ?? 1,
                'is_fixed_assets'=>$is_fixed_assets,
                'is_details'=>$is_details,
                'created_by'=> auth()->id(),
                'updated_by'=> auth()->id()
            ]);

            \DB::commit();
//            $rec = account::find($idd);dd($rec);
            $msg = 'تمت العلمية بنجاح';
            session::put('msgtype','success') ;
            return redirect()->route('accounts_with_param',['acc_type'=>Request('acc_type')])->with('message', $msg);


        }catch (\Exception $e){

            \DB::rollBack();

            $msg = 'عفواً، لا يمكن اتمام العملية للسبب التالي:'. PHP_EOL .$e->getMessage();
            session::put('msgtype','notsuccess') ;

            return redirect()->route('accounts_with_param',['acc_type'=>Request('acc_type')])->with('message', $msg);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(account $account)
    {

        $parents = account::all();
        $categories = category::all();
        $categorytxt2_list = account::select('categorytxt2')->distinct()->get();
        $classifications = Classification::where('archived',0)->get();
        return view('chartofaccounts.edit')
            ->with('account',$account)
            ->with('parents',$parents)
            ->with('acc_type',Request('acc_type'))
            ->with('categories',$categories)
            ->with('classifications',$classifications)
            ->with('categorytxt2_list',$categorytxt2_list)
            ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, account $account)
    {

        if (! Request()->has('name')  || is_null(Request('name'))){
            $msg = 'عفواً، يجب تحديد اسم الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }
        if (! Request()->has('category_id')  || is_null(Request('category_id'))){
            $msg = 'عفواً، يجب تحديد تصنيف الحساب قبل المتابعة في العملية';
            session::put('msgtype','notsuccess') ;
            return back()->with('message', $msg);
        }

        \DB::table('accounts')
            ->where('id',$account->id)
            ->update([
                'name' => Request('name')
                ,'code' => Request('code')
                ,'categorytxt2' => Request('categorytxt2')
                ,'parent_id' => Request('parent_id')
                ,'category_id' => Request('category_id')
                ,'classification_id' => Request('classification_id') ?? 1
                ,'updated_by' => auth()->id()
            ]);

//        return redirect('/accounts');
        return redirect()->route('accounts_with_param',['acc_type'=>Request('acc_type')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(account $account)
    {
//        $account->delete();
            if( \DB::table('accounts')
//                ->where('company_id',session::get('company_id'))
                ->where('parent_id',$account->id)
                ->doesntExist()
            &&
                \DB::table('journalds')
//                    ->where('company_id',session::get('company_id'))
                    ->where('account_id',$account->id)
                    ->doesntExist()
            &&
                \DB::table('treasury_transactions')
//                    ->where('company_id',session::get('company_id'))
                    ->where('account_id',$account->id)
                    ->doesntExist()
            &&
                \DB::table('treasury_transaction_details')
//                    ->where('company_id',session::get('company_id'))
                    ->where('account_id',$account->id)
                    ->doesntExist()
            &&
                \DB::table('treasuries')
//                    ->where('company_id',session::get('company_id'))
                    ->where('account_id',$account->id)
                    ->doesntExist()
            ){
                    \DB::table('accounts')->where('id',$account->id)->delete();
                    $msg = 'تمت العملية بنجاح';
                    session::put('msgtype','success') ;
                }
            else {
                $msg = 'عفواً، لا يمكن اجراء عملية الالغاء لارتباط العنصر ببيانات اخرى';
//                $msg = session()->get('notsuccess_pre') ;
                session::put('msgtype','notsuccess') ;
            }

//        return redirect('/accounts')->with('message', $msg);
        return redirect()->route('accounts_with_param',['acc_type'=>Request('acc_type')])->with('message', $msg);
    }
}
