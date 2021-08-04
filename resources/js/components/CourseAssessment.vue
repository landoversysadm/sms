<template>
	<div class="row">
		<div class="col-md-12 mt-3">
			<div class="card course-card">
				<div class="card-body">
					<div class="row" v-if="courseAndStudents.length > 0">
						<div class="col-md-12 hide-print mb-4">
							<button
								class="btn btn-apply"
								data-toggle="modal"
								data-target="#newSheetColumnModal"
							>Add new test scores to sheet</button>
							<button
								class="btn btn-danger btn-sm"
								data-toggle="modal"
								data-target="#deleteTestColumnModal"
							>Delete test from sheet</button>

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
										<td v-for="topic in topics" :key="topic.title" @dblclick="toggleEditInput(topic.title)">
											<span :ref="`text-${topic.title}`">{{topic.title}}</span>
											<input
												@keypress="(e)=> updateTitle(e, topic.title)"
												:id="`input-${topic.title}`"
												:ref="`input-${topic.title}`"
												:value="topic.title"
												class="input form-control border-none hide-print d-none"
											/>
										</td>
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
		</div>

		<div
			class="modal fade"
			id="newSheetColumnModal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="addSheet"
			aria-hidden="true"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addSheet">Provide Test name</h5>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							aria-label="Close"
							id="newTestModalCloseButton"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="addSheetColumn">
						<div class="modal-body">
							<div class="form-group">
								<input
									type="text"
									class="form-control"
									required
									v-model="newTest.name"
									placeholder="enter test name"
								/>
								<span class="form-feedback error" :class="newTest.display" v-text="newTest.error"></span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-apply">
								<span
									class="spinner-border spinner-border-sm spinner-submit"
									role="status"
									aria-hidden="true"
								></span> Save
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div
			class="modal fade"
			id="deleteTestColumnModal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="deleteSheet"
			aria-hidden="true"
		>
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addSheet">Select test name to delete</h5>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							aria-label="Close"
							id="deleteTestModalCloseButton"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="deleteTest">
						<div class="modal-body">
							<div class="form-group">
								<select class="form-control" v-model="testToDelete">
									<option v-for="topic in topics" :key="topic.title">{{topic.title}}</option>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
							<button type="submit" class="btn btn-danger">
								<span
									class="spinner-border spinner-border-sm spinner-submit"
									role="status"
									aria-hidden="true"
								></span> Delete
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { enrollmentMixin } from "./mixins/enrollmentMixin";
import { exportTable } from "../lib/csvExport";
export default {
	props: {
		courseAndStudents: {
			required: true
		},
		filter: {
			required: true
		}
	},
	mixins: [exportTable, enrollmentMixin],
	data() {
		return {
			showResits: false,
			hasUpdate: false,
			scoreSheet: [],
			normScoreSheet: [],
			topics: [],
			testToDelete: "",
			newTest: {
				name: "",
				error: "invalid name, length must be greater than two",
				display: "hide"
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
			this.hasUpdate = true;
			this.toastSuccess(
				`${title} updated to ${event.target.value}, use the save button to persist changes`
			);
		},
		deleteTest() {
			const title = this.testToDelete;
			if (title !== "") {
				this.scoreSheet.forEach(e => {
					e.scores = e.scores.filter(l => l.title !== title);
				});
				this.initTopics();
				document.getElementById("deleteTestModalCloseButton").click();
				this.toastSuccess(
					`${title} deleted, use the save button to persist changes`
				);
			}
		},
		addResitTo(score, elem) {
			const { value } = elem.previousElementSibling;
			if (value === "" || value === null || value === undefined) return true;
			if (parseInt(value) <= 0) return true;
			score.resits.push(parseInt(value));
			score.value = value;
			this.hasUpdate = true;
			elem.previousElementSibling.value = "";
		},
		initScoreSheet() {
			this.scoreSheet = this.courseAndStudents.map(data => ({
				id: data.assessment[0].id,
				fullName: `${data.student.user.first_name} ${data.student.user.last_name}`,
				scores: JSON.parse(data.assessment[0].scores)
			}));
			this.initTopics();
		},
		initTopics() {
			const maxSheet = this.scoreSheet.reduce((a, b) => {
				if (a.scores.length > b.scores.length) {
					return a;
				}
				return b;
			}, this.scoreSheet[0]);
			this.topics = maxSheet.scores;
			return true;
		},
		totalScore(scores) {
			const total = scores.reduce((a, b) => {
				if (isNaN(parseInt(b.value))) {
					b.value = 0;
				}
				return a + parseInt(b.value);
			}, 0);
			return total;
		},
		addSheetColumn() {
			if (this.newTest.name.length <= 2) {
				this.newTest.display = "block";
				return true;
			}
			if (
				this.topics.filter(
					e => this.newTest.name.toLowerCase() === e.title.toLowerCase()
				).length > 0
			) {
				this.toastError("Test name already exist");
				return true;
			}

			this.scoreSheet = this.scoreSheet.map(data => {
				data.scores.push({ title: this.newTest.name, value: 0, resits: [] });
				return { id: data.id, fullName: data.fullName, scores: data.scores };
			});
			this.newTest.name = "";
			this.initTopics();
			this.hasUpdate = true;
			document.getElementById("newTestModalCloseButton").click();
			return true;
		},
		ensureInt(event) {
			if (isNaN(parseInt(event.target.value))) {
				event.target.value = 0;
			} else {
				event.target.value = parseInt(event.target.value) + 0;
			}
			this.hasUpdate = true;
		},
		saveAssessment() {
			this.$emit("save", this.scoreSheet);
			this.hasUpdate = false;
		},
		preventNav(event) {
			if (!this.hasUpdate) return;
			event.preventDefault();
			event.returnValue = "";
		}
	},
	watch: {
		courseAndStudents(newVal, oldVal) {
			if (newVal.length > 0) this.initScoreSheet();
		}
	},
	beforeMount() {
		window.addEventListener("beforeunload", this.preventNav);
	},
	mounted() {
		if (this.courseAndStudents.length > 0) this.initScoreSheet();
	},
	beforeDestroy() {
		window.removeEventListener("beforeunload", this.preventNav);
	}
};
</script>
<style scoped>
</style>
