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

if (!$matricule) {
    header('Location: ListePompiers.php');
    exit;
}

$pompier = null;
$erreur = '';

try {
    // Récupération des informations du pompier
    $query = "
        SELECT 
            p.Matricule,
            p.NomPompier,
            p.PrenomPompier,
            CASE 
                WHEN pro.MatPro IS NOT NULL THEN 'Professionnel'
                WHEN vol.MatVolontaire IS NOT NULL THEN 'Volontaire'
                ELSE 'Non défini'
            END as TypePompier,
            COUNT(DISTINCT a.Date) as NbAffectations,
            COUNT(DISTINCT e.IdHabilitation) as NbHabilitations
        FROM Pompier p
        LEFT JOIN Professionnel pro ON p.Matricule = pro.MatPro
        LEFT JOIN Volontaire vol ON p.Matricule = vol.MatVolontaire
        LEFT JOIN Affectation a ON p.Matricule = a.Matricule
        LEFT JOIN Exercer e ON p.Matricule = e.Matricule
        WHERE p.Matricule = :matricule
        GROUP BY p.Matricule
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([':matricule' => $matricule]);
    $pompier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pompier) {
        header('Location: ListePompiers.php');
        exit;
    }
} catch (PDOException $e) {
    $erreur = "Erreur lors de la récupération des données";
    error_log($e->getMessage());
}

// ==========================
// 3. Traitement de la suppression
// ==========================
if (isset($_POST['confirmerSupp'])) {
    try {
        $db->beginTransaction();

        // Suppression des exercer (habilitations)
        $db->prepare('DELETE FROM Exercer WHERE Matricule = :mat')->execute([':mat' => $matricule]);
        
        // Suppression des affectations
        $db->prepare('DELETE FROM Affectation WHERE Matricule = :mat')->execute([':mat' => $matricule]);
        
        // Suppression du type (volontaire ou professionnel)
        $db->prepare('DELETE FROM Volontaire WHERE MatVolontaire = :mat')->execute([':mat' => $matricule]);
        $db->prepare('DELETE FROM Professionnel WHERE MatPro = :mat')->execute([':mat' => $matricule]);
        
        // Suppression du pompier
        $db->prepare('DELETE FROM Pompier WHERE Matricule = :mat')->execute([':mat' => $matricule]);

        $db->commit();
        
        // Redirection avec message de succès
        require_once 'include/entete.php';
        ?>
        <main class="d-flex flex-column justify-content-center align-items-center" style="height: 70vh;">
            <div class="alert alert-success text-center p-5 shadow-lg">
                <h2 class="mb-3">✅ Suppression effectuée avec succès !</h2>
                <p>Le pompier a été supprimé de la base de données.</p>
                <p>Vous allez être redirigé vers la liste des pompiers dans <strong>3 secondes</strong>...</p>
                <div class="spinner-border text-success mt-3" role="status">
                    <span class="visually-hidden">Redirection...</span>
                </div>
                <p class="mt-4">
                    <a href="ListePompiers.php" class="btn btn-primary">Redirection immédiate</a>
                </p>
            </div>
        </main>
        <script>
            setTimeout(() => {
                window.location.href = 'ListePompiers.php';
            }, 3000);
        </script>
        <?php
        require_once 'include/pied.php';
        exit;
        
    } catch (PDOException $e) {
        $db->rollBack();
        $erreur = "Erreur lors de la suppression : " . $e->getMessage();
        error_log($e->getMessage());
    }
}

require_once 'include/entete.php';
?>

<main>
  <div class="container">
    <?php echo generationEntete("Suppression d'un pompier", "Confirmer la suppression du pompier"); ?>
    
    <?php if ($erreur): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($erreur); ?>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header bg-danger text-white">
        <h4><i class="bi bi-exclamation-triangle"></i> Attention - Suppression définitive</h4>
      </div>
      <div class="card-body">
        <p class="lead">Vous êtes sur le point de supprimer le pompier suivant :</p>
        
        <table class="table table-bordered">
          <tr>
            <th style="width: 200px;">Matricule</th>
            <td><?php echo htmlspecialchars($pompier['Matricule']); ?></td>
          </tr>
          <tr>
            <th>Nom</th>
            <td><?php echo htmlspecialchars($pompier['NomPompier']); ?></td>
          </tr>
          <tr>
            <th>Prénom</th>
            <td><?php echo htmlspecialchars($pompier['PrenomPompier']); ?></td>
          </tr>
          <tr>
            <th>Type</th>
            <td><?php echo htmlspecialchars($pompier['TypePompier']); ?></td>
          </tr>
          <tr>
            <th>Nombre d'affectations</th>
            <td><?php echo $pompier['NbAffectations']; ?></td>
          </tr>
          <tr>
            <th>Nombre d'habilitations</th>
            <td><?php echo $pompier['NbHabilitations']; ?></td>
          </tr>
        </table>

        <div class="alert alert-warning">
          <strong>⚠️ Cette action est irréversible !</strong><br>
          Toutes les données associées à ce pompier seront également supprimées :
          <ul>
            <li>Historique des affectations</li>
            <li>Habilitations</li>
            <li>Informations de type (volontaire/professionnel)</li>
          </ul>
        </div>

        <form method="post" action="SuppPompier.php?matricule=<?php echo $matricule; ?>" class="mt-4">
          <div class="d-flex justify-content-between">
            <a href="ListePompiers.php" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button type="submit" name="confirmerSupp" class="btn btn-danger">
              <i class="bi bi-trash"></i> Confirmer la suppression
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<?php require('include/pied.php'); ?>
