<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card border-none">
				<div class="card-body bs">
					<div class="row mb-4" v-show="search">
						<div class="col-md-12">
							<div class="searchbar dataTables_filter">
								<input
									type="search"
									class="search_input"
									name
									placeholder="search"
									aria-controls="table-students"
									@keyup="emitSearch($event.target.value)"
								/>
								<a href="#" class="search_icon">
									<i class="fa fa-search"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-6 form-group font-small">
							<label for="filter_faculty">Course</label>
							<select
								name="course"
								id="filter_course"
								class="form-control select-with-border"
								v-model="course"
							>
								<option v-for="course in courses" :key="course.id" :value="course">{{course.title}}</option>
							</select>
						</div>
						<div class="col-md-4 col-sm-6 form-group font-small">
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
								@click="emitChange"
								:disabled="!filter.session || filter.session === ''"
								v-text="buttonText"
							></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: {
		courses: Array,
		buttonText: String,
		search: {
			default: true,
			type: Boolean
		}
	},
	data() {
		return {
			course: "",
			filter: {
				course: "",
				session: ""
			}
		};
	},
	watch: {
		course: function(newVal, oldVal) {
			this.filter.course = newVal;
			this.filter.session = "";
		}
	},
	methods: {
		emitChange() {
			this.$emit("filter", this.filter);
		},
		emitSearch(value) {
			this.$emit("search", value);
		}
	}
};
</script>
<style scoped>
</style>
