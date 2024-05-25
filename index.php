<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-content {
            max-width: 100%;
        }
        .modal-body img {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Task Table</h1>
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="searchTable()">
        </div>
        <div class="table-responsive">
            <table id="taskTable" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Task</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Color Code</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here by JavaScript -->
                </tbody>
            </table>
        </div>
        <button id="openModal" class="btn btn-primary mt-3" data-toggle="modal" data-target="#imageModal">Open Modal</button>
    </div>

    <!-- here will be the modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" id="fileInput" class="form-control-file">
                    <img id="imagePreview" src="" alt="Image Preview" class="mt-3" style="display:none;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchData();

            document.getElementById('fileInput').onchange = function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            };
        });
        // here I call the fetch data function from fetch_data.php 
        // which connects to the API and fetches the data from the
        // remote server
        function fetchData() {
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('taskTable').querySelector('tbody');
                    tbody.innerHTML = '';
                    data.forEach(task => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${task.task}</td>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td style="background-color:${task.colorCode};">${task.colorCode}</td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('taskTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? '' : 'none';
            }
        }

        setInterval(fetchData, 60 * 60 * 1000); // Auto-refresh every 60 minutes
    </script>
</body>
</html>
