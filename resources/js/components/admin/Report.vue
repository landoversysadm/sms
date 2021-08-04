<template>
	<div class="container-fluid">
		<ol class="breadcrumb hide-print">
			<li class="breadcrumb-item">
				<router-link to="/admin/dashboard">
					<i class="fa fa-fw fa-home"></i>
				</router-link>
			</li>
			<li class="breadcrumb-item active">Reports</li>
			<li class="position-absolute" style="right:2em; top">
				<a
					class="btn btn-apply"
					style="font-size:0.9em; margin-top:-0.4em"
					href="#"
					@click="exportTableToPrinter('table-test', 'html')"
				>
					Print Reports
					<i class="fa fa-print"></i>
				</a>
			</li>
		</ol>

		<div class="row">
			<div class="col-md-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs hide-print">
						<li class>
							<a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Course Reports</a>
						</li>
						<li class>
							<a href="#tab_2" data-toggle="tab" aria-expanded="false" class>Faculty Reports</a>
						</li>
					</ul>
					<div class="tab-content pl-3">
						<div class="tab-pane active" id="tab_1">
							<div class="box_general padding_bottom">
								<div class="header_box version_2 text-center">
									<h4 class="show-print">Course Reports</h4>
									<div class="row hide-print d-flex justify-content-center">
										<div class="my-auto col-md-3 col-sm-6 form-group font-small d-flex">
											<label for="fiter_from">Min Date</label>
											<input
												type="date"
												name="filter_from"
												id="filter_from"
												class="form-control"
												:max="todayDate"
												@change="filterCourse()"
												v-model="courseFilter.maxDate"
											/>
										</div>
										<div class="my-auto col-md-3 col-sm-6 form-group d-flex font-small">
											<label for="filter_from">Max Date</label>
											<input
												type="date"
												name="filter_to"
												id="filter_to"
												class="form-control"
												:max="todayDate"
												@change="filterCourse()"
												v-model="courseFilter.minDate"
											/>
										</div>
										<div class="my-auto col-md-3 col-sm-6 form-group d-flex font-small">
											<div class="form-group custom-list">
												<input
													type="checkbox"
													@change="filterCourse($event)"
													v-model="filterByAlumni"
													class="mr-2"
												/>
												<label class="checkbox-inline">Alumni only</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 hide-print">
										<span class="text-primary course-show-toggle">
											<i
												class="fa"
												:class="{'fa-toggle-on':coursesFilterShow, 'fa-toggle-off':!coursesFilterShow}"
												@click="coursesFilterShow = !coursesFilterShow"
											></i> courses filter
										</span>
									</div>
									<div
										class="mt-4 hide-print"
										:class="{'col-md-3':coursesFilterShow, 'd-none':!coursesFilterShow}"
									>
										<div class="form-group custom-list">
											<input type="checkbox" checked @change="selectAllCourse($event)" class="mr-2" />
											<label class="checkbox-inline">Select all</label>
										</div>

										<ul class="course-check-list">
											<li v-for="course in courses" :key="course.id" class="custom-list">
												<input
													class="courseCheckBox"
													checked
													type="checkbox"
													@change="toggleCourseStudentChart(course, $event.target.checked)"
												/>
												<label>{{course.title}}</label>
											</li>
										</ul>
									</div>
									<div
										class="w100-print"
										:class="{'col-md-9':coursesFilterShow, 'col-md-12':!coursesFilterShow}"
									>
										<apexchart
											class="mt-4 ml-4"
											ref="courseStudentChart"
											width="100%"
											height="500"
											type="bar"
											:options="courseStudentOptions"
											:series="courseStudentSeries"
										></apexchart>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" v-if="mostEnrolledCourse !== null">
										<p>Most Enrolled Course : {{mostEnrolledCourse.title}} {{mostEnrolledCourse.course_students_count}}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_2">
							<div class="box_general padding_bottom">
								<div class="header_box version_2 text-center show-print">
									<h4>Faculty Reports</h4>
								</div>
								<div class="row">
									<div class="col-md-12">
										<FacultyChart :showDownloadPdf="false"></FacultyChart>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { exportTable } from "../../lib/csvExport";
import { getDiffieHellman } from "crypto";
import { isNullOrUndefined } from "util";
export default {
	mixins: [exportTable],
	computed: {
		todayDate: function() {
			return new Date().toISOString().split("T")[0];
		}
	},
	watch: {
		coursesFilterShow: function(newval, oldVal) {
			this.updateCourseChart();
		},
		currentlyPlotttedCourseStudentData: function(newVal, oldVal) {
			if (newVal.length > 0) {
				let max = newVal[0];
				newVal.forEach(e => {
					if (e.course_students.length > max.course_students.length) {
						max = e;
					}
				});
				this.mostEnrolledCourse = max;
			} else {
				this.mostEnrolledCourse = null;
			}
		}
	},
	data: function() {
		return {
			mostEnrolledCourse: null,
			coursesFilterShow: false,
			courseFilter: {
				maxDate: "",
				minDate: new Date().toISOString().split("T")[0]
			},
			courses: null,
			currentlyPlotttedCourseStudentData: null,
			dateFilteredCourse: null,
			filterByAlumni: false,
			randomColor: function() {
				var r = Math.floor(Math.random() * 255);
				var g = Math.floor(Math.random() * 255);
				var b = Math.floor(Math.random() * 255);
				return "rgb(" + r + "," + g + "," + b + ")";
			},
			courseStudentOptions: {
				chart: {
					id: "courses-enrolled-student"
				},
				theme: {
					palette: "palette3"
				},
				yaxis: {
					decimalsInFloat: 0,
					forceNiceScale: true,
					labels: {
						show: false
					}
				},
				xaxis: {
					categories: [],
					labels: {
						rotate: -77.5
					}
				},
				title: {
					text: "Courses and Students",
					align: "center",
					style: {
						fontSize: "18px",
						color: "#16462b"
					}
				}
			},
			courseStudentSeries: [
				{
					name: "courses and enrolled students",
					data: []
				}
			]
		};
	},
	methods: {
		getAllCourse() {
			axios
				.get("/api/v1/admin/courseReport")
				.then(response => {
					if (response.data.status === "success") {
						this.courses = response.data.data;
						this.currentlyPlotttedCourseStudentData = response.data.data;
						this.updateCourseChart();
					}
				})
				.catch(response => {});
		},
		updateCourseChart(dateFilteredCourse = null) {
			let courses = !isNullOrUndefined(dateFilteredCourse)
				? dateFilteredCourse
				: this.currentlyPlotttedCourseStudentData;
			let newCourseTitle = [];
			let newCourseSeries = [];
			courses.forEach(element => {
				newCourseTitle.push(element.title);
				newCourseSeries.push(element.course_students.length);
			});
			this.courseStudentSeries = [
				{
					data: newCourseSeries
				}
			];
			this.courseStudentOptions = {
				...this.courseStudentOptions,
				...{
					xaxis: {
						categories: newCourseTitle
					}
				}
			};
		},
		filterCourse() {
			let updatedCourse = null;
			let tempCourse = [];
			tempCourse = JSON.parse(
				JSON.stringify(this.currentlyPlotttedCourseStudentData)
			);

			updatedCourse = tempCourse.map(c => {
				let cs = c.course_students.filter(cs => {
					if (!this.filterByAlumni) {
						return this.$moment(cs.created_at).isBetween(
							this.courseFilter.maxDate,
							this.courseFilter.minDate,
							"day",
							"[]"
						);
					}
					return (
						this.$moment(cs.created_at).isBetween(
							this.courseFilter.maxDate,
							this.courseFilter.minDate,
							"day",
							"[]"
						) && cs.status === 2
					);
				});
				c.course_students = cs;
				return c;
			});
			this.updateCourseChart(tempCourse);
		},
		toggleCourseStudentChart(course, value) {
			let updatedCourse = null;
			if (!value) {
				updatedCourse = this.currentlyPlotttedCourseStudentData.filter(c => {
					return JSON.stringify(c) != JSON.stringify(course);
				});
				this.currentlyPlotttedCourseStudentData = updatedCourse;
			} else {
				this.currentlyPlotttedCourseStudentData.push(course);
			}
			this.filterCourse();
		},
		selectAllCourse(e) {
			if (e.target.checked) {
				document.querySelectorAll(".courseCheckBox").forEach(e => {
					e.checked = true;
				});
				this.currentlyPlotttedCourseStudentData = this.courses;
				this.updateCourseChart();
			} else {
				document.querySelectorAll(".courseCheckBox").forEach(e => {
					e.checked = false;
				});
				this.currentlyPlotttedCourseStudentData = [];
				this.updateCourseChart();
			}
		},
		downloadCourseStudent() {
			var dataURL = this.$refs.courseStudentChart.dataURI().then(uri => {
				var pdf = new jsPDF();
				pdf.addImage(uri, "PNG", 0, 0);
				pdf.save("LABS-course-student-chart.pdf");
			});
		}
	},
	activated() {
		this.getAllCourse();
	}
};
</script>

<style>
.course-check-list {
	height: 300px;
	max-height: 300px;
	overflow-y: auto;
	font-size: 0.8em;
}
</style>
