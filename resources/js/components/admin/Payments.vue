<template>
	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">
					<i class="fa fa-fw fa-home"></i>
				</a>
			</li>
			<li class="breadcrumb-item active">Payment</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom mb-0">
					<ul class="nav nav-tabs">
						<li class="ml-3">
							<a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Pending Approval</a>
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
										<div v-if="nonValidatedPayments.length > 0">
											<table class="table table-responsive table-deep w-100">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col"></th>
														<th scope="col">Student</th>
														<th scope="col">Course</th>
														<th scope="col">Price</th>
														<th scope="col">Date Uploaded</th>
														<th scope="col">Status</th>
														<th scope="col">Slip</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(payment,index) in pendingPageItems" :key="payment.id" class="clickable">
														<td scope="row">{{index+1}}</td>
														<td
															scope="row"
															@click="showStudentProfile(payment.enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="payment.enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{payment.enrollment.student.user | fullName}}</td>
														<td scope="row" v-if="payment.enrollment.course">{{ payment.enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small class="deletedText">(course deleted)</small>
														</td>
														<td scope="row">{{ formatter.format(payment.enrollment.course.price) }}</td>
														<td scope="row">{{ payment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ payment.validated | isValidated() }}</td>
														<td
															scope="row"
															class="clickable"
															@click="viewPayment(payment)"
															data-toggle="modal"
															data-target="#paymentModal-v"
														>View</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="nonValidatedPayments"
												@changePage="onChangePendingPage"
												:key="pendingPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!paymentLoading">no payment is awiting validation</p>
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
										<div v-if="validatedPayments.length > 0">
											<table class="table table-responsive table-deep">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col"></th>
														<th scope="col">Student</th>
														<th scope="col">Course</th>
														<th scope="col">Uploaded on</th>
														<th scope="col">Approved on</th>
														<th scope="col">Status</th>
														<th scope="col">Slip</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(payment, index) in approvedPageItems" :key="payment.id" class="clickable">
														<td scope="row">{{index+1}}</td>
														<td
															scope="row"
															@click="showStudentProfile(payment.enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="payment.enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{payment.enrollment.student.user | fullName}}</td>
														<td scope="row" v-if="payment.enrollment.course">{{ payment.enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small class="deletedText">(course deleted)</small>
														</td>
														<td scope="row">{{ payment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ payment.updated_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ payment.validated | isValidated() }}</td>
														<td scope="row">
															<a :href="payment.slip_upload | fullPath" target="_blank">View</a>
														</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="validatedPayments"
												@changePage="onChangeApprovedPage"
												:key="approvedPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!paymentLoading">no validated payment</p>
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
										<div v-if="declinedPayments.length > 0">
											<table class="table table-deep table-responsive">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col"></th>
														<th scope="col">Student</th>
														<th scope="col">Course</th>
														<th scope="col">Date Uploaded</th>
														<th scope="col">Status</th>
														<th scope="col">Slip</th>
														<th scope="col">Reason</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(payment, index) in declinedPageItems" :key="payment.id" class="clickable">
														<td scope="row">{{index+1}}</td>
														<td
															scope="row"
															@click="showStudentProfile(payment.enrollment.student)"
															data-toggle="modal"
															data-target="#studentProfileModal"
															class="clickable"
														>
															<img
																:src="payment.enrollment.student.profile_picture_url | fullPath"
																alt
																style="width:35px; height:35px; float:right"
																class="circle"
															/>
														</td>
														<td scope="row">{{payment.enrollment.student.user | fullName}}</td>
														<td scope="row" v-if="payment.enrollment.course">{{ payment.enrollment.course.title}}</td>
														<td scope="row" v-else>
															<small class="deletedText">(course deleted)</small>
														</td>
														<td scope="row">{{ payment.created_at | moment("dddd, MMMM Do YYYY") }}</td>
														<td scope="row">{{ payment.validated | isValidated() }}</td>
														<td scope="row">
															<a :href="payment.slip_upload | fullPath" target="_blank">View</a>
														</td>
														<td scope="row">{{ payment.admin_note }}</td>
													</tr>
												</tbody>
											</table>
											<pagination
												:items="declinedPayments"
												@changePage="onChangeDeclinedPage"
												:key="declinedPageKey"
												class="float-right"
											></pagination>
										</div>
										<small v-else>
											<p v-if="!paymentLoading">no declined payment</p>
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

		<PaymentSlipModal></PaymentSlipModal>
		<ProfileModal
			:student-profile="studentProfile"
			:clicked-student="clickedStudent"
			:showOperation="false"
		></ProfileModal>
	</div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined } from "../mixins/enrollmentMixin";
export default {
	mixins: [enrollmentMixin],
	data() {
		return {
			paymentSlip: false,
			paymentLoading: true,
			allPayments: [],
			clickedPayment: {},
			declinedPayments: [],
			validatedPayments: [],
			approvedEnrollments: [],
			nonValidatedPayments: [],
			approvedPageKey: 0,
			approvedPageItems: [],
			declinedPageKey: 0,
			declinedPageItems: [],
			pendingPageKey: 0,
			pendingPageItems: [],
			enrollmentShow: false,
			showCourseDetail: true,
			clickedStudent: { profile_picture_url: "ola" },
			studentProfile: false,
			form: new Form({
				slip_upload: {}
			})
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
		viewPayment(payment) {
			this.clickedPayment = payment;
		},
		showStudentProfile(student) {
			this.clickedStudent = student;
			this.studentProfile = true;
		},
		fetchPayments() {
			axios.get("/api/v1/admin/payments").then(data => {
				this.allPayments = data.data.data;
				this.nonValidatedPayments = this.allPayments.filter(payment => {
					return payment.validated == 0;
				});
				this.declinedPayments = this.allPayments.filter(payment => {
					return payment.validated == 1;
				});
				this.validatedPayments = this.allPayments.filter(payment => {
					return payment.validated == 2;
				});
				this.paymentLoading = false;
				this.declinedPageKey += 1;
				this.approvedPageKey += 1;
				this.pendingPageKey += 1;
			});
		},
		approvePay() {
			let payment = this.clickedPayment;
			Swal.fire({
				title: "Are you sure?",
				text: "You won't be able to revert this!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, Accept payment!",
				allowOutsideClick: false
			}).then(result => {
				if (result.value) {
					NProgress.start();
					Fire.$emit("pageBusy");
					axios
						.patch("/api/v1/admin/payments/" + payment.id, {
							validated: 2
						})
						.then(data => {
							if (data.data.status == "success") {
								Fire.$emit("paymentStatusChanged");
								Swal.fire(
									"Approved!",
									"Student payment has been approved.",
									"success"
								);
								NProgress.done();
								Fire.$emit("pageFree");
							} else {
								this.showError();
								NProgress.remove();
								Fire.$emit("pageFree");
							}
						})
						.catch(error => {
							this.showError();
							NProgress.remove();
							Fire.$emit("pageFree");
						});
				}
			});
		},
		rejectPay() {
			let payment = this.clickedPayment;
			Swal.fire({
				title: "Are you sure?",
				text: "You won't be able to revert this!",
				type: "warning",
				input: "text",
				inputPlaceholder: "Give reason for payment rejection",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, reject payment!",
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
						.patch("/api/v1/admin/payments/" + payment.id, {
							validated: 1,
							adminNote: result.value
						})
						.then(data => {
							if (data.data.status == "success") {
								Fire.$emit("paymentStatusChanged");
								NProgress.done();
								Fire.$emit("pageFree");
								Swal.fire(
									"Successfull!",
									"The payment has been invalidated.",
									"success"
								);
							} else {
								NProgress.done();
								Fire.$emit("pageFree");
							}
						})
						.catch(error => {
							this.showError();
							NProgress.done();
							Fire.$emit("pageFree");
						});
				}
			});
		}
	},
	filters: {
		isValidated: function(value) {
			if (value == 0) {
				return "not validated";
			} else if (value == 1) {
				return "declined";
			}
			return "validated";
		},
		fullName: function(user) {
			return user.first_name + " " + user.last_name;
		}
	},
	mounted() {
		this.fetchPayments();
		Fire.$on("paymentStatusChanged", () => {
			this.fetchPayments();
		});
	},
	created() {}
};
</script>

<style>
</style>
