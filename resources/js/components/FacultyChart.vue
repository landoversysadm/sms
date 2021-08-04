<template>
    <div>
        <apexchart class="mt-2" ref="facultyChart" width="85%" height="500"  type="donut" :options="facultyOptions" :series="facultySeries"></apexchart>
        <div class="col-md-12 " v-if="showDownloadPdf">
            <button class="btn btn-apply float-right" @click="downloadAsPdf()">Save as PDF</button>
        </div>
    </div>
</template>

<script>
export default {
    props : {showDownloadPdf: {
                                type: Boolean,
                                default: false
                                }},
    data(){
        return {
            faculties : [],
            facultySeries: [],
            facultyOptions: {
                chart: {
                toolbar: {
                show: true,
                tools: {
                    download: true,
                }
                },
            },
            labels: []
            }
        }
    },
    methods : {
        getFaculty(){
            axios.get('/api/v1/admin/faculties').then((data)=>{
                this.faculties = data.data.data;
                this.updateFacultyChart();
            });
        },
        updateFacultyChart(){
            let newFacultySeries = [];
            let newFacultyLabels = [];
            this.faculties.forEach(faculty=> {
                newFacultyLabels.push(faculty.name.toLowerCase());
                newFacultySeries.push(faculty.courses.length);
            });
            this.facultySeries = newFacultySeries;
            this.facultyOptions= {...this.facultyOptions, ...{
                labels : newFacultyLabels
            } }
        },
        downloadAsPdf(){
            var dataURL = this.$refs.facultyChart.dataURI().then((uri) => {
                var pdf = new jsPDF();
                pdf.addImage(uri, 'PNG', 0, 0);
                pdf.save("LABS-faculty-chart.pdf");
            });
        }
    },
    created(){
        this.getFaculty();
    }
}
</script>

<style>

</style>
