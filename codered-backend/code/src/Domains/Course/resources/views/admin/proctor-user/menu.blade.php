@permitted([\App\Domains\Course\Rules\ProctorPermission::PROCTOR_INDEX, \App\Domains\Course\Rules\ProctorPermission::PROCTOR_CREATE])

<li>
	<a href="{{ url("{$admin_base_url}/proctor-user") }}">
		<i class="fas fa-user-alt"></i>
		<span> @lang('Proctor Users') </span>
	</a>
</li>

@endpermitted

