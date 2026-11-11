# 🚒 Application SDIS – Gestion opérationnelle des secours

## 🧭 Présentation générale

**SDIS** est une application web développée dans le cadre du **BTS SIO – option SLAM**.  
Elle a pour objectif de modéliser et de gérer les activités d’un **Service Départemental d’Incendie et de Secours (SDIS)**, ici inspiré du SDIS du Finistère.

Ce projet a une vocation pédagogique : il illustre les principes fondamentaux du développement web en **PHP procédural**, l’utilisation d’une **base de données MySQL** et la gestion des sessions utilisateurs.

---

## ⚙️ Fonctionnalités principales

- 👨‍🚒 **Gestion du personnel**
  - Fiches pompiers (identité, grade, habilitations)
  - Gestion des plannings et disponibilités

- 🚒 **Gestion des véhicules**
  - Suivi des entretiens et de l’état de chaque véhicule
  - Gestion des inventaires et disponibilités

- 🔥 **(À venir)** Gestion des interventions
  - Mobilisation automatique des véhicules et pompiers en fonction des habilitations et disponibilités

---

## 🧑‍💻 Technologies utilisées

| Composant | Technologie |
|------------|-------------|
| **Langage serveur** | PHP 8.2+ |
| **Base de données** | MySQL 8 |
| **Front-end** | HTML5, CSS3, Bootstrap 5 |
| **Scripts** | JavaScript (validation formulaire + preview image) |
| **Serveur local** | MAMP (macOS) ou XAMPP (Windows) |
| **Conventions** | PHPDoc et conventions de présentation inspirées de PSR-12 |

---

## 📂 Structure du projet

```
DSCPompier2026/
├── include/
│   ├── entete.php
│   ├── pied.php
│   ├── baseDonnees.php
│   ├── controleAcces.php
│   ├── mesFonctions.php
│   ├── televersement.php
│   ├── svg.html
│   └── .env.example
├── fichiersBD/
│   └── DSC.sql
├── images/
│   └── imagesCarte/
├── css/
│   └── styles.css
├── js/
│   └── app.js
├── index.php
├── README.md
```

---

## 🔐 Configuration de la base de données

Le fichier **`include/.env`** contient les informations de connexion à la base MySQL.
⚠️ Ce fichier ne doit **jamais être versionné** (ajouté à `.gitignore`).

Exemple :
```ini
DB_HOST=127.0.0.1
DB_PORT=8889
DB_NAME=DSC
DB_USER=userDSC
DB_PASS=IciVotreMotDePasse!
```

---

## 🚀 Installation et exécution

1. **Cloner le projet**
   ```bash
   git clone https://github.com/SebInfo/DSCPompier2026.git
   cd DSCPompier2026
   ```

2. **Configurer l’environnement**
   - Copier le fichier `include/.env.example` vers `include/.env`
   - Modifier les paramètres selon ta configuration MAMP/XAMPP

3. **Créer la base de données**
   - Créer une base nommée `DSC` dans phpMyAdmin.
   - Importer le fichier `fichiersBD/DSC.sql` dans cette base.

4. **Lancer le serveur local**
   - Sous MAMP, placer le projet dans le dossier `htdocs`
   - Accéder à : [http://localhost:8888/DSCPompier2026](http://localhost:8888/DSCPompier2026)

---

## 📖 Organisation et lisibilité du code

Le code reste volontairement procédural pour rester adapté à un premier TP de BTS SIO SLAM.

- Les pages PHP correspondent aux actions de l'application : ajouter, modifier ou supprimer.
- Les éléments communs sont regroupés dans `include/` : connexion à la base, contrôle d'accès, fonctions HTML et téléversement d'images.
- Les fonctions présentes dans `include/` disposent d'une PHPDoc avec leur rôle, leurs paramètres et leur valeur de retour.
- Les traitements longs, comme l'ajout d'un pompier, sont découpés en étapes commentées : récupération, validation, insertion et redirection.

Le projet n'utilise pas PSR-4 : cette norme concerne l'autoloading de classes avec Composer. Elle ne s'applique pas à cette application procédurale sans classes.

---

## 🧩 Vérifications utiles

- **Qualité du code**
  - Vérifier la syntaxe : `find . -name '*.php' -print0 | xargs -0 -n1 php -l`
  - PHPCS peut être ajouté ultérieurement pour contrôler les conventions PSR-12.
- **Documentation**
  - Les fonctions communes sont documentées avec PHPDoc.
- **Sécurité**
  - Variables sensibles dans `.env`
  - Connexion PDO avec gestion d’erreurs
  - Cookies configurés en mode `HttpOnly` + `SameSite=Lax`

---

## 👤 Auteur

**Sébastien Inion**  
> Projet réalisé dans le cadre du BTS SIO (option SLAM)  
> Lycée — 2026

---

## 📜 Licence

Ce projet est distribué sous licence **MIT**.  
Vous êtes libre de l’utiliser, le modifier et le redistribuer à condition de conserver la mention de l’auteur original.

---

## 🧠 Remarques pédagogiques

Ce projet illustre :
- Les bases de la programmation orientée serveur (PHP + MySQL)
- L’importance de la modularisation (`include/`, `require_once`)
- La lecture d'une PHPDoc et de conventions de code simples
- Les notions de sessions, cookies et formulaires sécurisés



