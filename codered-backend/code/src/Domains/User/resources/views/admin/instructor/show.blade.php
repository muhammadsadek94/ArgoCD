@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
	@include('admin.layouts.breadcrumb', [
		'page_title' => end($breadcrumb)->title,
		'crumbs' => $breadcrumb
	])
@endsection


@section('form')
	<div class="row">
		<div class="col-lg-4 col-xl-4">
			<div class="card-box text-center">
				@if($row->image)
					<img src="{{ url($row->image->full_url) }}" class="rounded-circle avatar-lg img-thumbnail"
						 alt="profile-image">
				@else
					<img src="{{ url('assets/imgs/default-profile.png') }}"
						 class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
				@endif
				<h4 class="mb-0">{{ $row->first_name }}</h4>
				<p class="text-muted">{{ $row->instructor_profile->designation }} @ {{
					$row->instructor_profile->current_employer }}</p>
				<p class="text-muted mb-0">{{ $row->instructor_profile->years_experience }} Years Experience</p>

				{{--				<button type="button" class="btn btn-success btn-xs waves-effect mb-2 waves-light">Follow</button>--}}
				{{--				<button type="button" class="btn btn-danger btn-xs waves-effect mb-2 waves-light">Message</button>--}}

				<div class="text-left mt-3">
					<h4 class="font-13 text-uppercase">About Me :</h4>
					<p class="text-muted font-13 mb-3">
						{{ $row->instructor_profile->profile_summary }}
					</p>
					<p class="text-muted mb-2 font-13">
						<strong>Full Name :</strong>
						<span class="ml-2">{{ $row->first_name }} {{ $row->last_name }}</span>
					</p>

					<p class="text-muted mb-2 font-13">
						<strong>Mobile :</strong>
						<span class="ml-2">{{ $row->phone }}</span>
					</p>

					<p class="text-muted mb-2 font-13">
						<strong>Email :</strong>
						<span class="ml-2 ">{{ $row->email }}</span>
					</p>
					<p class="text-muted mb-2 font-13">
						<strong>CV :</strong>
						<span class="ml-2 ">
							@if($row->instructor_profile->cv)
								<a href="{{ getSasBlob($row->instructor_profile->cv->path,$row->instructor_profile->cv->container)}}" download="{{$row->first_name}}">Download CV</a>
							@else
								Not found
							@endif
						</span>
					</p>
					<p class="text-muted mb-2 font-13">
						<strong>Video Sample :</strong>
						<span class="ml-2 ">
							@if($row->instructor_profile->video_sample)
								<a href="{{ getSasBlob($row->instructor_profile->video_sample->path,$row->instructor_profile->video_sample->container)}}}}" download="{{$row->first_name}}">Download Video Sample</a>
							@else
								Not found
							@endif
						</span>
					</p>



					{{--					<p class="text-muted mb-1 font-13"><strong>Location :</strong> <span class="ml-2">USA</span></p>--}}
				</div>

				<ul class="social-list list-inline mt-3 mb-0">
					@if($row->instructor_profile->github_url)
						<li class="list-inline-item">
							<a href="{{ $row->instructor_profile->github_url }}" target="_blank"
							   class="social-list-item border-secondary text-secondary">
								<i class="mdi mdi-github-circle"></i>
							</a>
						</li>
					@endif
					@if($row->instructor_profile->linkedin_url)
						<li class="list-inline-item">
							<a href="{{ $row->instructor_profile->linkedin_url }}" target="_blank"
							   class="social-list-item border-primary text-primary">
								<i class="mdi mdi-linkedin"></i>
							</a>
						</li>
					@endif
					@if($row->instructor_profile->blog_url)
						<li class="list-inline-item">
							<a href="{{ $row->instructor_profile->blog_url }}" target="_blank"
							   class="social-list-item border-info text-info">
								<i class="mdi mdi-web"></i>
							</a>
						</li>
					@endif
					@if($row->instructor_profile->article_url)
						<li class="list-inline-item">
							<a href="{{ $row->instructor_profile->article_url }}" target="_blank"
							   class="social-list-item border-danger text-danger">
								<i class="mdi mdi-blogger"></i>
							</a>
						</li>
					@endif


				</ul>
			</div> <!-- end card-box -->

		</div> <!-- end col-->

		<div class="col-lg-8 col-xl-8">
			<div class="card-box">
				<ul class="nav nav-pills navtab-bg nav-justified">
					<li class="nav-item">
						<a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
							About Me
						</a>
					</li>
					{{--					<li class="nav-item">--}}
					{{--						<a href="#timeline" data-toggle="tab" aria-expanded="true" class="nav-link">--}}
					{{--							Timeline--}}
					{{--						</a>--}}
					{{--					</li>--}}
					<li class="nav-item">
						<a href="{{ url("{$route}/{$row->id}/edit") }}" target="_blank" aria-expanded="false"
						   class="nav-link">
							Edit
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane show active" id="aboutme">

						<h5 class="mb-4 text-uppercase"><i class="mdi mdi-briefcase mr-1"></i>
							Experience</h5>

						<ul class="list-unstyled timeline-sm">
							<li class="timeline-sm-item">
								<span class="timeline-sm-date">Previous <br> Experience</span>
								<h5 class="mt-0 mb-1"> Have you create a course before
									? {{ $row->instructor_profile->have_courses ? 'Yes' : 'No' }}</h5>
								@if($row->instructor_profile->have_courses)
									<p class="text-muted mt-2">{{ $row->instructor_profile->course_information }}</p>
								@endif

								<h5 class="mt-0 mb-1"> Do you have trending course
									? {{ $row->instructor_profile->have_trending_course ? 'Yes' : 'No' }}</h5>
								@if($row->instructor_profile->have_trending_course)
									<p class="text-muted mt-2">{{ $row->instructor_profile->trending_course_description }}</p>
								@endif

							</li>
							<li class="timeline-sm-item">
								<span class="timeline-sm-date">Content Type</span>
								<h5 class="mt-0 mb-1">What would you be interested in created for EC-Council Learning?</h5>
								<p></p>
								<p class="text-muted mt-2">
									<label>
										<input disabled type="checkbox"
											   value="1" {{ $row->instructor_profile->interested_video ? 'checked' : '' }}>
										Video Course
									</label>
									<label>
										<input disabled type="checkbox"
											   value="1" {{ $row->instructor_profile->interested_assessments ? 'checked' : '' }}>
										Assessments
									</label>
									<label>
										<input disabled type="checkbox"
											   value="1" {{ $row->instructor_profile->interested_written_materials ? 'checked' : '' }}>
										Written Materials
									</label>

								</p>
							</li>

							<li class="timeline-sm-item">
								<span class="timeline-sm-date">Audience</span>
								<h5 class="mt-0 mb-1">Proposed Course Topics / Modules? </h5>
								<p class="text-muted mt-2 mb-0">{{ $row->instructor_profile->trending_course_topic }}</p>
								<hr>
								<h5 class="mt-0 mb-1">Target Audience? </h5>
								<p class="text-muted mt-2 mb-0">{{ $row->instructor_profile->trending_course_target_audience }}</p>
							</li>
						</ul>

						{{--						<h5 class="mb-3 mt-4 text-uppercase">--}}
						{{--							<i class="mdi mdi-cards-variant mr-1"></i>--}}
						{{--							Projects3--}}
						{{--						</h5>--}}
						{{--						<div class="table-responsive">--}}
						{{--							<table class="table table-borderless mb-0">--}}
						{{--								<thead class="thead-light">--}}
						{{--								<tr>--}}
						{{--									<th>#</th>--}}
						{{--									<th>Project Name</th>--}}
						{{--									<th>Start Date</th>--}}
						{{--									<th>Due Date</th>--}}
						{{--									<th>Status</th>--}}
						{{--									<th>Clients</th>--}}
						{{--								</tr>--}}
						{{--								</thead>--}}
						{{--								<tbody>--}}
						{{--								<tr>--}}
						{{--									<td>1</td>--}}
						{{--									<td>App design and development</td>--}}
						{{--									<td>01/01/2015</td>--}}
						{{--									<td>10/15/2018</td>--}}
						{{--									<td><span class="badge badge-info">Work in Progress</span></td>--}}
						{{--									<td>Halette Boivin</td>--}}
						{{--								</tr>--}}
						{{--								<tr>--}}
						{{--									<td>2</td>--}}
						{{--									<td>Coffee detail page - Main Page</td>--}}
						{{--									<td>21/07/2016</td>--}}
						{{--									<td>12/05/2018</td>--}}
						{{--									<td><span class="badge badge-success">Pending</span></td>--}}
						{{--									<td>Durandana Jolicoeur</td>--}}
						{{--								</tr>--}}
						{{--								<tr>--}}
						{{--									<td>3</td>--}}
						{{--									<td>Poster illustation design</td>--}}
						{{--									<td>18/03/2018</td>--}}
						{{--									<td>28/09/2018</td>--}}
						{{--									<td><span class="badge badge-pink">Done</span></td>--}}
						{{--									<td>Lucas Sabourin</td>--}}
						{{--								</tr>--}}
						{{--								<tr>--}}
						{{--									<td>4</td>--}}
						{{--									<td>Drinking bottle graphics</td>--}}
						{{--									<td>02/10/2017</td>--}}
						{{--									<td>07/05/2018</td>--}}
						{{--									<td><span class="badge badge-blue">Work in Progress</span></td>--}}
						{{--									<td>Donatien Brunelle</td>--}}
						{{--								</tr>--}}
						{{--								<tr>--}}
						{{--									<td>5</td>--}}
						{{--									<td>Landing page design - Home</td>--}}
						{{--									<td>17/01/2017</td>--}}
						{{--									<td>25/05/2021</td>--}}
						{{--									<td><span class="badge badge-warning">Coming soon</span></td>--}}
						{{--									<td>Karel Auberjo</td>--}}
						{{--								</tr>--}}
						{{--								--}}
						{{--								</tbody>--}}
						{{--							</table>--}}
						{{--						</div>--}}

					</div> <!-- end tab-pane -->
					<!-- end about me section content -->

					<div class="tab-pane" id="timeline">

						<!-- comment box -->
						<form action="#" class="comment-area-box mt-2 mb-3">
                                                <span class="input-icon">
                                                    <textarea rows="3" class="form-control"
															  placeholder="Write something..."></textarea>
                                                </span>
							<div class="comment-area-btn">
								<div class="float-right">
									<button type="submit" class="btn btn-sm btn-dark waves-effect waves-light">Post
									</button>
								</div>
								<div>
									<a href="#" class="btn btn-sm btn-light text-black-50 shadow-none"><i
												class="far fa-user"></i></a>
									<a href="#" class="btn btn-sm btn-light text-black-50 shadow-none"><i
												class="fa fa-map-marker-alt"></i></a>
									<a href="#" class="btn btn-sm btn-light text-black-50 shadow-none"><i
												class="fa fa-camera"></i></a>
									<a href="#" class="btn btn-sm btn-light text-black-50 shadow-none"><i
												class="far fa-smile"></i></a>
								</div>
							</div>
						</form>
						<!-- end comment box -->

						<!-- Story Box-->
						<div class="border border-light p-2 mb-3">
							<div class="media">
								<img class="mr-2 avatar-sm rounded-circle" src="assets/images/users/user-3.jpg"
									 alt="Generic placeholder image">
								<div class="media-body">
									<h5 class="m-0">Jeremy Tomlinson</h5>
									<p class="text-muted"><small>about 2 minuts ago</small></p>
								</div>
							</div>
							<p>Story based around the idea of time lapse, animation to post soon!</p>

							<img src="assets/images/small/img-1.jpg" alt="post-img" class="rounded mr-1"
								 height="60"/>
							<img src="assets/images/small/img-2.jpg" alt="post-img" class="rounded mr-1"
								 height="60"/>
							<img src="assets/images/small/img-3.jpg" alt="post-img" class="rounded"
								 height="60"/>

							<div class="mt-2">
								<a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
											class="mdi mdi-reply"></i> Reply</a>
								<a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
											class="mdi mdi-heart-outline"></i> Like</a>
								<a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
											class="mdi mdi-share-variant"></i> Share</a>
							</div>
						</div>

						<!-- Story Box-->
						<div class="border border-light p-2 mb-3">
							<div class="media">
								<img class="mr-2 avatar-sm rounded-circle" src="assets/images/users/user-4.jpg"
									 alt="Generic placeholder image">
								<div class="media-body">
									<h5 class="m-0">Thelma Fridley</h5>
									<p class="text-muted"><small>about 1 hour ago</small></p>
								</div>
							</div>
							<div class="font-16 text-center font-italic text-dark">
								<i class="mdi mdi-format-quote-open font-20"></i> Cras sit amet nibh libero, in
								gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras
								purus odio, vestibulum in vulputate at, tempus viverra turpis. Duis
								sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper
								porta. Mauris massa.
							</div>

							<div class="post-user-comment-box">
								<div class="media">
									<img class="mr-2 avatar-sm rounded-circle" src="assets/images/users/user-3.jpg"
										 alt="Generic placeholder image">
									<div class="media-body">
										<h5 class="mt-0">Jeremy Tomlinson <small class="text-muted">3 hours ago</small>
										</h5>
										Nice work, makes me think of The Money Pit.

										<br/>
										<a href="javascript: void(0);" class="text-muted font-13 d-inline-block mt-2"><i
													class="mdi mdi-reply"></i> Reply</a>

										<div class="media mt-3">
											<a class="pr-2" href="#">
												<img src="assets/images/users/user-4.jpg"
													 class="avatar-sm rounded-circle"
													 alt="Generic placeholder image">
											</a>
											<div class="media-body">
												<h5 class="mt-0">Kathleen Thomas <small class="text-muted">5 hours
														ago</small></h5>
												i'm in the middle of a timelapse animation myself! (Very different
												though.) Awesome stuff.
											</div>
										</div>
									</div>
								</div>

								<div class="media mt-2">
									<a class="pr-2" href="#">
										<img src="assets/images/users/user-1.jpg" class="rounded-circle"
											 alt="Generic placeholder image" height="31">
									</a>
									<div class="media-body">
										<input type="text" id="simpleinput"
											   class="form-control border-0 form-control-sm" placeholder="Add comment">
									</div>
								</div>
							</div>

							<div class="mt-2">
								<a href="javascript: void(0);" class="btn btn-sm btn-link text-danger"><i
											class="mdi mdi-heart"></i> Like (28)</a>
								<a href="javascript: void(0);" class="btn btn-sm btn-link text-muted"><i
											class="mdi mdi-share-variant"></i> Share</a>
							</div>
						</div>

						<!-- Story Box-->
						<div class="border border-light p-2 mb-3">
							<div class="media">
								<img class="mr-2 avatar-sm rounded-circle" src="assets/images/users/user-6.jpg"
									 alt="Generic placeholder image">
								<div class="media-body">
									<h5 class="m-0">Jeremy Tomlinson</h5>
									<p class="text-muted"><small>15 hours ago</small></p>
								</div>
							</div>
							<p>The parallax is a little odd but O.o that house build is awesome!!</p>

							<iframe src='https://player.vimeo.com/video/87993762' height='300'
									class="img-fluid border-0"></iframe>
						</div>

						<div class="text-center">
							<a href="javascript:void(0);" class="text-danger"><i
										class="mdi mdi-spin mdi-loading mr-1"></i> Load more </a>
						</div>

					</div>
					<!-- end timeline content-->


				</div> <!-- end tab-content -->
			</div> <!-- end card-box-->

		</div> <!-- end col -->
	</div>

@endsection

