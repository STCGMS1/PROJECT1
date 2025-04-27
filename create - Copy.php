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
        background-color: lawngreen;
        width: 50%;
        margin-left: 340px;
        margin-right: 340px;
        font-size: 30px;
      }
      img {
        border-radius: 50px;
      }
      h1 {
        color: white;
      }
      label {
        width: 240px;
        display: inline-block;
        font-size: 25px;

      }
      button {
        padding-left: 20px;
        padding-bottom: 10px;
        font-family: Arial;
        letter-spacing: 1.2px;
        line-height: 30px;
        cursor: pointer;
      }
      button a{
        color: white; font-weight: bold; font-size 25px;
      }
      button:hover{
        background: orange;
        color: white;
      }
      footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        color: #fff;
        padding: 10px 0;
        text-align: center;
      }
      .form{
        width: 100%;
        max-width: 300px;
        box-sizing: border-box;
      }
      .input{
        padding: 10px;
      }
    </style>
</head>
<body>
    
    <h1><i>Fill in the details</i></h1>
<form method="post">
    <input type="hidden" name="id" value="<?= $record['id'] ?>">
    
    <div class="form-group">
        <label for="name"><b>Name:</b></label>
        <input type="text" id="name" name="name" required 
               value="<?= htmlspecialchars($record['name']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="tag_number">Tag Number:</label>
        <input type="text" id="tag_number" name="tag_number" 
               value="<?= htmlspecialchars($record['tag_number']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="graveyard"><b>Graveyard:</b></label>
        <input type="text" id="graveyard" name="graveyard" placeholder="RB/Vhovha"
               value="<?= htmlspecialchars($record['graveyard']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="dod">Date of Death:</label>
        <input type="date" id="dod" name="dod" 
               value="<?= htmlspecialchars($record['dod']) ?>">
    </div></br>

    <div class="form-group">
        <label for="dob"><b>Date of Birth:</b></label>
        <input type="date" id="dob" name="dob" 
               value="<?= htmlspecialchars($record['dob']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="grave_class">Grave Class:</label>
        <input type="text" id="grave_class" name="grave_class" placeholder="Adult/child/reserved"
               value="<?= htmlspecialchars($record['grave_class']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="receipt_number"><b>Receipt Number:</b></label>
        <input type="text" id="receipt_number" name="receipt_number" 
               value="<?= htmlspecialchars($record['receipt_number']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="burial_order_number">Burial Order Number:</label>
        <input type="text" id="burial_order_number" name="burial_order_number" 
               value="<?= htmlspecialchars($record['burial_order_number']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="exemption"><b>Exempted:</b></label>
        <textarea id="exemption" name="exemption" placeholder="Yes/No"><?= htmlspecialchars($record['exemption']) ?></textarea>
    </div></br>
    
    <div class="form-group">
        <label for="reason">Reason:</label>
        <textarea id="reason" name="reason"><?= htmlspecialchars($record['reason']) ?></textarea>
    </div></br>
    
    <div class="form-group">
        <label for="longitude"><b>Longitude:</b></label>
        <input type="number" step="0.000001" id="longitude" name="longitude" 
               value="<?= htmlspecialchars($record['longitude']) ?>">
    </div></br>
    
    <div class="form-group">
        <label for="latitude">Latitude:</label>
        <input type="number" step="0.000001" id="latitude" name="latitude" placeholder="nagative"
               value="<?= htmlspecialchars($record['latitude']) ?>">
    </div></br>
    
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
