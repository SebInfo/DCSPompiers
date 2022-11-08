<?php
if (!isset($_SESSION))
{
    session_start(); 
}
if(isset($_POST['ValiderLogin']))
{
    if ($_POST['login']==='Mylène' and $_POST['motDePasse']==='Micoton')
    {
        $_SESSION['login']=true;
        header("Location:index.php");
    }
}
?>
<?php require('include/entete.inc.php');?>
<main>
    <?php echo generationEntete("Identification","Veuillez vous identifier."); ?>
    <form class="container col-6 col-md-4" method="post" action="connexion.php">
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" name="login" class="form-control" id="login" placeholder="Entrer le login">
    </div>
    <div class="form-group">
        <label for="motDePasse">Mot de passe</label>
        <input type="password" name="motDePasse" class="form-control" id="motDePasse" placeholder="mot de passe">
    </div>
    <button type="submit" class="btn btn-primary" name="ValiderLogin">Valider</button>
    </form>
</main>
<?php require('include/pied.inc.php');?>