<template>
	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">
					<i class="fa fa-fw fa-home"></i>
				</a>
			</li>
			<li class="breadcrumb-item active">Enrollments</li>
		</ol>

		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom mb-0">
					<ul class="nav nav-tabs">
						<li class="ml-3">
							<a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Pending</a>
						</li>
						<li class>
							<a href="#tab_2" data-toggle="tab" aria-expanded="false" class>Approved</a>
						</li>
						<li class>
							<a href="#tab_3" data-toggle="tab" aria-expanded="false" class>Declined</a>
						</li>
					</ul>
				</div>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<div class="row">
							<div class="col-md-12">
								<div class="card course-card">
									<div class="card-body text-center">
										<div v-if="pendingEnrollments.length > 0">
											<table class="table table-deep table-responsive">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col">First Name</th>
														<th scope="col">Last Name</th>
														<th scope="col">Course</th>
														<th scope="col">Date Applied</th>
														<th scope="col">status</th>
														<th scope="col"></th>
													</tr>
												</thead>

												<tbody>
													<tr v-for="enrollment in pendingPageItems" :key="enrollment.id">
														<td
															scope="row"
															@click="showStudentProfile(enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{enrollment.student.user.first_name}}</td>
														<td scope="row">{{enrollment.student.user.last_name}}</td>
														<td scope="row" v-if="enrollment.course">{{ enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small>(course deleted)</small>
														</td>
														<td scope="row">{{ enrollment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{enrollment.status}}</td>
														<td scope="row">
															<button
																class="btn btn-edit"
																data-toggle="modal"
																data-target="#enrollmentModal"
																@click="showMore(enrollment)"
															>
																<i class="fa fa-edit"></i>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="pendingEnrollments"
												@changePage="onChangePendingPage"
												:key="pendingPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!pendingEnrollmentsLoading">no pending enrollments</p>
											<p v-else>loading...</p>
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab_2">
						<div class="row">
							<div class="col-md-12">
								<div class="card course-card">
									<div class="card-body text-center">
										<div v-if="approvedEnrollments.length > 0">
											<table class="table table-deep table-responsive">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col"></th>
														<th scope="col">First Name</th>
														<th scope="col">Last Name</th>
														<th scope="col">Course</th>
														<th scope="col">Date Applied</th>
														<th scope="col">Date Approved</th>
														<th scope="col">Status</th>
														<th scope="col">View</th>
													</tr>
												</thead>

												<tbody>
													<tr v-for="(enrollment,index) in approvedPageItems" :key="enrollment.id">
														<td scope="row">{{index +1}}</td>
														<td
															scope="row"
															@click="showStudentProfile(enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{enrollment.student.user.first_name}}</td>
														<td scope="row">{{enrollment.student.user.last_name}}</td>
														<td scope="row" v-if="enrollment.course">{{ enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small>(course deleted)</small>
														</td>
														<td scope="row">{{ enrollment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ enrollment.updated_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{enrollment.status}}</td>
														<td scope="row">
															<button
																class="btn btn-edit"
																data-toggle="modal"
																data-target="#enrollmentModal-v"
																@click="showMore(enrollment)"
															>
																<i class="fa fa-eye"></i>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="approvedEnrollments"
												@changePage="onChangeApprovedPage"
												:key="approvedPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!approvedEnrollmentsLoading">no approved enrollments</p>
											<p v-else>loading...</p>
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab_3">
						<div class="row">
							<div class="col-md-12">
								<div class="card course-card">
									<div class="card-body text-center">
										<div v-if="declinedEnrollments.length > 0">
											<table class="table table-deep table-responsive">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col"></th>
														<th scope="col">First Name</th>
														<th scope="col">Last Name</th>
														<th scope="col">Course</th>
														<th scope="col">Date Applied</th>
														<th scope="col">Date Approved</th>
														<th scope="col">status</th>
														<th scope="col">View</th>
													</tr>
												</thead>

												<tbody>
													<tr
														v-for="(enrollment,index) in declinedPageItems"
														:key="enrollment.id"
														class="clickable"
														@click="showMore(enrollment)"
													>
														<td scope="row">{{index+1}}</td>
														<td
															scope="row"
															@click="showStudentProfile(enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{enrollment.student.user.first_name}}</td>
														<td scope="row">{{enrollment.student.user.last_name}}</td>
														<td scope="row" v-if="enrollment.course">{{ enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small>(course deleted)</small>
														</td>
														<td scope="row">{{ enrollment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ enrollment.updated_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{enrollment.status}}</td>
														<td scope="row">
															<button
																class="btn btn-edit"
																data-toggle="modal"
																data-target="#enrollmentModal-v"
																@click="showMore(enrollment)"
															>
																<i class="fa fa-eye"></i>
															</button>
														</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="declinedEnrollments"
												@changePage="onChangeDeclinedPage"
												:key="declinedPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!declinedEnrollmentsLoading">no declined enrollment</p>
											<p v-else>loading...</p>
										</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div
			class="modal fade course-full-detail"
			id="enrollmentModal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="enrollmentModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content" v-if="enrollmentShow">
					<div class="modal-header p-0 border-bottom-0">
						<img :src="clickedEnrollment.course.banner | fullPath" style="min-width:100%; height:300px;" />
						<div class="price">{{formatter.format(clickedEnrollment.course.price)}}</div>
						<div class="title">{{clickedEnrollment.course.title}}</div>
						<button
							type="button"
							class="close position-absolute"
							data-dismiss="modal"
							aria-label="Close"
							style="right:10px;"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<section>
							<h5 class="section-header">Description</h5>
							<p>{{clickedEnrollment.course.description }}</p>
						</section>
						<section>
							<h5 class="section-header">Duration</h5>
							<p>The course would run from the {{ clickedEnrollment.course.start_date | moment("dddd, MMMM Do YYYY") }} to {{ clickedEnrollment.course.end_date | moment("dddd, MMMM Do YYYY") }}</p>
						</section>
						<section>
							<h5 class="section-header">Requirements</h5>
							<ul
								class="bullets requirement-bullets"
								v-if="Object.keys(clickedEnrollment.course.requirement).length > 0"
							>
								<li v-for="i in Object.keys(clickedEnrollment.course.requirement)" :key="i">
									<b>{{clickedEnrollment.course.requirement[i].title}}</b>
									<br />
									{{clickedEnrollment.course.requirement[i].text}}
								</li>
							</ul>
							<ul class="bullets requirement-bullets" v-else>
								<li>NO requirement for this course</li>
							</ul>
						</section>
						<section>
							<h5 class="section-header">Applicant filled Requirements</h5>

							<table class="table">
								<tr>
									<th scope="col">description</th>
									<th scope="col">Submission</th>
								</tr>
								<tr v-for="i in Object.keys(clickedEnrollment.requirement_uploads)" :key="i">
									<td scope="row">{{i}}</td>
									<td scope="row" v-if="clickedEnrollment.requirement_uploads[i].type === 'file'">
										<a :href="clickedEnrollment.requirement_uploads[i].file | fullPath" target="_blank">View</a>
									</td>
									<td
										scope="row"
										v-if="clickedEnrollment.requirement_uploads[i].type === 'text'"
									>{{clickedEnrollment.requirement_uploads[i].response}}</td>
									<td
										scope="row"
										v-if="clickedEnrollment.requirement_uploads[i].type === 'check'"
									>{{(clickedEnrollment.requirement_uploads[i].response == "on")?"checked":"unchecked"}}</td>
								</tr>
							</table>
						</section>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-dismiss-modal" data-dismiss="modal">Close</button>
						<button
							type="button"
							class="btn btn-primary"
							v-if="$root.role === 2"
							@click="approve(clickedEnrollment)"
						>Approve</button>
						<button
							type="button"
							class="btn btn-danger"
							v-if="$root.role === 2"
							@click="decline(clickedEnrollment)"
						>Decline</button>
					</div>
				</div>
			</div>
		</div>

		<ProfileModal
			:student-profile="studentProfile"
			:clicked-student="clickedStudent"
			:showOperation="false"
		></ProfileModal>

		<div
			class="modal fade course-full-detail"
			id="enrollmentModal-v"
			tabindex="-1"
			role="dialog"
			aria-labelledby="enrollmentModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content" v-if="enrollmentShow">
					<div class="modal-header p-0 border-bottom-0">
						<img :src="clickedEnrollment.course.banner | fullPath" style="min-width:100%; height:300px;" />
						<div class="price">{{formatter.format(clickedEnrollment.course.price)}}</div>
						<div class="title">{{clickedEnrollment.course.title}}</div>
						<button
							type="button"
							class="close position-absolute"
							data-dismiss="modal"
							aria-label="Close"
							style="right:10px;"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<section>
							<h5 class="section-header">Description</h5>
							<p>{{clickedEnrollment.course.description }}</p>
						</section>
						<section>
							<h5 class="section-header">Duration</h5>
							<p>The course would run from the {{ clickedEnrollment.course.start_date | moment("dddd, MMMM Do YYYY") }} to {{ clickedEnrollment.course.end_date | moment("dddd, MMMM Do YYYY") }}</p>
						</section>
						<!-- <section>
             <h5 class="section-header">Requirements</h5>
             <ul class="bullets requirement-bullets" v-if="Object.keys(clickedEnrollment.course.requirement).length > 0">
                 <li v-for="i in Object.keys(clickedEnrollment.course.requirement)" :key="i" > <b>{{i}}</b> {{clickedEnrollment.course.requirement[i]}} </li>
             </ul>
             <ul class="bullets requirement-bullets" v-else>
                 <li> NO requirement for this course</li>
             </ul>
						</section>-->
						<!-- <section>
            <h5 class="section-header">Uploaded Requirements</h5>

            <table class="table">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">requirement</th>
                    <th scope="col">User upload</th>
                </tr>
                <tr v-for="i in Object.keys(clickedEnrollment.requirement_uploads)" :key="i">
                    <td scope="row">{{i}}</td>
                    <td scope="row">{{ clickedEnrollment.course.requirement[i] }}</td>
                    <td scope="row"><a :href="clickedEnrollment.requirement_uploads[i] | fullPath" target="_blank">View</a></td>
                </tr>
            </table>
						</section>-->
						<section>
							<h5 class="section-header">Applicant filled Requirements</h5>

							<table class="table">
								<tr>
									<th scope="col">description</th>
									<th scope="col">Submission</th>
								</tr>
								<tr v-for="i in Object.keys(clickedEnrollment.requirement_uploads)" :key="i">
									<td scope="row">{{i}}</td>
									<td scope="row" v-if="clickedEnrollment.requirement_uploads[i].type === 'file'">
										<a :href="clickedEnrollment.requirement_uploads[i].file | fullPath" target="_blank">View</a>
									</td>
									<td
										scope="row"
										v-if="clickedEnrollment.requirement_uploads[i].type === 'text'"
									>{{clickedEnrollment.requirement_uploads[i].response}}</td>
									<td
										scope="row"
										v-if="clickedEnrollment.requirement_uploads[i].type === 'check'"
									>{{(clickedEnrollment.requirement_uploads[i].response == "on")?"checked":"unchecked"}}</td>
								</tr>
							</table>
						</section>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-dismiss-modal" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { enrollmentMixin } from "../mixins/enrollmentMixin";
export default {
	mixins: [enrollmentMixin],
	data() {
		return {
			pendingEnrollments: [],
			approvedEnrollments: [],
			declinedEnrollments: [],
			approvedPageKey: 0,
			approvedPageItems: [],
			declinedPageKey: 0,
			declinedPageItems: [],
			pendingPageKey: 0,
			pendingPageItems: [],
			pendingEnrollmentsLoading: true,
			approvedEnrollmentsLoading: true,
			declinedEnrollmentsLoading: true,
			clickedStudent: { profile_picture_url: "ola" },
			enrollmentShow: false,
			studentProfile: false,
			clickedEnrollment: { name: "ola" }
		};
	},
	methods: {
		onChangeApprovedPage(pageItems) {
			this.approvedPageItems = pageItems;
		},
		onChangeDeclinedPage(pageItems) {
			this.declinedPageItems = pageItems;
		},
		onChangePendingPage(pageItems) {
			this.pendingPageItems = pageItems;
		},
		fetchEnrollments() {
			axios.get("/api/v1/admin/enrollment").then(data => {
				let enrollments = data.data.data;
				this.pendingEnrollments = enrollments.filter(enrollment => {
					return enrollment.status == "pending";
				});
				this.approvedEnrollments = enrollments.filter(enrollment => {
					return (
						enrollment.status == "approved" || enrollment.status == "completed"
					);
				});
				this.declinedEnrollments = enrollments.filter(enrollment => {
					return enrollment.status == "declined";
				});
				this.pendingEnrollmentsLoading = false;
				this.approvedEnrollmentsLoading = false;
				this.declinedEnrollmentsLoading = false;
				this.declinedPageKey += 1;
				this.approvedPageKey += 1;
				this.pendingPageKey += 1;
			});
		},
		print(v) {
			// console.log(v);
		},
		approve(enrollment) {
			NProgress.start();
			Fire.$emit("pageBusy");
			axios
				.patch("/api/v1/admin/enrollment/" + enrollment.id, {
					status: "approved"
				})
				.then(data => {
					if (data.data.status == "success") {
						this.showSuccess(
							"Enrollment Approved",
							"Payment Details would be sent to student immediately"
						);
						Fire.$emit("enrollmentStatusChanged");
						document.getElementsByClassName("btn-dismiss-modal")[0].click();
						NProgress.done();
						Fire.$emit("pageFree");
					} else {
						NProgress.done();
						Fire.$emit("pageFree");
						this.showError();
					}
				})
				.catch(data => {
					this.showError();
					NProgress.done();
					Fire.$emit("pageFree");
				});
		},
		decline(enrollment) {
			Swal.fire({
				title: "Are you sure?",
				text: "You won't be able to revert this!",
				type: "warning",
				input: "text",
				inputPlaceholder: "Give reason for decliining application",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, decline aplication",
				allowOutsideClick: false,
				inputValidator: value => {
					if (value.length < 3) {
						return "You need to provide a reason!";
					}
				}
			}).then(result => {
				if (result.value) {
					NProgress.start();
					Fire.$emit("pageBusy");
					axios
						.patch("/api/v1/admin/enrollment/" + enrollment.id, {
							status: "declined",
							admin_note: result.value
						})
						.then(data => {
							if (data.data.status == "success") {
								this.showSuccess(
									"Enrollment Declined",
									"Student would be notified via mail"
								);
								Fire.$emit("enrollmentStatusChanged");
								document.getElementsByClassName("btn-dismiss-modal")[0].click();
								NProgress.done();
								Fire.$emit("pageFree");
							} else {
								NProgress.done();
								Fire.$emit("pageFree");
								this.showError();
							}
						})
						.catch(data => {
							NProgress.done();
							NProgress.remove();
							Fire.$emit("pageFree");
							this.showError();
						});
				}
			});
		},
		showStudentProfile(student) {
			this.clickedStudent = student;
			this.studentProfile = true;
		}
	},
	created() {},
	mounted() {
		this.fetchEnrollments();
		Fire.$on("enrollmentStatusChanged", () => {
			this.fetchEnrollments();
		});
	}
};
</script>

<style>
</style>
