<div class="px-3 py-2 text-bg-danger">
    <div class="container">
        <?php $nomFichier=basename($_SERVER['SCRIPT_NAME']) ?>
        <div class="bi bi-fire d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="index.php" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#fire"/></svg>
                <h5>Services Départementaux D’incendie et de Secours (SDIS). Finistère (29)</h5>
            </a>
            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                    <a href="index.php" class="nav-link <?php if ($nomFichier==="index.php") { echo 'text-dark'; } else {echo 'text-white';}?>">
                        <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#home"/></svg>
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#fire"/></svg>
                        Interventions
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#table"/></svg>
                        Planning
                    </a>
                </li>
                <li>
                    <a href="lesVehicules.php" class="nav-link <?php if ($nomFichier==="lesVehicules.php") { echo 'text-dark'; } else {echo 'text-white';}?>">
                        <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#vehicule"/></svg>
                        Véhicules
                    </a>
                </li>
                <li>
                    <a href="lesPompiers.php" class="nav-link <?php if ($nomFichier==="lesPompiers.php") { echo 'text-dark'; } else {echo 'text-white';}?>">
                        <svg class="bi d-block mx-auto mb-1" width="24" height="24"><use xlink:href="#people-circle"/></svg>
                        Pompiers
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>