<?php

namespace App\Domains\Voucher\Http\Controllers\Admin;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Faq\Enum\FaqTypes;
use App\Domains\Voucher\Enum\VoucherUsageStatus;
use App\Domains\Voucher\Exports\VouchersExport;
use App\Domains\Voucher\Models\Voucher;
use App\Domains\Voucher\Rules\VoucherPermission;
use App\Foundation\Http\Controllers\Admin\CoreController;
use ColumnTypes;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Enum\PayableType;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\Voucher\Jobs\Admin\CreateVoucherJob;
use DB;

class VoucherController extends CoreController
{
    protected $domain = 'voucher';

    public function __construct(Voucher $model)
    {
        $this->model = $model;
        $this->searchColumn = ["name", "voucher", "user_id"];
        $this->select_columns = [

            [
                'name' => trans("Name"),

                'key'  => 'name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Voucher"),
                'key'  => 'voucher',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Subscription Package"),
                'key'  => function (Voucher $row) {
                    return $row->payable->name ?? '';
                },
                'type' => ColumnTypes::CALLBACK,
            ],

            [
                'name' => trans("Expiry Date"),
                'key'  => 'expired_at',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Access Duration(Days)"),
                'key'  => 'days',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name'         => trans("Access Type"),
                'key'          => function ($row) {
                    if ($row->access_type == SubscribeStatus::TRIAL) {
                        return '
                        <span style="" class="badge badge-warning">
                            Trial
                        </span>
                        ';
                    }
                    if ($row->access_type == SubscribeStatus::ACTIVE) {
                        return '
                        <span style="" class="badge badge-success">
                            Paid
                        </span>
                        ';
                    }
                },
                'type'         => ColumnTypes::CALLBACK,
            ],

            [
                'name' => trans(" User Email"),
                'key'  => function ($row) {

                    $user = DB::table('user_subscriptions')
                        ->join('vouchers', 'vouchers.voucher', '=', 'user_subscriptions.subscription_id')
                        ->join('users', 'users.id', '=', 'user_subscriptions.user_id')
                        ->select('users.email')
                        ->where('user_subscriptions.subscription_id', '=', $row->voucher)
                        ->first();

                    return $user->email ?? null;
                },

                'type' => ColumnTypes::CALLBACK,
            ],

            [
                'name' => trans("Tags"),
                'key'  => function ($row) {
                    $tags = "";
                    if (is_array($row->tags))
                        $tags = implode(",", $row->tags);
                    return $tags;
                },
                'type' => ColumnTypes::CALLBACK,
            ],

            [
                'name'         => trans("Status"),
                'key'          => 'is_used',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    VoucherUsageStatus::PENDING => [
                        'text'  => trans('Pending'),
                        'class' => 'badge badge-warning',
                    ],
                    VoucherUsageStatus::USED    => [
                        'text'  => trans('Used'),
                        'class' => 'badge badge-success',
                    ],
                ]
            ]

        ];
        //        VoucherPermission
        $this->permitted_actions = [
            'index'  => VoucherPermission::VOUCHER_INDEX,
            'create' => VoucherPermission::VOUCHER_CREATE,
            'edit'   => null,
            'show'   => null,
            'delete' => VoucherPermission::VOUCHER_DELETE,
        ];
        $this->sharedViews();
        parent::__construct();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            'name'            => ['required'],
            'expired_at'      => ['required', 'date'],
            'days'            => ['required', 'min:1', 'integer'],
            'number_vouchers' => ['required', 'min:1', 'integer'],
            'payable_id'      => ['required'],
            'tags'            => 'required|array',

        ]);

        if ($this->request->payable_type == PayableType::SUBSCRIPTION) {
            $this->request->request->add(['payable_id' => $this->request->payable_id]);
        }

        if ($this->request->payable_type == PayableType::MICRODEGREE) {
            $this->request->request->add(['payable_id' => $this->request->micro_degree_id]);
        }
    }

    public function store()
    {
        $this->ifMethodExistCallIt('onStore');

        $admin = auth('admin')->user();

        $this->serve(CreateVoucherJob::class, [
            'request' => $this->request->all(),
            'admin'   => $admin,
        ]);

        return redirect('/admin/voucher')->with('success', 'Once Voucher has been created, you will receive an email with the voucher codes');
    }

    private function sharedViews()
    {
        $package_subscription_lists = PackageSubscription::pluck('name', 'id');
        $micro_degrees_list = Course::Microdegrees()->get()->pluck('internal_name_or_name', 'id');
        $certifications_list = Course::CourseCertification()->get()->pluck('internal_name_or_name', 'id');
        $micro_degrees_list = collect($micro_degrees_list)->merge($certifications_list);
        $result = Voucher::whereNotNull('tags')->get(['tags']);

        $all_tags = [];

        foreach ($result as $key => $value) {
            foreach ($value->tags as $k => $v) {
                array_push($all_tags, $v);
            }
        }

        $tags_list = array_unique(array_values($all_tags));
        $tags_list = array_combine($tags_list, $tags_list);
        $tags_list['NoContact'] = 'NoContact';

        return view()->share(compact('package_subscription_lists', 'micro_degrees_list', 'tags_list'));
    }

    public function callbackQuery($query)
    {

        if ($this->request->search) {

            $query->ORwhereRaw('json_contains(tags, \'["' . $this->request->search . '"]\')');
        }

        return $query;
    }
}
