<template>
	<div class="container-fluid">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">
					<i class="fa fa-fw fa-home"></i>
				</a>
			</li>
			<li class="breadcrumb-item active">Courses</li>
		</ol>
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Your Courses</h2>
				<div class="filter hidden">
					<select name="orderby" class="selectbox" sb="96701764" style="display: none;">
						<option value="all" selected="selected">All</option>
						<option value="active">Active</option>
						<option value="approved">Approved</option>
						<option value="pending">Pending</option>
						<option value="declined">Declined</option>
					</select>
				</div>
			</div>
			<div class="list_general course-display">
				<ul>
					<li v-for="enrollment in allCourses" :key="enrollment.id">
						<div class="row" v-if="enrollment.course">
							<div class="col-md-4 d-flex justify-content-center p-4">
								<img
									:src="enrollment.course.banner | fullPath"
									alt
									class="align-self-center img-responsive"
								/>
							</div>
							<div class="col-md-8 p-4">
								<a
									v-if="(enrollment.status == 'declined') && shouldRenroll(enrollment.course_id, enrollment.id)"
									:href="`/enroll/${enrollment.course_id}`"
									class="btn btn-secondary float-right"
								> Re-enrol for course</a>
								<h4>{{enrollment.course.title}}</h4>
								<ul class="course_list">
									<li v-if="(enrollment.status == 'approved' && enrollment.payment_status == 1)">
										<strong>Enrollment status</strong>
										<i class="enrolled">Enrolled</i>
									</li>
									<li v-else>
										<strong>Enrollment status</strong>
										<i :class="enrollment.status">{{enrollment.status}}</i>
										<p v-if="enrollment.status == 'declined'">
											<strong>Reason for decline</strong>
											{{enrollment.admin_note}}
										</p>
									</li>
									<li v-if="(enrollment.status == 'approved' && enrollment.payment_status == 0)">
										<strong>Payment status</strong>
										<router-link to="/student/payments">
											<i>Pending</i>
										</router-link>
									</li>
									<li v-else-if="(enrollment.status == 'approved' && enrollment.payment_status == 1)">
										<strong>Payment status</strong>
										<i class="approved">Approved</i>
									</li>
									<li>
										<strong>Start date</strong>
										{{enrollment.course.start_date}}
									</li>
									<li>
										<strong>End date</strong>
										{{enrollment.course.end_date}}
									</li>
									<li>
										<strong>Faculty</strong>
										{{enrollment.course.faculty.name}}
									</li>
								</ul>
								<h6>Course description</h6>
								<p>{{enrollment.course.description}}</p>

								<p>
									<b>Instructors</b>
								</p>
								<p
									v-for="instructor in enrollment.course.instructors"
									:key="instructor.id"
								>{{instructor.first_name+ ' '+instructor.last_name}}</p>
							</div>
						</div>
					</li>
				</ul>
				<div class="text-center">
					<p v-if="coursesLoading">loading...</p>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined } from "../mixins/enrollmentMixin";
export default {
	data() {
		return {
			allCourses: {},
			coursesLoading: true,
			pending: "",
			reviewed: "",
			active: "",
			enrollments: "",
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
	mixins: [enrollmentMixin],
	methods: {
		fetchMyEnrollments() {
			axios.get("/api/v1/user/student/enrollment/history").then(data => {
				if (data.data.status) {
					let courses = data.data.data;
          this.enrollments = courses;
					this.reviewed = courses.filter(course => {
						return course.status == "approved" && course.payment_status == 0;
					});
					this.pending = courses.filter(course => {
						return course.status == "pending";
					});
					//this.active = (courses.filter((course)=>{return course.status == "active"}));
				}
			});
		},
		fetchMyCourses() {
			axios.get("/api/v1/user/student/enrollment").then(data => {
				this.allCourses = data.data.data;
				this.coursesLoading = false;
				this.active = data.data.data.filter(enrollment => {
					return enrollment.payment_status == 1;
				});
				//console.log(data.data);
			});
		},
		shouldRenroll(courseId, id) {
      let filtered = this.allCourses.filter(e => e.course_id == courseId);
      let last = filtered[filtered.length-1];
      return (last.id === id && last.status === "declined");
		}
	},
	created() {},
	mounted() {
		this.fetchMyCourses();
	}
};
</script>


<style>
</style>
