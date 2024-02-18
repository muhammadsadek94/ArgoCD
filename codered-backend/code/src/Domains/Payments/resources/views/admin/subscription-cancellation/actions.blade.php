@permitted([\App\Domains\Payments\Rules\SubscriptionCancellationPermission::SUBSCRIPTION_CANCELLATION_EDIT])

    @if($row->status != \App\Domains\Payments\Enum\SubscriptionCancellationRequestsStatus::CANCELLED)
        <a href="{{ url("{$route}/actions/{$row->id}/cancel") }}"
           data-toggle="tooltip" data-placement="top" title="Cancelled Subscription"
           onclick="return confirm('Do you sure mark this request as cancelled?')"
           class="btn btn-icon btn-sm mr-1">
            <i class="fas fa-check"></i>
        </a>
    @else
        <p class="text-black-50">No actions available</p>
    @endif

@endpermitted
