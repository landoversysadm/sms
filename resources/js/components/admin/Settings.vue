<template>
	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">
					<i class="fa fa-fw fa-home"></i>
				</a>
			</li>
			<li class="breadcrumb-item active">Settings</li>
		</ol>

		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="ml-3" v-if="$root.role === 2">
							<a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Payment Details</a>
						</li>
						<li class v-if="$root.role === 2">
							<a
								href="#tab_2"
								data-toggle="tab"
								@click="adminForm.password = alphaNumPassword(8)"
								aria-expanded="false"
							>Administrator Accounts</a>
						</li>
						<li class>
							<a href="#tab_3" data-toggle="tab" aria-expanded="false" class>Update Account</a>
						</li>
						<li class>
							<a href="#tab_4" data-toggle="tab" aria-expanded="false" class>Change Password</a>
						</li>
						<li class>
							<a href="#tab_5" data-toggle="tab" aria-expanded="false" class>Manage Requirements</a>
						</li>
            <li class>
							<a href="#tab_6" data-toggle="tab" aria-expanded="false" class>General</a>
						</li>
					</ul>
					<div class="tab-content pl-3">

						<div class="tab-pane active" id="tab_1">
							<div class="box_general padding_bottom">
								<div class="header_box version_2">
									<h2>
										<i class="fa fa-credit-card"></i>Payment details
									</h2>
								</div>
								<form action @submit.prevent="updateBankDetail()">
									<div class="form-group">
										<label>Account Name</label>
										<input
											class="form-control"
											type="text"
											name="accountName"
											id="accountName"
											v-model="bankForm.accountName"
											required
											:class="{ 'is-invalid': bankForm.errors.has('accountName') }"
										/>
										<has-error :form="bankForm" field="accountName"></has-error>
									</div>
									<div class="form-group">
										<label>Account Number</label>
										<input
											class="form-control"
											type="number"
											name="accountNumber"
											id="accountNumber"
											v-model="bankForm.accountNumber"
											required
											:class="{ 'is-invalid': bankForm.errors.has('accountNumber') }"
										/>
										<has-error :form="bankForm" field="accountNumber"></has-error>
									</div>
									<div class="form-group">
										<label>Bank Name</label>
										<input
											class="form-control"
											type="text"
											name="bankName"
											id="bankName"
											v-model="bankForm.bankName"
											required
											:class="{ 'is-invalid': bankForm.errors.has('bankName') }"
										/>
										<has-error :form="bankForm" field="bankName"></has-error>
									</div>
									<div class="form-group">
										<button class="btn btn-apply w-100" :disabled="$root.role != 2">Update Details</button>
									</div>
								</form>
							</div>
						</div>

						<div class="tab-pane" id="tab_2">
							<div class="box_general padding_bottom">
								<div class="header_box version_2">
									<h2>
										<i class="fa fa-users"></i>Administrator Accounts
									</h2>
								</div>
								<div class="row d-flex justify-content-center">
									<div class="table-wrapper col-md-9">
										<table class="table table-responsive table-striped table-light">
											<thead>
												<tr>
													<th>Full Name</th>
													<th>Email</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="admin in admins" :key="admin.length">
													<td
														v-if="admin.middle_name"
													>{{admin.first_name + ' '+admin.midlle_name+' '+admin.last_name}}</td>
													<td v-else>{{admin.first_name + ' '+admin.last_name}}</td>
													<td>{{admin.email}}</td>
													<td>
														<i class="icon-trash-4" v-if="myEmail !== admin.email" @click="deleteAdmin(admin.id)"></i>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<div class="row"></div>
								<div class="row justify-content-center d-flex">
									<div class="col-md-9">
										<form @submit.prevent="newAdmin">
											<div class="form-group">
												<label>First Name</label>
												<input
													class="form-control"
													type="text"
													name="accountName"
													id="firstName"
													v-model="adminForm.firstName"
													required
													:class="{ 'is-invalid': adminForm.errors.has('firstName') }"
												/>
												<has-error :form="adminForm" field="firstName"></has-error>
											</div>
											<div class="form-group">
												<label>Last Name</label>
												<input
													class="form-control"
													type="text"
													name="lastName"
													id="lastName"
													v-model="adminForm.lastName"
													required
													:class="{ 'is-invalid': adminForm.errors.has('lastName') }"
												/>
												<has-error :form="adminForm" field="lastName"></has-error>
											</div>
											<div class="form-group">
												<label>Middle Name</label>
												<input
													class="form-control"
													type="text"
													name="bankName"
													id="bankName"
													v-model="adminForm.middleName"
													required
													:class="{ 'is-invalid': adminForm.errors.has('middleName') }"
												/>
												<has-error :form="adminForm" field="MiddleName"></has-error>
											</div>
											<div class="form-group">
												<label>Email</label>
												<input
													class="form-control"
													type="text"
													name="emaile"
													id="email"
													v-model="adminForm.email"
													required
													:class="{ 'is-invalid': adminForm.errors.has('email') }"
												/>
												<has-error :form="adminForm" field="email"></has-error>
											</div>
											<div class="form-group">
												<label>
													Login Password
													<i @click="toClipboard" class="fa fa-clipboard" role="button"></i>
												</label>
												<input
													type="text"
													autocomplete="false"
													id="password"
													class="form-control"
													ref="autoPassword"
													:class="{ 'is-invalid': adminForm.errors.has('password') }"
													placeholder="Administrator password"
													v-model="adminForm.password"
													disabled
												/>
												<has-error :form="adminForm" field="password"></has-error>
											</div>
											<div class="form-group">
												<label>User Role</label>
												<select class="form-control" name="role" v-model="adminForm.role">
													<option value="4">Normal</option>
													<option value="2">Super</option>
												</select>
												<has-error :form="passwordForm" field="confirmPassword"></has-error>
											</div>
											<div class="form-group">
												<span
													class="form-text"
												>*New administrators are encouraged to change password on first login</span>
											</div>
											<div class="form-group">
												<button class="btn btn-apply w-100" type="submit">Add</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_3">
							<div class="tab-pane active" id="tab_1">
								<div class="box_general padding_bottom">
									<form action @submit.prevent="updatePersonalInfo()">
										<div class="form-group">
											<label>First Name</label>
											<input
												class="form-control"
												type="text"
												name="firstName"
												v-model="updateForm.firstName"
												required
												:class="{ 'is-invalid': updateForm.errors.has('firstName') }"
											/>
											<has-error :form="updateForm" field="firstName"></has-error>
										</div>
										<div class="form-group">
											<label>Last Name</label>
											<input
												class="form-control"
												type="text"
												name="lastName"
												v-model="updateForm.lastName"
												required
												:class="{ 'is-invalid': updateForm.errors.has('lastName') }"
											/>
											<has-error :form="updateForm" field="lastName"></has-error>
										</div>
										<div class="form-group">
											<label>Middle Name</label>
											<input
												class="form-control"
												type="text"
												name="middleName"
												v-model="updateForm.middleName"
												:class="{ 'is-invalid': updateForm.errors.has('middleName') }"
											/>
											<has-error :form="updateForm" field="middleName"></has-error>
										</div>
										<div class="form-group">
											<label>Email</label>
											<input
												class="form-control"
												type="text"
												name="email"
												v-model="updateForm.email"
												required
												:class="{ 'is-invalid': updateForm.errors.has('email') }"
											/>
											<has-error :form="updateForm" field="email"></has-error>
										</div>
										<div class="form-group">
											<button class="btn btn-apply w-100">Update</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab_4">
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

						<div class="tab-pane" id="tab_5">
							<div class="box_general padding_bottom">
								<div class="header_box version_2">
									<h2>
										<i class="fa fa-box"></i>Requiremnents
									</h2>
								</div>
								<div class="row">
									<div class="col-md-6">
										<small v-if="!requirements || requirements.length<1">No Requirements</small>
										<div v-else class="table-wrapper">
											<table class="table table-responsive table-striped table-light">
												<thead>
													<tr>
														<th>Title</th>
														<th>Type</th>
														<th>Text</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(req, i) in requirements" :key="`req${i}`">
														<td>{{req.title}}</td>
														<td>{{req.type}}</td>
														<td>{{req.text}}</td>
														<td>
															<a href="#" @click="deleteReq(req)">
																<i class="fa fa-trash"></i>
															</a>
															<a href="#" @click="editReq(req)">
																<i class="fa fa-edit"></i>
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-md-6">
										<form action @submit.prevent="(edittingReq)?updateReq():addRequirement()">
											<div class="form-group">
												<label>Requirement Title</label>
												<input
													class="form-control"
													type="text"
													name="requirementTitle"
													id="requirementTitle"
													v-model="requirementForm.title"
													required
													:class="{ 'is-invalid':requirementForm.errors.has('title') }"
												/>
												<has-error :form="requirementForm" field="title"></has-error>
											</div>
											<div class="form-group">
												<label>Requirement Text</label>
												<input
													class="form-control"
													type="text"
													name="requirementText"
													id="requirementText"
													v-model="requirementForm.text"
													required
													:class="{ 'is-invalid':requirementForm.errors.has('text') }"
												/>
												<has-error :form="requirementForm" field="text"></has-error>
											</div>
											<div class="form-group">
												<label>Requirement type</label>
												<select class="form-control" name="type" v-model="requirementForm.type">
													<option value="file">File (Applicants would need to upload a file)</option>
													<option value="check">Check (Applicants would need to check a box to agree)</option>
													<option value="text">Text (Applicants would need to fill a text box)</option>
												</select>
												<has-error :form="requirementForm" field="type"></has-error>
											</div>
											<div class="form-group">
												<button class="btn btn-apply" type="submit">{{(edittingReq)?"Update":"Add"}} Requirement</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

            <div class="tab-pane" id="tab_6">
							<div class="box_general padding_bottom">
								<div class="header_box version_2">
									<h2>
										<i class="fa fa-cog"></i>Geenral System settings
									</h2>
								</div>
                <SystemSettings></SystemSettings>
							</div>
            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined } from "../mixins/enrollmentMixin";
import SystemSettings from "./SystemSettings";
export default {
  mixins: [enrollmentMixin],
  components: { SystemSettings },
	data() {
		return {
			admins: [],
			requirements: [],
			myEmail: "",
			edittingReq: false,
			bankForm: new Form({
				accountName: "",
				accountNumber: "",
				bankName: "",
				id: ""
			}),
			requirementForm: new Form({
				title: "",
				type: "",
				text: ""
			}),
			adminForm: new Form({
				firstName: "",
				lastName: "",
				middleName: "",
				email: "",
				role: 4,
				password: ""
			}),
			passwordForm: new Form({
				section: "password",
				password: "",
				newPassword: "",
				confirmPassword: ""
			}),
			updateForm: new Form({
				section: "personalInfo",
				firstName: "",
				lastName: "",
				middleName: "",
				email: ""
			})
		};
	},
	methods: {
		getBankDetail() {
			axios
				.get("/api/v1/admin/bankDetails")
				.then(data => {
					if (data.data.status) {
						this.populateBankForm(data.data.data);
					}
				})
				.catch(data => {});
		},
		getAdmins() {
			axios.get("/api/v1/admin/accounts").then(data => {
				this.admins = data.data.data;
			});
		},
		getRequirements() {
			axios.get("/api/v1/admin/requirements").then(data => {
				this.requirements = data.data;
			});
		},
		newAdmin() {
			Fire.$emit("pageBusy");
			this.adminForm
				.post("/api/v1/admin/account")
				.then(data => {
					if (data.data.status === "success") {
						Fire.$emit("adminUpdated");
						this.showSuccess(
							"Operation Successful",
							"New Administrator account created successfully"
						);
						this.resetForm();
					}
				})
				.catch(d => {
					this.showError();
				})
				.finally(data => {
					Fire.$emit("pageFree");
					this.adminForm.password = this.alphaNumPassword(8);
				});
		},
		addRequirement() {
			Fire.$emit("pageBusy");
			this.requirementForm
				.post("/api/v1/admin/requirements")
				.then(data => {
					if (data.data.status === "success") {
						Fire.$emit("requirementUpdated");
						this.showSuccess(
							"Operation Successful",
							"New course requirement created successfully"
						);
					}
				})
				.catch(d => {
					this.showError();
				})
				.finally(data => {
					Fire.$emit("pageFree");
				});
		},
		editReq(req) {
			this.edittingReq = true;
			this.requirementForm.title = req.title;
			this.requirementForm.type = req.type;
			this.requirementForm.text = req.text;
			this.requirementForm.id = req.id;
		},
		updateReq() {
			NProgress.start();
			Fire.$emit("pageBusy");
			this.requirementForm.busy = true;
			this.requirementForm
				.patch(`/api/v1/admin/requirements/${this.requirementForm.id}`)
				.then(data => {
					if (data.data.status) {
						this.showSuccess(
							"Operation successful",
							"Requirement updated successfully"
						);
						Fire.$emit("requirementUpdated");
						this.edittingReq = false;
						this.requirementForm.reset();
					} else {
						this.showError();
					}
				})
				.catch(data => {
					this.showError();
				})
				.finally(() => {
					NProgress.done();
					Fire.$emit("pageFree");
					this.requirementForm.busy = false;
				});
		},
		deleteReq(req) {
			Swal.fire({
				title: "Caution!",
				text: "Deleting course requirement might affect a course during update",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "continue!",
				allowOutsideClick: true
			}).then(input => {
				if (input.value === true) {
					Fire.$emit("pageBusy");
					axios
						.delete(`/api/v1/admin/requirements/${req.id}`)
						.then(payload => {
							console.log(payload.data.status);
							if (payload.data.status == "success") {
								Fire.$emit("requirementUpdated");
								Toast.fire({
									type: "success",
									title: "Requirement deleted"
								});
							}
						})
						.catch(payload => {})
						.finally(payload => {
							Fire.$emit("pageFree");
						});
				}
			});
		},
		resetForm() {
			this.adminForm.firstName = "";
			this.adminForm.lastName = "";
			this.adminForm.middleName = "";
			this.adminForm.email = "";
			this.adminForm.role = 4;
			this.adminForm.password = this.alphaNumPassword(8);
		},
		deleteAdmin(adminId) {
			Fire.$emit("pageBusy");
			axios
				.delete(`/api/v1/admin/account/${adminId}`)
				.then(data => {
					if (data.data.status === "success") {
						Fire.$emit("adminUpdated");
						Toast.fire({
							type: "success",
							title: "Administrative account deleted successfullly"
						});
					}
				})
				.catch(d => {
					this.showError();
				})
				.finally(d => {
					Fire.$emit("pageFree");
				});
		},
		populateBankForm(bankDetails) {
			this.bankForm.accountName = bankDetails.accountName;
			this.bankForm.accountNumber = bankDetails.accountNumber;
			this.bankForm.bankName = bankDetails.bankName;
			this.bankForm.id = bankDetails.id;
		},
		updateBankDetail() {
			Fire.$emit("pageBusy");
			this.bankForm
				.patch("/api/v1/admin/bankDetails/" + this.bankForm.id)
				.then(data => {
					if (data.data.status) {
						Toast.fire({
							type: "success",
							title: "Profile update successfully"
						});
					}
				})
				.catch(error => {})
				.finally(data => {
					Fire.$emit("pageFree");
				});
		},
		updatePassword() {
			this.updateInfo("passwordForm");
		},
		updatePersonalInfo() {
			this.updateInfo("updateForm");
		},
		updateInfo($info) {
			Fire.$emit("pageBusy");
			this[$info]
				.patch("/api/v1/admin/profile")
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
				})
				.finally(data => {
					Fire.$emit("pageFree");
				});
		},
		getProfile() {
			axios
				.get("/api/v1/admin/profile")
				.then(data => {
					if (data.data.status) {
						this.populateUpdateForm(data.data.data);
					}
				})
				.catch(data => {
					console.log(data);
				});
		},
		populateUpdateForm(profile) {
			this.updateForm.firstName = profile.first_name;
			this.updateForm.lastName = profile.last_name;
			this.updateForm.middleName = profile.midlle_name;
			this.updateForm.email = profile.email;
			this.myEmail = profile.email;
		},
		alphaNumPassword(length) {
			return Math.round(
				Math.pow(36, length + 1) - Math.random() * Math.pow(36, length)
			)
				.toString(36)
				.slice(1);
		}
	},
	created() {
		Fire.$on("adminUpdated", () => {
			this.getAdmins();
		});
		Fire.$on("requirementUpdated", () => {
			this.getRequirements();
		});
		this.getProfile();
		this.getBankDetail();
		this.getAdmins();
		this.getRequirements();
	},
	mounted() {}
};
</script>

<style>
</style>


