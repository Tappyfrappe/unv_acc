function showDetails(studentId) {
    const detailsRow = document.getElementById(`details-${studentId}`);
    const detailsContent = document.getElementById(`details-content-${studentId}`);
    
    if (detailsRow.style.display === "none") {
        fetch(`student_details_ajax.php?student_id=${studentId}`)
            .then(response => response.text())
            .then(data => {
                detailsContent.innerHTML = data;
                detailsRow.style.display = "table-row";
            })
            .catch(error => {
                detailsContent.innerHTML = "<p>Error loading details.</p>";
                console.error("Error fetching student details:", error);
            });
    } else {
        detailsRow.style.display = "none";
    }
}
