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
						<CourseFilter
							:courses="assignedCourses"
							:button-text="`fetch assessment`"
							:search="false"
							@filter="(filter)=>{newFilter(filter)}"
						></CourseFilter>
					</div>
				</div>
			</div>

			<!-- <div class="col-md-12 mt-3">
				<div class="card course-card">
					<div class="card-body">
						<div class="row" v-if="courseAndStudents.length > 0">
							<div class="col-md-12 hide-print mb-4">
								<button
									class="btn btn-apply"
									data-toggle="modal"
									data-target="#newSheetColumnModal"
								>add new test scores to sheet</button>

								<button class="btn btn-apply float-right" @click="showResits = !showResits">Show Re-sits</button>
							</div>
							<div class="col-md-12">
								<table
									class="table text-transform-uppercase full-border table-responsive border-bottom"
									id="table-test"
								>
									<thead class="font-weight-bold text-center">
										<tr>
											<td colspan="2">COURSE</td>
											<td :colspan="topics.length + 1" v-text="filter.course.title"></td>
										</tr>
										<tr>
											<td colspan="2">DURATION</td>
											<td
												:colspan="topics.length + 1"
											>{{filter.session.start_date | moment("DD MMMM YYYY")}} - {{filter.session.end_date | moment("DD MMMM YYYY")}}</td>
										</tr>
										<tr>
											<td>s/N</td>
											<td>NAME</td>
											<td v-for="topic in topics" :key="topic.title" v-text="topic.title"></td>
											<td>total</td>
										</tr>
									</thead>
									<tbody class="text-center">
										<tr v-for="(sheet,index) in scoreSheet" :key="sheet.id">
											<td v-text="index+1"></td>
											<td>{{sheet.fullName}}</td>
											<td v-for="score in sheet.scores" :key="score.title">
												<input
													type="number"
													:id="score.title"
													v-model="score.value"
													class="input form-control border-none hide-print"
													@keyup="ensureInt"
												/>
												<span class="show-print">{{score.value}}</span>
												<div class="hide-print" v-show="showResits">
													<p>Resits : {{score.resits.length}}</p>
													<p v-for="resit in score.resits" :key="resit">{{resit}}</p>
													<input type="number" @keyup="ensureInt" />
													<button class="btn with-border" @click="addResitTo(score,$event.target)">Add</button>
												</div>
											</td>
											<td>{{totalScore(sheet.scores)}}</td>
										</tr>
										<tr></tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-12 d-flex justify-content-end hide-print">
								<button class="btn btn-apply" @click="saveAssessment">Save sheet</button>
								<button class="ml-2 btn btn-apply" @click="exportTableToCSV('members.csv')">Export sheet</button>
								<button
									class="ml-2 btn btn-apply"
									@click="exportTableToPrinter('table-test', 'html')"
								>Print sheet</button>
							</div>
						</div>
						<div class="row" v-else>
							<div class="col-md-12 page-default">
								<div class="text-center">
									<h1>ASSESSMENTS</h1>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>-->
		</div>
		<CourseAssessment :courseAndStudents="courseAndStudents" :filter="filter" @save="saveAssessment" />
	</div>
</template>

<script>
import { instructorMixin } from "../mixins/instructorMixin";
import { enrollmentMixin } from "../mixins/enrollmentMixin";
import CourseFilter from "../CourseFilter";
import CourseAssessment from "../CourseAssessment";
export default {
	mixins: [enrollmentMixin, instructorMixin],
	components: {
		CourseFilter,
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
			cache: {
				courseId: "",
				sessionId: ""
			}
		};
	},
	methods: {
		newFilter(filter) {
			this.filter = filter;
			this.fetchAssessments();
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
				.get(`/api/v1/instructor/assessment/${courseId}/${sessionId}`)
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
				.post("/api/v1/instructor/assessment/save", {
					scoreSheet
				})
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
	watch: {},
	created() {
		Fire.$on("assessmentStatusChnaged", data => {
			this.fetchAssessments("runtime");
		});
	}
};
</script>

<style>
</style>
