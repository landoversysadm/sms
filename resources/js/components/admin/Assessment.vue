<template>
	<div class="container-fluid">
		<ol class="breadcrumb hide-print">
			<li class="breadcrumb-item">
				<router-link to="/admin/dashboard">
					<i class="fa fa-fw fa-home"></i>
				</router-link>
			</li>
			<li class="breadcrumb-item active">Assessments</li>
		</ol>

		<div class="row">
			<div class="col-md-12 d-flex justify-content-center show-print">
				<img src="/img/logo.png" alt="LABS-LOGO" style="width: 300px; text-align: center;" />
			</div>
			<div class="col-md-12 hide-print">
				<div class="card course-card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-3 col-sm-6 form-group font-small">
								<label for="filter_faculty">Faculty</label>
								<select
									name="course"
									id="filter_faculty"
									class="form-control select-with-border"
									v-model="filter.faculty"
								>
									<option v-for="faculty in faculties" :key="faculty.id" :value="faculty">{{faculty.name}}</option>
								</select>
							</div>
							<div class="col-md-3 col-sm-6 form-group font-small">
								<label for="filter_faculty">Course</label>
								<select
									name="course"
									id="filter_course"
									class="form-control select-with-border"
									v-model="filter.course"
								>
									<option
										v-for="course in filter.faculty.courses"
										:key="course.id"
										:value="course"
									>{{course.title}}</option>
								</select>
							</div>
							<div class="col-md-3 col-sm-6 form-group font-small">
								<label for="filter_faculty">Session</label>
								<select
									name="course"
									id="filter_course"
									class="form-control select-with-border"
									v-model="filter.session"
								>
									<option
										v-for="session in filter.course.sessions"
										:key="session.id"
										:value="session"
									>{{session.start_date | moment("DD - MMMM - YYYY")}} / {{session.end_date | moment("DD- MMMM - YYYY")}}</option>
								</select>
							</div>
							<div
								class="col-md-3 col-sm-6 form-group font-small d-flex justify-content-center align-items-center"
							>
								<button
									class="btn btn-apply"
									@click="fetchAssessments"
									:disabled="filter.session == ''"
								>Fetch Assessment sheet</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<CourseAssessment :courseAndStudents="courseAndStudents" :filter="filter" @save="saveAssessment" />
	</div>
</template>

<script>
import { enrollmentMixin } from "../mixins/enrollmentMixin";
import { exportTable } from "../../lib/csvExport";
import CourseAssessment from "../CourseAssessment";
export default {
	mixins: [exportTable, enrollmentMixin],
	components: {
		CourseAssessment
	},
	data() {
		return {
			faculties: [{ id: -1, name: "Fetching faculties  ...." }],
			filter: {
				faculty: {
					courses: [{ id: -1, title: "Select faculty first" }]
				},
				course: "",
				session: ""
			},
			courseAndStudents: "",
			newTest: {
				name: "",
				error: "invalid name, length must be greater than two",
				display: "hide"
			},
			cache: {
				courseId: "",
				sessionId: ""
			}
		};
	},
	methods: {
		toggleEditInput(title) {
			this.$refs[`text-${title}`][0].classList.toggle("d-none");
			this.$refs[`input-${title}`][0].classList.toggle("d-none");
		},
		updateTitle(event, title) {
			if (event.keyCode !== 13) {
				return;
			}
			this.toggleEditInput(title);
			if (event.target.value == title) {
				this.toastSuccess("No changes detected");
				return;
			}
			this.scoreSheet.forEach(e =>
				e.scores.forEach(l => {
					if (l.title === title) l.title = event.target.value;
				})
			);
			this.toastSuccess(
				`${title} updated to ${event.target.value}, use the save button to persist changes`
			);
		},
		fetchAllFaculties() {
			axios.get("/api/v1/faculties").then(data => {
				this.faculties = data.data.data;
			});
		},
		fetchAssessments(type) {
			let courseId = "";
			let sessionId = "";
			Fire.$emit("pageBusy");
			if (type === "runtime") {
				courseId = this.cache.courseId;
				sessionId = this.cache.sessionId;
			} else {
				courseId = this.filter.course.id;
				sessionId = this.filter.session.id;
				this.cache.courseId = courseId;
				this.cache.sessionId = sessionId;
			}

			axios
				.get(`/api/v1/admin/assessment/${courseId}/${sessionId}`)
				.then(data => {
					this.courseAndStudents = data.data.data;
				})
				.catch(data => {})
				.finally(data => {
					Fire.$emit("pageFree");
				});
		},
		saveAssessment(scoreSheet) {
			Fire.$emit("pageBusy");
			axios
				.post("/api/v1/admin/assessment/save", { scoreSheet })
				.then(data => {
					if (data.data.status) {
						Fire.$emit("assessmentStatusChnaged");
						this.showSuccess("Successful", "");
					}
				})
				.catch(data => {
					console.log(data);
					this.showError();
				})
				.finally(data => {
					Fire.$emit("pageFree");
				});
		}
	},
	created() {
		this.fetchAllFaculties();
		Fire.$on("assessmentStatusChnaged", data => {
			this.fetchAssessments("runtime");
		});
	}
};
</script>

<style>
</style>
