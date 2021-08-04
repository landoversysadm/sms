import print from "print-js";

const exportTable = {
    methods: {
        exportTableToPrinter(elem, mode) {
            // let divToPrint=document.querySelector(".table");
            // let newWin = window.open("");
            // newWin.document.write(divToPrint.outerHTML);
            // newWin.print();
            // newWin.close();
            // print({printable:elem,type:mode,
            //     header: '<h3 class="custom-h3">Landover Business School</h3>',
            //     headerStyle: 'border:none',
            //     style: '.custom-h3 { color: #16462b; border:none;} .hidden { display: block; } .hide-print { display: none !important; }',
            //     css : 'print.css'
            // });
            window.print();
        },
        exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll(".table tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length; j++) {
                    var input = cols[j].firstElementChild;
                    if (input === null || input === undefined) {
                        row.push(cols[j].innerText);
                    } else {
                        row.push(input.value);
                    }
                }

                console.log(cols[j]);

                csv.push(row.join(","));
            }
            // Download CSV file
            this.downloadCSV(csv.join("\n"), filename);
        },
        downloadCSV(csv, filename) {
            let csvFile;
            let downloadLink;

            // CSV file
            csvFile = new Blob([csv], { type: "text/csv" });

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";
            downloadLink.target = "_blank";

            // Add the link to DOM
            console.log(downloadLink);
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();
        }
    }
};

export { exportTable };
