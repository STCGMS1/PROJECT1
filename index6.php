
        <?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Fetch all burial records
$query = "SELECT id, name, tag_number, graveyard, dod, grave_class, 
          receipt_number, burial_order_number, exemption, reason, 
          ST_X(geom) as longitude, ST_Y(geom) as latitude 
          FROM burial_records 
          ORDER BY dod DESC";
$stmt = $db->query($query);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>All Burial Records</h2>
<a href="create.php" class="btn">Add New Record</a>



<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Tag Number</th>
            <th>Graveyard</th>
            <th>Date of Death</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record['name']) ?></td>
            <td><?= htmlspecialchars($record['tag_number']) ?></td>
            <td><?= htmlspecialchars($record['graveyard']) ?></td>
            <td><?= date('M d, Y', strtotime($record['dod'])) ?></td>
            <td>
                <?php if ($record['longitude'] && $record['latitude']): ?>
                    <?= round($record['longitude'], 6) ?>, <?= round($record['latitude'], 6) ?>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <a href="create.php?id=<?= $record['id'] ?>" class="btn">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        h1, h2 {
            color: #2c3e50;
            margin: 0;
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }

        .records-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .records-table th, 
        .records-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        .records-table th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .records-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .records-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }

        .records-table tbody tr:last-of-type {
            border-bottom: 2px solid #3498db;
        }

        .records-table tbody tr:hover {
            background-color: #f1f8fe;
        }

        .no-records {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 20px;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9em;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-edit {
            background-color: #2ecc71;
            color: white;
        }

        .btn-edit:hover {
            background-color: #27ae60;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Map Link */
        .map-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .map-link:hover {
            text-decoration: underline;
        }

        /* Actions Column */
        .actions {
            white-space: nowrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn-primary {
                margin-top: 10px;
            }
            
            .records-table {
                font-size: 0.8em;
            }
            
            .records-table th, 
            .records-table td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    
</body>
</html>