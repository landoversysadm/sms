const instructorMixin = {
  data() {
    return {
      filter: {},
      loading: true,
      filtering: false,
      assignedCourses: [],
      instructor: { courses: [] },
      classRoom: {},
    };
  },
  watch: {
    filter(newVal, oldVal) {},
  },
  methods: {
    fetchProfile() {
      axios.get('/api/v1/instructor/profile').then((response) => {
        if (response.data) {
          this.loading = false;
          this.instructor = response.data.data;
          this.assignedCourses = this.instructor.courses;
        }
      });
    },
    getStudents(courseId, sessionId) {
      this.filtering = true;
      axios
        .get(`/api/v1/instructor/students/${courseId}/${sessionId}`)
        .then((response) => {
          this.classRoom = response.data.data;
        })
        .finally((response) => {
          this.filtering = false;
        });
    },
    newFilter(filter) {
      this.filter = filter;
      const courseId = filter.course.id;
      const sessionId = filter.session.id;
      this.getStudents(courseId, sessionId);
    },
  },
  filters: {},
  created() {},
  mounted() {
    this.fetchProfile();
  },
};

export { instructorMixin };
