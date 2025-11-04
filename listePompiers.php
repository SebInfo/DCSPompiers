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
require_once 'include/entete.php';
?>

<main>
  <?php echo generationEntete("Liste des Pompiers", "Gestion complète des pompiers"); ?>
  
  <div class="container py-3">
    <div class="mb-3">
      <a href="ajoutPompier.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Ajouter un pompier
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date Naissance</th>
            <th>Sexe</th>
            <th>Grade</th>
            <th>Téléphone</th>
            <th>Type</th>
            <th>Caserne Actuelle</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          try {
              // Requête pour récupérer tous les pompiers avec leurs informations
              $query = "
                SELECT 
                  p.Matricule,
                  p.NomPompier,
                  p.PrenomPompier,
                  p.DateNaissPompier,
                  p.SexePompier,
                  p.TelPompier,
                  g.LblGrade,
                  c.NomCaserne,
                  CASE 
                    WHEN pro.MatPro IS NOT NULL THEN 'Professionnel'
                    WHEN vol.MatVolontaire IS NOT NULL THEN 'Volontaire'
                    ELSE 'Non défini'
                  END as TypePompier
                FROM Pompier p
                LEFT JOIN Grade g ON p.IdGrade = g.idGrade
                LEFT JOIN Professionnel pro ON p.Matricule = pro.MatPro
                LEFT JOIN Volontaire vol ON p.Matricule = vol.MatVolontaire
                LEFT JOIN (
                  SELECT Matricule, IdCaserne, MAX(Date) as DateMax
                  FROM Affectation
                  GROUP BY Matricule
                ) a ON p.Matricule = a.Matricule
                LEFT JOIN Caserne c ON a.IdCaserne = c.idCaserne
                ORDER BY p.NomPompier, p.PrenomPompier
              ";
              
              $stmt = $db->query($query);
              
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['Matricule']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['NomPompier']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['PrenomPompier']) . "</td>";
                  echo "<td>" . date('d/m/Y', strtotime($row['DateNaissPompier'])) . "</td>";
                  echo "<td>" . htmlspecialchars($row['SexePompier']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['LblGrade']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['TelPompier']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['TypePompier']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['NomCaserne'] ?? 'Non affecté') . "</td>";
                  echo "<td>";
                  echo "<a href='ModifPompier.php?matricule=" . $row['Matricule'] . "' class='btn btn-sm btn-primary me-1' title='Modifier'><i class='bi bi-pencil'></i></a>";
                  echo "<a href='GestionAffectation.php?matricule=" . $row['Matricule'] . "' class='btn btn-sm btn-warning me-1' title='Gérer affectations'><i class='bi bi-building'></i></a>";
                  echo "<a href='SuppPompier.php?matricule=" . $row['Matricule'] . "' class='btn btn-sm btn-danger' title='Supprimer' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce pompier ?\")'><i class='bi bi-trash'></i></a>";
                  echo "</td>";
                  echo "</tr>";
              }
          } catch (PDOException $e) {
              echo "<tr><td colspan='10' class='text-center text-danger'>Erreur lors de la récupération des données</td></tr>";
              error_log($e->getMessage());
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require('include/pied.php'); ?>
