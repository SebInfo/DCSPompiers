<?php
// ==========================
// 1. Contrôle d'accès
// ==========================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: connexion.php');
    exit;
}

require_once __DIR__ . '/include/baseDonnees.php';

// ==========================
// 2. Récupération du pompier
// ==========================
$matricule = filter_input(INPUT_GET, 'matricule', FILTER_VALIDATE_INT);
if (!$matricule && isset($_POST['matricule'])) {
    $matricule = filter_input(INPUT_POST, 'matricule', FILTER_VALIDATE_INT);
}

if (!$matricule) {
    header('Location: ListePompiers.php');
    exit;
}

$pompier = null;
$affectations = [];
$casernes = [];
$erreur = '';
$success = false;

try {
    // Récupération des informations du pompier
    $query = "SELECT Matricule, NomPompier, PrenomPompier FROM Pompier WHERE Matricule = :matricule";
    $stmt = $db->prepare($query);
    $stmt->execute([':matricule' => $matricule]);
    $pompier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pompier) {
        header('Location: ListePompiers.php');
        exit;
    }
    
    // Récupération de l'historique des affectations
    $queryAff = "
        SELECT 
            a.Date,
            a.IdCaserne,
            c.NomCaserne
        FROM Affectation a
        JOIN Caserne c ON a.IdCaserne = c.idCaserne
        WHERE a.Matricule = :matricule
        ORDER BY a.Date DESC
    ";
    $stmtAff = $db->prepare($queryAff);
    $stmtAff->execute([':matricule' => $matricule]);
    $affectations = $stmtAff->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupération de la liste des casernes
    $queryCaserne = "SELECT idCaserne, NomCaserne FROM Caserne ORDER BY NomCaserne";
    $casernes = $db->query($queryCaserne)->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $erreur = "Erreur lors de la récupération des données";
    error_log($e->getMessage());
}

// ==========================
// 3. Traitement - Ajout d'une affectation
// ==========================
if (isset($_POST['ajouterAffectation'])) {
    $dateAffectation = $_POST['dateAffectation'] ?? null;
    $idCaserne = $_POST['caserne'] ?? null;
    
    if ($dateAffectation && $idCaserne) {
        try {
            // Vérifier si une affectation existe déjà à cette date pour ce pompier
            $checkQuery = "SELECT COUNT(*) FROM Affectation WHERE Matricule = :mat AND Date = :date";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->execute([':mat' => $matricule, ':date' => $dateAffectation]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $erreur = "Une affectation existe déjà à cette date pour ce pompier.";
            } else {
                $insertQuery = "INSERT INTO Affectation (Matricule, Date, IdCaserne) VALUES (:mat, :date, :cas)";
                $insertStmt = $db->prepare($insertQuery);
                $insertStmt->execute([
                    ':mat' => $matricule,
                    ':date' => $dateAffectation,
                    ':cas' => $idCaserne
                ]);
                $success = true;
                
                // Recharger les affectations
                $stmtAff->execute([':matricule' => $matricule]);
                $affectations = $stmtAff->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'ajout de l'affectation";
            error_log($e->getMessage());
        }
    } else {
        $erreur = "Veuillez remplir tous les champs";
    }
}

// ==========================
// 4. Traitement - Suppression d'une affectation
// ==========================
if (isset($_POST['supprimerAffectation'])) {
    $dateSupp = $_POST['dateSupp'] ?? null;
    $caserneSupp = $_POST['caserneSupp'] ?? null;
    
    if ($dateSupp && $caserneSupp) {
        try {
            // Vérifier qu'il reste au moins une affectation
            if (count($affectations) <= 1) {
                $erreur = "Impossible de supprimer la dernière affectation. Un pompier doit avoir au moins une affectation.";
            } else {
                $deleteQuery = "DELETE FROM Affectation WHERE Matricule = :mat AND Date = :date AND IdCaserne = :cas";
                $deleteStmt = $db->prepare($deleteQuery);
                $deleteStmt->execute([
                    ':mat' => $matricule,
                    ':date' => $dateSupp,
                    ':cas' => $caserneSupp
                ]);
                $success = true;
                
                // Recharger les affectations
                $stmtAff->execute([':matricule' => $matricule]);
                $affectations = $stmtAff->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la suppression de l'affectation";
            error_log($e->getMessage());
        }
    }
}

// ==========================
// 5. Traitement - Modification d'une affectation
// ==========================
if (isset($_POST['modifierAffectation'])) {
    $ancienneDate = $_POST['ancienneDate'] ?? null;
    $ancienneCaserne = $_POST['ancienneCaserne'] ?? null;
    $nouvelleDate = $_POST['nouvelleDate'] ?? null;
    $nouvelleCaserne = $_POST['nouvelleCaserne'] ?? null;
    
    if ($ancienneDate && $ancienneCaserne && $nouvelleDate && $nouvelleCaserne) {
        try {
            // Vérifier si la nouvelle combinaison n'existe pas déjà
            if ($ancienneDate != $nouvelleDate || $ancienneCaserne != $nouvelleCaserne) {
                $checkQuery = "SELECT COUNT(*) FROM Affectation WHERE Matricule = :mat AND Date = :date AND IdCaserne = :cas";
                $checkStmt = $db->prepare($checkQuery);
                $checkStmt->execute([':mat' => $matricule, ':date' => $nouvelleDate, ':cas' => $nouvelleCaserne]);
                
                if ($checkStmt->fetchColumn() > 0) {
                    $erreur = "Cette affectation existe déjà.";
                } else {
                    // Supprimer l'ancienne et insérer la nouvelle
                    $db->beginTransaction();
                    
                    $deleteQuery = "DELETE FROM Affectation WHERE Matricule = :mat AND Date = :date AND IdCaserne = :cas";
                    $deleteStmt = $db->prepare($deleteQuery);
                    $deleteStmt->execute([':mat' => $matricule, ':date' => $ancienneDate, ':cas' => $ancienneCaserne]);
                    
                    $insertQuery = "INSERT INTO Affectation (Matricule, Date, IdCaserne) VALUES (:mat, :date, :cas)";
                    $insertStmt = $db->prepare($insertQuery);
                    $insertStmt->execute([':mat' => $matricule, ':date' => $nouvelleDate, ':cas' => $nouvelleCaserne]);
                    
                    $db->commit();
                    $success = true;
                    
                    // Recharger les affectations
                    $stmtAff->execute([':matricule' => $matricule]);
                    $affectations = $stmtAff->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (PDOException $e) {
            $db->rollBack();
            $erreur = "Erreur lors de la modification de l'affectation";
            error_log($e->getMessage());
        }
    }
}

require_once 'include/entete.php';
?>

<main>
  <div class="container">
    <?php echo generationEntete(
        "Gestion des affectations", 
        "Historique et gestion des affectations de " . htmlspecialchars($pompier['NomPompier'] . " " . $pompier['PrenomPompier'])
    ); ?>
    
    <?php if ($success): ?>
      <div class="alert alert-success alert-dismissible fade show">
        ✅ L'opération a été effectuée avec succès !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <?php if ($erreur): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        ❌ <?php echo htmlspecialchars($erreur); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row">
      <!-- Colonne gauche : Historique des affectations -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h5><i class="bi bi-clock-history"></i> Historique des affectations</h5>
          </div>
          <div class="card-body">
            <?php if (empty($affectations)): ?>
              <p class="text-muted">Aucune affectation enregistrée.</p>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Caserne</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($affectations as $index => $aff): ?>
                      <tr>
                        <td><?php echo date('d/m/Y', strtotime($aff['Date'])); ?></td>
                        <td>
                          <?php echo htmlspecialchars($aff['NomCaserne']); ?>
                          <?php if ($index === 0): ?>
                            <span class="badge bg-success">Actuelle</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <!-- Bouton Modifier -->
                          <button type="button" class="btn btn-sm btn-warning" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalModif<?php echo $index; ?>">
                            <i class="bi bi-pencil"></i>
                          </button>
                          
                          <!-- Bouton Supprimer -->
                          <form method="post" style="display:inline;" 
                                onsubmit="return confirm('Confirmer la suppression de cette affectation ?');">
                            <input type="hidden" name="matricule" value="<?php echo $matricule; ?>">
                            <input type="hidden" name="dateSupp" value="<?php echo $aff['Date']; ?>">
                            <input type="hidden" name="caserneSupp" value="<?php echo $aff['IdCaserne']; ?>">
                            <button type="submit" name="supprimerAffectation" class="btn btn-sm btn-danger">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                          
                          <!-- Modal de modification -->
                          <div class="modal fade" id="modalModif<?php echo $index; ?>" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <form method="post">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Modifier l'affectation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body">
                                    <input type="hidden" name="matricule" value="<?php echo $matricule; ?>">
                                    <input type="hidden" name="ancienneDate" value="<?php echo $aff['Date']; ?>">
                                    <input type="hidden" name="ancienneCaserne" value="<?php echo $aff['IdCaserne']; ?>">
                                    
                                    <div class="mb-3">
                                      <label class="form-label">Nouvelle date</label>
                                      <input type="date" class="form-control" name="nouvelleDate" 
                                             value="<?php echo $aff['Date']; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                      <label class="form-label">Nouvelle caserne</label>
                                      <select class="form-control" name="nouvelleCaserne" required>
                                        <?php foreach ($casernes as $cas): ?>
                                          <option value="<?php echo $cas['idCaserne']; ?>"
                                                  <?php echo ($cas['idCaserne'] == $aff['IdCaserne']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cas['NomCaserne']); ?>
                                          </option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" name="modifierAffectation" class="btn btn-primary">Enregistrer</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Colonne droite : Ajouter une affectation -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h5><i class="bi bi-plus-circle"></i> Nouvelle affectation</h5>
          </div>
          <div class="card-body">
            <form method="post">
              <input type="hidden" name="matricule" value="<?php echo $matricule; ?>">
              
              <div class="mb-3">
                <label for="dateAffectation" class="form-label">Date d'affectation</label>
                <input type="date" class="form-control" id="dateAffectation" 
                       name="dateAffectation" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
              
              <div class="mb-3">
                <label for="caserne" class="form-label">Caserne</label>
                <select class="form-control" id="caserne" name="caserne" required>
                  <option value="">-- Sélectionner --</option>
                  <?php foreach ($casernes as $cas): ?>
                    <option value="<?php echo $cas['idCaserne']; ?>">
                      <?php echo htmlspecialchars($cas['NomCaserne']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              
              <button type="submit" name="ajouterAffectation" class="btn btn-success w-100">
                <i class="bi bi-plus"></i> Ajouter
              </button>
            </form>
          </div>
        </div>
        
        <div class="mt-3">
          <a href="ListePompiers.php" class="btn btn-secondary w-100">
            <i class="bi bi-arrow-left"></i> Retour à la liste
          </a>
        </div>
      </div>
    </div>

    <div class="alert alert-info mt-4">
      <strong><i class="bi bi-info-circle"></i> Information :</strong>
      L'historique complet des affectations est conservé. Vous pouvez ajouter, modifier ou supprimer des affectations.
      La plus récente est considérée comme l'affectation actuelle.
    </div>
  </div>
</main>

<?php require('include/pied.php'); ?>
