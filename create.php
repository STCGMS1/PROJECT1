<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$record = ['id' => '', 'name' => '', 'tag_number' => '', 'graveyard' => '', 
           'dod' => '', 'dob' => '', 'grave_class' => '', 'receipt_number' => '', 
           'burial_order_number' => '', 'exemption' => '', 'reason' => '', 
           'longitude' => '', 'latitude' => ''];

// If editing, fetch the record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT id, name, tag_number, graveyard, dod, dob, grave_class, 
              receipt_number, burial_order_number, exemption, reason, 
              ST_X(geom) as longitude, ST_Y(geom) as latitude 
              FROM burial_records WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $record = array_merge($record, $_POST);
    
    try {
        if (empty($record['id'])) {
            // Insert new record
            $query = "INSERT INTO burial_records 
                     (name, tag_number, graveyard, dod, dob, grave_class, 
                      receipt_number, burial_order_number, exemption, reason, geom) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ST_SetSRID(ST_MakePoint(?, ?), 4326))";
            $stmt = $db->prepare($query);
            $stmt->execute([
                $record['name'], $record['tag_number'], $record['graveyard'], 
                $record['dod'],$record['dob'], $record['grave_class'], $record['receipt_number'], 
                $record['burial_order_number'], $record['exemption'], 
                $record['reason'], $record['longitude'], $record['latitude']
            ]);
            $message = "Record added successfully!";
        } else {
            // Update existing record
            $query = "UPDATE burial_records SET 
                      name = ?, tag_number = ?, graveyard = ?, dod = ?, dob = ?,
                      grave_class = ?, receipt_number = ?, burial_order_number = ?, 
                      exemption = ?, reason = ?, geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) 
                      WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([
                $record['name'], $record['tag_number'], $record['graveyard'], 
                $record['dod'], $record['dod'], $record['grave_class'], $record['receipt_number'], 
                $record['burial_order_number'], $record['exemption'], 
                $record['reason'], $record['longitude'], $record['latitude'], 
                $record['id']
            ]);
            $message = "Record updated successfully!";
        }
        
        header("Location: index6.php?success=" . urlencode($message));
        exit();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<h2><?= empty($record['id']) ? 'Add New' : 'Edit' ?> Burial Record</h2>

<?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add entry</title>
    <style>
        body {
            background-color: lightblue;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: black;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        h2 {
            font-size: 18px;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        
        .field-group {
            margin-bottom: 20px;
        }
        
        .field-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        
        .field-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .name-section {
            display: flex;
            gap: 20px;
        }
        
        .name-section .field-group {
            flex: 1;
        }
        
        .dashed-line {
            border-top: 1px dashed #ccc;
            margin: 20px 0;
        }
        
        .phone-numbers {
            display: flex;
            gap: 20px;
        }
        
        .phone-numbers .field-group {
            flex: 1;
        }
        
        .email-section {
            display: flex;
            align-items: flex-end;
            gap: 20px;
        }
        
        .email-input {
            flex: 1;
        }
        
        .send-button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .send-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
<form method="post">
    <input type="hidden" name="id" value="<?= $record['id'] ?>">
    

    <div class="field-group">
        <div class="field-group">
            <div class="field-label">Name</div>
            <input type="text" class="field-input" id="name" name="name" required 
               value="<?= htmlspecialchars($record['name']) ?>">
        </div>
    </div>

    <div class="field-group">
        <div class="field-group">
            <div class="field-label">Tag number</div>
            <input type="text" class="field-input" id="tag_number" name="tag_number" 
               value="<?= htmlspecialchars($record['tag_number']) ?>">
        </div>
    </div>
    <div class="field-group">
        <div class="field-group">
            <div class="field-label">Graveyard</div>
            <input type="text" class="field-input" id="graveyard" name="graveyard" placeholder="RB/Vhovha"
            value="<?= htmlspecialchars($record['graveyard']) ?>">
        </div>
    </div>

    <div class="dashed-line"></div>
    
    
    <div class="field-group">
        
        <div class="field-group">
            <div class="field-label">Date of Death</div>
            <input type="date" class="field-input" id="dod" name="dod" 
               value="<?= htmlspecialchars($record['dod']) ?>">
        </div>
        <div class="field-group">
            <div class="field-label">Date of Birth</div>
            <input type="date" class="field-input" id="dob" name="dob" 
               value="<?= htmlspecialchars($record['dob']) ?>">
        </div>
    </div>
    
    <div class="dashed-line"></div>

    <div class="field-group">
        
        <div class="field-group">
            <input type="text" class="field-input" id="grave_class" name="grave_class" placeholder="Adult/child/reserved"
               value="<?= htmlspecialchars($record['grave_class']) ?>">
        </div>
        
        <div class="name-section">
            <div class="field-group">
                <input type="text" class="field-input" id="receipt_number" name="receipt_number" placeholder="Receipt Number"
               value="<?= htmlspecialchars($record['receipt_number']) ?>">
            </div>
            <div class="field-group">
                <input type="text" class="field-input" id="burial_order_number" name="burial_order_number" placeholder="Burial Order Number"
               value="<?= htmlspecialchars($record['burial_order_number']) ?>">
            </div>
        </div>
    </div></br>
    
    <h2>Exemption Details</h2>
    <div class="dashed-line"></div>
    
    <div class="phone-numbers">
        <div class="field-group">
        <textarea class="field-input" id="exemption" name="exemption" placeholder="Yes/No"><?= htmlspecialchars($record['exemption']) ?></textarea>
        </div>
        <div class="field-group">
        <textarea class="field-input" id="exemption" name="exemption" placeholder="Reason"><?= htmlspecialchars($record['reason']) ?></textarea>
        </div>
    </div>
    
    <div class="dashed-line"></div>
    <div class="field-group">
        Location
        <div class="name-section">
            <div class="field-group">
                <div class="field-label">Longitude</div>
                <input type="number" class="field-input" step="0.000001" id="longitude" name="longitude" 
               value="<?= htmlspecialchars($record['longitude']) ?>">
            </div>
            <div class="field-group">
                <div class="field-label">Latitude (-)</div>
                <input type="number" class="field-input" step="0.000001" id="latitude" name="latitude" placeholder="Should contain a nagative sign"
               value="<?= htmlspecialchars($record['latitude']) ?>">
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn"><b>Save Record</b></button></br>
    <a href="index.php" class="btn">Cancel</a></br></br></br></br></br></br>
</form>
</body>
<footer>
  Any queries contact us on 112/ 116 <br>
  Shurugwi Town Concil 2025&copy; All Rights Reserved
</footer>
</html>
<?php require_once 'includes/footer.php'; ?>
