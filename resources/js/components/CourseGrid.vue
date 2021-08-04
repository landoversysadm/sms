<template>
	<div>
		<div class="row">
			<!-- /box_grid -->
			<div class="col-md-12 text-center">
				<p v-if="courseLoading">
					<small>loading...</small>
				</p>
			</div>
			<div v-for="course in allCourses" :key="course.id" class="col-xl-4 col-lg-6 col-md-6">
				<div class="box_grid home-course-grid wow animated" style="visibility: visible;">
					<figure class="block-reveal">
						<div class="block-horizzontal"></div>
						<div class="tint-bg"></div>
						<!-- <a href="#0" class="wish_bt"></a> -->
						<a href="#">
							<img :src="course.banner | fullPath" class="img-fluid course-list-img" alt />
						</a>
					</figure>
					<div class="wrapper">
						<small>Faculty</small>
						<p>{{course.faculty.name}}</p>
						<h3>{{course.title}}</h3>
						<p>{{course.description | truncateText(50)}}</p>
					</div>
					<ul v-if="course.coming_soon">
						<li>
							<i class="icon_clock_alt"></i> coming soon
						</li>
						<li>
							<a
								href="#"
								data-toggle="modal"
								data-target=".bd-example-modal-lg"
								@click="updateEnrollModal(course)"
							>View more</a>
						</li>
					</ul>
					<ul v-else>
						<li>
							<i class="icon_clock_alt"></i>
							{{ course.start_date | moment("MMMM Do YYYY") }}
						</li>
						<li>
							<a
								href="#"
								data-toggle="modal"
								data-target=".bd-example-modal-lg"
								@click="updateEnrollModal(course)"
							>Enroll now</a>
						</li>
					</ul>
				</div>
				<!-- /box_grid -->
			</div>

			<div v-if="allCourses.length<1" class="col-md-12 text-center">
				<h5 class="text-uppercase">No new Course is available for now</h5>
			</div>
		</div>
	</div>
</template>

<script>
import { isNullOrUndefined } from "util";
import { enrollmentMixin } from "../components/mixins/enrollmentMixin";
export default {
	mixins: [enrollmentMixin],
	data() {
		return {
			allCourses: {},
			allCoursesCache: {},
			courseLoading: true,
			enrolledCourses: {},
			def: {
				course: {
					banner: "",
					price: "",
					start_date: "1990/1/1",
					end_date: "1990/1/1",
					requirement: {}
				}
			},
			showCourseDetail: true,
			clickedCourse: {
				requirement: {}
			},
			formatter: new Intl.NumberFormat("en-NG", {
				style: "currency",
				currency: "NGN",
				currencyDisplay: "symbol"
			}),
			form: new Form({
				requirementFiles: []
			})
		};
	},
	watch: {
		$route(to, from) {
			this.filterFaculty(this.$route.hash.replace("#", ""));
		}
	},
	methods: {
		fetchAllCourses() {
			axios
				.get("/api/v1/courses")
				.then(data => {
					let generalCourses = data.data.data;
					this.allCourses = data.data.data;
					this.courseLoading = false;
					try {
						this.allCourses = this.allCourses.filter(course => {
							return !this.enrolledCourses.find(
								enrollment =>
									enrollment.course_id == course.id &&
									enrollment.status != "declined"
							);
						});
					} catch (error) {
						//console.log(error);
					}
					this.allCoursesCache = this.allCourses;
					let hash = this.$route.hash.replace("#", "");
					this.filterFaculty(hash);
				})
				.catch(e => {});
		},
		fetchMyEnrollments() {
			try {
				axios
					.get("/api/v1/user/student/enrollment/history")
					.then(data => {
						if (data.data.status) {
							let courses = data.data.data;
							this.enrolledCourses = courses;
						}
					})
					.catch(e => {})
					.finally(() => {
						this.fetchAllCourses();
					});
			} catch (err) {}
		},
		updateEnrollModal(course) {
			this.clickedCourse = course;
			this.showCourseDetail = true;
			try {
				this.clickedCourse.requirement = JSON.parse(course.requirement);
				if (isNullOrUndefined(this.clickedCourse.requirement)) {
					this.clickedCourse.requirement = {};
				}
				def.updateModalCourse(this.clickedCourse);
			} catch (error) {}
		},
		filterFaculty(facultyName) {
			let faculty = decodeURI(facultyName);
			if (faculty.length > 0) {
				this.allCourses = this.allCoursesCache.filter(course => {
					return course.faculty.name.toLowerCase() === faculty;
				});
			} else {
				this.allCourses = this.allCoursesCache;
			}
		}
	},
	created() {
		this.fetchMyEnrollments();
	},
	mounted() {}
};
</script>

<style>
</style>
