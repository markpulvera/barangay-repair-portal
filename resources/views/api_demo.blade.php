<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REST API Live Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        button { background-color: #0d6efd; color: white; border: none; padding: 10px 18px; font-size: 14px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0b5ed7; }
        pre { background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; max-height: 350px; }
        .badge { background: #198754; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Barangay Repair Portal — REST API Live Demo</h2>
        <p>Click the button below to fetch live JSON data directly from your REST API endpoint: <code>/api/repairs</code></p>
        <button onclick="fetchApiData()">Fetch Repairs via API</button>
    </div>

    <div class="card">
        <h3>API Response Status: <span id="status-code" class="badge" style="background:#6c757d;">Not Started</span></h3>
        <h4>Raw JSON Output (Returned by <code>RepairApiController</code>):</h4>
        <pre id="json-output">// Click "Fetch Repairs via API" above to load data...</pre>
    </div>

    <script>
        function fetchApiData() {
            const outputElement = document.getElementById('json-output');
            const statusElement = document.getElementById('status-code');

            outputElement.textContent = "Fetching data from /api/repairs...";

            fetch('/api/repairs')
                .then(response => {
                    statusElement.textContent = response.status + " " + response.statusText;
                    statusElement.style.backgroundColor = response.ok ? '#198754' : '#dc3545';
                    return response.json();
                })
                .then(data => {
                    outputElement.textContent = JSON.stringify(data, null, 4);
                })
                .catch(error => {
                    statusElement.textContent = "Error";
                    statusElement.style.backgroundColor = '#dc3545';
                    outputElement.textContent = "Failed to fetch API data: " + error;
                });
        }
    </script>

</body>
</html>