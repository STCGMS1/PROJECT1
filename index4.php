<?php
require_once 'config/db.php';


// Initialize variables
$searchTerm = '';
$records = [];

// Process search if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    
    if (!empty($searchTerm)) {
        $query = "SELECT id, name, tag_number, graveyard, dod, dob, grave_class, 
                 receipt_number, burial_order_number, exemption, reason, 
                 ST_X(geom) as longitude, ST_Y(geom) as latitude 
                 FROM burial_records 
                 WHERE name ILIKE :search OR tag_number ILIKE :search
                 ORDER BY name ASC";
        
        $stmt = $db->prepare($query);
        $stmt->bindValue(':search', "%$searchTerm%");
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Default view - show all records
    $query = "SELECT id, name, tag_number, graveyard, dod, dob, grave_class, 
              receipt_number, burial_order_number, exemption, reason, 
              ST_X(geom) as longitude, ST_Y(geom) as latitude 
              FROM burial_records 
              ORDER BY dod DESC 
              LIMIT 50";
    $records = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="page-header">
    <h2>Burial Records Management</h2>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Record
    </a>
</div>

<div class="search-container">
    <form method="get" class="search-form">
        <div class="search-input-group">
            <input type="text" name="search" placeholder="Search by name or tag number..." 
                   value="<?= htmlspecialchars($searchTerm) ?>" class="search-input">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($searchTerm)): ?>
                <a href="index.php" class="clear-search">Clear search</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3><?= empty($searchTerm) ? 'Recent Records' : 'Search Results' ?></h3>
        <div class="record-count"><?= count($records) ?> records found</div>
    </div>
    
    <div class="table-responsive">
        <table class="records-table">
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
                <?php if (count($records) > 0): ?>
                    <?php foreach ($records as $record): ?>
                    <tr>
                        <td>
                            <div class="name-cell">
                                <strong><?= htmlspecialchars($record['name']) ?></strong>
                                <?php if ($record['exemption']): ?>
                                    <span class="badge badge-exemption">Exemption</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($record['tag_number']) ?></td>
                        <td><?= htmlspecialchars($record['graveyard']) ?></td>
                        <td>
                            <?= $record['dod'] ? date('M d, Y', strtotime($record['dod'])) : 'N/A' ?>
                        </td>
                        <td>
                            <?php if ($record['longitude'] && $record['latitude']): ?>
                                <a href="https://www.google.com/maps?q=<?= $record['latitude'] ?>,<?= $record['longitude'] ?>" 
                                   target="_blank" class="map-link">
                                    <i class="fas fa-map-marker-alt"></i> View Map
                                </a>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="create.php?id=<?= $record['id'] ?>" class="btn btn-sm btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-records">
                            <i class="fas fa-info-circle"></i> No records found matching your search criteria
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Search Styles */
.search-container {
    margin-bottom: 30px;
}

.search-form {
    max-width: 600px;
    margin: 0 auto;
}

.search-input-group {
    display: flex;
    position: relative;
}

.search-input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    font-size: 16px;
    transition: all 0.3s;
}

.search-input:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

.search-button {
    padding: 12px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.search-button:hover {
    background-color: #2980b9;
}

.clear-search {
    position: absolute;
    right: 110px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8c8d;
    text-decoration: none;
    font-size: 14px;
}

.clear-search:hover {
    color: #e74c3c;
}

/* Card Styles */
.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
    overflow: hidden;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
}

.record-count {
    color: #7f8c8d;
    font-size: 14px;
}

/* Enhanced Table Styles */
.records-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.records-table th {
    position: sticky;
    top: 0;
    background-color: #3498db;
    color: white;
    padding: 15px;
    font-weight: 500;
}

.records-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.records-table tr:last-child td {
    border-bottom: none;
}

.records-table tr:hover td {
    background-color: #f8fafc;
}

.name-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.badge-exemption {
    background-color: #f39c12;
    color: white;
}

.text-muted {
    color: #95a5a6;
}

/* Button Styles */
.btn-sm {
    padding: 6px 12px;
    font-size: 14px;
}

/* Font Awesome Icons */
.fas {
    margin-right: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-input-group {
        flex-direction: column;
    }
    
    .search-input {
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .search-button {
        border-radius: 4px;
        width: 100%;
    }
    
    .clear-search {
        position: static;
        transform: none;
        margin-top: 10px;
        display: inline-block;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .record-count {
        margin-top: 5px;
    }
}
    </style>
</head>
<body>
    
</body>
</html>