<template>
	<div
		class="modal fade bd-example-modal-lg course-full-detail"
		tabindex="-1"
		role="dialog"
		aria-labelledby="mySmallModalLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog modal-lg">
			<div class="modal-content border-0">
				<div class="modal-header p-0 border-bottom-0">
					<img :src="course.banner | fullPath" style="min-width:100%; height:300px;" />

					<!-- <div class="price">{{formatter.format(course.price)}}</div> -->
					<div class="title">{{ course.title }}</div>
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
				<div class="modal-body" v-show="showCourseDetail">
					<section>
						<h5 class="section-header">Description</h5>
						<p>{{ course.description }}</p>
					</section>
					<section v-if="!course.coming_soon">
						<h5 class="section-header">Duration</h5>
						<p>
							The course would run from the
							{{ course.start_date | moment("MMMM, Do YYYY") }} to
							{{ course.end_date | moment("MMMM, Do YYYY") }}
						</p>
					</section>
					<!-- <section>
            <h5 class="section-header" v-if="course.instructors.length > 0">Instructors</h5>
            <p v-for="instructor in course.instructors" :key="instructor.id">{{instructor.first_name+ ' '+instructor.last_name}}</p>
					</section>-->
					<section>
						<h5 class="section-header">Requirements for uploads</h5>
						<ul class="bullets requirement-bullets" v-if="Object.keys(course.requirement).length > 0">
							<li v-for="i in Object.keys(course.requirement)" :key="i">
								<b>{{ course.requirement[i].title }}</b>
								<br />
								{{ course.requirement[i].text }}
							</li>
						</ul>
						<ul class="bullets requirement-bullets" v-else>
							<li>NO requirement for this course</li>
						</ul>
					</section>
				</div>
				<div class="modal-footer" v-if="!course.coming_soon">
					<a type="button " class="btn btn-apply" :href="'/enroll/' + course.id">Continue</a>
				</div>
				<div class="modal-footer" v-else>
					<p class="coming-soon-text">coming soon</p>
				</div>
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
			course: {
				banner: "",
				price: "",
				start_date: "2019-06-18 18:59:36",
				end_date: "2019-06-18 18:59:36",
				requirement: {}
			},
			showCourseDetail: true,
			formatter: new Intl.NumberFormat("en-NG", {
				style: "currency",
				currency: "NGN",
				currencyDisplay: "symbol"
			})
		};
	},
	methods: {
		updateEnrollModal() {
			this.course = def.course;
		},
		enroll() {
			this.$router.push("enroll");
		}
	},
	created() {
		const vm = this;
		Fire.$on("modalCourseUpdated", function() {
			vm.updateEnrollModal();
		});
	},
	mounted() {}
};
</script>

<style>
.coming-soon-text {
	color: #cac2c2;
}
</style>
