<template>
	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">
					<i class="fa fa-fw fa-home"></i>
				</a>
			</li>
			<li class="breadcrumb-item active">Account</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<!-- Custom Tabs -->
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="ml-3">
							<a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Profile</a>
						</li>
						<li class>
							<a href="#tab_2" data-toggle="tab" aria-expanded="false">Change Password</a>
						</li>
					</ul>
					<div class="tab-content pl-3">
						<div class="tab-pane active" id="tab_1">
							<div class="box_general padding_bottom">
								<div class="header_box version_2">
									<h2>
										<i class="fa fa-user"></i>Profile details
									</h2>
								</div>
								<div class="row profile-details">
									<div class="col-md-8 add_top_30">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header" @click="showPersonalInfo = !showPersonalInfo">
														<h5 class="d-inline">Personal Information</h5>
														<span class="float-right clickable">
															<i class="fa fa-plus" v-if="!showPersonalInfo"></i>
															<i class="fa fa-minus" v-else></i>
														</span>
													</div>

													<div class="card-body" v-show="showPersonalInfo">
														<form @submit.prevent="updatePersonalInfo()">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Title</label>
																		<select
																			name="title"
																			id="title"
																			class="form-control"
																			required
																			v-model="personalInfo.title"
																		>
																			<option value>Select title</option>
																			<option value="Miss">Miss</option>
																			<option value="Master">Master</option>
																			<option value="Mrs">Mrs.</option>
																			<option value="Mr">Mr.</option>
																		</select>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Last Name</label>
																		<input
																			type="text"
																			class="form-control"
																			name="surname"
																			id="surname"
																			placeholder="Surname"
																			v-model="personalInfo.surname"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>First Name</label>
																		<input
																			type="text"
																			class="form-control"
																			name="firstname"
																			id="firstname"
																			placeholder="Firstname"
																			v-model="personalInfo.firstname"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Middle Name</label>
																		<input
																			type="text"
																			class="form-control"
																			name="middlename"
																			id="middlename"
																			placeholder="Middlename"
																			required
																			v-model="personalInfo.middlename"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Date of Birth</label>
																		<input
																			type="date"
																			class="form-control"
																			name="dob"
																			id="dob"
																			placeholder="Date of Birth"
																			required
																			v-model="personalInfo.dob"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Nationality</label>
																		<!-- <input
																			type="text"
																			class="form-control"
																			name="pob"
																			id="pob"
																			placeholder="Place of Birth"
																			required
																			v-model="personalInfo.pob"
																		/>-->
																		<select
																			name="pob"
																			class="form-control crs-country"
																			data-default-option="Nationality"
																			data-preferred="NG,GH"
																			v-model="personalInfo.pob"
																		></select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label
																			for="country-r"
																			v-if="rCountryS"
																			class="fomt-sm"
																		>Country of residence - {{rCountryS}}</label>
																		<label
																			for="country-r"
																			class="font-sm"
																			v-else
																		>You haven't set your country of residence</label>
																		<select
																			name="pob"
																			id="countr-r"
																			class="form-control crs-country"
																			data-region-id="rcountry-crs"
																			data-default-option="Select country of residence"
																			data-preferred="NG,GH"
																			v-model="personalInfo.rCountry"
																		></select>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label for="rcountry-crs" v-if="rStateS">State of residence - {{rStateS}}</label>
																		<label for="rcountry-crs" v-else>You haven't set your state of residence</label>
																		<select
																			name="pob"
																			id="rcountry-crs"
																			class="form-control"
																			data-default-option="Select state of residence"
																			v-model="personalInfo.rState"
																			:data-value="personalInfo.rState"
																		></select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Street Address</label>
																		<textarea
																			name="rAddress"
																			placeholder="Residential Address"
																			id="rAddress"
																			cols="30"
																			rows="2"
																			required
																			class="form-control"
																			v-model="personalInfo.rAddress"
																		></textarea>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Mailing Address</label>
																		<textarea
																			name="mAddress"
																			placeholder="Mailing Address"
																			id="mAddress"
																			cols="30"
																			rows="2"
																			required
																			class="form-control"
																			v-model="personalInfo.mAddress"
																		></textarea>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Work Phone</label>
																		<input
																			type="tel"
																			name="workPhone"
																			id="workPhone"
																			placeholder="Work Phone"
																			value
																			class="form-control"
																			v-model="personalInfo.workPhone"
																		/>
																	</div>
																</div>

																<div class="col-md-6">
																	<div class="form-group">
																		<label>Mobile Phone</label>
																		<input
																			type="tel"
																			name="mobilePhone"
																			id="mobilePhone"
																			placeholder="Mobile Phone"
																			required
																			class="form-control"
																			v-model="personalInfo.mobilePhone"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Email</label>
																		<input
																			type="email"
																			name="email"
																			id="email"
																			placeholder="Email Address"
																			readonly
																			value
																			class="form-control"
																			v-model="personalInfo.email"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<button type="submit" class="btn btn-apply float-right">Save changes</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header" @click="showEmergencyInfo = !showEmergencyInfo">
														<h5 class="d-inline">Emergency Information</h5>
														<span class="float-right clickable">
															<i class="fa fa-plus" v-if="!showEmergencyInfo"></i>
															<i class="fa fa-minus" v-else></i>
														</span>
													</div>
													<div class="card-body" v-show="showEmergencyInfo">
														<form @submit.prevent="updateEmergencyInfo()">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Full Name</label>
																		<input
																			type="text"
																			name="emergency_name"
																			placeholder="Name"
																			class="form-control"
																			v-model="this.emergencyInfo.name"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Relationship</label>
																		<input
																			type="text"
																			name="emergency_relationship"
																			placeholder="Relationship"
																			required
																			class="form-control"
																			v-model="this.emergencyInfo.relationship"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Phone Number</label>
																		<input
																			type="tel"
																			name="emergency_number"
																			placeholder="Phone Number"
																			required
																			class="form-control"
																			v-model="this.emergencyInfo.mobile_phone"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Email Address</label>
																		<input
																			type="email"
																			name="emergency_email"
																			placeholder="Email Address"
																			required
																			class="form-control"
																			v-model="this.emergencyInfo.email"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<button type="submit" class="btn btn-apply float-right">Save changes</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header" @click="showReferenceInfo = !showReferenceInfo ">
														<h5 class="d-inline">Reference(s) Information</h5>
														<span class="float-right clickable">
															<i class="fa fa-plus" v-if="!showReferenceInfo "></i>
															<i class="fa fa-minus" v-else></i>
														</span>
													</div>
													<div class="card-body mt-2" v-show="showReferenceInfo">
														<form @submit.prevent="updateReferenceInfo()">
															<h5 class="text-center">Reference 1</h5>
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Full Name</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_name1"
																			placeholder="Full Name"
																			required
																			v-model="referenceInfo.name1"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Relationship</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_relationship1"
																			placeholder="Relationship"
																			required
																			v-model="referenceInfo.name2"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Phone Number</label>
																		<input
																			type="number"
																			class="form-control"
																			name="ref_number1"
																			placeholder="Address"
																			required
																			v-model="referenceInfo.number1"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Email</label>
																		<input
																			type="email"
																			class="form-control"
																			name="ref_email1"
																			placeholder="Email Address"
																			required
																			v-model="referenceInfo.email1"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Address</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_address1"
																			placeholder="Address"
																			required
																			v-model="referenceInfo.address1"
																		/>
																	</div>
																</div>
															</div>

															<h5 class="text-center mt-4">Reference 2</h5>
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Full Name</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_name2"
																			placeholder="Full Name"
																			required
																			v-model="referenceInfo.name2"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Relationship</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_relationship2"
																			placeholder="Relationship"
																			required
																			v-model="referenceInfo.relationship2"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Phone Number</label>
																		<input
																			type="number"
																			class="form-control"
																			name="ref_number2"
																			placeholder="Address"
																			required
																			v-model="referenceInfo.number2"
																		/>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Email</label>
																		<input
																			type="email"
																			class="form-control"
																			name="ref_email2"
																			placeholder="Email Address"
																			required
																			v-model="referenceInfo.email2"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Address</label>
																		<input
																			type="text"
																			class="form-control"
																			name="ref_address2"
																			placeholder="Address"
																			required
																			v-model="referenceInfo.address2"
																		/>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12">
																	<button type="submit" class="btn btn-apply float-right">Save changes</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header" @click="showAlumniInfo = !showAlumniInfo">
														<h5 class="d-inline">Alumni Information</h5>
														<span class="float-right clickable">
															<i class="fa fa-plus" v-if="!showAlumniInfo"></i>
															<i class="fa fa-minus" v-else></i>
														</span>
													</div>
													<div class="card-body" v-show="showAlumniInfo">
														<table class="table">
															<thead>
																<tr>
																	<td>Course</td>
																	<td>Date</td>
																</tr>
															</thead>
															<tbody>
																<tr v-for="i in alumniInfo.course.length" :key="i">
																	<td>{{alumniInfo.course[i]}}</td>
																	<td>{{alumniInfo.date[i]}}</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>

										<!-- /row-->

										<!-- /row-->

										<!-- /row-->
									</div>

									<div class="col-md-4 d-flex justify-content-center">
										<div class="form-group align-self-center text-center">
											<label>Passport Photograph</label>
											<div class="profile-picture">
												<div
													class="w-100 h-100 hidden"
													id="passportProcessingLoader"
													style="
    position: absolute;
    z-index: 100;
    left: -0;
    background-color: #607d8b99;
    padding-top: 100px;
    color: black;"
												>please wait...</div>
												<img
													:src="user.student.profile_picture_url | fullPath"
													id="uploadedImg"
													style="display:block; border-radius:4px"
													class="w-100 h-100"
												/>
											</div>
											<input
												type="file"
												class="custom-file-input passport-upload hidden"
												name="passport"
												id="passportUpload"
												@change="updatePassport"
												accept=".jpg, .png, .jpeg"
											/>

											<button class="btn btn-apply mt-4" @click="clickUploadInput()">Update Passport</button>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<div class="row py-4 justify-content-center">
								<div class="col-md-6">
									<div class="box_general padding_bottom">
										<form action @submit.prevent="updatePassword()">
											<div class="header_box version_2">
												<h2>
													<i class="fa fa-lock"></i>Change password
												</h2>
											</div>
											<div class="form-group">
												<label>Old password</label>
												<input
													class="form-control"
													type="password"
													name="oldPassword"
													id="oldPassword"
													v-model="passwordForm.password"
													required
													:class="{ 'is-invalid': passwordForm.errors.has('password') }"
												/>
												<has-error :form="passwordForm" field="password"></has-error>
											</div>
											<div class="form-group">
												<label>New password</label>
												<input
													class="form-control"
													type="password"
													name="newPassword"
													v-model="passwordForm.newPassword"
													id="newPassword"
													required
													:class="{ 'is-invalid': passwordForm.errors.has('newPassword') }"
												/>
												<has-error :form="passwordForm" field="newPassword"></has-error>
											</div>
											<div class="form-group">
												<label>Confirm new password</label>
												<input
													class="form-control"
													type="password"
													name="cNewPassword"
													id="cNewPassword"
													required
													v-model="passwordForm.confirmPassword"
													:class="{ 'is-invalid': passwordForm.errors.has('confirmPassword') }"
												/>
												<has-error :form="passwordForm" field="confirmPassword"></has-error>
											</div>
											<div class="form-group">
												<button class="btn btn-apply" type="submit">Update Password</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- /.tab-pane -->

						<!-- /.tab-pane -->
					</div>
					<!-- /.tab-content -->
				</div>
				<!-- nav-tabs-custom -->
			</div>
		</div>
	</div>
</template>

<script>
import crs from "../../lib/crs";
import { enrollmentMixin, isNullOrUndefined } from "../mixins/enrollmentMixin";
export default {
	mixins: [enrollmentMixin],
	data() {
		return {
			showPersonalInfo: false,
			showEmergencyInfo: false,
			showReferenceInfo: false,
			showAlumniInfo: false,
			rStateS: null,
			rCountryS: null,
			user: {
				student: {
					profile_picture_url: ""
				}
			},
			personalInfo: "",
			alumniInfo: {
				course: [],
				date: []
			},
			referenceInfo: "",
			emergencyInfo: "",
			passwordForm: new Form({
				section: "password",
				password: "",
				newPassword: "",
				confirmPassword: ""
			})
		};
	},
	methods: {
		getUser() {
			axios.get("/api/v1/user/student").then(data => {
				this.user = data.data.data[0];
				this.personalInfo = new Form({
					section: "personalInfo",
					title: this.user.student.title,
					surname: this.user.last_name,
					firstname: this.user.first_name,
					middlename: this.user.midlle_name,
					pob: this.user.student.place_of_birth,
					rCountry: this.user.student.residential_country,
					rState: this.user.student.residential_state,
					dob: this.user.student.date_of_birth,
					rAddress: this.user.student.residential_address,
					mAddress: this.user.student.mailing_address,
					workPhone: this.user.student.work_phone,
					mobilePhone: this.user.student.mobile_phone,
					email: this.user.email
				});

				this.rStateS = this.personalInfo.rState;
				this.rCountryS = this.personalInfo.rCountry;

				this.referenceInfo = new Form({
					section: "referenceInfo",
					name1: this.user.student.ref_name1,
					name2: this.user.student.ref_name2,
					address1: this.user.student.ref_address1,
					address2: this.user.student.ref_address2,
					relationship1: this.user.student.ref_relationship1,
					relationship2: this.user.student.ref_relationship2,
					number1: this.user.student.ref_number1,
					number2: this.user.student.ref_number2,
					email1: this.user.student.ref_email1,
					email2: this.user.student.ref_email2
				});

				this.emergencyInfo = new Form({
					section: "emergencyInfo",
					name: this.user.student.emergency_name,
					email: this.user.student.emergency_email,
					mobile_phone: this.user.student.emergency_mobile_phone,
					relationship: this.user.student.emergency_relationship
				});

				this.alumniInfo = {
					course: JSON.parse(this.user.student.alumni_course),
					date: JSON.parse(this.user.student.alumni_date)
				};
			});
		},
		clickUploadInput() {
			document.getElementById("passportUpload").click();
			return true;
		},
		updatePassword() {
			this.updateInfo("passwordForm");
		},
		updatePassport() {
			let supportedFormat = ["image/jpeg", "image/jpg", "image/png"];
			let passport = document.getElementById("passportUpload").files[0];
			const reachMaxSize = passport.size / 1048576 > 1;
			if (supportedFormat.includes(passport.type) && !reachMaxSize) {
				document
					.getElementById("passportProcessingLoader")
					.classList.remove("hidden");
				let reader = new FileReader();
				reader.onloadend = function() {
					document
						.getElementById("uploadedImg")
						.setAttribute("src", reader.result);
					let newPassport = {
						name: "passport",
						data: reader.result,
						type: passport.type
					};
					axios
						.patch("/api/v1/user/student/profile/picture", {
							passport: newPassport
						})
						.then(function(data) {
							console.log(data);
							document
								.getElementById("passportProcessingLoader")
								.classList.add("hidden");
						})
						.catch(function(data) {
							console.log(data);
							document
								.getElementById("passportProcessingLoader")
								.classList.add("hidden");
						});
				};
				reader.readAsDataURL(passport);
			} else {
				alert("invalid file, max file size 1mb");
			}
		},
		updatePersonalInfo() {
			this.updateInfo("personalInfo");
		},
		updateReferenceInfo() {
			this.updateInfo("referenceInfo");
		},
		updateEmergencyInfo() {
			this.updateInfo("emergencyInfo");
		},
		updateInfo($info) {
			Fire.$emit("pageBusy");
			console.log($info);
			this[$info]
				.patch("/api/v1/user/student/profile")
				.then(function(data) {
					if (data.data.status) {
						Toast.fire({
							type: "success",
							title: "Profile update successfully"
						});
					} else {
						Swal.fire({
							title: "Error!",
							text: "Error occured while performing operation",
							type: "error",
							confirmButtonText: "Continue"
						});
						console.log("data");
						Fire.$emit("pageFree");
					}
					Fire.$emit("pageFree");
				})
				.catch(function(data) {
					Swal.fire({
						title: "Error!",
						text: "Error occured while performing operation",
						type: "error",
						confirmButtonText: "Continue"
					});
					console.log("data");
					Fire.$emit("pageFree");
				});
		},
		showError() {
			Swal.fire({
				title: "Error!",
				text: "Error occured while performing operation",
				type: "error",
				confirmButtonText: "Continue"
			});
		}
	},
	created() {
		this.getUser();
		crs.init();
	},
	mounted() {}
};
</script>

<style>
</style>
