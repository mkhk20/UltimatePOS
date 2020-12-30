<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use App\AccountType;
use App\TransactionPayment;
use App\Utils\Util;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Media;

class AccountController extends Controller
{
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        if (request()->ajax()) {
            $accounts = Account::leftjoin('account_transactions as AT', function ($join) {
                $join->on('AT.account_id', '=', 'accounts.id');
                $join->whereNull('AT.deleted_at');
            })
            ->leftjoin(
                'account_types as ats',
                'accounts.account_type_id',
                '=',
                'ats.id'
            )
            ->leftjoin(
                'account_types as pat',
                'ats.parent_account_type_id',
                '=',
                'pat.id'
            )
            ->leftJoin('users AS u', 'accounts.created_by', '=', 'u.id')
                                ->where('accounts.business_id', $business_id)
                                ->select(['accounts.name', 'accounts.account_number', 'accounts.note', 'accounts.id', 'accounts.account_type_id',
                                    'ats.name as account_type_name',
                                    'pat.name as parent_account_type_name',
                                    'is_closed', DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance"),
                                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by")
                                    ])
                                ->groupBy('accounts.id');

            $is_closed = request()->input('account_status') == 'closed' ? 1 : 0;
            $accounts->where('is_closed', $is_closed);

            return DataTables::of($accounts)
                            ->addColumn(
                                'action',
                                '<button data-href="{{action(\'AccountController@edit\',[$id])}}" data-container=".account_model" class="btn btn-xs btn-primary btn-modal"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                                <a href="{{action(\'AccountController@show\',[$id])}}" class="btn btn-warning btn-xs"><i class="fa fa-book"></i> @lang("account.account_book")</a>&nbsp;
                                @if($is_closed == 0)
                                <button data-href="{{action(\'AccountController@getFundTransfer\',[$id])}}" class="btn btn-xs btn-info btn-modal" data-container=".view_modal"><i class="fa fa-exchange"></i> @lang("account.fund_transfer")</button>

                                <button data-href="{{action(\'AccountController@getDeposit\',[$id])}}" class="btn btn-xs btn-success btn-modal" data-container=".view_modal"><i class="fas fa-money-bill-alt"></i> @lang("account.deposit")</button>

                                <button data-url="{{action(\'AccountController@close\',[$id])}}" class="btn btn-xs btn-danger close_account"><i class="fa fa-power-off"></i> @lang("messages.close")</button>
                                @elseif($is_closed == 1)
                                    <button data-url="{{action(\'AccountController@activate\',[$id])}}" class="btn btn-xs btn-success activate_account"><i class="fa fa-power-off"></i> @lang("messages.activate")</button>
                                @endif'
                            )
                            ->editColumn('name', function ($row) {
                                if ($row->is_closed == 1) {
                                    return $row->name . ' <small class="label pull-right bg-red no-print">' . __("account.closed") . '</small><span class="print_section">(' . __("account.closed") . ')</span>';
                                } else {
                                    return $row->name;
                                }
                            })
                            ->editColumn('balance', function ($row) {
                                return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                            })
                            ->editColumn('account_type', function ($row) {
                                $account_type = '';
                                if (!empty($row->account_type->parent_account)) {
                                    $account_type .= $row->account_type->parent_account->name . ' - ';
                                }
                                if (!empty($row->account_type)) {
                                    $account_type .= $row->account_type->name;
                                }
                                return $account_type;
                            })
                            ->editColumn('parent_account_type_name', function ($row) {
                                $parent_account_type_name = empty($row->parent_account_type_name) ? $row->account_type_name : $row->parent_account_type_name;
                                return $parent_account_type_name;
                            })
                            ->editColumn('account_type_name', function ($row) {
                                $account_type_name = empty($row->parent_account_type_name) ? '' : $row->account_type_name;
                                return $account_type_name;
                            })
                            ->removeColumn('id')
                            ->removeColumn('is_closed')
                            ->rawColumns(['action', 'balance', 'name'])
                            ->make(true);
        }

        $not_linked_payments = TransactionPayment::leftjoin(
            'transactions as T',
            'transaction_payments.transaction_id',
            '=',
            'T.id'
        )
                                    ->whereNull('transaction_payments.parent_id')
                                    ->where('transaction_payments.business_id', $business_id)
                                    ->whereNull('account_id')
                                    ->count();

        // $capital_account_count = Account::where('business_id', $business_id)
        //                             ->NotClosed()
        //                             ->where('account_type', 'capital')
        //                             ->count();

        $account_types = AccountType::where('business_id', $business_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();

        return view('account.index')
                ->with(compact('not_linked_payments', 'account_types'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        $account_types = AccountType::where('business_id', $business_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();

        return view('account.create')
                ->with(compact('account_types'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'account_number', 'note', 'account_type_id']);
                $business_id = $request->session()->get('user.business_id');
                $user_id = $request->session()->get('user.id');
                $input['business_id'] = $business_id;
                $input['created_by'] = $user_id;
               
                $account = Account::create($input);

                //Opening Balance
                $opening_bal = $request->input('opening_balance');

                if (!empty($opening_bal)) {
                    $ob_transaction_data = [
                        'amount' =>$this->commonUtil->num_uf($opening_bal),
                        'account_id' => $account->id,
                        'type' => 'credit',
                        'sub_type' => 'opening_balance',
                        'operation_date' => \Carbon::now(),
                        'created_by' => $user_id
                    ];

                    AccountTransaction::createAccountTransaction($ob_transaction_data);
                }
                
                $output = ['success' => true,
                            'msg' => __("account.account_created_success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                            ];
            }

            return $output;
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $accounts = AccountTransaction::join(
                'accounts as A',
                'account_transactions.account_id',
                '=',
                'A.id'
            )
            ->leftJoin('transaction_payments AS tp', 'account_transactions.transaction_payment_id', '=', 'tp.id')
            ->leftJoin('users AS u', 'account_transactions.created_by', '=', 'u.id')
            ->leftJoin('contacts AS c', 'tp.payment_for', '=', 'c.id')
                            ->where('A.business_id', $business_id)
                            ->where('A.id', $id)
                            ->with(['transaction', 'transaction.contact', 'transfer_transaction', 'media', 'transfer_transaction.media'])
                            ->select(['account_transactions.type', 'account_transactions.amount', 'operation_date',
                                'sub_type', 'transfer_transaction_id',
                                DB::raw('(SELECT SUM(IF(AT.type="credit", AT.amount, -1 * AT.amount)) from account_transactions as AT WHERE AT.operation_date <= account_transactions.operation_date AND AT.account_id  =account_transactions.account_id AND AT.deleted_at IS NULL AND AT.id <= account_transactions.id) as balance'),
                                'account_transactions.transaction_id',
                                'account_transactions.id',
                                'account_transactions.note',
                                'tp.is_advance',
                                'tp.payment_ref_no',
                                'c.name as payment_for',
                                DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by")
                                ])
                             ->groupBy('account_transactions.id')
                             ->orderBy('account_transactions.id', 'asc')
                             ->orderBy('account_transactions.operation_date', 'asc');
            if (!empty(request()->input('type'))) {
                $accounts->where('account_transactions.type', request()->input('type'));
            }

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            
            if (!empty($start_date) && !empty($end_date)) {
                $accounts->whereBetween(DB::raw('date(operation_date)'), [$start_date, $end_date]);
            }

            return DataTables::of($accounts)
                            ->addColumn('debit', function ($row) {
                                if ($row->type == 'debit') {
                                    return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                                }
                                return '';
                            })
                            ->addColumn('credit', function ($row) {
                                if ($row->type == 'credit') {
                                    return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                                }
                                return '';
                            })
                            ->editColumn('balance', function ($row) {
                                return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                            })
                            ->editColumn('operation_date', function ($row) {
                                return $this->commonUtil->format_date($row->operation_date, true);
                            })
                            ->editColumn('sub_type', function ($row) {
                                return $this->__getPaymentDetails($row);
                            })
                            ->editColumn('action', function ($row) {
                                $action = '';
                                if ($row->sub_type == 'fund_transfer' || $row->sub_type == 'deposit') {
                                    $action = '<button type="button" class="btn btn-danger btn-xs delete_account_transaction" data-href="' . action('AccountController@destroyAccountTransaction', [$row->id]) . '"><i class="fa fa-trash"></i> ' . __('messages.delete') . '</button>';
                                }

                                if (!empty($row->media->first()) || (!empty($row->transfer_transaction && !empty($row->transfer_transaction->media->first()) ))) {
                                    $display_url = !empty($row->media->first()) ? $row->media->first()->display_url : $row->transfer_transaction->media->first()->display_url;

                                    $display_name = !empty($row->media->first()) ? $row->media->first()->display_name : $row->transfer_transaction->media->first()->display_name;

                                    $action .= '&nbsp; <a class="btn btn-success btn-xs" href="' . $display_url . '" download="' . $display_name . '"><i class="fa fa-download"></i> ' . __('purchase.download_document') . '</a>';
                                }
                                return $action;
                            })
                            ->removeColumn('id')
                            ->removeColumn('is_closed')
                            ->rawColumns(['credit', 'debit', 'balance', 'sub_type', 'action'])
                            ->make(true);
        }
        $account = Account::where('business_id', $business_id)
                        ->with(['account_type', 'account_type.parent_account'])
                        ->findOrFail($id);

        return view('account.show')
                ->with(compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $account = Account::where('business_id', $business_id)
                                ->find($id);

            $account_types = AccountType::where('business_id', $business_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();
           
            return view('account.edit')
                ->with(compact('account', 'account_types'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'account_number', 'note', 'account_type_id']);

                $business_id = request()->session()->get('user.business_id');
                $account = Account::where('business_id', $business_id)
                                                    ->findOrFail($id);
                $account->name = $input['name'];
                $account->account_number = $input['account_number'];
                $account->note = $input['note'];
                $account->account_type_id = $input['account_type_id'];
                $account->save();

                $output = ['success' => true,
                                'msg' => __("account.account_updated_success")
                                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroyAccountTransaction($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $account_transaction = AccountTransaction::findOrFail($id);
                
                if (in_array($account_transaction->sub_type, ['fund_transfer', 'deposit'])) {
                    //Delete transfer transaction for fund transfer
                    if (!empty($account_transaction->transfer_transaction_id)) {
                        $transfer_transaction = AccountTransaction::findOrFail($account_transaction->transfer_transaction_id);
                        $transfer_transaction->delete();
                    }
                    $account_transaction->delete();
                }

                $output = ['success' => true,
                            'msg' => __("lang_v1.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Closes the specified account.
     * @return Response
     */
    public function close($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $business_id = session()->get('user.business_id');
            
                $account = Account::where('business_id', $business_id)
                                                    ->findOrFail($id);
                $account->is_closed = 1;
                $account->save();

                $output = ['success' => true,
                                    'msg' => __("account.account_closed_success")
                                    ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }

    /**
     * Shows form to transfer fund.
     * @param  int $id
     * @return Response
     */
    public function getFundTransfer($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            $business_id = session()->get('user.business_id');
            
            $from_account = Account::where('business_id', $business_id)
                            ->NotClosed()
                            ->find($id);

            $to_accounts = Account::where('business_id', $business_id)
                            ->where('id', '!=', $id)
                            ->NotClosed()
                            ->pluck('name', 'id');

            return view('account.transfer')
                ->with(compact('from_account', 'to_accounts'));
        }
    }

    /**
     * Transfers fund from one account to another.
     * @return Response
     */
    public function postFundTransfer(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $business_id = session()->get('user.business_id');

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $from = $request->input('from_account');
            $to = $request->input('to_account');
            $note = $request->input('note');
            if (!empty($amount)) {
                $debit_data = [
                    'amount' => $amount,
                    'account_id' => $from,
                    'type' => 'debit',
                    'sub_type' => 'fund_transfer',
                    'created_by' => session()->get('user.id'),
                    'note' => $note,
                    'transfer_account_id' => $to,
                    'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                ];

                DB::beginTransaction();
                $debit = AccountTransaction::createAccountTransaction($debit_data);

                $credit_data = [
                        'amount' => $amount,
                        'account_id' => $to,
                        'type' => 'credit',
                        'sub_type' => 'fund_transfer',
                        'created_by' => session()->get('user.id'),
                        'note' => $note,
                        'transfer_account_id' => $from,
                        'transfer_transaction_id' => $debit->id,
                        'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                    ];

                $credit = AccountTransaction::createAccountTransaction($credit_data);

                $debit->transfer_transaction_id = $credit->id;
                $debit->save();

                Media::uploadMedia($business_id, $debit, $request, 'document');

                DB::commit();
            }
            
            $output = ['success' => true,
                                'msg' => __("account.fund_transfered_success")
                                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("messages.something_went_wrong")
                    ];
        }

        return redirect()->action('AccountController@index')->with('status', $output);
    }

    /**
     * Shows deposit form.
     * @param  int $id
     * @return Response
     */
    public function getDeposit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            $business_id = session()->get('user.business_id');
            
            $account = Account::where('business_id', $business_id)
                            ->NotClosed()
                            ->find($id);

            $from_accounts = Account::where('business_id', $business_id)
                            ->where('id', '!=', $id)
                            // ->where('account_type', 'capital')
                            ->NotClosed()
                            ->pluck('name', 'id');

            return view('account.deposit')
                ->with(compact('account', 'account', 'from_accounts'));
        }
    }

    /**
     * Deposits amount.
     * @param  Request $request
     * @return json
     */
    public function postDeposit(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = session()->get('user.business_id');

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $account_id = $request->input('account_id');
            $note = $request->input('note');

            $account = Account::where('business_id', $business_id)
                            ->findOrFail($account_id);

            if (!empty($amount)) {
                $credit_data = [
                    'amount' => $amount,
                    'account_id' => $account_id,
                    'type' => 'credit',
                    'sub_type' => 'deposit',
                    'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                    'created_by' => session()->get('user.id'),
                    'note' => $note
                ];
                $credit = AccountTransaction::createAccountTransaction($credit_data);

                $from_account = $request->input('from_account');
                if (!empty($from_account)) {
                    $debit_data = $credit_data;
                    $debit_data['type'] = 'debit';
                    $debit_data['account_id'] = $from_account;
                    $debit_data['transfer_transaction_id'] = $credit->id;

                    $debit = AccountTransaction::createAccountTransaction($debit_data);

                    $credit->transfer_transaction_id = $debit->id;

                    $credit->save();
                }
            }
            
            $output = ['success' => true,
                                'msg' => __("account.deposited_successfully")
                                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("messages.something_went_wrong")
                    ];
        }

        return $output;
    }

    /**
     * Calculates account current balance.
     * @param  int $id
     * @return json
     */
    public function getAccountBalance($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = session()->get('user.business_id');
        $account = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
            ->whereNull('AT.deleted_at')
            ->where('accounts.business_id', $business_id)
            ->where('accounts.id', $id)
            ->select('accounts.*', DB::raw("SUM( IF(AT.type='credit', amount, -1 * amount) ) as balance"))
            ->first();

        return $account;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function cashFlow()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $accounts = AccountTransaction::join(
                'accounts as A',
                'account_transactions.account_id',
                '=',
                'A.id'
                )
                ->leftjoin(
                    'transaction_payments as TP',
                    'account_transactions.transaction_payment_id',
                    '=',
                    'TP.id'
                )
                ->where('A.business_id', $business_id)
                ->with(['transaction', 'transaction.contact', 'transfer_transaction'])
                ->select(['type', 'account_transactions.amount', 'operation_date',
                    'sub_type', 'transfer_transaction_id',
                    DB::raw("(SELECT SUM(IF(AT.type='credit', AT.amount, -1 * AT.amount)) from account_transactions as AT JOIN accounts as ac ON ac.id=AT.account_id WHERE ac.business_id= $business_id AND AT.operation_date <= account_transactions.operation_date AND AT.deleted_at IS NULL) as balance"),
                    'account_transactions.transaction_id',
                    'account_transactions.id',
                    'A.name as account_name',
                    'TP.payment_ref_no as payment_ref_no'
                    ])
                 ->groupBy('account_transactions.id')
                 ->orderBy('account_transactions.operation_date', 'desc');
            if (!empty(request()->input('type'))) {
                $accounts->where('type', request()->input('type'));
            }

            if (!empty(request()->input('account_id'))) {
                $accounts->where('A.id', request()->input('account_id'));
            }

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            
            if (!empty($start_date) && !empty($end_date)) {
                $accounts->whereBetween(DB::raw('date(operation_date)'), [$start_date, $end_date]);
            }

            return DataTables::of($accounts)
                ->addColumn('debit', function ($row) {
                    if ($row->type == 'debit') {
                        return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                    }
                    return '';
                })
                ->addColumn('credit', function ($row) {
                    if ($row->type == 'credit') {
                        return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                    }
                    return '';
                })
                ->editColumn('balance', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                })
                ->editColumn('operation_date', function ($row) {
                    return $this->commonUtil->format_date($row->operation_date, true);
                })
                ->editColumn('sub_type', function ($row) {
                    return $this->__getPaymentDetails($row);
                })
                ->removeColumn('id')
                ->rawColumns(['credit', 'debit', 'balance', 'sub_type'])
                ->make(true);
        }
        $accounts = Account::forDropdown($business_id, false);
                            
        return view('account.cash_flow')
                 ->with(compact('accounts'));
    }

    public function __getPaymentDetails($row)
    {
        $details = '';
        if (!empty($row->sub_type)) {
            $details = __('account.' . $row->sub_type);
            if (in_array($row->sub_type, ['fund_transfer', 'deposit']) && !empty($row->transfer_transaction)) {
                if ($row->type == 'credit') {
                    $details .= ' ( ' . __('account.from') .': ' . $row->transfer_transaction->account->name . ')';
                } else {
                    $details .= ' ( ' . __('account.to') .': ' . $row->transfer_transaction->account->name . ')';
                }
            }
        } else {
            if (!empty($row->transaction->type)) {
                if ($row->transaction->type == 'purchase') {
                    $details = __('lang_v1.purchase') . '<br><b>' . __('purchase.supplier') . ':</b> ' . $row->transaction->contact->name . '<br><b>'.
                    __('purchase.ref_no') . ':</b> <a href="#" data-href="' . action("PurchaseController@show", [$row->transaction->id]) . '" class="btn-modal" data-container=".view_modal">' . $row->transaction->ref_no . '</a>';
                }elseif ($row->transaction->type == 'expense') {
                    $details = __('lang_v1.expense') . '<br><b>' . __('purchase.ref_no') . ':</b>' . $row->transaction->ref_no;
                } elseif ($row->transaction->type == 'sell') {
                    $details = __('sale.sale') . '<br><b>' . __('contact.customer') . ':</b> ' . $row->transaction->contact->name . '<br><b>'.
                    __('sale.invoice_no') . ':</b> <a href="#" data-href="' . action("SellController@show", [$row->transaction->id]) . '" class="btn-modal" data-container=".view_modal">' . $row->transaction->invoice_no . '</a>';
                }
            }
        }

        if (!empty($row->payment_ref_no)) {
            if (!empty($details)) {
                $details .= '<br/>';
            }

            $details .= '<b>' . __('lang_v1.pay_reference_no') . ':</b> ' . $row->payment_ref_no;
        }
        if (!empty($row->payment_for)) {
            if (!empty($details)) {
                $details .= '<br/>';
            }

            $details .= '<b>' . __('account.payment_for') . ':</b> ' . $row->payment_for;
        }

        if ($row->is_advance == 1) {
            $details .= '<br>(' . __('lang_v1.advance_payment') . ')';
        }

        return $details;
    }

    /**
     * activate the specified account.
     * @return Response
     */
    public function activate($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $business_id = session()->get('user.business_id');
            
                $account = Account::where('business_id', $business_id)
                                ->findOrFail($id);

                $account->is_closed = 0;
                $account->save();

                $output = ['success' => true,
                        'msg' => __("lang_v1.success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }
}
